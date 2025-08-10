<template>
    <div>
<!--        Home-->
    </div>
</template>

<script>
    import io from 'socket.io-client';
    import { loadScript } from "vue-plugin-load-script";
    // loadScript('http://localhost:6001/socket.io/socket.io.js');
    export default {
        name: "Home.vue",
        data() {
            return {
                // socket: io('http://localhost:6001', { transports: ['websocket'] }),
                param:''
            }
        },
        created() {
            // var socket = io.connect('http://localhost:6001');
            // console.log(socket);
            // socket.on('error', function(data)
            // {
            //     console.log(data);
            // });
            // socket.on('videos', function(data){ //4
            //     console.log(data);
            // });
            // console.log(this.socket.connected);
            // this.socket.on('error', function(socket) {
            //     console.log(socket);
            // });

            // this.socket.subscribe('videos', (data) => {
            //     console.log(data);
            // });
            // this.socket.on('videos', (data) => {
            //     console.log(data);
                // if(Number(message.data.userId) === Number(this.userId)) {
                //     this.users.push({
                //         authMode:message.data.authMode
                //         ,authTel:message.data.authTel
                //         ,authName:message.data.authName
                //         ,createdAt:message.data.createdAt
                //     })
                // }
            // });


        }
    }
</script>

<style scoped>

</style>
