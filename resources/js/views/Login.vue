<template>
    <div class="container" style="max-width: 400px; margin-top: 150px">
        <div class="mb-3">
            <label for="iin" class="form-label" >ИИН</label>
            <input class="form-control" type="text" id="iin" v-model="form.iin" required placeholder="ИИН">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input class="form-control" type="password" id="password" multiple v-model="form.password" required placeholder="Пароль">
        </div>
        <div class="mb-3">
            <button type="submit" class="primary-button" @click.prevent="login">Войти</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
    export default {
        components: {},
        data(){
            return {
                form:{
                    iin: "",
                    password: ""
                },
                API_URL: process.env.MIX_APP_URL_API,
                PUBLIC_PATH: process.env.MIX_APP_PATH_PUBLIC,
                error: false
            }
        },
        methods: {
            login(){
                axios
                .post(this.API_URL + "/auth/login", this.form, {
                    headers: {
                        'Content-type': 'application/json'
                    }
                }).then(res => {
                    if(res.data.result){
                        let payload = this.$jwt_decode(res.headers['x-auth'])
                        this.$store.commit('setToken', res.headers['x-auth'])
                        this.$store.commit('setUserData', payload)
                        this.$flashMessage.show({
                            status: 'success',
                            title: 'Успех',
                            text: res.data.message,
                        });
                        this.$router.push(this.PUBLIC_PATH);
                    }else{
                        this.$flashMessage.show({
                            status: 'error',
                            title: 'Ошибка',
                            text: err.response.data.message || 'Ошибка',
                        });
                    }
                })
                .catch(err => {
                    this.$flashMessage.show({
                        status: 'error',
                        title: 'Ошибка',
                        text: err.response.data.message || 'Ошибка',
                    });
                })
            }
        }
    }
</script>
