<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Add News</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add News
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
                        <h4 class="card-title">Add News</h4>
                    </div>
                    <div class="card-body">
                        <form ref="offerForm" class="form-horizontal needs-validation" @submit.prevent="createNews">
                            <!--<div class=" row mb-4"> <label class="col-md-3 form-label">Reference</label>
                                <div class="col-md-9"> <input type="text" v-model="formData.reference" name="reference"
                                        class="form-control" value="">
                                </div>
                            </div>-->
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Label</label>
                                <div class="col-md-9"> 
                                    <input v-model="formData.label" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Label" required> 
                                        <!-- <vue-editor v-model="formData.label" type="text" class="form-control"
                                        name="introduction" value="texte"></vue-editor> -->
                                        </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Image</label>
                                <div class="col-md-9"> <input @change="uploadImage" type="file" name="image"
                                        class="form-control" value="Upload image"> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Introduction</label>
                                <div class="col-md-9">
                                    <input v-model="formData.introduction" type="text" class="form-control"
                                        name="details" value="introduction"/>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Text</label>
                                <div class="col-md-9">
                                    <vue-editor v-model="formData.texte" type="text" class="form-control"
                                        name="introduction" value="texte"></vue-editor>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Link</label>
                                <div class="col-md-9">
                                    <input v-model="formData.link" type="text" class="form-control"
                                        name="details" value="details"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-primary" type="submit">Save News</button>
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
                texte: '',
                label: '',
                image: '',
                Introduction: '',
                link:'',
            },
            

        }
    },
    async mounted() {
        

    },
    methods: {
        createNews() {
            let form_Data = new FormData();
            form_Data.append('texte', this.formData.texte)
            form_Data.append('image', this.formData.image)
            form_Data.append('label', this.formData.label)
            form_Data.append('introduction', this.formData.introduction)
            form_Data.append('link', this.formData.link)
            const token = localStorage.getItem("auth-token");
            axios.post("/api/InsertNews", form_Data, {
                headers: {
                    Authorization: "Bearer " + token,
                },
            })
                .then(response => {
                    console.log(response.status)
                    this.$router.back();
                })
            this.formData = {
                texte: '',
                label: '',
                image: '',
                Introduction: '',
                link:'',
            };
            
        },
        uploadImage(e) {
            let file = e.target.files[0];
            this.formData.image = file;
        }
    }
};


</script>
