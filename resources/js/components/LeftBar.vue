<template>
    <div class="w-60 h-full shadow-md bg-white absolute" id="sidenavSecExample">
  <div v-if="isLogged" class="pt-4 pb-2 px-6">
    <a href="#!">
      <div class="flex items-center">
        <div class="shrink-0">
          <img src="https://mdbcdn.b-cdn.net/img/new/avatars/8.webp" class="rounded-full w-10" alt="Avatar">
        </div>
        <div class="grow ml-3">
          <p class="text-sm font-semibold text-blue-600">{{ getNameAndRole }}</p>
        </div>
      </div>
    </a>
  </div>
  <ul class="relative px-1">
    <li v-if="isLogged" class="relative">
        <router-link class="flex items-center text-sm py-4 px-6 h-12 overflow-hidden text-gray-700 text-ellipsis whitespace-nowrap rounded hover:text-blue-600 hover:bg-blue-50 transition duration-300 ease-in-out" :to="links.usersList.href"><span>{{ links.usersList.title }}</span></router-link>
    </li>
    <li v-if="isLogged" class="relative">
        <router-link class="flex items-center text-sm py-4 px-6 h-12 overflow-hidden text-gray-700 text-ellipsis whitespace-nowrap rounded hover:text-blue-600 hover:bg-blue-50 transition duration-300 ease-in-out" :to="links.logsList.href"><span>{{ links.logsList.title }}</span></router-link>
    </li>
  </ul>
  <div class="text-center bottom-0 absolute w-full">
    <hr class="m-0">
    <p class="py-2 text-sm text-gray-700">Mediana Services</p>
  </div>
</div>
</template>

<script>
import axios from 'axios';
    export default {
        data() {
            return {
                links: {
                    logsList: {
                        'title': 'Журнал',
                        'href': process.env.MIX_PATH_PUBLIC + '/history/list/'
                    },
                    usersList: {
                        'title': 'Список пользователей',
                        'href': process.env.MIX_PATH_PUBLIC + '/user/list/'
                    },
                    test: {
                        'title': 'TEST',
                        'href': process.env.MIX_PATH_PUBLIC + '/test/'
                    }
                }
            }
        },
        computed: {
            isLogged(){
                if(this.$store.state.token == null){
                    return false;
                }else{
                    return true;
                }
            },
            getNameAndRole(){
                if (this.$store.state.userData){
                    return this.$store.state.userData['name'];
                }else{
                    return false;
                }
            },
        }
    }
</script>
