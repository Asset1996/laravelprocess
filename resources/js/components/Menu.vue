<template>
<nav class="navbar navbar-light" style="background-color: #32a1ce;">
  <div class="container-fluid">
      <div class="menu-logo">
          <router-link class="navbar-brand" :to="links.home.href">REGI<span style="color:orange">ME</span></router-link>
      </div>
    <div class="d-flex">
      <router-link class="primary-button" v-if="!isLogged" :to="links.login.href"> Войти </router-link>
      <router-link class="primary-button" v-if="isLogged" :to="links.logout.href" @click.prevent="logout"> Выйти </router-link>
    </div>
  </div>
</nav>

</template>
<script>
    export default {
        data() {
            return {
                links: {
                    home: {
                        'title': 'REGIME',
                        'href': process.env.MIX_APP_PATH_PUBLIC
                    },
                    login: {
                        'title': 'Войти',
                        'href': process.env.MIX_APP_PATH_PUBLIC + '/login/'
                    },
                    logout: {
                        'title': 'Выйти',
                        'href': process.env.MIX_APP_PATH_API + '/auth/logout/'
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
                .post(process.env.MIX_APP_PATH_API + '/auth/logout', [], {headers})
                .then(response => {
                    this.$store.commit('clearToken')
                    this.$store.commit('clearUserData')
                    this.$router.push(process.env.MIX_APP_PATH_PUBLIC + '/login')
                })
                .catch(error => {
                });
            }
        }
    }
</script>
