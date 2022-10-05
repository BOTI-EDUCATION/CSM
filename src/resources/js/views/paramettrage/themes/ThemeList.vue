<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <router-link class="btn btn-primary" to="/paramettrage/themes/add">
          <i class="fe fe-plus"></i>
          Ajouter un nouveau theme
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
                      <th>Modules</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="themes.length > 0">
                    <tr v-for="themeItem in themes" :key="themeItem.id">
                      <td>{{ themeItem.id }}</td>
                      <td>{{ themeItem.label }}</td>
                      <td> <span v-for="module in themeItem.modules" :key="module.id" class="tag tag-rounded tag-primary text-nowrap mx-1">{{module.label}}</span> </td>
                      <td>
                        <router-link
                          :to="'/paramettrage/themes/edit/' + themeItem.id"
                        >
                          <i class="fe fe-edit-2"></i>
                        </router-link>
                        <a @click="deleteTheme($event,themeItem.id)" href="" class="text-danger">
                          <i class="fe fe-trash-2"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="5" class="text-center">
                        <h6>Aucun theme n'est créé</h6>
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
      themes: [],
    };
  },
  methods: {
    getThemeList: async function () {
      
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getThemesList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.themes = result.data;
            console.log(this.themes.length);
          })
          .catch(function (err) {
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
                .post("/api/deleteTheme/"+id , {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                })
                .then((result) => {
                  this.themes = result.data;
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
    this.getThemeList();
  },
};
</script>

<style scoped>
.text-right{
  text-align: right;
}
</style>