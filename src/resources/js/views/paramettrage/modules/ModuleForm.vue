<template>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>Ajouter un module</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendModule" >
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
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Icone"
                      v-model="formControls.icone"
                    />
                  </div>
                </div>
                <div class="form-group text-end">
                  <router-link to="/paramettrage/modules" class="btn btn-primary-light">annuler</router-link>
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

export default {
    
   
  components: {
  },
  data() {
    return {
      module: {},
      formControls: {
        label: "",
        icon: ""
      }
    };
  },
  methods: {
    getModule: async function (moduleId) {
      const token = localStorage.getItem("auth-token");
      if (moduleId && token) {
        await axios
          .get("/api/getModuleFormInfo/" + moduleId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
              this.module = result.data;
            this.formControls = {
              label: result.data.label,
              icon: result.data.icon
            };
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendModule: async function (e) {
        e.preventDefault();
      const token = localStorage.getItem("auth-token");
        
      let formData = new FormData();
      const config = {
        headers: {
            Authorization: "Bearer " + token,
            "content-type": "multipart/form-data"
        },
      };
        if(this.module.id)
       formData.append("module", this.module.id);

      formData.append(
        "label",
        this.formControls.label
      );
      formData.append(
        "icone",
        this.formControls.icone
      );

      axios
        .post("/api/saveModule", formData, config)
        .then((response) => {
            this.$router.push("/paramettrage/modules");
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  },
  mounted() {

    this.getModule(this.$route.params.id);
  },
};
</script>