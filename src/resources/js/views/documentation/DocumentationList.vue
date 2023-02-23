<template>
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right mb-3">
      <router-link :to="'/documentation/add'" class="btn btn-primary"
        >Add documentation</router-link
      >
    </div>
    <div class="col-sm-12 col-md-12">
      <div class="col-md-6" style="padding-left: 0rem">

        <p
          class="badge bg-danger"
          style="cursor: pointer"
          @click="type = 'no_type'"
        >
          No type
        </p>

        <p
          class="badge bg-success"
          style="cursor: pointer"
          @click="type = 'school'"
        >
          School
        </p>
        <p
          class="badge bg-info"
          style="cursor: pointer"
          @click="type = 'british'"
        >
          British
        </p>
        <p
          class="badge bg-primary"
          style="cursor: pointer"
          @click="type = 'kinder'"
        >
          Kinder
        </p>
       
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Tutoriels et videos for Boti School : <b> <span class="badge bg-dark text-warning">{{ type }}</span> </b>
          </h3>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              <!--------------------- SCHOOL --------------------->
              <div v-if="type == 'school'" class="col-12 col-md-3 border-end">
                <p
                  class="badge bg-success"
                  style="cursor: pointer"
                  @click="type = 'school'"
                >
                  School
                </p>

                <p
                  class="badge bg-info"
                  style="cursor: pointer"
                  @click="changeTheType('School-British')"
                >
                  School-British
                </p>

                <p
                  class="badge bg-primary"
                  style="cursor: pointer"
                  @click="changeTheType('School-kinder')"
                >
                  School-Kinder
                </p>

                <div v-if="treeData.length <= 0">
                    <div class="alert alert-danger text-center"> <strong>Empty Data !</strong> </div>
                </div>
                <div v-else>
                  <Tree
                  @item-clicked="loadDocumentation"
                  :list="treeData"
                  :isWrapper="true"
                />
                </div>
              </div>
              <!--------------------- BRITISH --------------------->
              <div
                v-else-if="type == 'british'"
                class="col-12 col-md-3 border-end"
              >
                <p
                  class="badge bg-info"
                  style="cursor: pointer"
                  @click="type = 'british'"
                >
                  British
                </p>
                <p
                  class="badge bg-success"
                  style="cursor: pointer"
                  @click="changeTheType('British-School')"
                >
                  British-School
                </p>
                <p
                  class="badge bg-primary"
                  style="cursor: pointer"
                  @click="changeTheType('British-Kinder')"
                >
                  British-kinder
                </p>
                <div v-if="treeDataBritish.length <= 0">
                    <div class="alert alert-danger text-center"> <strong>Empty Data !</strong> </div>
                </div>
                <div v-else>
                  <Tree
                  @item-clicked="loadDocumentationBritish"
                  :list="treeDataBritish"
                  :isWrapper="true"
                />
                </div>
              </div>
              <!--------------------- NOTYPE --------------------->
              <div
                v-else-if="type == 'no_type'"
                class="col-12 col-md-3 border-end"
              >
                <p class="badge bg-danger">No type</p>
                <div v-if="treeDataNoType.length <= 0">
                    <div class="alert alert-danger text-center"> <strong>Empty Data !</strong> </div>
                </div>
                <div v-else>
                  <Tree
                  @item-clicked="loadDocumentationNo"
                  :list="treeDataNoType"
                  :isWrapper="true"
                />
                </div>
              </div>
              <!--------------------- BYTYPES --------------------->
              <div
                v-else-if="type == 'kinder'"
                class="col-12 col-md-3 border-end"
              >
                <p
                  class="badge bg-primary"
                  style="cursor: pointer"
                  @click="type = 'kinder'"
                >
                  Kinder
                </p>
                <p
                  class="badge bg-info"
                  style="cursor: pointer"
                  @click="changeTheType('Kinder-British')"
                >
                  Kinder-British
                </p>

                <p
                  class="badge bg-success"
                  style="cursor: pointer"
                  @click="changeTheType('Kinder-School')"
                >
                  Kinder-School
                </p>
                <div v-if="treeDataKinder.length <= 0">
                    <div class="alert alert-danger text-center"> <strong>Empty Data !</strong> </div>
                </div>
                <div v-else>
                  <Tree
                  @item-clicked="loadDocumentationKinder"
                  :list="treeDataKinder"
                  :isWrapper="true"
                />
                </div>
               
              </div>
              <!--------------------- BYTYPES --------------------->
              <div v-else class="col-12 col-md-3 border-end">
                <div
                  v-if="type == 'British-School' || type == 'British-Kinder'"
                >
                  <p
                    class="badge bg-info"
                    style="cursor: pointer"
                    @click="type = 'british'"
                  >
                    British
                  </p>
                  <p
                    class="badge bg-success"
                    style="cursor: pointer"
                    @click="changeTheType('British-School')"
                  >
                    British-School
                  </p>
                  <p
                    class="badge bg-primary"
                    style="cursor: pointer"
                    @click="changeTheType('British-Kinder')"
                  >
                    British-kinder
                  </p>
                </div>

                <div v-if="type == 'School-British' || type == 'School-kinder'">
                  <p
                    class="badge bg-success"
                    style="cursor: pointer"
                    @click="type = 'school'"
                  >
                    School
                  </p>
                  <p
                    class="badge bg-info"
                    style="cursor: pointer"
                    @click="changeTheType('School-British')"
                  >
                    School-British
                  </p>

                  <p
                    class="badge bg-primary"
                    style="cursor: pointer"
                    @click="changeTheType('School-kinder')"
                  >
                    School-Kinder
                  </p>
                </div>

                <div v-if="type == 'Kinder-British' || type == 'Kinder-School'">
                  <p
                    class="badge bg-primary"
                    style="cursor: pointer"
                    @click="type = 'kinder'"
                  >
                    Kinder
                  </p>
                  <p
                    class="badge bg-info"
                    style="cursor: pointer"
                    @click="changeTheType('Kinder-British')"
                  >
                    Kinder-British
                  </p>

                  <p
                    class="badge bg-success"
                    style="cursor: pointer"
                    @click="changeTheType('Kinder-School')"
                  >
                    Kinder-School
                  </p>
                </div>
       
                
                  <div v-if="treeDataByTypes.length <= 0">
                    <div class="alert alert-danger text-center"> <strong>Empty Data !</strong> </div>
                </div>
                <div v-else>
                  <Tree
                  @item-clicked="loadDocByTypes"
                  :list="treeDataByTypes"
                  :isWrapper="true"
                />
                </div>
              </div>

              <div class="col-12 col-md-9 border-start">
                <h3 v-if="documentation && type == 'school'">
                  {{ documentation.title }}
                </h3>
                <h3 v-else-if="documentationBritish && type == 'british'">
                  {{ documentationBritish.title }}
                </h3>

                <h3 v-else-if="documentationKinder && type == 'kinder'">
                  {{ documentationKinder.title }}
                </h3>

                <h3
                  v-else-if="documentationByTypes && type == 'British-School'"
                >
                  {{ documentationByTypes.title }}
                </h3>
                <h3
                  v-else-if="documentationByTypes && type == 'British-Kinder'"
                >
                  {{ documentationByTypes.title }}
                </h3>
                <h3
                  v-else-if="documentationByTypes && type == 'School-British'"
                >
                  {{ documentationByTypes.title }}
                </h3>
                <h3
                  v-else-if="documentationByTypes && type == 'School-kinder'"
                >
                  {{ documentationByTypes.title }}
                </h3>
                <h3
                  v-else-if="documentationByTypes && type == 'Kinder-British'"
                >
                  {{ documentationByTypes.title }}
                </h3>
                <h3
                  v-else-if="documentationByTypes && type == 'Kinder-School'"
                >
                  {{ documentationByTypes.title }}
                </h3>
                <h3 v-else-if="documentationNoType && type == 'no_type'">
                  {{ documentationNoType.title }}
                </h3>

                <div
                  v-if="documentation && type == 'school'"
                  v-html="documentation.details"
                ></div>
                <div
                  v-else-if="documentationBritish && type == 'british'"
                  v-html="documentationBritish.details"
                ></div>
                <div
                  v-else-if="documentationKinder && type == 'kinder'"
                  v-html="documentationKinder.details"
                ></div>
                <div
                  v-else-if="documentationByTypes && type == 'British-School'"
                  v-html="documentationByTypes.details"
                ></div>
                <div
                  v-else-if="documentationByTypes && type == 'British-Kinder'"
                  v-html="documentationByTypes.details"
                ></div>
                <div
                  v-else-if="documentationByTypes && type == 'School-British'"
                  v-html="documentationByTypes.details"
                ></div>
                <div
                  v-else-if="documentationByTypes && type == 'School-kinder'"
                  v-html="documentationByTypes.details"
                ></div>
                <div
                  v-else-if="documentationByTypes && type == 'Kinder-British'"
                  v-html="documentationByTypes.details"
                ></div>
                <div
                  v-else-if="documentationByTypes && type == 'Kinder-School'"
                  v-html="documentationByTypes.details"
                ></div>
                <div
                  v-else-if="documentationNoType && type == 'no_type'"
                  v-html="documentationNoType.details"
                ></div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import {Swiper , SwiperSlide} from 'swiper/vue';
