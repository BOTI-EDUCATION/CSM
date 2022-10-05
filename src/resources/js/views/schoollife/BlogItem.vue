<template>
        <!--Form-->
            <div class="col-xl-12 col-lg-12" style="width:100%">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Blog</h4>
                    </div>
                    <div class="card-body">
                        <form ref="offerForm" class="form-horizontal needs-validation" @submit.prevent="createOffer">
                            <!--<div class=" row mb-4"> <label class="col-md-3 form-label">Reference</label>
                                <div class="col-md-9"> <input type="text" v-model="formData.reference" name="reference"
                                        class="form-control" value="">
                                </div>
                            </div>-->
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Label</label>
                                <div class="col-md-9"> <input v-model="formData.label" type="text" id="example-email"
                                        name="label" class="form-control" placeholder="Titre" required> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Image</label>
                                <div class="col-md-9"> <input @change="uploadImage" type="file" name="image"
                                        class="form-control" value="Upload image"> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Details</label>
                                <div class="col-md-9">
                                    <vue-editor v-model="formData.details" type="text" class="form-control"
                                        name="details" value="details"></vue-editor>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Links</label>
                                <div class="col-md-9" id="divKeywords">
                                    <input v-model="formData.links" type="text" id="links"
                                        name="label" class="form-control" placeholder="Titre" required>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
        <!--end Form-->
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
                label: '',
                introduction: '',
                details: '',
                date: new Date().toISOString().slice(0, 10),
                tags: [],
                infos: [
                    {
                        key: '',
                        value: '',
                    }
                ],
            },
            schools: [],
            cities: [],

            infosList: [
                { alias: '', label: '', values_possible: [] }
            ]

        }
    },
    async mounted() {
        let tmp = []
        villes.map((ville) => {
            tmp.push(ville.ville)
        })
        this.cities = [...new Set(tmp.map(item => item))];
        console.log(this.cities)
        const token = localStorage.getItem("auth-token");
        await axios
            .get("/api/getSchoolsList", {
                headers: {
                    Authorization: "Bearer " + token,
                },
            })
            .then(async (result) => {
                //this.schools = result.data;
                result.data.map((school) => {
                    this.schools.push({ alias: school.id, label: school.name })
                })
            })
            .catch((err) => {
                console.log("error")
            });


        await axios
            .get("/api/getInfosList", {
                headers: {
                    Authorization: "Bearer " + token,
                },
            })
            .then(async (result) => {
                console.log("Infos List: ", result.data);
                this.infosList = result.data

            })
            .catch((err) => {
                console.log("error")
            });

    },
    methods: {
        createOffer() {
            let form_Data = new FormData();
            form_Data.append('reference', this.formData.reference)
            form_Data.append('title', this.formData.title)
            form_Data.append('image', this.formData.image)
            form_Data.append('localization', this.formData.localization)
            form_Data.append('city', this.formData.city)
            form_Data.append('ecole', this.formData.ecole)
            form_Data.append('address', this.formData.address)
            form_Data.append('date', this.formData.date)
            for (let index = 0; index < this.formData.infos.length; index++) {
                const element = this.formData.infos[index];
                form_Data.append(
                    "infos[]",
                    JSON.stringify(element)
                );

            }

            form_Data.append('details', this.formData.details)


            //console.log(this.formData)
            const token = localStorage.getItem("auth-token");
            axios.post("/api/insertNewOffer", form_Data, {
                headers: {
                    Authorization: "Bearer " + token,
                },
            })
                .then(response => {
                    console.log(response.status)
                    this.$router.back();
                })
            this.formData = {
                reference: '',
                title: '',
                image: '',
                localization: '',
                city: '',
                ecole: '',
                address: '',
                date: new Date().toISOString().slice(0, 10),
                infos: [
                    {
                        key: '',
                        value: '',
                    }
                ],
                details: '',
            };
            //e.preventDefault();

        },
        getPossibleValues(key, list) {
            for (let i = 0; i < list.length; i++) {
                if (list[i].label === key) {
                    return list[i].values_possible
                }
            }

        },
        addField() {

            if (this.formData.infos.length < this.infosList.length) {
                this.formData.infos.push({
                    key: '',
                    value: ''
                })
                console.log("added")
            }
        },
        deleteField(counter) {
            if (this.formData.infos.length > 1) {
                this.formData.infos.splice(counter, 1);
            }
        },
        uploadImage(e) {
            let file = e.target.files[0];
            this.formData.image = file;
            //console.log(file)
        }
    }
};


</script>
