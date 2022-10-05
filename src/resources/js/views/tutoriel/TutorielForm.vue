<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-12 text-center">
              <h4> <b> Add new tutoriel </b> </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
              <form @submit="sendTutoriel" >
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="">Titre</label>
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Name"
                      v-model="formControls.label"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="">Youtube ID</label>
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Youtube ID"
                      v-model="formControls.youtubeID"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="">Module</label>
                    <vue-select
                      multiple
                      label="label"
                      :options="modules"
                      placeholder="Modules"
                      v-model="selectedModules"
                      searchable
                      @option:selected="updateThemes"
                    ></vue-select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="">Theme</label>
                    <vue-select
                      multiple
                      label="label"
                      :reduce="(theme) => theme.id"
                      :options="themes"
                      placeholder="Themes"
                      v-model="formControls.themes"
                      searchable
                    ></vue-select>
                  </div>
                </div>
                <div class="form-row mb-3">
                    <label for="">Details</label>
                  <vue-editor
                    id="details"
                    v-model="formControls.details"
                  >
                  </vue-editor> 
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="">Order</label>
                    <input type="number" class="form-control" 
                      v-model="formControls.order"
                    >
                  </div>
                </div>
                <div class="form-group text-end">
                  <button @click="$event.preventDefault(); $router.back()" class="btn btn-primary-light">annuler</button>
                  <button class="btn btn-primary">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import vSelect from 'vue-select'

import { VueEditor } from "vue2-editor";

export default {
    
   
  components: {
    "vue-select":vSelect,
    "vue-editor": VueEditor
  },
  data() {
    return {
      tutoriel: {},
      modules: [],
      themes: [],
      selectedModules: [],
      formControls: {
        id:null,
        label: "",
        youtubeID: "",
        details: "",
        themes: [],
        order: null
      },
    };
  },
  methods: {
    getTutoriel: async function (tutorielId) {
      const token = localStorage.getItem("auth-token");
      if (tutorielId && token) {
        await axios
          .get("/api/getTutorielFormInfo/" + tutorielId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
              this.tutoriel = result.data;
            this.formControls = {
              label: this.tutoriel.label,
              details: this.tutoriel.details,
              date: this.tutoriel.date,
              enabled: this.tutoriel.enabled,
              image: {},
              files: [],
            };
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    getTutorielModules: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getTutorielModules", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.modules = result.data;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendTutoriel: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
        
      const config = {
        headers: {
            Authorization: "Bearer " + token
        },
      };

      axios
        .post("/api/saveTutoriel", this.formControls , config)
        .then((response) => {
            // this.$router.push("/paramettrage/tutoriels");
            this.$router.back();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    updateThemes: function(){
      let themes = [];
      for (let i = 0; i < this.selectedModules.length; i++) {
        const module = this.selectedModules[i];
        for (let j = 0; j < module.themes.length; j++) {
          const theme = module.themes[j];

          themes.push(theme)
        }
      }

      this.themes = themes;
    }
  },
  mounted() {
    // this.getTutoriel(this.$route.params.id);
    this.getTutorielModules();
  },
};
</script>

<style>

.ql-container{
  height: auto !important;
}

</style>