import Layout from "../../components/Layout.vue";
import Tree from "../../components/tree/Tree.vue";

export default {
  components: {
    Layout,
    Tree,
  },
  data() {
    return {
      treeData: null,
      treeDataBritish: null,
      treeDataKinder: null,
      treeDataNoType: null,
      treeDataByTypes: null,

      documentation: null,
      documentationBritish: null,
      documentationKinder: null,
      documentationNoType: null,
      documentationByTypes: null,

      type: "school",
      type_1: null,
      type_2: null,

    };
  },
  methods: {
    getTreeData () {
      const token = localStorage.getItem("auth-token");
      if (token) {
         axios
          .get("/api/TreeDDocumentationTreeData", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then(result => {
            this.treeData = result.data.school;
            this.treeDataKinder = result.data.kinder;
            this.treeDataBritish = result.data.british;
            this.treeDataNoType = result.data.noType;
            this.treeDataBritishSchool = result.data.britishSchool;
          })
          .catch(function (err) {});
      }
    },

    changeTheType(type) {
      this.type = type;
      if (this.type == "British-School") {
        this.getTreeDataByTypes("british", "school");
      } else if (this.type == "British-Kinder") {
        this.getTreeDataByTypes("british", "kinder");
      } else if (this.type == "School-British") {
        this.getTreeDataByTypes("school", "british");
      } else if (this.type == "School-kinder") {
        this.getTreeDataByTypes("school", "kinder");
      } else if (this.type == "Kinder-British") {
        this.getTreeDataByTypes("kinder", "british");
      } else if (this.type == "Kinder-School") {
        this.getTreeDataByTypes("kinder", "school");
      }
    },
    getTreeDataByTypes(type_1, type_2) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/getDocsByTypes/" + type_1 + "/" + type_2, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result.data.byTypes);
            this.treeDataByTypes = result.data.byTypes;
          })
          .catch((error) => {
            error;
          });
      }
    },
    loadDocumentation: async function (item) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/loadDocumentationSchool/" + item.id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.documentation = result.data;
          })
          .catch(function (err) {});
      }
    },

    loadDocumentationKinder: async function (item) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/loadDocumentationKinder/" + item.id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.documentationKinder = result.data;
          })
          .catch(function (err) {});
      }
    },

    loadDocumentationBritish: async function (item) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/loadDocumentationBritish/" + item.id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.documentationBritish = result.data;
          })
          .catch(function (err) {});
      }
    },

    loadDocumentationNo: async function (item) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/loadDocumentationNoType/" + item.id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.documentationNoType = result.data;
          })
          .catch(function (err) {});
      }
    },

    loadDocByTypes: async function (item) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/loadDocumenataionByTypes/" + item.id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.documentationByTypes = result.data;
          })
          .catch(function (err) {});
      }
    },
  },
  mounted() {
    this.getTreeData();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
</style>
