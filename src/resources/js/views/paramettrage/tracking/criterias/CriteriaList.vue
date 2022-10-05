<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <router-link class="btn btn-primary" to="/paramettrage/criteria/add">
          <i class="fe fe-plus"></i>
            Add a new criteria
        </router-link>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table text-nowrap text-md-nowrap mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Label</th>
                  <th>Short</th>
                  <th>Alias</th>
                  <th>Section</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="criteria in criterias" :key="criteria.id">
                  <td>{{criteria.id}}</td>
                  <td>{{criteria.label}}</td>
                  <td>{{criteria.short}}</td>
                  <td>{{criteria.alias}}</td>
                  <td>{{criteria.section.label}}</td>
                  <td>
                    <div class="row">
                      <router-link :to="'/paramettrage/criteria/edit/'+criteria.id" class="text-primary" ><i class="fe fe-edit-2"></i></router-link>
                      <a @click="deleteTrackingCriteria($event,criteria.id)" href="javasacript:void(0)" class="text-danger">
                          <i class="fe fe-trash-2"></i>
                        </a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      criterias: [],
    };
  },
  methods: {
    getTrackingCriterias: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getTrackingCriterias", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.criterias = result.data;
          })
          .catch(function (err) {
          });
      }
    },
    deleteTrackingCriteria: async function (e,id){
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: 'Vous êtes sûr de vouloir supprimer ce module',
          icon: 'warning',
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Supprimer`,
          cancelButtonText: `Annuler`,
        }).then( async (result) => {
            if (result.isDenied) {
              await axios
                .post("/api/deleteTrackingCriteria/"+id ,{}, {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                })
                .then((result) => {
                  this.criterias = result.data;
                })
                .catch(function (err) {
                });
            }
          });
      }else{
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    }
  },
  mounted() {
    this.getTrackingCriterias();
  },
};
</script>

<style scoped>

</style>