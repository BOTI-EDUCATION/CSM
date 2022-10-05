<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">New Article</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New article
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
                        <h4 class="card-title">New Article</h4>
                    </div>
                    <div class="card-body">
                        <form ref="offerForm" class="form-horizontal needs-validation" @submit.prevent="createArticle">
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
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Introduction</label>
                                <div class="col-md-9">
                                    <input v-model="formData.introduction" type="text" class="form-control"
                                        name="introduction" value="Introduction" placeholder="text">
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Details</label>
                                <div class="col-md-9">
                                    <vue-editor required v-model="formData.details" type="text" class="form-control"
                                        name="details" value="details"></vue-editor>
                                </div>
                            </div>
                            <div class=" row mb-4"> <label class="col-md-3 form-label">Keywords</label>
                                <div class="col-md-9" id="divKeywords">
                                    <ImsKeywordBox v-model="formData.tags" />
                                    <!-- <input type="text" id="txtInput" class="form-control" placeholder="Enter keyword..." /> -->
                                </div>
                            </div>
                                <div class="row mb-4 mb-4">
                                <label class="col-md-3 form-label">Keys</label>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options="choices.map(choice => choice.label)" v-model="formData.keys[0].key"
                                                placeholder="Choose item" searchable>
                                                <!-- <template #search="{ attributes, events }">
                                                    <input class="vs__search" :required="!formData.keys[0].key"
                                                        v-bind="attributes" v-on="events" />
                                                </template> -->
                                            </vue-select>
                                        </div>
                                        <div class="col-md-6">
                                            <input v-model="formData.keys[0].value" class="form-control" placeholder="Enter value" type="text">
                                            <!-- <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options = "getPossibleValues(formData.keys[0].key,infosList)"
                                                v-model="formData.keys[0].value" placeholder="Choose item" searchable>
                                                <template #search="{ attributes, events }">
                                                    <input class="vs__search" :required="!formData.keys[0].value"
                                                        v-bind="attributes" v-on="events" />
                                                </template>
                                            </vue-select> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <a @click="deleteFieldKey(0)" class="btn btn-primary"><i class="bi bi-trash"></i></a>
                                </div>
                                <div class="col-md-1 text-center">
                                    <input type="button" style="width:35%" @click="addFieldKey" class="btn btn-primary"
                                        value="+">
                                </div>
                            </div>
                            <div v-if="formData.keys.length > 1" v-for="(info, index) in formData.keys.slice(1)"
                                v-bind:key="index" class="row mb-4 mb-4">
                                <label class="col-md-3 form-label"></label>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options="choices.map(choice => choice.label)" v-model="formData.keys[index + 1].key"
                                                placeholder="Choose item" searchable>
                                            </vue-select>
                                        </div>
                                        <div class="col-md-6">
                                            <input v-model="formData.keys[index + 1].value" class="form-control"
                                                type="text" name="infos.value" required>
                                            <!-- <vue-select class="" name="value" label="value" :reduce="info => info"
                                                :options = "getPossibleValues(formData.infos[index + 1].key,infosList)"
                                                v-model="formData.infos[index + 1].value" placeholder="Choose item"
                                                searchable>
                                            </vue-select> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <a @click="deleteFieldKey(index+1)" class="btn btn-primary"><i
                                            class="bi bi-trash"></i></a>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-2 form-label">Blogs</label>
                                <div class="col-md-9" id="divKeywords">
                                    <!-- <Blog_item v-model="formData.blog[formData.blog.length-1]" /> -->
                                    <!--Form-->
                                    <div class="col-xl-12 col-lg-12" style="width:100%">
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                    <div class=" row mb-4"> <label class="col-md-3 form-label"
                                                            for="example-email">Label</label>
                                                        <div class="col-md-9"> <input
                                                                v-model="formData.blog[formData.blog.length - 1].label"
                                                                type="text" id="example-email" name="label"
                                                                class="form-control" placeholder="Titre" >
                                                        </div>
                                                    </div>
                                                    <div class=" row mb-4"> <label class="col-md-3 form-label"
                                                            for="example-email">Images</label>
                                                        <div class="col-md-9"> <input @change="uploadImageBlog"
                                                                type="file" name="image" class="form-control"
                                                                value="Upload image" multiple>
                                                        </div>
                                                    </div>
                                                    <div class=" row mb-4"> <label
                                                            class="col-md-3 form-label">Details</label>
                                                        <div class="col-md-9">
                                                            <vue-editor
                                                                v-model="formData.blog[formData.blog.length - 1].details"
                                                                type="text" class="form-control" name="details"
                                                                value="details">
                                                            </vue-editor>
                                                        </div>
                                                    </div>
                                                    <div class=" row mb-4"> <label
                                                            class="col-md-3 form-label">Links</label>
                                                        <div class="col-md-9" id="divKeywords">
                                                            <input
                                                                v-model="formData.blog[formData.blog.length - 1].link"
                                                                type="text" id="links" name="label" class="form-control"
                                                                placeholder="Links" >
                                                        </div>
                                                    </div>
                                               
                                            </div>
                                        </div>
                                    </div>

                                    <!--end Form-->
                                </div>
                                <div class="col-md-1">
                                    <span class="text-info"><input class="btn btn-primary" type="button" @click.stop="addBlog" value="+"/></span>
                                    <!-- <button class="btn" @click.stop="addBlog"><span class="ml-3 text-danger"><i class="bi bi-plus-lg"></i></span></button> -->
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-3"></div>
                                <div class="col-9">
                                    <div v-if="formData.blog.length > 1" v-for="(blog, index) in formData.blog.slice(0, formData.blog.length - 1)" class="col-md-12">
                                        <p>
                                            <div class="card card-body bg-light border border-primary">
                                                <a data-bs-toggle="collapse" v-bind:href="`#collapse${index}`" role="button"
                                                    aria-expanded="false" aria-controls="collapseExample">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="mr-auto p-2">
                                                            <p>Blog {{ index + 1 }}</p>
                                                        </div>
                                                        <div class="p-2">
                                                            <span class="mr-3"><i class="bi bi-caret-down-fill"></i></span>
                                                            <button class="btn" @click.stop="deleteField(index)"><span class="ml-3 text-danger"><i class="bi bi-x-lg"></i></span></button>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </p>
                                        <div class="collapse" v-bind:id="`collapse${index}`">
                                            <div class="card card-body">
                                                label: {{ blog.label }} <br>
                                                order: {{ blog.order }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-primary" type="submit">Save Article</button>
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
import axios from "axios";
import Layout from "../../components/Layout.vue";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import ImsKeywordBox from "ims-keyword-box";

import { VueEditor } from "vue2-editor";
// import villes from "./ville.json";

export default {
  components: {
    Layout,
    ImsKeywordBox,
    Dropzone: vue2Dropzone,
    "vue-select": vSelect,
    "vue-editor": VueEditor,
  },
  data() {
    return {
      infosList: [],
      choices: [
        {
          label: "total learners registered",
          alias: "total_learners_registered",
        },
        {
          label: "total instructors registered",
          alias: "total_instructors_registered",
        },
      ],
      formData: {
        label: "",
        introduction: "",
        details: "",
        keys: [
          {
            key: "",
            value: "",
          },
        ],
        blog: [
          {
            label: "",
            details: "",
            link: "",
            // order: ''
          },
        ],
        // date: new Date().toISOString().slice(0, 10),
        tags: [],
      },
    };
  },
  async mounted() {
    const token = localStorage.getItem("auth-token");
  },
  methods: {
    createArticle() {
      let form_Data = new FormData();
      
      form_Data.append("label", this.formData.label);
      form_Data.append("details", this.formData.details);
      form_Data.append("image", this.formData.image);
      form_Data.append("introduction", this.formData.introduction);
      // form_Data.append('tags', this.formData.tags)
      for (let index = 0; index < this.formData.blog.length; index++) {
        const element = this.formData.blog[index];
        form_Data.append("blogs[]", JSON.stringify(element));
      }

      for (let index = 0; index < this.formData.tags.length; index++) {
        const element = this.formData.tags[index];
        form_Data.append("tags[]", JSON.stringify(element));
      }

      for (let index = 0; index < this.formData.keys.length; index++) {
        const element = this.formData.keys[index];
        form_Data.append("keys[]", JSON.stringify(element));
      }

      const token = localStorage.getItem("auth-token");
      axios
        .post("/api/InsertArticle", form_Data, {
          headers: {
            Authorization: "Bearer " + token,
          },
        })
        .then((response) => {
          this.$router.back();
        });
      this.formData = {
        label: "",
        introduction: "",
        details: "",
        blog: [
          {
            label: "",
            details: "",
            link: "",
            order: "",
          },
        ],
        tags: [],
      };
    },
    getPossibleValues(key, list) {
      for (let i = 0; i < list.length; i++) {
        if (list[i].label === key) {
          return list[i].values_possible;
        }
      }
    },
    addBlog() {
      if (this.formData.blog.length < 4) {
        console.log("done !!!");
        this.formData.blog.push({
          label: "",
          details: "",
          link: "",
          order: "",
        });
      }
    },
    addFieldKey() {
      if (this.formData.keys.length < this.choices.length) {
        this.formData.keys.push({
          key: "",
          value: "",
        });
        console.log("added");
      }
    },
    deleteFieldKey(counter) {
      if (this.formData.keys.length > 1) {
        this.formData.keys.splice(counter, 1);
      }
    },
    deleteField(counter) {
      if (this.formData.blog.length > 1) {
        this.formData.blog.splice(counter, 1);
      }
    },
    uploadImage(e) {
      let file = e.target.files[0];
      this.formData.image = file;
      //console.log(file)
    },
    uploadImageBlog(e) {
      let files = e.target.files[0];
      this.formData.blog[this.formData.blog.length - 1].image = files;
      //console.log(file)
    },
  },
};
</script>
