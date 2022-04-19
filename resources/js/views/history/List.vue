<template>
    <div>
        <!-- component -->
        <div class="sm:px-6 w-full">
            <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
            <div class="px-4 md:px-10 py-4 md:py-7">
                <div class="flex items-center justify-between">
                    <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">Журнал</p>
                    <!-- <div class="py-3 px-4 flex items-center text-sm font-medium leading-none text-gray-600 bg-gray-200 hover:bg-gray-300 cursor-pointer rounded">
                        <p>Sort By:</p>
                        <select aria-label="select" class="focus:text-indigo-600 focus:outline-none bg-transparent ml-1">
                            <option class="text-sm text-indigo-800">Первый</option>
                            <option class="text-sm text-indigo-800">Второй</option>
                            <option class="text-sm text-indigo-800">Третий</option>
                        </select>
                    </div> -->
                </div>
            </div>
            <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
                <!-- <div class="sm:flex items-center justify-between">
                    <div class="flex items-center">
                        <a class="rounded-full focus:outline-none focus:ring-2  focus:bg-indigo-50 focus:ring-indigo-800" href=" javascript:void(0)">
                            <div class="py-2 px-8 bg-indigo-100 text-indigo-700 rounded-full">
                                <p>Все</p>
                            </div>
                        </a>
                        <a class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 ml-4 sm:ml-8" href="javascript:void(0)">
                            <div class="py-2 px-8 text-gray-600 hover:text-indigo-700 hover:bg-indigo-100 rounded-full ">
                                <p>Не все</p>
                            </div>
                        </a>
                        <a class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 ml-4 sm:ml-8" href="javascript:void(0)">
                            <div class="py-2 px-8 text-gray-600 hover:text-indigo-700 hover:bg-indigo-100 rounded-full ">
                                <p>Не всякий</p>
                            </div>
                        </a>
                    </div>
                </div> -->
                <div class="mt-7 overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr tabindex="0" class="focus:outline-none h-16 border border-gray-100 rounded">
                                <th class="uk-table-shrink">No</th>
                                <th v-for="header in headers" v-bind:key="header.id" class="uk-table-shrink" :class="header.class">
                                    {{header.value}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, key) in body" v-bind:key="key" tabindex="0" class="focus:outline-none h-16 border border-gray-100 rounded">
                                <td>{{ key + page_limit + 1 }}</td>
                                <template v-for="(header, hKey) in headers" :key="hKey">
                                    <td>
                                        <template v-if="header.key == 'image_path'">
                                            <img 
                                                data-bs-toggle="modal" data-bs-target="#imageModal"
                                                type="button" 
                                                class="uk-preserve-width" 
                                                :src="MAIN_URL + item[header.key]" 
                                                width="80" height="60" 
                                                alt=""
                                                @click="sendInfoToModal(item[header.key])"
                                            >
                                        </template>
                                        <template v-else>
                                            <template v-if="isObject(item[header.key])">
                                                <template v-if="item[header.key].key == 0">
                                                    <button class="py-3 px-3 text-sm focus:outline-none leading-none text-red-700 bg-red-100 rounded">{{ item[header.key].value }}</button>
                                                </template>
                                                <template v-else>
                                                    <button class="py-3 px-3 text-sm focus:outline-none leading-none text-black-700 bg-green-100 rounded">{{ item[header.key].value }}</button>
                                                </template>
                                            </template>
                                            <template v-else>
                                                {{ item[header.key] }}
                                            </template>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- paginator -->
        <div class="container px-5 py-2 mx-auto lg:pt-12 lg:px-32">
            <div v-if="paginator.has_pages" class="flex justify-center">
                <nav aria-label="Page navigation example">
                    <ul class="flex list-style-none">
                        <li v-for="item in paginator.path_list" v-bind:key="item.key" class="page-item" v-bind:class="{ active: item.active }">
                            <button v-if="item.url"
                                class="page-link relative block py-1.5 px-3 rounded border-0 outline-none transition-all duration-300 rounded"
                                v-bind:class="{ 'bg-blue-600 text-white hover:text-white hover:bg-blue-600 shadow-md focus:shadow-md': item.active }"
                                @click.prevent="logsList(item.url)">{{item.label}}</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
            class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div v-if="image" class="modal-body relative p-4">
                    <p><img :src="'http://localhost:8888/laravelprocess' + image" alt=""></p>
                </div>
                <div
                    class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
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
                    ease-in-out" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "List",
        data(){
            return {
                body: [],
                headers: [],
                paginator: [],
                image: null,
                MAIN_URL: process.env.MIX_APP_URL,
                API_URL: process.env.MIX_APP_URL_API,
                PUBLIC_URL: process.env.MIX_APP_URL_API,
                page_limit: 0
            }
        },
        mounted() {
            this.logsList()
        },
        methods: {
            logsList(url=null) {
                let headers = {}
                if(this.$store.state.token !== null) {
                    headers['Authorization'] = 'Bearer' + ' ' + this.$store.state.token
                }
                headers['Accept'] = 'application/json'
                if(url == null) {
                    url = this.API_URL + '/history/list'
                }

                axios
                .get(url, {headers})
                .then(response => {
                    if(response.data.result){
                        this.headers = response.data.data.headers;
                        this.body = response.data.data.body;
                        this.paginator = response.data.data.paginator;
                        if(this.paginator.current_page !== 1){
                            this.page_limit = this.paginator.per_page * this.paginator.current_page - this.paginator.per_page
                        }else{
                            this.page_limit = 0
                        }
                    }
                })
                .catch(err => {
                    this.$flashMessage.show({
                        status: 'error',
                        title: 'Ошибка',
                        text: err.response.data.message || 'Ошибка',
                    });
                    this.$router.push(this.PUBLIC_URL)
                })
            },
            isObject(item){
                return typeof item == 'object'
            },
            sendInfoToModal(image) {
                this.image = image;
            }
        }
    }
</script>
