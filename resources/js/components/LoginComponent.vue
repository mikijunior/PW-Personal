<template>
    <div class="row justify-content-center align-items-center">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger" v-if="lErrors.msg">
                        <p>{{ $ml.get('errLogin') }}</p>
                    </div>

                    <form autocomplete="off" @submit.prevent="recaptcha" method="post">
                        <div class="form-group">
                            <label for="name" v-text="$ml.get('login')"></label>
                            <input @change="change('name')" v-validate="{ required: true, regex: /^[0-9a-z][a-z0-9-_]{5,32}$/ }"
                                   id="name" class="form-control" name="name" v-model="data.name">
                            <div v-if="errors.has('name')" class="alert alert-danger">{{ errors.first('name') }}</div>
                            <div v-if="lErrors.name" class="alert alert-danger">{{ lErrors.name[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="password" v-text="$ml.get('password')"></label>
                            <input @change="change('password')" v-validate="{ required: true, min: 6 }" type="password" name="password"
                                   id="password" class="form-control" v-model="data.password" required>
                            <div v-if="errors.has('password')" class="alert alert-danger">{{ errors.first('password') }}</div>
                            <div v-if="lErrors.password" class="alert alert-danger">{{ lErrors.password[0] }}</div>
                        </div>
                        <button class="btn btn-primary" v-text="$ml.get('signIn')"></button>
                        <router-link href="#" :to="{ name: 'resetPassword' }" v-text="$ml.get('forgot')"></router-link>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data: () => ({
            data: {
                name: null,
                password: null,
            },
            error: false,
            lErrors: {}
        }),
        methods: {
            login() {
                let app = this;
                this.$auth.login({
                    params: this.data,
                    rememberMe: true,
                    success: function () {},
                    error: function (resp) {
                        app.error = true;
                        if( resp.response.data.errors ){
                            app.lErrors = resp.response.data.errors;
                        } else if (resp.response.data.msg) {
                            this.$set(this.lErrors, 'msg', resp.response.data.msg)
                        }
                        this.error = true;
                    },
                    redirect: '/personal',
                    fetchUser: true,
                });
            },

            change(item) {
                if (this.lErrors.msg) {
                    this.lErrors.msg = '';
                }
                if (this.lErrors[item]) {
                    this.lErrors[item] = '';
                }
                this.error = false;
            },

            recaptcha() {
                this.$recaptcha('login').then((token) => {
                    axios.post('/recaptcha', {
                        token
                    }).then(resp => {
                        if (resp.data !== false) {
                            console.log(resp.data);
                            if (!this.error.any()) {
                                // this.login();
                            }
                        } else {
                            alert(this.$ml.get('captchaFailed'));
                        }
                    });
                })
            },
        }
    }
</script>
