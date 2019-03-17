<template>
    <div>
        <div v-if="success" class="alert alert-success">
            <h4>{{ $ml.get('msgResetLink') }}</h4>
        </div>
        <div v-if="!success" class="container">
            <div class="form-group">
                <label for="email" v-text="$ml.get('email')"></label>
                <input @change="change('email')" v-validate="'required|email'" name="email" type="email" id="email"
                       class="form-control"
                       v-model="email"
                       required>
                <small class="form-text text-muted" v-if="errors.has('email')">{{ errors.first('email') }}</small>
                <small class="form-text text-muted" v-if="lErrors.email">{{ lErrors.email }}</small>
            </div>
            <button @click="recaptcha" class="btn btn-primary" v-text="$ml.get('send')"></button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                success: false,
                error: false,
                lErrors: {}
            };
        },
        methods:
            {
                reset() {
                    axios.post('/password/email', {
                        email: this.email
                    }).then(res => {
                        this.success = true;
                    }).catch(res => {
                        this.$set(this.lErrors, 'email', res.response.data.email);
                        this.error = true;
                    })
                },
                recaptcha() {
                    this.$recaptcha('login').then((token) => {
                        this.checkError();
                        axios.post('/recaptcha', {
                            token
                        }).then(resp => {
                            if (resp.data !== false) {
                                if (!this.error) {
                                    this.reset();
                                }
                            } else {
                                alert(this.$ml.get('captchaFailed'));
                            }
                        });
                    })
                },
                change(item) {
                    if (this.lErrors[item]) {
                        this.lErrors[item] = '';
                    }
                    this.checkError();
                },
                checkError(arg = false) {
                    this.error = this.errors.any() || arg;
                }
            }
    }
</script>
