<template>
<!-- This example requires Tailwind CSS v2.0+ -->
<div class="relative bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex justify-between items-center border-b-2 border-gray-100 py-6 md:justify-start md:space-x-10">
      <div class="flex justify-start lg:w-0 lg:flex-1">
        <router-link class="nav-link active" :to="links.home.href">
          <span class="sr-only">{{ links.home.title }}</span>
          <img class="h-8 w-auto sm:h-10" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="">
        </router-link>
      </div>
      <div class="-mr-2 -my-2 md:hidden">
        <button type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
          <span class="sr-only">Open menu</span>
          <!-- Heroicon name: outline/menu -->
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
      <nav class="hidden md:flex space-x-10">
        <div class="relative">
          <!-- Item active: "text-gray-900", Item inactive: "text-gray-500" -->
          <!-- <button type="button" class="text-gray-500 group bg-white rounded-md inline-flex items-center text-base font-medium hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" aria-expanded="false">
            <span><router-link class="nav-link active" :to="links.home.href">{{ links.home.title }}</router-link></span>
          </button> -->
        </div>
      </nav>
      <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
        <router-link v-if="!isLogged" :to="links.login.href" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900"> Войти </router-link>
        <router-link v-if="isLogged" :to="links.logout.href" @click.prevent="logout" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900"> Выйти </router-link>
        <router-link v-if="!isLogged" :to="'/'" class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700"> Зарегистрироваться </router-link>
      </div>
    </div>
  </div>
</div>

    
</template>
<script>
    export default {
        data() {
            return {
                links: {
                    home: {
                        'title': 'Главное',
                        'href': process.env.MIX_THIS_PATH_PUBLIC
                    },
                    login: {
                        'title': 'Войти',
                        'href': process.env.MIX_THIS_PATH_PUBLIC + '/login/'
                    },
                    logout: {
                        'title': 'Выйти',
                        'href': process.env.MIX_THIS_PATH_API + '/auth/logout/'
                    },
                }
            }
        },
        computed:{
            isLogged(){
                if(this.$store.state.token == null){
                    return false;
                }else{
                    return true;
                }
            }
        },
        methods: {
            logout() {
                let headers = {}
                if(this.$store.state.token !== null) {
                    headers['Authorization'] = 'Bearer' + ' ' + this.$store.state.token
                }
                headers['Accept'] = 'application/json'

                axios
                .post(process.env.MIX_THIS_PATH_API + '/auth/logout', [], {headers})
                .then(response => {
                    this.$store.commit('clearToken')
                    this.$store.commit('clearUserData')
                    this.$router.push(process.env.MIX_THIS_PATH_PUBLIC + '/login')
                })
                .catch(error => {
                });
            }
        }
    }
</script>
