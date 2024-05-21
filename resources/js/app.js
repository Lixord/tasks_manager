import Vue from "vue";
import TaskComponent from "./components/TaskComponent.vue";

require('./bootstrap');


const app = new Vue({
    el: '#app',
    components:{
        TaskComponent,
    }
});
