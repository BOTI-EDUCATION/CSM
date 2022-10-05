<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">New Quote</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New Quote
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
                        <h4 class="card-title">New Quote</h4>
                    </div>
                    <div class="card-body">
                        <form ref="offerForm" class="form-horizontal needs-validation" @submit.prevent="createQuote">
                            <!--<div class=" row mb-4"> <label class="col-md-3 form-label">Reference</label>
                                <div class="col-md-9"> <input type="text" v-model="formData.reference" name="reference"
                                        class="form-control" value="">
                                </div>
                            </div>-->
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Texte</label>
                                <div class="col-md-9"> 
                                    <!-- <input v-model="formData.title" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Titre" required>  -->
                                        <vue-editor v-model="formData.texte" type="text" class="form-control"
                                        name="introduction" value="texte"></vue-editor>
                                        </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Image</label>
                                <div class="col-md-9"> <input @change="uploadImage" type="file" name="image"
                                        class="form-control" value="Upload image"> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Author</label>
                                <div class="col-md-9">
                                    <input v-model="formData.author" type="text" class="form-control"
                                        name="introduction" value="Author" placeholder="text">
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Function</label>
                                <div class="col-md-9">
                                    <input v-model="formData.function" type="text" class="form-control"
                                        name="details" value="details"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-primary" type="submit">Save Quote</button>
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
                author: '',
                function: '',
                published: '',
                image:'',
            },
            

        }
    },
    async mounted() {
        

    },
    methods: {
        createQuote() {
            let form_Data = new FormData();
            form_Data.append('texte', this.formData.texte)
            form_Data.append('image', this.formData.image)
            form_Data.append('author', this.formData.author)
            form_Data.append('function', this.formData.function)
            const token = localStorage.getItem("auth-token");
            axios.post("/api/InsertNewQuote", form_Data, {
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
                author: '',
                function: '',
                published: '',
                image:'',
            };
            
        },
        uploadImage(e) {
            let file = e.target.files[0];
            this.formData.image = file;
        }
    }
};


</script>
