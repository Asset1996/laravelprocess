import { createStore } from 'vuex'

const store = createStore({
    state () {
      return {
        token: JSON.parse(localStorage.getItem('token') || null),
        userData: JSON.parse(localStorage.getItem('userData') || null)
      }
    },
    getters: {
        getToken(state){
            return state.token
        }
    },
    mutations: {
      setToken (state, token) {
        state.token = token
        localStorage.setItem('token', JSON.stringify(token))
      },
      clearToken (state) {
        state.token = null
        localStorage.removeItem('token')
      },
      setUserData (state, payload){
        let user = [];
        user = {
          name: payload['name'] || '',
          surname: payload['surname'] || '',
          roles_id: payload['roles_id'] || '',
          photo: payload['photo'] || '',
          positions_id: payload['positions_id'] || ''
        }
        state.userData = user
        localStorage.setItem('userData', JSON.stringify(user))
      },
      clearUserData (state) {
        state.userData = []
        localStorage.removeItem('userData')
      }
    }
})

export default store