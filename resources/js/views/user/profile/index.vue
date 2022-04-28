<template>
    <div class="container">
    <div class="main-body">
    
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img :src="photo_url" alt="Admin" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4>{{fio}}</h4>
                      <p class="text-secondary mb-1">{{position}}</p>
                      <p class="text-muted font-size-sm">Mediana Services LLC</p>
                      <button class="btn btn-outline-primary">Сменить аватар</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                    <template v-for="(item, key) in body" v-bind:key="key">
                        <div class="row">
                            <div class="col-sm-3">
                            <h6 class="mb-0">{{item.label}}</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{item.value}}
                            </div>
                        </div>
                        <hr>
                    </template>
                  <div class="row">
                    <div class="col-sm-12">
                      <a class="btn btn-info " target="__blank" href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Редактировать профиль</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row gutters-sm">
                <div class="col-sm-12 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Успеваемость</i>{{fio}}</h6>
                      <small>Web Design</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

    </div>
    </div>

</template>

<script>
import axios from 'axios'
export default {
    data(){
        return{
            body: null,
            photo_url: null,
            position: null,
            fio: null,
            API_URL: process.env.MIX_APP_URL_API,
            APP_URL: process.env.MIX_APP_URL
        }
    },
    mounted(){
        axios
        .get(`${this.API_URL}/user/profile`)
        .then(response => {
            if(response.data){
                this.body = response.data.data.body
                this.photo_url = this.APP_URL + response.data.data.user.photo
                this.position = response.data.data.user.position
                this.fio = response.data.data.user.fio
            }
        })
    }
}
</script>

