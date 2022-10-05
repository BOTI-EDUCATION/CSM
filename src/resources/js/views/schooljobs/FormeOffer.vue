<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">New job offer</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Jobs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New job offer
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
                        <h4 class="card-title">New job offer</h4>
                    </div>
                    <div class="card-body">
                        <form ref="offerForm" class="form-horizontal needs-validation" @submit.prevent="createOffer">
                            <!--<div class=" row mb-4"> <label class="col-md-3 form-label">Reference</label>
                                <div class="col-md-9"> <input type="text" v-model="formData.reference" name="reference"
                                        class="form-control" value="">
                                </div>
                            </div>-->
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Titre</label>
                                <div class="col-md-9"> <input v-model="formData.title" type="text" id="example-email"
                                        name="titre" class="form-control" placeholder="Titre" required> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label" for="example-email">Image</label>
                                <div class="col-md-9"> <input @change="uploadImage" type="file" name="image"
                                        class="form-control" value="Upload image"> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Introduction</label>
                                <div class="col-md-9">
                                    <!--<vue-editor v-model="formData.introduction" type="text" class="form-control"
                                        name="introduction" value="Introduction"></vue-editor>-->
                                    <input v-model="formData.introduction" type="text" class="form-control"
                                        name="introduction" value="Introduction" placeholder="text">
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Details</label>
                                <div class="col-md-9">
                                    <vue-editor v-model="formData.details" type="text" class="form-control"
                                        name="details" value="details"></vue-editor>
                                </div>
                            </div>
                            <div class="row mb-4 mb-4">
                                <label class="col-md-3 form-label">Infos</label>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options="infosList.map(info => info.label)" v-model="formData.infos[0].key"
                                                placeholder="Choose item" searchable>
                                                <template #search="{ attributes, events }">
                                                    <input class="vs__search" :required="!formData.infos[0].key"
                                                        v-bind="attributes" v-on="events" />
                                                </template>
                                            </vue-select>
                                        </div>
                                        <div class="col-md-6">
                                            <!--<input v-model="formData.infos[0].value" required class="form-control"
                                                type="text" name="infos.value">-->
                                            <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options = "getPossibleValues(formData.infos[0].key,infosList)"
                                                v-model="formData.infos[0].value" placeholder="Choose item" searchable>
                                                <template #search="{ attributes, events }">
                                                    <input class="vs__search" :required="!formData.infos[0].value"
                                                        v-bind="attributes" v-on="events" />
                                                </template>
                                            </vue-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <a @click="deleteField(0)" class="btn btn-primary"><i class="bi bi-trash"></i></a>
                                </div>
                                <div class="col-md-1 text-center">
                                    <input type="button" style="width:35%" @click="addField" class="btn btn-primary"
                                        value="+">
                                </div>
                            </div>
                            <div v-if="formData.infos.length > 1" v-for="(info, index) in formData.infos.slice(1)"
                                v-bind:key="index" class="row mb-4 mb-4">
                                <label class="col-md-3 form-label"></label>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options="infosList.map(info => info.label)" v-model="formData.infos[index + 1].key"
                                                placeholder="Choose item" searchable>
                                            </vue-select>
                                        </div>
                                        <div class="col-md-6">
                                            <!--<input v-model="formData.infos[index + 1].value" class="form-control"
                                                type="text" name="infos.value">-->
                                            <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options = "getPossibleValues(formData.infos[index + 1].key,infosList)"
                                                v-model="formData.infos[index + 1].value" placeholder="Choose item"
                                                searchable>
                                            </vue-select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <a @click="deleteField(index+1)" class="btn btn-primary"><i
                                            class="bi bi-trash"></i></a>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                            <div class="row pb-5">
                                <div class="col-md-3">
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">School</label>
                                <!--<div class="col-md-9"> <input :value="ecole" @input="e => formData.ecole = e.target.value"
                                        class="form-control" type="text" name="ecole"> </div>-->
                                <div class="form-group col-md-9">
                                    <vue-select class="" name="ecole" :options="schools" :reduce="school => school.alias" label="label"
                                        v-model="formData.ecole" placeholder="School Name" searchable>
                                        <template #search="{ attributes, events }">
                                            <input class="vs__search" :required="!formData.ecole" v-bind="attributes"
                                                v-on="events" />
                                        </template>
                                    </vue-select>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">City</label>
                                <div class="col-md-9">
                                    <!--<input v-model="formData.city" class="form-control" type="text" name="city">-->
                                    <vue-select v-model="formData.city" class="" name="city" label="city"
                                        :options="cities" placeholder="City Name" searchable>
                                    </vue-select>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Address</label>
                                <div class="col-md-9"> <input v-model="formData.address" class="form-control"
                                        type="text" name="address"> </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Date</label>
                                <div class="col-md-9">
                                    <div class="">
                                        <input class="form-control" type="date" v-model="formData.date" name="date">
                                    </div>
                                    <!--<input v-model="formData.date" class="form-control" type="text"
                                        name="date">-->
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Localization</label>
                                <div class="col-md-9"> <input v-model="formData.localisation" class="form-control"
                                        type="text" name="localization">
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-primary" type="submit">Save Offer</button>
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
                reference: '',
                title: '',
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
            },
            schools: [],
            cities: [],
            infosList: [
                {alias:'',label:'',values_possible:[]}
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
                    this.schools.push({alias:school.id,label:school.name})
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
                this.infosList=result.data  
                // console.log("infos list here: ",this.infosList.map(info => info.label))

                // result.data.map((info) => {
                //     this.infosList.push({alias:info.alias,label:info.label,values_possible:info.values_possible})
                // })

            })
            .catch((err) => {
                console.log("error")
            });



        //this.countries=data
        //console.log(data);

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
            
        },
        getPossibleValues(key,list){
            for(let i=0; i<list.length;i++){
                if(list[i].label === key){
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
