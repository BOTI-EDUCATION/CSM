<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>Ajouter une criteria</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendCriteria" >
                <div class="form-row">
                  <div class="form-group col-12 col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Label"
                      v-model="formControls.label"
                    />
                  </div>
                  <div class="form-group col-12 col-md-4">
                    <select id="" class="form-control form-select" v-model="formControls.section">
                      <option v-for="section in sections" :key="section.id" :value="section.id">{{section.label}}</option>
                    </select>
                  </div>
                  <div class="form-group col-12 col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Short Label"
                      v-model="formControls.short"
                    />
                  </div>
                </div>
                <div class="form-group text-end">
                  <router-link to="/paramettrage/criteria" class="btn btn-primary-light">annuler</router-link>
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
      criteria: {},
      formControls: {
        label: "",
        section: "",
        short: ""
      },
      sections:[]
    };
  },
  methods: {
    getTrackingSections: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getTrackingSections", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.sections = result.data;
          })
          .catch((err) =>  {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    getCriteria: async function (criteriaId) {
      const token = localStorage.getItem("auth-token");
      if (criteriaId && token) {
        await axios
          .get("/api/getCriteria/" + criteriaId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
              this.criteria = result.data;
            this.formControls = {
              label: result.data.label,
              short: result.data.short,
              section: result.data.section
            };
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendCriteria: async function (e) {
        e.preventDefault();
      const token = localStorage.getItem("auth-token");
        
      let formData = new FormData();
      const config = {
        headers: {
            Authorization: "Bearer " + token,
            "content-type": "multipart/form-data"
        },
      };
        if(this.criteria.id)
       formData.append("criteria", this.criteria.id);

      formData.append(
        "label",
        this.formControls.label
      );

      formData.append(
        "short",
        this.formControls.short
      );

      formData.append(
        "section",
        this.formControls.section
      );

      axios
        .post("/api/saveCriteria", formData, config)
        .then((response) => {
            this.$router.push("/paramettrage/criteria");
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getCriteria(this.$route.params.id);
    this.getTrackingSections();
  },
};
</script>