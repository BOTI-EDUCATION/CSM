<template>
    <Layout>
        <div class="page-header">
            <h1 class="page-title">Errors Tracking</h1>
            <div>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    Errors Tracking
                </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-body overflow-auto">
                    <table class="table">
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Page</th>
                            <th>Error</th>
                            <th>Message</th>
                            <th>handling</th>
                        </tr>
                    </thead>
                    <tbody v-if="errors.length > 0">
                        <tr v-for="error in errors" :key="error.id">
                            <td>
                                <div class="align-items-center d-flex" >
                                <img
                                :src="error.school.logo"
                                alt="profile-lead"
                                class="avatar profile-user brround cover-image me-2"
                                />
                                {{ error.school.name }}
                                </div>
                            </td>
                            <td> {{error.page}} </td>
                            <td> {{error.error}} </td>
                            <td> {{error.message}} </td>
                            <td> 
                                <button @click="markHandling(error.id)" class="btn btn-primary"> <i class="fe fe-check"></i> </button>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
  import Layout from '../../components/Layout.vue'

export default {
    components: {
        Layout
    },
    data() {
        return {
            errors: []

        }
    },
    methods: {
        getErrorsList: async function(){
            const token = localStorage.getItem("auth-token");
            if (token) {
                await axios
                .get("/api/getErrorsList", {
                    params : {},
                    headers: {
                        Authorization: "Bearer " + token,
                    }
                } )
                .then(async (result) => {
                    this.errors = result.data;
                    // console.log(result)
                })
                .catch(function (err) {});
            }
        },
        markHandling: async function(id){
            const token = localStorage.getItem("auth-token");
            if (token) {
                await axios
                .get("/api/markHandled", {
                    params : {
                        error: id
                    },
                    headers: {
                        Authorization: "Bearer " + token,
                    }
                } )
                .then(async (result) => {
                    this.getErrorsList();
                    this.$swal({
                        title: "Error marked as handled !",
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: `Ok`,
                        cancelButtonText: `Annuler`,
                    }).then((result) => {});
                    // console.log(result)
                })
                .catch(function (err) {});
            }
        }
    },
    mounted() {
        this.getErrorsList();
    },
}
</script>