<template>

    <!--Table-->
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="e-table">
                <div class="table-responsive table-lg">
                    <table class="table border-top table-bordered mb-0">
                        <thead>
                            <tr>
                                <!--<th class="text-center">Photo</th>-->
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th class="text-center">Validation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="candidat in allCandidats">
                                <!--<td class="align-middle text-center"><img alt="image" class="avatar avatar-md br-7"
                                        src="/csm/assets/images/user.png"></td>-->
                                <td class="text-nowrap align-middle">{{ candidat.nom + " " + candidat.prenom }}
                                </td>
                                <td class="text-nowrap align-middle"><span>{{ candidat.email }}</span></td>
                                <td class="text-nowrap align-middle"><span>{{ candidat.tel }}</span></td>

                                <td class="text-center align-middle px-2">
                                    <div class="align-top">
                                        <div class="form-check form-switch">
                                            <input @change="(e)=>{checkHandler(e,candidat)}" class="form-check-input" 
                                            :disabled="candidat.validation===1 ? true : false" 
                                            :checked="candidat.validation===1 ? true : false"
                                                :name="'validation'+candidat.id" type="checkbox" value="" id="flexSwitchCheckChecked">
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- COL-END -->
    </div>
    <!--end Table-->

</template>

<script>
import axios from "axios";
import Layout from "../../components/Layout.vue";
export default {
    name: 'listeCandidatsOffer',
    props: ['offerId'],
    components: {
        Layout,
    },
    data() {
        return {
            allCandidats: [],
            //idOffer: this.offer.id,
            checked: false,
        }
    },
    methods: {
        shareData(candidat) {
            this.$router.push({ name: "candidatDetails", params: { data: candidat } });
        },
        checkHandler(e, cdt) {
            this.checked = e.target.checked
            e.target.disabled=true
            console.log(this.checked, this.offerId, cdt.id)
            var validation = this.checked ? 1 : 0
            if(validation===1){
                var formData = new FormData();
            formData.append('validation',validation)
            formData.append('idOffer',this.offerId)
            formData.append('idCandidat',cdt.id)
            const token = localStorage.getItem('auth-token')
    
                axios.post("/api/validateOfferCandidacy",formData, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                })
            }
        }
    },
    async mounted() {
        const token = localStorage.getItem('auth-token')
        // console.log("id here: ", this.offerId)
        await axios
            .get('/api/getAllCandidatsOffer/' + this.offerId, {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
            .then(response => {
                this.allCandidats = response.data
                //console.log("Validation here: ",response.data) 
            })

    },
    created() {
        //this.idOffer = this.$route.params.data.id
        //console.log("id passed from here: ", this.$route.params.data.id)
    }
};
</script>