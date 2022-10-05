<template>
    <router-view></router-view>
</template>

<script>
export default {
    data() {
        return {
            auth : false
        }
    },
    methods: {
        checklogin : async function(){
            const token = localStorage.getItem('auth-token')
            if(token){
                await axios.get('/api/user',{
                    headers:{
                        Authorization : 'Bearer '+token
                    }
                }).then(async (result)=>{
                    this.auth = true;
                }).catch(function(err){
                    localStorage.removeItem('auth-token');
                    this.auth = false;
                    this.$router.push('/login');
                });
            }else{
                this.$router.push('/login');
            }
        } ,
    },
    async mounted() {
        const token = localStorage.getItem('auth-token')
        if(token){
            await this.checklogin();
        }else{
            this.$router.push('/login');
        }
    },
    watch:{
        async $route (to, from){
            await this.checklogin();
        }
    },
    
}
</script>
