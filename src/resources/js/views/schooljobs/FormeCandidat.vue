<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">New job offer</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Jobs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New job Candidates
                    </li>
                </ol>
            </div>
            <a class="mb-5 btn btn-primary" href="javascript:history.back()">Go Back</a>
        </div>
        <!-- PAGE-HEADER END -->
        <!--Form-->
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New job Candidates</h4>
                    </div>
                    <div class="card-body">
                        <form ref="offerForm" class="form-horizontal needs-validation" @submit.prevent="createCandidat">
                            <!--<div class=" row mb-4"> <label class="col-md-3 form-label">Reference</label>
                                <div class="col-md-9"> <input type="text" v-model="formData.reference" name="reference"
                                        class="form-control" value="">
                                </div>
                            </div>-->
                            <div class="row mb-4"> <label class="col-md-3 form-label" for="example-email">Nom</label>
                                <div class="col-md-9"> <input v-model="formData.nom" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Nom" required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label" for="example-email">Prenom</label>
                                <div class="col-md-9"> <input v-model="formData.prenom" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Prenom" required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label" for="example-email">Email</label>
                                <div class="col-md-9"> <input v-model="formData.email" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Email" required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label" for="example-email">CIN</label>
                                <div class="col-md-9"> <input v-model="formData.cin" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="CIN" required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label"
                                    for="example-email">telephone</label>
                                <div class="col-md-9"> <input v-model="formData.telephone" type="text"
                                        id="example-email" name="titre" class="form-control" placeholder="Telephone"
                                        required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label"
                                    for="example-email">Nationalité</label>
                                <div class="col-md-9"> <input v-model="formData.nationality" type="text"
                                        id="example-email" name="titre" class="form-control" placeholder="Nationalité"
                                        required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label"
                                    for="example-email">Password</label>
                                <div class="col-md-9"> <input v-model="formData.password" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Password" required> </div>
                            </div>
                            <div class="row mb-4"> <label class="col-md-3 form-label" for="example-email">Job
                                    Offer</label>
                                <div class="col-md-9">
                                    <vue-select class="" name="offer" :reduce="offer => offer.alias" label="label"
                                    :options="offers" v-model="formData.offer" placeholder="Choose Offer" searchable>
                                    </vue-select>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">CV</label>
                                <div class="col-md-9"> <input @change="uploadFile" type="file" name="cv"
                                        class="form-control" value="Upload CV"> </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-primary" type="submit">Save Candidate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end Form-->
    </Layout>
</template>

<script>
import axios from 'axios';
import Layout from "../../components/Layout.vue";
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import { VueEditor } from "vue2-editor";
import villes from "./ville.json";

export default {
    components: {
        Layout,
        Dropzone: vue2Dropzone,
        "vue-select": vSelect,
        "vue-editor": VueEditor
    },
    data() {
        return {
            formData: {
                nom: '',
                prenom: '',
                email: '',
                file: '',
                offer: '',
                cin: '',
                telephone: '',
                nationality: '',
                password: '',
            },
            offers: [],
        }
    },
    async mounted() {
        let tmp = []

        const token = localStorage.getItem("auth-token");
        await axios
            .get("/api/getAllOffers", {
                headers: {
                    Authorization: "Bearer " + token,
                },
            })
            .then(async (result) => {
                console.log("offers list: ", result.data);
                result.data.map((offer) => {
                    this.offers.push({ alias: offer.id, label: offer.title })
                })
                console.log(this.offers)
            })
            .catch((err) => {
                console.log("error")
            });
    },
    methods: {
        createCandidat() {
            let form_Data = new FormData();
            let form_DataOC = new FormData();
            form_Data.append('nom', this.formData.nom)
            form_Data.append('prenom', this.formData.prenom)
            form_Data.append('email', this.formData.email)
            form_Data.append('cin', this.formData.cin)
            form_Data.append('telephone', this.formData.telephone)
            form_Data.append('nationality', this.formData.nationality)
            form_Data.append('password', this.formData.password)

            //console.log(this.formData)
            //const token = localStorage.getItem("auth-token");
            axios.post("/api/addCandidat", form_Data)
                .then(response => {
                    console.log(response.status)
                    console.log("here you find the offer: ", this.formData.offer)
                    if (this.formData.offer != '') {
                        const token = localStorage.getItem("auth-token");

                        form_DataOC.append('job_offer', this.formData.offer)
                        form_DataOC.append('email', this.formData.email)
                        form_DataOC.append('Files', this.formData.file)
                        axios
                            .post("/api/addJobOfferCandidacies2", form_DataOC, {
                                headers: {
                                    Authorization: "Bearer " + token,
                                },
                            })
                            .then(response => {
                                console.log(response.status)
                            })
                            .catch((err) => {
                                console.log("error")
                            });
                    }
                    this.formData = {
                        nom: '',
                        prenom: '',
                        email: '',
                        file:'',
                        cin: '',
                        telephone: '',
                        offer: '',
                        nationality: '',
                        password: '',
                    };
                    this.$router.back();
                })



        },
        uploadFile(e) {
            let file = e.target.files[0];
            this.formData.file = file;
            console.log(file)
        }
    }
};


</script>
