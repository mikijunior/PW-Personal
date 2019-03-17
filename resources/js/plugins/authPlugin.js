const AuthPlugin = {
    install(Vue, options) {
        Vue.authPlugin = {
            setToken(token) {
                localStorage.setItem('token', token);

                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.getToken();
                return {
                    meta: [
                        {
                            auth: true
                        }
                    ]
                }
            },

            getToken() {
                let token = localStorage.getItem('token');

                if (!token) {
                    return null;
                }

                return token;
            },

            destroyToken() {
                localStorage.removeItem('token');
                return {
                    meta: [
                        {
                            auth: false
                        }
                    ]
                }
            },

            isAuthenticated() {
                return !!this.getToken();
            }
        };

        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.authPlugin.getToken();


        Object.defineProperties(Vue.prototype, {
            $authorization: {
                get() {
                    return Vue.authPlugin;
                }
            }
        });
    }
};

export default AuthPlugin;