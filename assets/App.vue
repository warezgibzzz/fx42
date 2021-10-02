<template>
    <div class="navbar mb-2 shadow-lg bg-neutral text-neutral-context">
        <div class="flex-none px-2 mx-2">
            <router-link :to="{name: 'Index'}" class="text-lg font-bold">FX42 Industries</router-link>
        </div>
        <div class="flex-1 px-2 mx-2">
            <div class="items-stretch hidden lg:flex" v-if="loggedIn">
                <router-link :to="{name: 'Members'}" class="btn btn-ghost btn-sm rounded-btn">Персонажи</router-link>
            </div>
        </div>
        <div class="flex-none">
            <div v-if="loggedIn" class="avatar dropdown dropdown-end">
                <discord-avatar :discordId="user.discordUser.id" :avatarHash="user.discordUser.avatar"/>
                <ul class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52">
                    <li>
                        <router-link :to="{name:'Profile'}">Профиль</router-link>
                    </li>
                    <li>
                        <a href="/logout">Выйти</a>
                    </li>
                </ul>
            </div>
            <a v-else class="btn btn-primary" href="/connect/discord"><fa-icon :icon="['fab', 'discord']"/>&nbsp;Войти при помощи Discord</a>
        </div>
    </div>
    <div class="md:container md:mx-auto">
        <router-view/>
    </div>
</template>

<script>
import {FontAwesomeIcon, FontAwesomeLayers, FontAwesomeLayersText} from "@fortawesome/vue-fontawesome";
import { onMounted, reactive, ref } from '@vue/runtime-core';
import { mapActions, useStore } from 'vuex';
import { library } from '@fortawesome/fontawesome-svg-core'
import { faDiscord } from '@fortawesome/free-brands-svg-icons'
import DiscordAvatar from './Components/DiscordAvatar.vue'

library.add(faDiscord)

export default {
    name: "App",
    components: {
        'fa-icon': FontAwesomeIcon,
        'discord-avatar': DiscordAvatar
    },
    setup() {
        const store = useStore()

        onMounted(() => {
            store.dispatch('getUser')

            console.log(store)
        })

        return {
            user: store.getters.getUser,
            loggedIn: store.getters.loggedIn
        }
    }
}
</script>

<style scoped>

</style>