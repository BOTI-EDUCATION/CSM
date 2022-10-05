<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Job Offers</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Jobs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Job Offers
                    </li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->
        <!-- Button Add-->
        <div class="row mb-5">
            <div class="col-xl-4 col-md-4">
            </div>
            <div class="col-xl-4 col-md-4">
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="row d-flex flex-row-reverse">
                    <div class="col-md-6">
                        <router-link exact to="/formeOffer" class="br-6 side-menu__item btn btn-primary"
                            data-bs-toggle="slide">
                            <div class="text-center " style="width:100%">New Job Offer</div>
                        </router-link>
                    </div>
                    <div class="col-md-6">
                        <input @keyup="filterOffers" type="text" placeholder="Search ..." class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--end Button-->
        <!--Table-->
        <div>
            <div class="row">
                <div class="col-md-6" style="cursor: pointer;" v-for="(offer, index) in filteredOffers" @click="shareDataOffer(offer)">
                    <div class="row card p-5">
                        <div class="row">
                            <div class="col-md-4">
                                <img alt="image" width="200" class="br-7" :src="offer.schoolLogo">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ offer.title }}</strong></h3>
                                    </div>
                                    <div class="col-md-12">
                                       <h5 class="text-muted"><i class="bi bi-building"></i>  {{ offer.ecole_name }} {{" | "}} <i class="bi bi-geo-alt-fill"></i> {{offer.city}}</h5> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                                    <div class="fw-bold text-muted me-2 mt-2">
                                    <i class="bi bi-calendar3"></i>{{" "}} {{ offer.date }}</div>
</div>
                    </div>
                </div>
            </div>
        </div>
        <!--end Table-->


    </Layout>

</template>

<script>
import Layout from "../../components/Layout.vue";
import axios from 'axios';
import ListeCandidatOffer from "./ListeCandidatOffer.vue";

export default {
    components: {
        Layout,
        ListeCandidatOffer,
    },
    data() {
        return {
            allOffers: [],
            filteredOffers: [],
        }
    },
    methods: {
        shareDataOffer(offer) {
            // this.$router.push({ name: "listeOffres/view/"+offer.id, params: { data: offer } });
            this.$router.push({ name: "offerDetails", params: { id:offer.id , data: offer } });
        },
        deleteOffer(id) {
            const token = localStorage.getItem('auth-token')

            axios
                .post('/api/deleteOffer/' + id, {}, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                })
                .then(response => {
                    console.log(response.data)
                })
            location.reload();
        },
        filterOffers: function (e) {
            let value = e.target.value;
            console.log(value)
            this.filteredOffers = this.allOffers.filter((offer => {
                return (offer.ecole_name.toLowerCase().includes(value.toLowerCase()) || offer.date.toLowerCase().includes(value.toLowerCase()) || offer.city.toLowerCase().includes(value.toLowerCase()))
            }))
            console.log(this.filteredOffers.filterOffers)
        },
        sortListAsc() {
            this.filteredOffers = this.filteredOffers.sort(function (a, b) {
                var c = new Date(a.date);
                var d = new Date(b.date);
                return c - d;
            }).reverse();
            //console.log(new Date(this.filteredOffers.date))
        },
        sortListDesc() {
            this.filteredOffers = this.filteredOffers.sort(function (a, b) {
                var c = new Date(a.date);
                var d = new Date(b.date);
                return c - d;
            });
        }
    },
    async mounted() {
        await axios
            .get('/api/getAllOffers')
            .then(response => {
                this.allOffers = response.data
                this.filteredOffers = this.allOffers.reverse()
            })

    }

};
</script>