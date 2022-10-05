<template>
<Layout>

        <div class="page-header">
            <h1 class="page-title">Details Candidat</h1>
        </div>
        <a class="mb-5 btn btn-primary" href="javascript:history.back()">Go Back</a>
        <div class="row">
            <div class="col-xl-12">
                <div class="card p-3 mx-auto" style="width: 700px;">
                    <div class="card-body">
                        <div class="row">
                            <div class="details col-xl-12 col-lg-12 col-md-12 mt-4 mt-xl-0">
                                <div class="mt-2 mb-4">
                                    <h3 class="mb-3 fw-semibold">Nom et Prénom : {{detailsData.nom+" "+detailsData.prenom }}</h3>
                                    <div class=" mt-4 mb-5"><span class="fw-bold me-2">Email :</span>{{detailsData.email}}</div>
                                    <div class=" mt-4 mb-5"><span class="fw-bold me-2">CIN :</span>{{detailsData.cin}}</div>
                                    <div class=" mt-4 mb-5"><span class="fw-bold me-2">Nationalité :</span>{{detailsData.nationality}}</div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</Layout>
</template>

<script>
import Layout from "../../components/Layout.vue";
export default {
    name:"candidatDetails",
    components: {
        Layout,
    },
    data(){
        return{
            detailsData:{
                nom: '',
                prenom: '',
                cin: '',
                email: '',
                nationality: '',
            },
        }
    },
    created(){
        this.detailsData=this.$route.params.data
    }
};
</script>