<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Candidates List</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Jobs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Candidates List
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
                        <router-link exact to="/formeCandidat" class="br-6 side-menu__item btn btn-primary"
                            data-bs-toggle="slide">
                            <div class="text-center " style="width:100%">New Job Candidate</div>
                        </router-link>
                    </div>
                    <div class="col-md-6">
                        <input @keyup="filterCandidats" type="text" placeholder="Search ..." class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <!--end Button-->
        <!--Table-->
        <div class="row row-cards">
            <div class="col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header border-bottom-0 p-4"></div>
                    <!--<div class="card-header border-bottom-0 p-4">
                        <h2 class="card-title">1 - 30 of 546 offers</h2>
                        <div class="page-options ms-auto"> <select
                                class="form-control select2 w-100 select2-hidden-accessible" tabindex="-1"
                                aria-hidden="true">
                                <option value="asc">Latest</option>
                                <option value="desc">Oldest</option>
                            </select><span class="select2 select2-container select2-container--default" dir="ltr"
                                style="width: 100%;"><span class="selection"><span
                                        class="select2-selection select2-selection--single" role="combobox"
                                        aria-haspopup="true" aria-expanded="false" tabindex="0"
                                        aria-labelledby="select2-b77d-container"><span
                                            class="select2-selection__rendered" id="select2-b77d-container"
                                            title="Latest">Latest</span><span class="select2-selection__arrow"
                                            role="presentation"><b role="presentation"></b></span></span></span><span
                                    class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                    </div>-->
                    <div class="e-table px-5 pb-5">
                        <div class="table-responsive table-lg">
                            <table class="table border-top table-bordered mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <!--<th class="text-center">Picture</th>-->
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <!--<th class="text-center">Actions</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="cursor: pointer;" v-for="candidat in filteredCandidats"
                                        @click="shareData(candidat)">

                                        <!--<td class="align-middle text-center"><img alt="image"
                                                class="avatar avatar-md br-7" src="/csm/assets/images/user.png"></td>-->
                                        <td class="text-nowrap align-middle"><strong>{{ candidat.nom + " " +
                                                candidat.prenom
                                        }}</strong>
                                        </td>
                                        <td class="text-nowrap align-middle"><span>{{ candidat.email }}</span></td>
                                        <td class="text-nowrap align-middle"><span>{{ candidat.tel }}</span></td>

                                        <!-- <td class="text-center align-middle">
                                            <div class="btn-group btn btn-primary align-top">
                                                <router-link exact to="/candidatDetails" class="side-menu__item"
                                                    data-bs-toggle="slide">
                                                    Details
                                                </router-link>
                                                <button @click="shareData(candidat)"
                                                    class="btn btn-primary">Details</button>
                                            </div>
                                        </td>-->

                                    </tr>

                                    <!--<tr>
                                        <td class="align-middle text-center"><img alt="image"
                                                class="avatar avatar-md br-7" src="/csm/assets/images/user.png"></td>
                                        <td class="text-nowrap align-middle">Ahmed Semoud</td>
                                        <td class="text-nowrap align-middle"><span>26 Jan
                                                2018</span></td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group btn btn-primary align-top">
                                                <router-link exact to="/candidatDetails" class="side-menu__item"
                                                    data-bs-toggle="slide">
                                                    Details
                                                </router-link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle text-center"><img alt="image"
                                                class="avatar avatar-md br-7" src="/csm/assets/images/user.png"></td>
                                        <td class="text-nowrap align-middle">Ahmed Semoud</td>
                                        <td class="text-nowrap align-middle"><span>26 Jan
                                                2018</span></td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group btn btn-primary align-top">
                                                <router-link exact to="/candidatDetails" class="side-menu__item"
                                                    data-bs-toggle="slide">
                                                    Details
                                                </router-link>
                                            </div>
                                        </td>
                                    </tr>-->

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- COL-END -->
        </div>
        <!--end Table-->

    </Layout>
</template>

<script>
import Layout from "../../components/Layout.vue";
export default {
    components: {
        Layout,
    },
    data() {
        return {
            allCandidats: [],
            filteredCandidats: []
        }
    },
    methods: {
        shareData(candidat) {
            this.$router.push({ name: "candidatDetails", params: {id:candidat.id, data: candidat } });
        },
        filterCandidats: function (e) {
            let value = e.target.value;
            console.log(value)
            this.filteredCandidats = this.allCandidats.filter((candidat => {
                return (candidat.nom.toLowerCase().includes(value.toLowerCase()) || candidat.prenom.toLowerCase().includes(value.toLowerCase()) || candidat.email.toLowerCase().includes(value.toLowerCase()) || candidat.tel.toLowerCase().includes(value.toLowerCase()))
            }))

        }
    },
    async mounted() {
        const token = localStorage.getItem('auth-token')
        await axios
            .get('/api/getAllCandidats', {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
            .then(response => {
                this.allCandidats = response.data
                this.filteredCandidats = this.allCandidats
                //console.log(response.data)
            })

    }
};
</script>