import {createRouter, createWebHistory} from "vue-router";
import Main from "./components/Main.vue";
import BookList from "./components/books/BookList.vue";
import BookShow from "./components/books/BookShow.vue";
import Login from "./components/auth/Login.vue";
import Register from "./components/auth/Register.vue";
import SecurityService from "./services/security.service";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: Main
        },
        {
            path: '/login',
            component: () => Login
        },
        {
            path: '/register',
            component: () => Register
        },
        {
            path: '/books',
            component: () => BookList,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/books/:id',
            component: () => BookShow,
            meta: {
                requiresAuth: true
            }
        }
    ],
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        if (SecurityService.isAuthenticated()) {
            next();
        } else {
            next('/login');
        }
    } else {
        next();
    }
});

export default router;