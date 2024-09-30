import { createRouter, createWebHistory } from "vue-router";
import {authRequest} from "@/api.js";

const router = createRouter({
    history: createWebHistory(process.env.VUE_DOMAIN_FRONTEND),
    routes: [
        {
            path: "/",
            name: "Home",
            component: () => import("@/views/HomePage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/posts/:id",
            name: "SinglePost",
            component: () => import("@/views/SinglePostPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/post-list/:page",
            name: "ListPost",
            component: () => import("@/views/BlogPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/registration",
            name: "Registration",
            component: () => import("@/views/RegistrationPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/login",
            name: "Login",
            component: () => import("@/views/LoginPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/reset-email",
            name: "PasswordResetEmail",
            component: () => import("@/views/ResetPasswordEmailPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/password/reset/:token",
            name: "PasswordReset",
            component: () => import("@/views/ResetPasswordPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/profile",
            name: "Profile",
            component: () => import("@/views/ProfilePage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/admin",
            name: "admin",
            component: () => import("@/views/admin/AdminIndex.vue"),
            children: [
                {
                    path: '',
                    component: () => import("@/views/admin/AdminDashboard.vue"),
                },
                {
                    path: 'posts',
                    component: () => import("@/views/admin/AdminPosts.vue"),
                },
                {
                    path: 'posts/:id',
                    component: () => import("@/views/admin/AdminPostsSingle.vue"),
                },
                {
                    path: 'users',
                    component: () => import("@/views/admin/AdminUsers.vue"),
                },
                {
                    path: 'users/:id',
                    component: () => import("@/views/admin/AdminUsersSingle.vue"),
                },
            ]
        },

        //page not found
        {
            path: "/404",
            name: "404",
            component: () => import("@/views/NotFoundPage404.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: '/:pathMatch(.*)*',
            component: () => import("@/views/NotFoundPage404.vue"),
            meta: {
                layout : "mainLayout"
            }
        },

    ],
});



// protect router
router.beforeEach( async (to, from, next) => {
    if ( to.name === 'admin' || to.name === 'Profile' ) {
        if ( localStorage.getItem("token") !== null ) {

            let response = await authRequest('/api/authorization', 'get');

            if (to.name === 'admin') {
                if (response.data.permission === 'admin') {
                    next()
                } else {
                    next({name: 'Login'})
                }
            }

            if (to.name === 'Profile') {
                if (response.data.permission === 'user' || response.data.permission === 'admin') {
                    next()
                } else {
                    next({name: 'Login'})
                }
            }
        }
        else {
            next({name: 'Login'})
        }
    }
    else {
        next();
    }
})

export default router;