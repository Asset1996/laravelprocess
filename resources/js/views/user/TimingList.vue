<template>
    <!-- header -->
    <nav class="navbar">
        <div class="container-fluid">
            <p>{{user_fio}}</p>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Экспорт
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" :href="EXPORT_URL">Excel</a></li>
                    <li><a class="dropdown-item" :href="EXPORT_URL">PDF</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- component -->
    <table class="table table-hover">
        <thead>
            <tr tabindex="0" class="border border-gray-100">
                <th class="uk-table-shrink">No</th>
                <th v-for="header in headers" v-bind:key="header.id" :class="header.class">
                    {{header.value}}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, key) in body" v-bind:key="key" tabindex="0" class="border border-gray-100">
                <td>{{ key + page_limit + 1 }}</td>
                <template v-for="(header, hKey) in headers" :key="hKey">
                    <td>
                        {{ item[header.key] }}
                    </td>
                </template>
            </tr>
        </tbody>
    </table>

    <!-- paginator -->
    <div class="container px-5 py-2 mx-auto lg:pt-12 lg:px-32">
        <div v-if="paginator.has_pages" class="flex justify-center">
            <nav aria-label="Page navigation example">
                <ul class="flex list-style-none">
                    <li v-for="item in paginator.path_list" v-bind:key="item.key" class="page-item" v-bind:class="{ active: item.active }">
                        <button v-if="item.url"
                            class="page-link relative block py-1.5 px-3 rounded border-0 outline-none transition-all duration-300 rounded"
                            v-bind:class="{ 'bg-blue-600 text-white hover:text-white hover:bg-blue-600 shadow-md focus:shadow-md': item.active }"
                            @click.prevent="setParam('page', item.page)">{{item.label}}</button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data (){
        return {
            body: [],
            headers: [],
            paginator: [],
            user_id: this.$route.params.user_id,
            user_fio: '',
            API_URL: process.env.MIX_APP_URL_API,
            PUBLIC_URL: process.env.MIX_APP_URL_PUBLIC,
            EXPORT_URL: `${process.env.MIX_APP_URL_API}/user/timings-export/${this.$route.params.user_id}`,
            params: {
                page: null
            },
            page_limit: 0
        }
    },
    mounted() {
        this.getTimingList()
    },
    methods: {
        getTimingList(url){
            if(url == null) {
                url = this.API_URL + '/user/timings-list/' + this.user_id
            }
            axios
            .get(url, {params: this.params})
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
                    this.user_fio = response.data.data.userFio;
                }
            })
            .catch(error => {
                this.$flashMessage.show({
                    status: 'error',
                    title: 'Ошибка',
                    text: error.response.data.message || 'Ошибка',
                });
                this.$router.push(this.PUBLIC_URL)
            })
        },
        setParam(key, val){
            this.params[key] = val;
            this.getTimingList()
        }
    }
    
}
</script>
