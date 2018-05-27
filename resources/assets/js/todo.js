require('./vue-assets');

Vue.component('create-todo', require('./components/CreateTodo.vue'));
Vue.component('view-todo', require('./components/ViewTodo.vue'));

var app = new Vue({
    el: '#my-app'
});

