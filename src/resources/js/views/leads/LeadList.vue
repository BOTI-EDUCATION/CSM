<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <router-link class="btn btn-info btn-icon me-3" to="/leads/map">
          <i class="fe fe-map"></i>
        </router-link>
        <router-link class="btn btn-primary" to="/leads/add">
          <i class="fe fe-plus"></i>
          Add a new lead
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
                      <th>School</th>
                      <th>Sales Manager</th>
                      <th>City</th>
                      <th>Effectif</th>
                      <th>Last Contact</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="this.leads.length > 0">
                    <tr v-for="lead in this.leads" :key="lead.id">
                      <td>
                        <div class="align-items-center d-flex" >
                        <img
                          :src="lead.logo"
                          alt="profile-lead"
                          class="avatar profile-user brround cover-image me-2"
                        />
                        {{ lead.name }}
                        </div>
                      </td>
                      <td>
                        <div class="align-items-center d-flex" >
                        <template v-if="lead.sales_manager">

                        <img
                          :src="lead.sales_manager.img"
                          alt="profile-lead"
                          class="avatar profile-user brround cover-image me-2"
                        />
                        {{ lead.sales_manager.name }}
                        </template>
                        </div>
                      </td>
                      <td>
                        <div class="align-items-center d-flex" >
                        {{lead.city}}
                        </div>
                      </td>
                      <td>
                        <div class="align-items-center d-flex" >
                        {{lead.effectif}}
                        </div>
                      </td>
                      <td>
                        <div class="align-items-center d-flex" >
                        {{lead.last_contact}}
                        </div>
                      </td>
                      <td>
                        <div class="align-items-center d-flex" >
                        <router-link class="mx-2"
                          :to="'/leads/edit/' + lead.id"
                        >
                          <i class="fe fa-lg fe-edit-2"></i>
                        </router-link>
                         <router-link class="mx-2"
                          :to="'/leads/view/' + lead.id"
                        >
                          <i class="fe fa-lg fe-eye"></i>
                         </router-link>
                        
                        </div>
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
  emits:[ 'load-leads' ],
  data() {
    return {
      leads: [],
    };
  },
  methods: {
    getLeadsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getLeadsList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.leads = result.data;
            this.$emit('load-leads',this.leads.length)
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    deleteLead: async function (e, id) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer cette école",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Supprimer`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post("/api/deleteLead/" + id, {} , {
                headers: {
                  Authorization: "Bearer " + token,
                }
              })
              .then((result) => {
                this.leads = result.data;
              })
              .catch(function (err) {
                console.log(token)
              });
          }
        });
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
  },
  mounted() {
    this.getLeadsList();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
.profile {
  max-width: 100px;
}
.user-card .actions {
  display: flex;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}
.user-card .actions > * {
  flex: 1;
}
a .hoverable{
  transition: all ease-in-out .4s;
}
a .hoverable:hover{
      box-shadow: 0px 6px 15px 0px #33333344;
}
</style>