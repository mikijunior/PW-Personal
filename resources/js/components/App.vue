<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">PW Personal</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li v-if="!$auth.check()" class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'login' }" v-text="$ml.get('login')"></router-link>
                    </li>
                    <li v-if="!$auth.check()" class="nav-itemt">
                        <router-link class="nav-link" :to="{ name: 'register' }" v-text="$ml.get('registration')"></router-link>
                    </li>
                    <li v-if="$auth.check()" class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'personal' }" v-text="$ml.get('personal')"></router-link>
                    </li>
                    <li v-if="$auth.check()" class="nav-item">
                        <a class="nav-link" href="#" @click.prevent="$auth.logout()" v-text="$ml.get('logout')"></a>
                    </li>
                    <li v-if="$auth.check()" class="nav-item">
                        <router-link class="nav-link" href="#" :to="{ name: 'changePassword' }" v-text="$ml.get('changePassword')"></router-link>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"  style="padding: 0;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img v-if="axios.defaults.headers.appLocale === 'en'" src="../../../public/flags/en.png" width="32" height="32">
                            <img v-if="axios.defaults.headers.appLocale === 'ru'" src="../../../public/flags/ru.png" width="32" height="32">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" @click="changeLocale('en')">
                                <img src="../../../public/flags/en.png" width="32" height="32">ENG</a>
                            <a class="dropdown-item" @click="changeLocale('ru')">
                                <img src="../../../public/flags/ru.png" width="32" height="32">RUS</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>

</template>

<script>
    import { Validator }  from 'vee-validate';

    export default {
        name: "App",
        methods: {
            changeLocale(lang) {
                this.$ml.change(lang);
                localStorage.setItem('lang', lang);
                axios.defaults.headers.appLocale = lang;
                Validator.localize(lang, lang);
            }
        }
    }
</script>
