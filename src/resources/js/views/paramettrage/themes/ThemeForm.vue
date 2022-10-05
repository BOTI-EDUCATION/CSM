<template>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>Ajouter un theme</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendTheme" >
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Label"
                      v-model="formControls.label"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <vue-select class="" multiple name="modules"
                    :reduce="module => module.id" label="label"
                    :options="modules" v-model="formControls.modules" placeholder="Modules" searchable >
                    </vue-select>
                  </div>
                </div>
                <div class="form-group text-end">
                  <router-link to="/paramettrage/themes" class="btn btn-primary-light">annuler</router-link>
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
import 'vue-select/dist/vue-select.css';

export default {
  components: {
    "vue-select":vSelect
  },
  data() {
    return {
      theme: {},
      modules: [],
      formControls: {
        label: "",
        modules: "",
      },
    };
  },
  methods: {
    getModulesList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getModulesList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            let modules = []
            result.data.forEach(element => {
              modules.push({id:element.id,label:element.label})
            });
            this.modules = modules;
            console.log(this.modules.length);
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    getTheme: async function (themeId) {
      const token = localStorage.getItem("auth-token");
      if (themeId && token) {
        await axios
          .get("/api/getThemeFormInfo/" + themeId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.theme = result.data;
            this.formControls = {
              label: result.data.label,
              modules: result.data.modules,
            };
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendTheme: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");

      let formData = new FormData();
      const config = {
        headers: {
          Authorization: "Bearer " + token,
          "content-type": "multipart/form-data",
        },
      };
      if (this.theme.id) formData.append("theme", this.theme.id);

      formData.append("label", this.formControls.label);
      formData.append("modules", this.formControls.modules);

      axios
        .post("/api/saveTheme", formData, config)
        .then((response) => {
          this.$router.push("/paramettrage/themes");
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getTheme(this.$route.params.id);
    this.getModulesList();
  },
};
</script>