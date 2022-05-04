<template>
    <nav class="nav flex-column">
        <div class="position-relative">
            <div v-if="isLogged" class="left-profile-info leftbar-item">
                <router-link :to="links.profileIndex.href">
                    <div class="row center-items">
                        <div class="col-2 center-items">
                            <img class="left-avatar" :src="photo_url" alt="Avatar">
                        </div>
                        <div class="col-10 center-items">
                            {{ user_name }}
                        </div>
                    </div>
                </router-link>
            </div>
            <div v-if="isLogged" class="leftbar-item">
                <router-link class="nav-link" :to="links.usersList.href">{{ links.usersList.title }}</router-link>
            </div>
            <div v-if="isLogged" class="leftbar-item">
                <router-link class="nav-link" :to="links.logsList.href">{{ links.logsList.title }}</router-link>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                links: {
                    logsList: {
                        'title': 'Журнал',
                        'href': process.env.MIX_APP_PATH_PUBLIC + '/history/list/'
                    },
                    usersList: {
                        'title': 'Список пользователей',
                        'href': process.env.MIX_APP_PATH_PUBLIC + '/user/list/'
                    },
                    profileIndex: {
                        'title': 'Кабинет',
                        'href': process.env.MIX_APP_PATH_PUBLIC + '/user/profile/'
                    }
                },
                photo_url: '',
                user_name: '',
            }
        },
        mounted(){
            if (this.$store.state.userData){
                this.photo_url = process.env.MIX_APP_PATH + this.$store.state.userData['photo']
                this.user_name = this.$store.state.userData['name']
            }
        },
        computed: {
            isLogged(){
                if(this.$store.state.token == null){
                    return false;
                }else{
                    return true;
                }
            }
        }
    }
</script>
