import { createRouter, createWebHistory } from "vue-router";

import Store from './store'
import { flashMessage } from '@smartweb/vue-flash-message';

import Index from './views/Index.vue'
import Login from './views/Login.vue'
import LogsList from './views/history/List.vue'
import UsersList from './views/user/List.vue'
import TimingList from './views/user/TimingList.vue'
import Test from './views/Test.vue'

const PUBLIC_URL = process.env.MIX_APP_PATH_PUBLIC

const routes = [
    {
        path: PUBLIC_URL,
        component:() => import('./views/Index.vue')
    },
    {
        path: PUBLIC_URL + '/login',
        component:() => import('./views/Login.vue')
    },
    {
        path: PUBLIC_URL + '/history/list',
        component:() => import('./views/history/List.vue'),
        beforeEnter: () => isSuperadmin()
    },
    {
        path: PUBLIC_URL + '/user/list',
        component:() => import('./views/user/List.vue'),
        beforeEnter: () => isSuperadmin()
    },
    {
        path: PUBLIC_URL + '/user/timings-list/:user_id',
        component:() => import('./views/user/TimingList.vue'),
        beforeEnter: () => isSuperadmin()
    },
    {
        path: PUBLIC_URL + '/test',
        component:() => import('./views/Test.vue')
    },
]

function isSuperadmin() {
    if (Store.state.userData['roles_id'] == 1) {
        return true;
    }
    flashMessage.show({
        status: 'error',
        title: 'Ошибка',
        text: 'You have no permission',
    })
    return false;
}

const router = createRouter({
    history: createWebHistory(process.env.APP_URL),
    routes
})

export default router