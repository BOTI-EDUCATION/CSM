<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">News</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">News
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
                        <router-link exact to="/FormeNews" class="br-6 side-menu__item btn btn-primary"
                            data-bs-toggle="slide">
                            <div class="text-center " style="width:100%">Add News</div>
                        </router-link>
                    </div>
                    <div class="col-md-6">
                        <input @keyup="filterNews" type="text" placeholder="Search ..." class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--end Button-->
        <!--Table-->
        <div>
            <div class="row">
                <div class="col-md-6" style="cursor: pointer;" v-for="(news, index) in filteredNews" @click="shareDataNews(news)">
                    <div class="row card p-5">
                        <div class="row">
                            <div class="col-md-4">
                                <img alt="image" width="200" class="br-7" :src="news.image">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ news.id }}</strong></h3>
                                    </div>
                                    <div class="col-md-12">
                                       <h5 class="text-muted"><i class="bi bi-building"></i>  {{ news.label }}</h5> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                                    <div class="fw-bold text-muted me-2 mt-2">
                                    <i class="bi bi-calendar3"></i>{{" "}} {{ news.date.slice(0,10) }}</div>
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
    name:"ListeNews",
    components: {
        Layout,
    },
    data() {
        return {
            allNews: [],
            filteredNews : [],
        }
    },
    methods: {
        shareDataNews(news) {
            this.$router.push({ name: "newsDetailSchoolLife", params: { id:news.id} });
        },
        filterNews: function (e) {
            let value = e.target.value;
            this.filteredNews = this.allNews.filter((_new => {
                return (_new.label.toLowerCase().includes(value.toLowerCase()) || _new.date.toLowerCase().includes(value.toLowerCase()) || _new.text.toLowerCase().includes(value.toLowerCase()))
            }))
        },
        
    },
    async mounted() {
        await axios
            .get('/api/getAllNews')
            .then(response => {
                this.allNews = response.data
                this.filteredNews = this.allNews.reverse()
            })

    }

};
</script>