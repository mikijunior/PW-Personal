<template>
    <div>
        <div v-if="success" class="alert alert-success">
            {{ $ml.get('msgSuccessReset') }}
        </div>
        <div v-if="!success" class="container">
            <div class="form-group">
                <label for="email" v-text="$ml.get('email')">E-mail</label>
                <input @change="change('email')" v-validate="'required|email'" name="email" type="email" id="email"
                       class="form-control"
                       v-model="email"
                       required>
                <small class="form-text text-muted" v-if="errors.has('email')">{{ errors.first('email') }}</small>
                <small class="form-text text-muted" v-if="lErrors.email">{{ lErrors.email }}</small>

            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('password') }">
                <label for="password" v-text="$ml.get('password')"></label>
                <input @change="change('password')" type="password" v-validate="'required'" name="password"
                       id="password" class="form-control"
                       v-model="password" ref="password" required>
                <small class="form-text text-muted" v-if="errors.has('password')">{{ errors.first('password') }}</small>
                <small class="form-text text-muted" v-if="lErrors.password">{{ lErrors.password[0] }}</small>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('confirm') }">
                <label for="confirm" v-text="$ml.get('confirm')">Password Confirm</label>
                <input type="password" name="confirm" v-validate="'required|confirmed:password'" id="confirm"
                       class="form-control" v-model="confirm" data-vv-as="password">
                <small class="form-text text-muted" v-if="errors.has('confirm')">{{ errors.first('confirm') }}</small>
            </div>

            <button @click="recaptcha" class="btn btn-primary" v-text="$ml.get('reset')"></button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                password: '',
                confirm: '',
                success: false,
                error: false,
                lErrors: {}
            };
        },
        methods:
            {
                reset() {
                    axios.post('/password/reset', {
                        email: this.email,
                        token: this.$route.params.token,
                        password: this.password,
                        password_confirmation: this.confirm
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
