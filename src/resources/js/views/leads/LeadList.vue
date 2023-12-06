<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 .col-md-12 text-right flex-end">
        <div class="col-md-3 text-right">
          <input
            type="text"
            placeholder="Search by school"
            v-model="keySearch"
            @keyup="filterOnLead($event)"
            class="form-control"
          />
        </div>
        <div class="col-md-3">
          <router-link class="btn btn-success me-3" to="/leads/kanban">
            <i class="fa-solid fa-arrow-right"></i>

          </router-link>
          <router-link class="btn btn-info btn-icon me-3" to="/leads/map">
            <i class="fe fe-map"></i>
          </router-link>
          <router-link class="btn btn-primary" to="/leads/add">
            <i class="fe fe-plus"></i>
           
          </router-link>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                  <!-- <table class="table text-nowrap text-md-nowrap mb-0"> -->
                  <v-table
                    class="table text-nowrap text-md-nowrap mb-2"
                    :data="filtredLeads"
                    :currentPage.sync="currentPage"
                    :pageSize="10"
                    @totalPagesChanged="totalPages = $event"
                  >
                    <!-- v-for="lead in this.filtredLeads" -->
                    <thead slot="head">
                      <tr>
                        <th>School</th>
                        <th>Sales Manager</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Comment</th>
                        <th>Creation Date</th>
                        <th>Last Contact</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody slot="body" slot-scope="{ displayData }">
                      <tr v-for="row in displayData" :key="row.guid">

                          <td>
                            <div class="align-items-center d-flex">
                              <img
                                :src="row.logo"
                                alt="profile-lead"
                                class="avatar profile-user brround cover-image me-2"
                              />
                             <router-link :to="'leads/view/'+row.id"> {{ row.name }}</router-link>
                            </div>
                          </td>
                          <td>
                            <div class="align-items-center d-flex justify-content-center">
                              <template v-if="row.sales_manager">
                                <img
                                  :src="row.sales_manager.img"
                                  alt="profile-lead"
                                  class="avatar profile-user brround cover-image me-2"
                                />
                                <!-- {{ row.sales_manager.name }} -->
                              </template>
                            </div>
                          </td>
                          <td>
                            <div class="align-items-center d-flex">
                              {{ row.city }}
                            </div>
                          </td>
                          <td>
                            <div class="align-items-center d-flex">
                              <span class="badge rounded-pill bg-info" v-if="row.status && row.status != 'null'">{{
                                row.status
                              }}</span>
                            </div>
                          </td>
                          <td>
                            <div class="align-items-center d-flex" v-if="row.source && row.source != 'null'">
                              {{ row.source }}
                            </div>
                          </td>
                          <td>
                            <div class="align-items-center d-flex" v-if="row.comment && row.comment != 'null'">
                              {{ row.comment }}
                            </div>
                          </td>
                          <td>
                            {{ row.created_at }}
                          </td>
                          <td>
                            <div class="align-items-center d-flex">
                              {{ row.last_contact }}
                            </div>
                          </td>
                          <td>
                            <div class="align-items-center d-flex">
                              <a href="https://www.google.com/maps" target="_blank" v-if="row.localisation && row.adresse" class="btn btn-sm btn-danger text-light me-1 mx-1">
                                <i class="fa-solid fa-location-dot"></i>
                              </a>
                              <router-link
                                class="btn btn-sm btn-warning text-dark me-1 mx-1"
                                :to="'/leads/edit/' + row.id"
                              >
                                <i class="fe fa-lg fe-edit-2"></i>
                              </router-link>
                              <router-link
                                class="btn btn-sm btn-info text-light me-1 mx-1"
                                :to="'/leads/view/' + row.id"
                              >
                                <i class="fe fa-lg fe-eye"></i>
                              </router-link>
                            </div>
                          </td>
                      </tr>
                    </tbody>
                  </v-table>
                  <smart-pagination
                    :currentPage.sync="currentPage"
                    :totalPages="totalPages"
                    class="mb-2"
                  />
                </div>
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
  emits: ["load-leads"],
  data() {
    return {
      leads: [],
      filtredLeads: [],
      keySearch: "",
      totalPages: 0,
      currentPage: 1,
    };
  },
  methods: {
    filterOnLead() {
      this.filtredLeads = this.leads.filter((ld) => {
        if (ld.name.toLowerCase().includes(this.keySearch.toLowerCase())) {
          this.filtredLeads = ld;
          return this.filtredLeads;
        }
      });
    },
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
            this.filtredLeads = this.leads;
            console.log("leads...", this.filtredLeads);
            this.$emit("load-leads", this.leads.length);
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
              .post(
                "/api/deleteLead/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.leads = result.data;
              })
              .catch(function (err) {
                console.log(token);
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
  computed: {
    rows() {
      return this.filtredLeads.length;
    },
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
a .hoverable {
  transition: all ease-in-out 0.4s;
}
a .hoverable:hover {
  box-shadow: 0px 6px 15px 0px #33333344;
}

.flex-end {
  display: flex;
  justify-content: flex-end;
  align-content: center;
}
</style>