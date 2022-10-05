<template>
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
      <div class="card">
        <div class="card-body">
          <div class="container">
            <div class="row mb-3">
              <div class="col-12 col-md-4">
                <label for="" class="">Category</label>
              </div>
              <div class="col-12 col-md-8">
                {{document?document.title:'-'}}
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-4">
                <label for="" class="">Title</label>
              </div>
              <div class="col-12 col-md-8">
                <input type="text" v-model="formControls.title" class="form-control">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-4">
                <label for="" class="">Roles</label>
              </div>
              <div class="col-12 col-md-8">
                <vue-select
                  multiple
                  :reduce="(role) => role.id"
                  label="label"
                  :options="roles"
                  v-model="formControls.roles"
                  placeholder="Roles"
                  searchable
                ></vue-select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-4">
                <label for="" class="">Details</label>
              </div>
              <div class="col-12 col-md-8">
                <vue-editor 
                    id="details"
                    v-model="formControls.details">
                </vue-editor>
              </div>
            </div>
            <div class="row">
              <div class="col-12 text-right">
                <button @click="saveDocumentation" class="btn btn-primary">Enregistrer</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import { VueEditor } from "vue2-editor";

export default {
  components: {
    'vue-select' : vSelect,
    'vue-editor' : VueEditor,
  },
  data() {
    return {
      document: {},
      roles: [
          {
            'id': 'responsable-pedagogique',
            'label': 'Responsable pédagogique'
          },
          {
            'id': 'responsable-financier',
            'label': 'Responsable financier'
          },
          {
            'id': 'account_manager',
            'label': 'Account Manager'
          },
      ],
      formControls: {
        parent: null,
        id: null,
        title: "",
        details: "",
        roles: [],
      },
    };
  },
  methods: {
    getDocumentation: async function (id) {
      const token = localStorage.getItem("auth-token");
      if (id && token) {
        await axios
          .get("/api/getDocumentation/" + id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then( (result) => {
            if (this.$route.path.includes("/add/")) {

              this.document =  result.data;
              this.formControls = {
                parent:  result.data.id,
                id: null,
                title: "",
                details: "",
                roles: [],
              };

            } else if(this.$route.path.includes("/edit/")) {
              this.document =  result.data.parent;
              
              this.formControls = {
                parent:  (result.data.parent?result.data.parent.id:null),
                id:  result.data.id,
                title:  result.data.title,
                details:  result.data.details,
                roles:  result.data.roles,
              };

            }
          })
          .catch(function (err) {});
      }
    },
    getRole: async function(){
      // const token = localStorage.getItem("auth-token");
      // if (token) {
      //   await axios
      //     .get("/api/getRolesList", {
      //       headers: {
      //         Authorization: "Bearer " + token,
      //       },
      //     })
      //     .then( (result) => {
      //       this.roles = result.data
      //     })
      //     .catch(function (err) {});
      // }
    },
    saveDocumentation : async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .post("/api/saveDocumentation", this.formControls , {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then( (result) => {
            this.$router.back();
          })
          .catch(function (err) {});
      }
    }
  },
  mounted() {
    // this.getRole();
    this.getDocumentation(this.$route.params.id);
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
</style>
