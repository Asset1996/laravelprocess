<template>
    <!-- content -->
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img 
                            :src="photo_url" alt="Admin" 
                            class="rounded-circle" 
                            width="150"
                        >
                        <div class="mt-3">
                        <h4>{{fio}}</h4>
                        <p class="text-secondary mb-1">{{position}}</p>
                        <p class="text-muted font-size-sm">Mediana Services LLC</p>
                        <button 
                            data-bs-toggle="modal" data-bs-target="#imageModal"
                            class="btn btn-outline-primary">
                            Сменить аватар
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <template v-for="(item, key) in body" v-bind:key="key">
                                <div class="row">
                                    <div class="col-sm-3">
                                    <h6 class="mb-0">{{item.label}}</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{item.value}}
                                    </div>
                                </div>
                                <hr>
                            </template>
                        <div class="row">
                            <div class="col-sm-12">
                            <a class="btn btn-info " target="__blank" href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Редактировать профиль</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row gutters-sm">
                        <div class="col-sm-12 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                            <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Успеваемость</i>{{fio}}</h6>
                            <small>Web Design</small>
                            <div class="progress mb-3" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <form @submit="formSubmit" enctype="multipart/form-data">
                    <input type="file" class="form-control" v-on:change="onChange">
                    <button class="primary-button btn-block">Загрузить</button>
                </form>
                <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button" class="px-6
                    py-2.5
                    bg-purple-600
                    text-white
                    font-medium
                    text-xs
                    leading-tight
                    uppercase
                    rounded
                    shadow-md
                    hover:bg-purple-700 hover:shadow-lg
                    focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0
                    active:bg-purple-800 active:shadow-lg
                    transition
                    duration-150
                    ease-in-out" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios'
export default {
    data(){
        return{
            body: null,
            photo_url: null,
            position: null,
            fio: null,
            file: null,
            API_URL: process.env.MIX_APP_URL_API,
            APP_URL: process.env.MIX_APP_URL
        }
    },
    mounted(){
        this.getData()
    },
    methods:{
        onChange(e) {
            this.file = e.target.files[0];
            console.log(this.file)
        },
        getData(){
            axios
            .get(`${this.API_URL}/user/profile`)
            .then(response => {
                if(response.data){
                    this.body = response.data.data.body
                    this.photo_url = this.APP_URL + response.data.data.user.photo
                    this.position = response.data.data.user.position
                    this.fio = response.data.data.user.fio
                }
            })
        },
        formSubmit(e) {
            e.preventDefault();
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData();
            data.append('file', this.file);
            axios.post(`${this.API_URL}/user/profile/upload`, data, config)
            .then(res => {
                if(res.data.result){
                    this.$store.commit('setUserData', {"photo": res.data.data.photo})
                    this.$flashMessage.show({
                        status: 'success',
                        title: 'Успех',
                        text: res.data.message,
                    });
                    this.$router.go()
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
            });
        }
    }
}
</script>

