<template>
    <div class="navbar mb-2 shadow-lg bg-neutral text-neutral-context">
        <div class="flex-none px-2 mx-2">
            <router-link to="/" class="text-lg font-bold">FX42 Industries</router-link>
        </div>
        <div class="flex-1 px-2 mx-2">
            <div class="items-stretch hidden lg:flex">
                <a class="btn btn-ghost btn-sm rounded-btn">
                    Home
                </a>
                <a class="btn btn-ghost btn-sm rounded-btn">
                    Portfolio
                </a>
                <a class="btn btn-ghost btn-sm rounded-btn">
                    About
                </a>
                <a class="btn btn-ghost btn-sm rounded-btn">
                    Contact
                </a>
            </div>
        </div>
        <div class="flex-none">
            <div v-if="loggedIn" class="avatar dropdown dropdown-end">
                <div class="rounded-full w-10 h-10 m-1" tabindex="0">
                    <img src="https://i.pravatar.cc/500?img=32" alt="User"/>
                </div>
                <ul class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52">
                    <li>
                        <router-link to="/profile">Профиль</router-link>
                    </li>
                    <li>
                        <a href="/logout">Выйти</a>
                    </li>
                </ul>
            </div>
            <a v-else class="btn btn-primary" href="/connect/discord"><FAIcon :icon="['fab', 'discord']"/>&nbsp;Войти при помощи Discord</a>
        </div>
    </div>
</template>

<script>
import { library } from '@fortawesome/fontawesome-svg-core'
import { faDiscord } from '@fortawesome/free-brands-svg-icons'

library.add(faDiscord)

export default {
    name: "App"
}
</script>

<style scoped>

</style>