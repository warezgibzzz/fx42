import {createRouter, createWebHashHistory} from "vue-router";
import Index from "../Components/Index.vue"
import Profile from "../Components/Profile.vue"
import Members from "../Components/Members.vue"

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
  {
    path: "/members",
    name: "Members",
    component: Members,
    meta: {
      title: "Персонажи"
    }
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
