<template>
    <Layout>
        <div class="page-header">
            <h1 class="page-title">Article Details</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="javascript:void(0)">News Details </a>
                    </li>
                </ol>
            </div>
        </div>

        <a class="mb-5 btn btn-primary" href="javascript:history.back()">Go Back</a>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">{{ news.label }}
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <h4> Image : </h4>
                                <img :src="news.image" alt="" class="img-fluids">
                            </div>
                            <div class="col-12 col-md-8" v-html="news.text"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../../components/Layout.vue';


export default {
    name: "newsDetailSchoolLife",
    components: {
        Layout
    },
    data() {
        return {
            news: {}
        };
    },
    methods: {
        getArticle: async function (postId) {
            // const token = localStorage.getItem("auth-token");
            // if (postId && token) {
                await axios
                    .get("/api/getNewsDetails/" + postId)
                    .then(async (result) => {
                        this.news = result.data;
                    })
            }
        // },
    },
    // created() {
    //     // this.article = this.$route.params.data
    // },
    mounted() {
        
        this.getArticle(this.$route.params.id);
        // this.initCharts();
    },
};
</script>

<style scoped>
.text-right {
    text-align: right;
}

.profile-cover__img .profile-img-1 {
    position: relative;
}

.profile-cover__img .profile-img-1>img {
    margin-left: 0px;
}

.profile-cover__img .profile-img-1>span {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    width: auto;
    margin: auto;
    display: block;
    text-align: center;
}

.profile-cover__img .profile-img-content {
    margin-left: 20px;
}

.main-profile-contact-list.d-flex>div {
    flex: 1;
}

.princing-item {
    height: 100%;
    border-radius: 10px;
}
</style>