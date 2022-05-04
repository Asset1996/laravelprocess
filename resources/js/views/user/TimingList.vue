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
    <paginator :paginator="paginator" @setPage="setParam"></paginator>
</template>

<script>
import axios from 'axios'
import paginator from '../../components/Paginator.vue'
export default {
    components:{

    },
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
