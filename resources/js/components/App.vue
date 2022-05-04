<template>
    <div>
        <div class="col-12">
            <Menu></Menu>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div v-if="isLogged" class="col-3 leftbar">
                        <LeftBar ></LeftBar>
                    </div>
                    <div :class=divClass>
                        <div class="container">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
                <FlashMessage position="right top" time="3000"  strategy="single"/>
            </div>
            <div class="col-12">
                <!-- <Footer ></Footer> -->
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            divClass: 'col-12'
        }
    },
    computed: {
        isLogged(){
            if(this.$store.state.token == null){
                this.divClass = 'col-12'
                return false;
            }else{
                this.divClass = 'col-9'
                return true;
            }
        },
    }
}
</script>