<template>
    <!-- component -->
    <div class="sm:px-6 w-full">
        <div class="px-4 md:px-10 py-4 md:py-7">
            <div class="flex items-center justify-between">
                <p tabindex="0" class="focus:outline-none text-base sm:text-lg md:text-xl lg:text-2xl font-bold leading-normal text-gray-800">USER FIO</p>
                <div class="flex justify-center">
                    <div class="dropdown relative">
                    <button
                        class="
                        dropdown-toggle
                        px-6
                        py-2.5
                        bg-blue-600
                        text-white
                        font-medium
                        text-xs
                        leading-tight
                        uppercase
                        rounded
                        shadow-md
                        hover:bg-blue-700 hover:shadow-lg
                        focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0
                        active:bg-blue-800 active:shadow-lg active:text-white
                        transition
                        duration-150
                        ease-in-out
                        flex
                        items-center
                        whitespace-nowrap
                        "
                        type="button"
                        id="dropdownMenuButton1"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Экспорт
                        <path
                            fill="currentColor"
                            d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"
                        ></path>
                    </button>
                    <ul
                        class="
                        dropdown-menu
                        min-w-max
                        absolute
                        hidden
                        bg-white
                        text-base
                        z-50
                        float-left
                        py-2
                        list-none
                        text-left
                        rounded-lg
                        shadow-lg
                        mt-1
                        hidden
                        m-0
                        bg-clip-padding
                        border-none
                        "
                        aria-labelledby="dropdownMenuButton1"
                    >
                        <li>
                            <a
                                class="
                                dropdown-item
                                text-sm
                                py-2
                                px-4
                                font-normal
                                block
                                w-full
                                whitespace-nowrap
                                bg-transparent
                                text-gray-700
                                hover:bg-gray-100
                                "
                                :href="EXPORT_URL"
                                >Excel
                            </a
                        >
                        </li>
                        <li>
                        <a
                            class="
                            dropdown-item
                            text-sm
                            py-2
                            px-4
                            font-normal
                            block
                            w-full
                            whitespace-nowrap
                            bg-transparent
                            text-gray-700
                            hover:bg-gray-100
                            "
                            :href="EXPORT_URL"
                            >PDF</a
                        >
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white py-4 md:py-7 px-4 md:px-8 xl:px-10">
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
                            <td>{{ key + 1 }}</td>
                            <template v-for="(header, hKey) in headers" :key="hKey">
                                <td>
                                    {{ item[header.key] }}
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
            MAIN_URL: process.env.MIX_THIS_URL,
            API_URL: process.env.MIX_THIS_URL_API,
            PUBLIC_URL: process.env.MIX_THIS_URL_PUBLIC,
            PUBLIC_PATH: process.env.MIX_THIS_PATH_PUBLIC,
            EXPORT_URL: `${process.env.MIX_THIS_URL_API}/user/timings-export/${this.$route.params.user_id}`,
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
            let request_headers = {}
            if(this.$store.state.token !== null) {
                    request_headers['Authorization'] = 'Bearer' + ' ' + this.$store.state.token
                }
            request_headers['Accept'] = 'application/json'
            if(url == null) {
                url = this.API_URL + '/user/timings-list/' + this.user_id
            }

            axios
            .get(url, {headers: request_headers, params: this.params})
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
                    let payload = this.$jwt_decode(this.$store.state.token)
                    this.user_fio = payload.surname + ' ' + payload.name
                }
            })
            .catch(error => {
                this.$flashMessage.show({
                    status: 'error',
                    title: 'Ошибка',
                    text: err.response.data.message || 'Ошибка',
                });
                this.$router.push(this.PUBLIC_URL)
            })
        },
        setParam(key, val){
            this.params[key] = val;
            this.logsList()
        }
    }
    
}
</script>
