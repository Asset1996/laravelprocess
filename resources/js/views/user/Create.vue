<template>
    <!-- header -->
    <nav class="navbar">
        <div class="container-fluid">
            <div>Создание нового пользователя</div>
        </div>
    </nav>
    <!-- component -->
    <form>
        <div v-for="(item, key) in fields" v-bind:key="key" class="form-group">
            <label for="exampleInputEmail1">{{item.label}}</label>
            <template v-if="item.type == 'dropdown'">
                <select v-model="item.value" class="form-control">
                    <template v-for="handbook in item.handbook" v-bind:key="handbook.id" >
                        <option :value="handbook.id" class="text-sm text-indigo-800">{{handbook.title}}</option>
                    </template>
                </select>
            </template>
            <template v-else>
                <input :type="item.type" class="form-control" v-model="item.value">
            </template>
        </div>

        <button @click.prevent="sendRequest" type="submit" style="margin-top: 10px" class="btn btn-success">Создать</button>
    </form>
</template>

<script>
import axios from 'axios'
export default {
    data(){
        return {
            API_URL: process.env.MIX_APP_URL_API,
            fields: {}
        }
    },
    mounted(){
        this.getFields()
    },
    methods:{
        getFields(){
            axios
            .get(this.API_URL + '/user/create')
            .then(response => {
                if(response.data.result){
                    this.fields = response.data.data.fields;
                }
            })
            .catch(error => {
                this.$flashMessage.show({
                    status: 'error',
                    title: 'Ошибка',
                    text: error.response.data.message || 'Ошибка',
                });
                this.$router.push(this.PUBLIC_PATH)
            })
        },
        sendRequest(){
            let sendData = {};
            for (let [key, item] of Object.entries(this.fields)) {
                sendData[key] = item.value;
            }
            axios
            .post(this.API_URL + '/user/create', sendData, {
                headers: {
                    'Content-type': 'application/json'
                }
            })
            .then(res => {
                if(res.data.result){
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
                        text: 'Ошибка',
                    });
                }
            })
            .catch(err => {
                if(err.response.status == 422){
                    for (let [key, value] of Object.entries(err.response.data.errors)) {
                        this.$flashMessage.show({
                            status: 'error',
                            title: 'Ошибка валидации',
                            text: value || 'Ошибка',
                        });
                    }
                }else{
                    this.$flashMessage.show({
                        status: 'error',
                        title: 'Ошибка',
                        text: err.response.data.message || 'Ошибка',
                    });
                }
            })
            
        }
    }
}
</script>
