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
        <div class="card p-3">
            <div class="e-table px-5 pb-5">
                <div class="table-responsive table-lg">
                    <table class="table border-top table-bordered mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">School</th>
                                <th>Poste Recherché</th>
                                <th>Date</th>
                                <th>Candidacies Number</th>
                                <th class="text-center">Actions</th>
                                <!--<th class="text-center">
                                    <button class="btn" @click="sortListAsc()"><h4><i class="bi bi-sort-up"></i></h4></button>
                                    <button class="btn" @click="sortListDesc()"><h4><i class="bi bi-sort-down"></i></h4></button>
                                </th>-->
                            </tr>
                        </thead>
                        <template v-for="(offer, index) in filteredOffers.slice(0, 10)">
                            <tbody>
                                <tr style="cursor: pointer;">
                                    <td class="align-middle text-center">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img alt="image" width="200" class="br-7" :src="offer.schoolLogo">
                                            </div>
                                            <div class="col-md-6 text-uppercase">
                                                <h5><strong>{{ offer.ecole_name }}</strong></h5>
                                            </div>
                                        </div>
                                    </td>
                                    <!--"/csm/assets/images/user.png"-->
                                    <td class="text-nowrap align-middle">{{ offer.title }}</td>
                                    <td class="text-nowrap align-middle"><span>{{ offer.date }}</span></td>
                                    <td class="text-nowrap align-middle"><span>{{ offer.nbrCandidates }}</span></td>
                                    <td class="text-center align-middle">

                                        <router-link :to="{ name: 'offerDetails', params: { data: offer } }"
                                            class="side-menu__item" data-bs-toggle="slide">
                                            Details
                                        </router-link>
                                        <!--<button @click="shareData(offer)" class="mx-4 btn btn-primary">Applicants
                                    for this offer</button>
                                <button @click="deleteOffer(offer.id)" class="mx-4 btn btn-danger">Delete</button>-->
                                    </td>
                                    <!--
                                        <td>
                                        <a class="btn" data-bs-toggle="collapse" :href="'#collapseExample' + offer.id"
                                            role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="bi bi-caret-down-fill"></i> </a>
                                    </td>
                                    -->
                                </tr>

                                <!--<tr :id="'collapseExample' + offer.id" class="collapse col-md-12">
                                    <td class="px-0" style="border: none;" colspan="6">
                                    <keep-alive>
                                        <ListeCandidatOffer :key="offer.id" :offerId="offer.id"></ListeCandidatOffer>
                                    </keep-alive>
                                    </td>
                                </tr>-->

                            </tbody>
                        </template>
                    </table>
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
        shareData(offer) {
            //this.$router.push({ name: "offerDetails", params: { data: offer } });
            this.$router.push({ name: "listeCandidatsOffer", params: { data: offer } });

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