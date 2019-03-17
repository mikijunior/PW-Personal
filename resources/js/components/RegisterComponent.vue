<template>
    <div>
        <div v-if="success" class="alert alert-success">
            <h4>{{ $ml.get('msgVerify') }}</h4>
        </div>
        <div v-if="!success" class="container">
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('login') || lErrors.name }">
                <label for="login" v-text="$ml.get('login')"></label>
                <input type="text" @change="change('name')"
                       v-validate="{ required: true, regex: /^[0-9a-z][a-z0-9-_]{5,32}$/ }" name="login" id="login"
                       class="form-control" v-model="login" required>
                <small class="form-text text-muted" v-if="errors.has('login')">{{ $ml.get('errLoginMsg') }}</small>
                <small class="form-text text-muted" v-if="lErrors.name">{{ lErrors.name[0] }}</small>
                <small class="form-text text-muted" v-if="!lErrors.name && !errors.has('name') && loginAvailability">
                    {{ $ml.get('loginAvailability') }}
                </small>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('email') || lErrors.email}">
                <label for="email" v-text="$ml.get('email')"></label>
                <input @change="change('email')" v-validate="'required|email'" name="email" type="email" id="email"
                       class="form-control"
                       v-model="email"
                       required>
                <small class="form-text text-muted" v-if="errors.has('email')">{{ errors.first('email') }}</small>
                <small class="form-text text-muted" v-if="lErrors.email">{{ lErrors.email[0] }}</small>
                <small class="form-text text-muted" v-if="!lErrors.email && !errors.has('email') && emailAvailability">
                    {{ $ml.get('emailAvailability') }}
                </small>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('password') }">
                <label for="password" v-text="$ml.get('password')"></label>
                <input @change="change('password')" type="password" v-validate="'required'" name="password"
                       id="password" class="form-control"
                       v-model="password" ref="password" required>
                <small class="form-text text-muted" v-if="errors.has('password')">{{ errors.first('password') }}</small>
                <small class="form-text text-muted" v-if="lErrors.password">{{ lErrors.password[0] }}</small>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('password') }">
                <label for="confirm" v-text="$ml.get('confirm')"></label>
                <input type="password" name="confirm" v-validate="'required|confirmed:password'" id="confirm"
                       class="form-control" v-model="confirm" data-vv-as="password">
                <small class="form-text text-muted" v-if="errors.has('confirm')">{{ errors.first('confirm') }}</small>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('question') || lErrors.Promt }">
                <label for="question" v-text="$ml.get('question')"></label>
                <input @change="change('Promt')" id="question" v-validate="{required: true, regex: /[\w\s-+,.?]{3,32}/}"
                       name="question"
                       class="form-control" v-model="question" required>
                <small class="form-text text-muted" v-if="errors.has('question')">{{ errors.first('question') }}</small>
                <small class="form-text text-muted" v-if="lErrors.Promt">{{ lErrors.Promt[0] }}</small>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': errors.has('answer') || lErrors.answer }">
                <label for="answer" v-text="$ml.get('answer')"></label>
                <input @change="change('answer')" id="answer" v-validate="{required: true, regex: /[\w\s-+,.@?]{3,32}/}"
                       name="answer"
                       class="form-control" v-model="answer" required>
                <small class="form-text text-muted" v-if="errors.has('answer')">{{ errors.first('answer') }}</small>
                <small class="form-text text-muted" v-if="lErrors.answer">{{ lErrors.answer[0] }}</small>
            </div>
            <button @click="recaptcha" class="btn btn-primary" v-text="$ml.get('register')"></button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                login: '',
                email: '',
                password: '',
                confirm: '',
                success: false,
                question: '',
                answer: '',
                error: false,
                lErrors: {
                    email: []
                },
                loginAvailability: false,
                emailAvailability: false,
            };
        },
        methods:
            {
                register() {
                    const app = this;
                    this.$auth.register({
                        method: 'post',
                        data: {
                            name: app.login,
                            email: app.email,
                            password: app.password,
                            Promt: app.question,
                            answer: app.answer,
                        },
                        success: function () {
                            app.login = '';
                            app.email = '';
                            app.password = '';
                            app.confirm = '';
                            app.question = '';
                            app.answer = '';
                            app.error = false;
                            app.lErrors = {};
                            app.success = true;
                        },
                        error: function (resp) {
                            this.error = true;
                            this.lErrors = resp.response.data.errors;
                            this.checkError(true);
                        },
                        redirect: null
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
                                    this.register();
                                }
                            } else {
                                alert(this.$ml.get('captchaFailed'));
                            }
                        });
                    })
                },
                change(item) {
                    if (item === 'email' || item === 'name') {
                        if (!this.error) {
                            this.checkUnique(item);
                        }
                    }
                    if (this.lErrors[item]) {
                        this.lErrors[item] = '';
                    }
                    this.checkError();
                },
                checkError(arg = false) {
                    this.error = this.errors.any() || arg;
                },
                checkUnique(item) {
                    if (item === 'name') {
                        axios.post('auth/check-unique', {
                            login: this.login,
                        }).then(resp => {
                            this.loginAvailability = true;
                        }).catch(resp => {
                            this.$set(this.lErrors, 'name', resp.response.data.errors.login);
                        })

                    }
                    if (item === 'email') {
                        axios.post('auth/check-unique', {
                            email: this.email
                        }).then(resp => {
                            this.emailAvailability = true;
                        }).catch(resp => {
                            this.$set(this.lErrors, 'email', resp.response.data.errors.email);
                        })
                    }
                }
            }
    }
</script>
