import {createRouter, createWebHistory} from "vue-router";
import Index from "../Components/Index.vue"

const routes = [
  {
    path: "/",
    name: "Index",
    component: Index,
    meta: {
      title: "Главная"
    }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
