<template>
    <div>
<!--        <router-view />-->
    </div>
</template>

<script>
    // import io from 'socket.io-client';
    export default {
        name: "App.vue",
        // data() {
        //     return {
        //         socket: io('http://localhost:6001'),
        //         param:''
        //
        //     }
        // },
        created() {
            // console.log(this.socket);
            // this.socket.on('videos', (message) => {
            //     console.log(message);
            //     // if(Number(message.data.userId) === Number(this.userId)) {
            //     //     this.users.push({
            //     //         authMode:message.data.authMode
            //     //         ,authTel:message.data.authTel
            //     //         ,authName:message.data.authName
            //     //         ,createdAt:message.data.createdAt
            //     //     })
            //     // }
            // });
        }
    }
</script>

<style scoped>

</style>
