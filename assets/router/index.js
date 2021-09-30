import {createRouter, createWebHashHistory} from "vue-router";
import Index from "../Components/Index.vue"
import Profile from "../Components/Profile.vue"

const routes = [
  {
    path: "/",
    name: "Index",
    component: Index,
    meta: {
      title: "Главная"
    }
  },
  {
    path: "/profile",
    name: "Profile",
    component: Profile,
    meta: {
      title: "Профиль"
    }
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
