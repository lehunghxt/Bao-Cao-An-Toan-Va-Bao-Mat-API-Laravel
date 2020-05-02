
require('./bootstrap');

window.Vue = require('vue');
import App from './App.vue';
import VueRouter from 'vue-router'
Vue.use(VueRouter)

let routes = [
    { 
    	path: '/', 
    	component: require('./components/Home.vue').default 
    },
    { 
        path: '*', 
        component: require('./components/Home.vue').default 
    },
    { 
    	path: '/login', 
    	component: require('./components/Login.vue').default 
    },
    { 
    	path: '/register', 
    	component: require('./components/Register.vue').default 
    },
    { 
        path: '/changepass', 
        component: require('./components/ChangePassword.vue').default 
    },
    { 
    	path: '/dashboard', 
    	component: require('./components/Dashboard.vue').default 
    },
    // POST
    { 
    	path: '/posts', 
    	component: require('./components/post/ListPost.vue').default,
    },
    { 
    	path: '/addPost', 
    	component: require('./components/post/AddPost.vue').default,
    },
      { 
    	path: '/editPost/:id', 
    	component: require('./components/post/EditPost.vue').default,
    },
    // CATEGORY
    { 
    	path: '/category', 
    	component: require('./components/category/ListCategory.vue').default 
    },
    { 
    	path: '/addCategory', 
    	component: require('./components/category/AddCategory.vue').default 
    },
    { 
    	path: '/editCategory/:id', 
    	component: require('./components/category/EditCategory.vue').default 
    },
    // USER
    { 
    	path: '/user', 
    	component: require('./components/user/ListUser.vue').default 
    },
    { 
    	path: '/addUser', 
    	component: require('./components/user/AddUser.vue').default 
    },
    { 
    	path: '/editUser/:id', 
    	component: require('./components/user/EditUser.vue').default 
    },
  ]

  const router = new VueRouter({
    mode: 'history',
    routes 
  })

Vue.component('dashboard', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
    router,
    render: app => app(App),
});
