import { createRouter, createWebHistory } from 'vue-router'
import Register from './components/pages/Register.vue'
import Login from './components/pages/Login.vue'
import WordList from './components/pages/WordList.vue'
import AddWord from './components/pages/AddWord.vue'
import ExampleList from './components/pages/ExampleList.vue'
import UpdateWord from './components/pages/UpdateWord.vue'

const routes = [
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/list',
        name: 'WordList',
        component: WordList,
    },
    {
        path: '/list/example',
        name: 'ExampleList',
        component: ExampleList,
    },
    {
        path: '/add',
        name: 'AddWord',
        component: AddWord,
    },
    {
        path: '/word/:id',
        name: 'UpdateWord',
        component: UpdateWord,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router