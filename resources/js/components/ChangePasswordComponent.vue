<template>
    <div class="container">
        <div class="form-group">
            <label v-text="$ml.get('login')"></label>
            <input type="text" class="form-control" v-model="name" disabled>
        </div>
        <div class="form-group">
            <label for="oldPassword" v-text="$ml.get('currentPassword')"></label>
            <input type="password" class="form-control" name="oldPassword" id="oldPassword" v-validate="'required'"
                   v-model="oldPassword" required>
            <small class="form-text text-muted" v-if="errors.has('oldPassword')">{{ errors.first('oldPassword') }}
            </small>
            <small class="form-text text-muted" v-if="lErrors.oldPassword">{{ lErrors.oldPassword }}</small>
        </div>
        <div class="form-group">
            <label v-text="$ml.get('question')"></label>
            <input type="text" class="form-control" v-model="question" disabled>
        </div>
        <div class="form-group">
            <label for="answer" v-text="$ml.get('answer')"></label>
            <input class="form-control" name="answer" id="answer" v-validate="'required'" v-model="answer" required>
            <small class="form-text text-muted" v-if="lErrors.answer">{{ lErrors.answer }}</small>
        </div>

        <div class="form-group">
            <label for="newPassword" v-text="$ml.get('newPassword')"></label>
            <input type="password" class="form-control" name="newPassword" id="newPassword"
                   v-validate="{ required: true, min: 6 }"
                   v-model="newPassword" ref="newPassword" required>
            <small class="form-text text-muted" v-if="errors.has('newPassword')">{{ errors.first('newPassword') }}
            </small>
        </div>
        <div class="form-group">
            <label for="confirm" v-text="$ml.get('newConfirm')"></label>
            <input type="password" class="form-control" name="confirm" id="confirm"
                   v-validate="{ required: true, min: 6, confirmed:newPassword }"
                   v-model="confirm"
                   data-vv-as="newPassword" required>
            <span class="form-text text-muted" v-if="errors.has('confirm')">{{ errors.first('confirm') }}</span>
        </div>
        <button class="btn btn-primary" @click="recaptcha" v-text="$ml.get('reset')"></button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                oldPassword: '',
                newPassword: '',
                confirm: '',
                question: '',
                answer: '',
                lErrors: {}
            }
        },
        methods:
            {
                reset() {
                    axios.post('personal/change-password', {
                        password: this.oldPassword,
                        newPassword: this.newPassword,
                        Promt: this.question,
                        answer: this.answer
                    }).then(() => {
                        this.$auth.logout();
                    }).catch(res => {
                        if (res.response.data.password) {
                            this.$set(this.lErrors, 'oldPassword', res.response.data.password);
                        } else if (res.response.data.answer) {
                            this.$set(this.lErrors, 'answer', res.response.data.answer);
                        }
                    })
                },
                recaptcha() {
                    this.$recaptcha('login').then((token) => {
                        axios.post('/recaptcha', {
                            token
                        }).then(resp => {
                            if (resp.data !== false) {
                                if (!this.errors.any()) {
                                    this.reset();
                                }
                            } else {
                                alert(this.$ml.get('captchaFailed'));
                            }
                        });

                    })
                },
                getUserData() {
                    axios.get('personal/user')
                        .then(response => {
                            this.name = response.data.name;
                            this.question = response.data.Promt;
                        });
                }
            },
        mounted() {
            this.getUserData();
        }
    }
</script>