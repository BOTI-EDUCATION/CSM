<template>
  <Layout>
    <!-- PAGE-HEADER -->
    <div class="page-header">
      <h1 class="page-title">New Article</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">School Life</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            New article
          </li>
        </ol>
      </div>
      <a class="mb-5 btn btn-primary" href="javascript:history.back()"
        >Go Back</a
      >
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
            <form ref="offerForm" class="form-horizontal needs-validation">
              <!-- category -->
              <div class="row mb-4">
                <label class="col-md-3 form-label" for="example-email"
                  >Category</label
                >
                <div class="col-md-9">
                  <vue-select
                    class=""
                    name="value"
                    :reduce="(info) => info.id"
                    :options="categories"
                    label="label"
                    v-model="formData.category"
                    placeholder="Choose item"
                    searchable
                    @change="hasVideoMethod(formData.category)"
                  />
                </div>
              </div>

              <!-- label -->
              <div class="row mb-4">
                <label class="col-md-3 form-label" for="example-email"
                  >Label</label
                >
                <div class="col-md-9">
                  <input
                    v-model="formData.label"
                    type="text"
                    id="example-email"
                    name="label"
                    class="form-control"
                    placeholder="Titre"
                    required
                  />
                </div>
              </div>
              <!-- label -->

              <!-- category -->
              <div class="row mb-4" v-if="hasVideoMethod(formData.category)">
                <label class="col-md-3 form-label" for="example-email"
                  >podcast video</label
                >
                <div class="col-md-9">
                  <input
                    @change="uploadVideo"
                    type="file"
                    name="video"
                    class="form-control"
                    value="Upload image"
                  />
                </div>
              </div>

              <div class="row mb-4" v-if="hasDateMethod(formData.category)">
                <label class="col-md-3 form-label" for="example-email"
                  >date d'evenement</label
                >
                <div class="col-md-9">
                  <b-form-input
                    class="mb-3"
                    id="type"
                    type="date"
                    v-model="formData.date_event"
                  ></b-form-input>
                  <b-form-input
                    id="type"
                    type="time"
                    v-model="formData.time_event"
                  ></b-form-input>
                </div>
              </div>

              <!-- article Image -->
              <div class="row mb-4">
                <label class="col-md-3 form-label" for="example-email"
                  >Image</label
                >
                <div class="col-md-9">
                  <input
                    @change="uploadImage"
                    type="file"
                    name="image"
                    class="form-control"
                    value="Upload image"
                  />
                </div>
              </div>

              <!-- article Image MOBILE FOR EDUCATION-->
              <div class="row mb-4" v-if="choosedCategory == 'education'">
                <label class="col-md-3 form-label" for="example-email"
                  >Image Mobile <br /><small>Taille ( 50 * 50 )</small></label
                >
                <div class="col-md-9">
                  <input
                    @change="uploadImage"
                    type="file"
                    name="image-mobile"
                    class="form-control"
                    value="Upload image"
                  />
                </div>
              </div>

              <!-- article Image -->
              <!-- introduction -->
              <div class="row mb-4">
                <label class="col-md-3 form-label">Introduction</label>
                <div class="col-md-9">
                  <input
                    v-model="formData.introduction"
                    type="text"
                    class="form-control"
                    name="introduction"
                    value="Introduction"
                    placeholder="text"
                  />
                </div>
              </div>
              <!-- introduction -->
              <!-- article details -->
              <div class="row mb-4" v-show="choosedCategory != 'interview'">
                <label class="col-md-3 form-label">Details</label>
                <div class="col-md-9">
                  <vue-editor
                    required
                    v-model="formData.details"
                    type="text"
                    class="form-control"
                    name="details"
                    value="details"
                  ></vue-editor>
                </div>
              </div>
              <!-- article details -->

              <!-- keywords -->
              <div class="row mb-4" v-if="choosedCategory != 'interview'">
                <label class="col-md-3 form-label">Keywords</label>
                <div class="col-md-9" id="divKeywords">
                  <ImsKeywordBox v-model="formData.tags" />
                </div>
              </div>
              <!-- keywords -->
              <!-- keys -->
              <div class="row mb-4" v-if="choosedCategory != 'interview'">
                <label class="col-md-3 form-label">Keys</label>
                <div class="col-md-7">
                  <div class="row">
                    <div class="col-md-6">
                      <vue-select
                        class=""
                        name="value"
                        label="value"
                        :reduce="(info) => info"
                        :options="choices.map((choice) => choice.label)"
                        v-model="formData.keys[0].key"
                        placeholder="Choose item"
                        searchable
                      ></vue-select>
                    </div>
                    <div class="col-md-6">
                      <input
                        v-model="formData.keys[0].value"
                        class="form-control"
                        placeholder="Enter value"
                        type="text"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                  <a @click="deleteFieldKey(0)" class="btn btn-primary"
                    ><i class="bi bi-trash"></i
                  ></a>
                </div>
                <div class="col-md-1 text-center">
                  <input
                    type="button"
                    style="width: 35%"
                    @click="addFieldKey"
                    class="btn btn-primary"
                    value="+"
                  />
                </div>
              </div>
              <!-- keys -->
              <!-- keys -->
              <div
                v-if="formData.keys.length > 1"
                v-for="(info, index) in formData.keys.slice(1)"
                v-bind:key="index"
                class="row mb-4 mb-4"
              >
                <label class="col-md-3 form-label"></label>
                <div class="col-md-7">
                  <div class="row">
                    <div class="col-md-6">
                      <vue-select
                        class=""
                        name="value"
                        label="value"
                        :reduce="(info) => info"
                        :options="choices.map((choice) => choice.label)"
                        v-model="formData.keys[index + 1].key"
                        placeholder="Choose item"
                        searchable
                      >
                      </vue-select>
                    </div>
                    <div class="col-md-6">
                      <input
                        v-model="formData.keys[index + 1].value"
                        class="form-control"
                        type="text"
                        name="infos.value"
                        required
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                  <a @click="deleteFieldKey(index + 1)" class="btn btn-primary"
                    ><i class="bi bi-trash"></i
                  ></a>
                </div>
                <div class="col-md-1"></div>
              </div>

              <div class="row mb-4">
                <div class="col-md-9">
                  <b-form-checkbox
                    id="checkbox-1"
                    v-model="formData.visible"
                    name="checkbox-1"
                    :value="true"
                    :unchecked-value="false"
                    @change="visibleio"
                  >
                    publier votre article
                  </b-form-checkbox>
                </div>
              </div>

              <div class="row">
                <div class="d-flex flex-row-reverse">
                  <button
                    class="btn btn-primary"
                    @click.prevent="createArticle()"
                  >
                    Save Article
                  </button>
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
      minDate: "",
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
      categories: [],
      formData: {
        label: "",
        introduction: "",
        details: "",
        category: "",
        keys: [
          {
            key: "",
            value: "",
          },
        ],
        tags: [],
        visible: false,
        hasvideo: false,
        haseDate: false,
        date_event: null,
        time_event: null,
        choosedCategory: null,
      },
    };
  },
  async mounted() {
    const token = localStorage.getItem("auth-token");
    const axi = await axios.get("api/getAllcategories");
    this.categories = axi.data.categories;

    const date = new Date();
    this.minDate = `${date.getFullYear()}-${
      date.getMonth() + 1
    }-${date.getDate()}`;
    console.log(this.minDate);
  },
  methods: {
    createArticle() {
      console.log(this.formData);
      let form_Data = new FormData();

      form_Data.append("label", this.formData.label);
      form_Data.append("details", this.formData.details);
      form_Data.append("image", this.formData.image);
      form_Data.append("video", this.formData.video);
      form_Data.append("introduction", this.formData.introduction);
      form_Data.append("category", this.formData.category);
      form_Data.append("visible", this.formData.visible);
      form_Data.append("hasVideo", this.formData.hasvideo);
      form_Data.append("haseDate", this.formData.haseDate);
      form_Data.append("date_event", this.formData.date_event);
      form_Data.append("time_event", this.formData.time_event);

      // for (let index = 0; index < this.formData.blog.length - 1; index++) {
      //   const element = this.formData.blog[index];
      //   form_Data.append("blogs[]", JSON.stringify(element));
      // }

      for (let index = 0; index < this.formData.tags.length; index++) {
        const element = this.formData.tags[index];
        form_Data.append("tags[]", JSON.stringify(element));
      }

      for (let index = 0; index < this.formData.keys.length - 1; index++) {
        const element = this.formData.keys[index];
        form_Data.append("keys[]", [JSON.stringify(element), element.image]);
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
        this.formData.blog[this.formData.blog.length - 1].order =
          this.formData.blog.length;
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
    },
    uploadVideo(e) {
      let file = e.target.files[0];
      this.formData.video = file;
    },
    uploadImageBlog(e) {
      let files = e.target.files[0];
      this.formData.blogImage = files;
    },
    // setdate(e) {
    //   console.log(this.formData.date_event);
    // },
    // setTime(e) {
    //   console.log(this.formData.time_event);
    // },
    hasVideoMethod(cat) {
      // this.choosedCategory = cat;
      if (cat !== "" && cat !== null) {
        const x = this.categories
          .map((e) => {
            return e.id == cat && e;
          })
          .filter((e) => {
            return e !== false;
          })[0];
        this.formData.hasvideo = x.hasVideo;
        this.choosedCategory = x.alias;
        return x.hasVideo;
      }
      this.formData.hasvideo = false;
      return false;
    },
    hasDateMethod(cat) {
      if (cat !== "" && cat !== null) {
        const x = this.categories
          .map((e) => {
            return e.id == cat && e;
          })
          .filter((e) => {
            return e !== false;
          })[0];
        this.formData.haseDate = x.specific_date;
        return x.specific_date;
      }
      this.formData.haseDate = false;
      return false;
    },
    visibleio() {
      // this.formData.visible = !this.formData.visible
      console.log(this.formData.visible);
    },
  },
};
</script>
