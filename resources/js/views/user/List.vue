<template>
    <!-- header -->
    <nav class="navbar">
        <div class="container-fluid">
            <div>Список пользователей</div>
            <div class="d-flex"><router-link :to="PUBLIC_PATH + '/user/create'" type="button" class="primary-button">Добавить пользователя</router-link></div>
        </div>
    </nav>
    <!-- component -->
    <table class="table table-hover">
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
                    <template v-if="isObject(item[header.key])">
                        <td :style="item[header.key].class">
                            {{ item[header.key].value }}
                        </td>
                    </template>
                    <template v-else>
                        <td>
                            <template v-if="header.key == 'fio'">
                                <router-link :to="PUBLIC_PATH+'/user/timings-list/'+item['id']">{{ item[header.key] }}</router-link>
                            </template>
                            <template v-else>
                                {{ item[header.key] }}
                            </template>
                        </td>
                    </template>
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
    components: {
        paginator
    },
    data(){
        return {
            body: [],
            headers: [],
            paginator: [],
            MAIN_URL: process.env.MIX_APP_URL,
            API_URL: process.env.MIX_APP_URL_API,
            PUBLIC_URL: process.env.MIX_APP_URL_PUBLIC,
            PUBLIC_PATH: process.env.MIX_APP_PATH_PUBLIC,
            params: {
                page: null
            },
            page_limit: 0
        }
    },
    mounted() {
        this.getUsersList()
    },
    methods: {
        getUsersList(url=null){
            if(url == null) {
                url = this.API_URL + '/user/list'
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
        isObject(item){
            return typeof item == 'object'
        },
        setParam(key, val){
            this.params[key] = val;
            this.getUsersList()
        }
    }
}
</script>
