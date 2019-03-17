<template>
    <div>
        <p>{{ $ml.get('login') + ' : ' + name }}</p>
        <p>{{ $ml.get('email') + ' : ' + email }}</p>
        <p>{{ $ml.get('question') + ' : ' + question }}</p>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name: '',
                question: '',
                email: '',
            }
        },
        methods: {
            getUserData() {
                axios.get('personal/user')
                    .then( response => {
                        this.name = response.data.name;
                        this.question = response.data.Promt;
                        this.email = response.data.email;
                    });
            }
        },
        mounted() {
            this.getUserData();
        }
    }
</script>