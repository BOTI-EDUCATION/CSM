<template>
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right mb-3">
        <router-link :to="'/documentation/add'" class="btn btn-primary">Add documentation</router-link>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tutoriels et videos for Boti School :</h3>
          </div>
          <div class="card-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 col-md-3 border-end">
                  <Tree @item-clicked="loadDocumentation" :list="treeData" :isWrapper="true" />
                </div>
                <div v-if="documentation" class="col-12 col-md-9 border-start">
                  <h3>{{documentation.title}}</h3>
                  <div v-html="documentation.details"></div>
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
    Tree
  },
  data() {
    return {
      treeData: null,
      documentation: null,
    };
  },
  methods: {
    getTreeData: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getDocumentationTreeData", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.treeData = result.data;
          })
          .catch(function (err) {
          });
      }
    },
    loadDocumentation: async function(item){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/loadDocumentation/"+item.id , {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.documentation = result.data;
          })
          .catch(function (err) {
          });
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
