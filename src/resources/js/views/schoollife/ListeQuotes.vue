<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Quotes</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Quotes
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
                        <router-link exact to="/FormeQuote" class="br-6 side-menu__item btn btn-primary"
                            data-bs-toggle="slide">
                            <div class="text-center " style="width:100%">New Quote</div>
                        </router-link>
                    </div>
                    <div class="col-md-6">
                        <input @keyup="filterQuotes" type="text" placeholder="Search ..." class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--end Button-->
        <!--Table-->
        <div>
            <div class="row">
                <div class="col-md-6" style="cursor: pointer;" v-for="(quote, index) in filteredQuotes" @click="shareDataQuote(quote)">
                    <div class="row card p-5">
                        <div class="row">
                            <div class="col-md-4">
                                <img alt="image" width="200" class="br-7" :src="quote.image">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ quote.id }}</strong></h3>
                                    </div>
                                    <div class="col-md-12">
                                       <h5 class="text-muted"><i class="bi bi-building"></i>  {{ quote.Author }} {{" | "}} <i class="bi bi-geo-alt-fill"></i> {{quote.function}}</h5> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                                    <div class="fw-bold text-muted me-2 mt-2">
                                    <i class="bi bi-calendar3"></i>{{" "}} {{ quote.date.slice(0,10) }}</div>
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

export default {
    name:"ListeQuotes",
    components: {
        Layout,
    },
    data() {
        return {
            allQuotes: [],
            filteredQuotes : [],
        }
    },
    methods: {
        shareDataQuote(quote) {
            this.$router.push({ name: "quoteDetailSchoolLife", params: { id:quote.id} });
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
        filterQuotes: function (e) {
            let value = e.target.value;
            this.filteredQuotes = this.allQuotes.filter((quote => {
                return (quote.Author.toLowerCase().includes(value.toLowerCase()) || quote.date.toLowerCase().includes(value.toLowerCase()) || quote.function.toLowerCase().includes(value.toLowerCase()))
            }))
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
            .get('/api/getAllQuotes')
            .then(response => {
                this.allQuotes = response.data
                this.filteredQuotes = this.allQuotes.reverse()
            })

    }

};
</script>