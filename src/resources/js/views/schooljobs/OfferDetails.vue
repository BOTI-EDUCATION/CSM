<template>
    <Layout>
        <div class="page-header">
            <h1 class="page-title">Details Offre</h1>
        </div>
        <a class="mb-5 btn btn-primary" href="javascript:history.back()">Go Back</a>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mx-auto">
                    <div class="card-body">
                        <div class="row row-sm mb-3">
                            <div class="d-flex flex-row-reverse">
                                    <div class="fw-bold text-muted me-2 mt-4 mb-5">
                                        <i class="bi bi-calendar3"></i> {{
                                        this.daysBetween(this.detailsData.date) == 0 ? "Publiée Aujourd'hui" : "Publiée il y a "+this.daysBetween(this.detailsData.date) +" jours" }}
                                    </div>
                                    <div class="fw-bold text-muted me-2 mt-4 mb-5">
                                        {{this.nbrCandidates}} Candidates
                                    </div>
                                </div>
                            
                            <div class="details col-xl-6 col-lg-12 col-md-12 mt-4 mt-xl-0">
                                <div class="row">
                                    <h2 class="text-danger mb-3 fw-semibold">{{ this.detailsData.title }} {{ " - " }}
                                        {{ this.detailsData.city }}{{" ("}} {{this.detailsData.date}} {{")"}}</h2>
                                    <span class="col-2 card p-2 mx-3 text-center bg-success">
                                                <span style="font-weight:bold;color:white">{{this.detailsData.ecole_name}}</span></span>
                                    <span class="col-2 card p-2 mx-3 text-center bg-success">
                                                <span style="font-weight:bold;color:white">{{this.detailsData.date}}</span></span>
                                    <!-- <span class="col-2 card p-2 mx-3 text-center bg-success">
                                                <span style="font-weight:bold">{{this.detailsData.ecole}}</span></span> -->
                                                
                                </div>

                                <div class="row">
                                <h4 class="mb-3 fw-semibold">{{ this.detailsData.city }}{{","}} {{ this.detailsData.address }} </h4>
                                </div>

                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12">
                                <div class=""><img :src="this.detailsData.picture" width="400px" alt="img"
                                        class="img-fluid mx-auto d-block">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <h3 style="font-weight:bold">Infos:</h3>
                                <div class="col-lg-12 px-3">
                                    <p class="col-lg-12 p-2 mx-3 text-left " v-for="info in this.detailsData.infos"
                                        style="color:black">
                                        <span style="font-size: 18px;">
                                            {{ info.key }}{{ " : " }} <span style="font-weight:bold">{{ info.value }}</span> </span></p>
                                </div>
                            </div>
                            <div class="col-7 border-left border-dark">
                                <h3 style="font-weight:bold">Candidates:</h3>
                                <div class="col-lg-12 px-3">
                                    <div class="">
                                        <ListeCandidatOffer :key="this.detailsData.id" :offerId="this.detailsData.id">
                                        </ListeCandidatOffer>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-12 px-3">
                <div class="card p-3">
                  <ListeCandidatOffer :key="this.detailsData.id" :offerId="this.detailsData.id"></ListeCandidatOffer>
                </div>
                <div class="">
                       <button class="btn btn-primary mx-auto" @click="shareDataCandidats('test')"><h1 class="p-0 m-0">Candidates List</h1></button>
                </div>
            </div> -->
        </div>
    </Layout>

</template>

<script>
import Layout from "../../components/Layout.vue";
import ListeCandidatOffer from "./ListeCandidatOffer.vue";
export default {
    name: 'offerDetails',
    components: {
        Layout,
        ListeCandidatOffer,
    },
    data() {
        return {
            detailsData: {
                id: '',
                reference: '',
                title: '',
                localization: '',
                city: '',
                ecole_name: '',
                address: '',
                date: '',
                infos: '',
                details: '',
            },
            nbrCandidates:0,
            candidatsList:[],
        }
    },
    methods: {
        daysBetween(date) {
            var date1 = new Date(date);
            var date2 = new Date();

            // To calculate the time difference of two dates
            var Difference_In_Time = date2.getTime() - date1.getTime();
            Difference_In_Time = parseInt(Difference_In_Time / (1000 * 3600 * 24))
            return Difference_In_Time
        },
        shareDataCandidats(offer) {
            this.$router.push({ name: "listeCandidatsOffer", params: {data: this.detailsData } });
            //console.log(this.detailsData)
        },
        getOffer : async function (postId){
         const token = localStorage.getItem("auth-token");
            if (postId && token) {
                await axios
                    .get("/api/getOffer/" + postId, {
                        headers: {
                            Authorization: "Bearer " + token,
                        },
                    })
                    .then(async (result) => {
                        this.detailsData = result.data;
                    })
            }
        }
    },
    created() {
        // this.detailsData = this.$route.params.data
    },
    async mounted(){
        this.getOffer(this.$route.params.id)
        const token = localStorage.getItem('auth-token')
        await axios
            .get('/api/getAllCandidatsOffer/' + this.detailsData.id, {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
            .then(response => {
                this.candidatsList=response.data
                this.nbrCandidates=response.data.length
                console.log("Validation here: ",response.data) 
            })
    }
};
</script>