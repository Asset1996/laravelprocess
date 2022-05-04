<template>
    <div>
        <!-- header -->
        <nav class="navbar">
            <div class="container-fluid">
                <div>Журнал</div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Поиск по ФИО</span>
                    <input v-model="filter" type="text" class="form-control" aria-label="Введите имя, фамилию искомого пользователя">
                    <span class="input-group-text">Поиск по дате</span>
                    <input type="date"  @input="setParam('created_at', $event.srcElement.value)" class="form-control">
                </div>
            </div>
        </nav>

        <!-- component -->
        <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Время входа</th>
                <th scope="col">Локация входа</th>
                <th scope="col">Снимок входа</th>
                <th scope="col">Время выхода</th>
                <th scope="col">Локация выхода</th>
                <th scope="col">Снимок выхода</th>
                </tr>
            </thead>
        </table>
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <template v-for="(item, key) in body.filter(b => b.user.fio.toLowerCase().includes(filter.toLowerCase()))" v-bind:key="key">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+item.user.id" aria-expanded="true" :aria-controls="'collapse'+item.user.id">
                        {{item.user.fio}}
                    </button>
                    </h2>
                    <div :id="'collapse'+item.user.id" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-6">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr class="table-row" v-for="(sub_item, sub_key) in item.couples" v-bind:key="sub_key">
                                                <th scope="row">{{ sub_key + 1}}</th>
                                                <td><span v-if="sub_item[1]">{{sub_item[1].created_at}}</span></td>
                                                <td><span v-if="sub_item[1]">{{sub_item[1].device.location}}</span></td>
                                                <td><span v-if="sub_item[1]">
                                                    <img 
                                                        data-bs-toggle="modal" data-bs-target="#imageModal"
                                                        type="button" 
                                                        class="uk-preserve-width" 
                                                        :src="MAIN_URL + sub_item[1].image_path" 
                                                        width="80" height="60" 
                                                        alt=""
                                                        @click="sendInfoToModal(sub_item[1].image_path)"
                                                    >
                                                </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr class="table-row" v-for="(sub_item, sub_key) in item.couples" v-bind:key="sub_key">
                                                <th scope="row">{{ sub_key + 1}}</th>
                                                <td><span v-if="sub_item[0]">{{sub_item[0].created_at}}</span></td>
                                                <td><span v-if="sub_item[0]">{{sub_item[0].device.location}}</span></td>
                                                <td><span v-if="sub_item[0]">
                                                    <img 
                                                        data-bs-toggle="modal" data-bs-target="#imageModal"
                                                        type="button" 
                                                        class="uk-preserve-width" 
                                                        :src="MAIN_URL + sub_item[0].image_path" 
                                                        width="80" height="60" 
                                                        alt=""
                                                        @click="sendInfoToModal(sub_item[0].image_path)"
                                                    >
                                                </span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal -->
        <div class="modal" tabindex="-1" id="imageModal">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-body">
                    <div v-if="image" class="modal-body relative p-4">
                        <p><img :src="MAIN_URL + image" alt=""></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        components: {},
        data(){
            return {
                body: [],
                userList: [],
                image: null,
                MAIN_URL: process.env.MIX_APP_URL,
                API_URL: process.env.MIX_APP_URL_API,
                PUBLIC_URL: process.env.MIX_APP_URL_API,
                params: {
                    fio: null,
                    created_at: null,
                    page: null
                },
                filter: '',
            }
        },
        mounted() {
            this.logsList(),
            this.getUsersFioAndIdList()
        },
        methods: {
            logsList(url=null) {
                if(url == null) {
                    url = this.API_URL + '/history/list'
                }
                axios
                .get(url, {params: this.params})
                .then(response => {
                    if(response.data.result){
                        this.body = response.data.data.body;
                    }
                })
                .catch(err => {
                    this.$flashMessage.show({
                        status: 'error',
                        title: 'Ошибка',
                        text: err || 'Ошибка',
                    });
                    this.$router.push(this.PUBLIC_URL)
                })
            },
            isObject(item){
                return typeof item == 'object'
            },
            sendInfoToModal(image) {
                this.image = image;
            },
            getUsersFioAndIdList(url=null){
                let request_headers = {}
                request_headers['Accept'] = 'application/json'
                if(this.$store.state.token !== null) {
                    request_headers['Authorization'] = `Bearer ${this.$store.state.token}`
                }
                url = this.API_URL + '/user/fio-and-id-list'

                axios
                .get(url, {headers: request_headers})
                .then(response => {
                    if(response.data.result){
                        this.userList = response.data.data;
                    }
                })
            },
            setParam(key, val){
                this.params[key] = val;
                this.logsList()
            },
            // filteredList(search){
            //     console.log(search)
            //     let filtered = this.body.filter((b) => {
            //         return b.user.fio.toLowerCase().includes(search.toLowerCase())
            //     })
            //     console.log(filtered)
            // }
        },
        
    }
</script>
