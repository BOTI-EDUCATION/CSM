<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>Ajouter une section</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendSection" >
                <div class="form-row">
                  <div class="form-group col-12 offset-md-3 col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Prénom"
                      v-model="formControls.label"
                    />
                  </div>
                </div>
                <div class="form-group text-end">
                  <router-link to="/paramettrage/sections" class="btn btn-primary-light">annuler</router-link>
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
      section: {},
      formControls: {
        label: ""
      },
    };
  },
  methods: {
    getSection: async function (sectionId) {
      const token = localStorage.getItem("auth-token");
      if (sectionId && token) {
        await axios
          .get("/api/getSection/" + sectionId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
              this.section = result.data;
            this.formControls = {
              label: result.data.label
            };
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendSection: async function (e) {
        e.preventDefault();
      const token = localStorage.getItem("auth-token");
        
      let formData = new FormData();
      const config = {
        headers: {
            Authorization: "Bearer " + token,
            "content-type": "multipart/form-data"
        },
      };
        if(this.section.id)
       formData.append("section", this.section.id);

      formData.append(
        "label",
        this.formControls.label
      );

      axios
        .post("/api/saveSection", formData, config)
        .then((response) => {
            this.$router.push("/paramettrage/section");
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getSection(this.$route.params.id);
  },
};
</script>