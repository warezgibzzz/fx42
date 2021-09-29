import Vue from "vue";
import VueRouter from "vue-router";
import Index from "../Components/Index.vue"
Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "home",
    component: Index,
    meta: {
      title: "Главная"
    }
  },
];

const router = new VueRouter({
  mode: "history",
  routes
});

Vue.router = router;

export default router;
