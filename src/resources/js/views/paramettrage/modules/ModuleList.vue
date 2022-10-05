<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <router-link class="btn btn-primary" to="/paramettrage/modules/add">
          <i class="fe fe-plus"></i>
          Ajouter un nouveau module
        </router-link>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <table class="table text-nowrap text-md-nowrap mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Label</th>
                      <th>Alias</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="modules.length > 0">
                    <tr v-for="moduleItem in modules" :key="moduleItem.id">
                      <td>{{ moduleItem.id }}</td>
                      <td>{{ moduleItem.label }}</td>
                      <td>{{ moduleItem.alias }}</td>
                      <td>
                        <router-link
                          :to="'/paramettrage/modules/edit/' + moduleItem.id"
                        >
                          <i class="fe fe-edit-2"></i>
                        </router-link>
                        <a @click="deleteModule($event,moduleItem.id)" href="" class="text-danger">
                          <i class="fe fe-trash-2"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="5" class="text-center">
                        <h6>Aucun utilisateur n'est créé</h6>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
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
      modules: [],
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
            this.modules = result.data;
            console.log(this.modules.length);
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }else{
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    deleteModule: async function (e,id){
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
                .post("/api/deleteModule/"+id , {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                })
                .then((result) => {
                  this.modules = result.data;
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
    this.getModulesList();
  },
};
</script>

<style scoped>
.text-right{
  text-align: right;
}
</style>