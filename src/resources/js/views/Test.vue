<template>
    <Layout>
        <div class="page-header">
            <h1 class="page-title">Alerts</h1>
            <div>
                <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Componets</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Alerts
                </li>
                </ol>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary" @click="checklogin">refresh user</button>
                </div>
                <div class="col-md-12">
                    {{resultU}}
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../components/Layout.vue'
export default {
    
    components: {
        Layout
    },
    data() {
        return {
            resultU: '',
            resultA: '',
            resultApi: '',
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
                },).then(async (result)=>{
                    this.resultU = result.data;
                    console.log(result);
                }).catch(function(err){
                    console.log(err);
                });
            }
        } ,
    },
    mounted() {
        this.checklogin();
    },
}
</script>