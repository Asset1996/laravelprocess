import axios from 'axios'
import store from './store'

axios.interceptors.request.use(
    (config) => {
        const token = store.state.token;
        if (token) {
            config.headers["Authorization"] = 'Bearer ' + token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axios.interceptors.response.use((response) => {
    return response
}, function (error) {
    let originalRequest = error.config
    if (error.response.status === 403 && !originalRequest._retry) {
        originalRequest._retry = true
        window.location.href = process.env.MIX_APP_PATH_PUBLIC + '/'
        return
    }
    if (error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true
        store.commit('clearToken')
        store.commit('clearUserData')
        window.location.href = process.env.MIX_APP_PATH_PUBLIC + '/login'
        return
    }

    return Promise.reject(error)
})

export default axios