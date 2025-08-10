<template>
    <div>
        mvsssssssssssssssss
    </div>
</template>

<script>
import io from 'socket.io-client';
export default {
    name: "mvs.vue",
    data() {
        return {
            socket: io('http://localhost:3000/')
        }
    },
    mounted() {
        this.socket.on('videos', (message) => {
            console.log(message);
            // if(Number(message.data.userId) === Number(this.userId)) {
            //     this.users.push({
            //         authMode:message.data.authMode
            //         ,authTel:message.data.authTel
            //         ,authName:message.data.authName
            //         ,createdAt:message.data.createdAt
            //     })
            // }
        });
    }
}
</script>

<style scoped>

</style>
