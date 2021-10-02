<template>
    <div class="rounded-full w-10 h-10 m-1" tabindex="0">
        <img :src="'https://cdn.discordapp.com/avatars/' + discordId + '/' + avatarHash + '.png'" alt="User"/>
    </div>
</template>

<script>
export default {
    
    props: {
        discordId: {default: null},
        avatarHash: {default: null}
    },
    name: "DiscordAvatar"
}
</script>

<style scoped>

</style>