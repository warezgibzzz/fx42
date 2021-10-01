<template>
    <div class="rounded-full w-10 h-10 m-1" tabindex="0">
        <img src="" alt="User"/>
    </div>
</template>

<script>
export default {
    
    props: {
        discordId: {default: null},
        avatarHash: {default: null}
    }

    //"https://cdn.discordapp.com/avatars/" . $user->getId() . "/" . $user->getAvatar() . ".png";
}
</script>

<style scoped>

</style>