<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Article List</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Article List
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
                        <router-link exact to="/formeBlog" class="br-6 side-menu__item btn btn-primary"
                            data-bs-toggle="slide">
                            <div class="text-center " style="width:100%">New Article</div>
                        </router-link>
                    </div>
                    <div class="col-md-6">
                        <input @keyup="filterArticles" type="text" placeholder="Search ..." class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--end Button-->
        <!--Table-->
        <div>
            <div class="row">
                <div class="col-md-6" style="cursor: pointer;" v-for="(article, index) in filteredArticles">
                    <!-- blogDetailSchoolLife -->
                    <div class="row card p-5">
                        <transition name="fade">
                            <span class="btnDlt mb-3" @click.prevent="deleteArt(article.id)">
                                delete
                            </span>
                        </transition>
                        <div class="row" @click="shareDataArticle(article)">
                            <div class="col-md-4">
                                <img alt="image" width="200" class="br-7" :src="article.image">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ article.label }}</strong></h3>
                                    </div>
                                    <div class="col-md-12">
                                        <span class="row">
                                            <span class="col-1 h4 p-3"><i class="bi bi-tags-fill"></i></span>
                                            <span v-for="tag in article.keywords"
                                                class="col-3 text-center text-muted card h6 bg-light text-black p-2 mx-1">{{
                                                    tag.replaceAll('"', '') }} </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end Table-->


    </Layout>
</template>
<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity .5s
}

.fade-enter,
.fade-leave-to

/* .fade-leave-active in <2.1.8 */
    {
    opacity: 0
}
.btnDlt{
    color: white;
    padding: 6px 20px;
    background-color: #FF7D63;
    border-radius: 10px;
    border: none;
    width: 80px;
}
</style>

<script>
import Layout from "../../components/Layout.vue";
import axios from 'axios';

export default {
    components: {
        Layout,
    },
    data() {
        return {
            allArticles: [],
            filteredArticles: [],
        }
    },
    methods: {
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

        async deleteArt(id) {
            const token = localStorage.getItem("auth-token");
            this.$swal({
        title: "Vous êtes sûr de vouloir supprimer cette article",
        icon: "warning",
        showConfirmButton: false,
        showDenyButton: true,
        showCancelButton: true,
        denyButtonText: `Supprimer`,
        cancelButtonText: `Annuler`,
      }).then(async (result) => {
        if(result.isDenied){
            let axi = await axios.post(`api/deleteArt/${id}`, null, {
                headers: {
                    Authorization: "Bearer " + token,
                },
            }).then(res => {
                this.filteredArticles = res.data.articles
            })
            console.log(axi);
            this.ListeBlogs = axi.data.article

        }


      });
        },

        filterArticles: function (e) {
            let value = e.target.value;
            console.log(value)
            this.filteredArticles = this.allArticles.filter((article => {
                return (article.label.toLowerCase().includes(value.toLowerCase()))
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
        },
        shareDataArticle(article) {
            // this.$router.push({ name: "listeOffres/view/"+offer.id, params: { data: offer } });
            this.$router.push({ name: "BlogDetails", params: { id: article.id } });
        },
    },
    async mounted() {
        await axios
            .get('/api/getAllArticles')
            .then(response => {
                this.allArticles = response.data
                this.filteredArticles = this.allArticles.reverse()
            })

    }

};
</script>