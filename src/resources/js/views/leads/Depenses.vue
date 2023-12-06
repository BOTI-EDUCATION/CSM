<template>
  <div>
    <Layout>
      <div class="page-header">
        <h1 class="page-title">
          Depenses
          <span v-if="depenses.length > 0">({{ depenses.length }})</span>
        </h1>
        <div>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
              <router-link to="/schools"> Leads </router-link>
            </li>
          </ol>
        </div>
      </div>
      <div class="row justify-content-end">
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-label"> Sales Manager </label>

            <vue-select
              name="account_manager"
              :options="salesManager"
              :reduce="(sale) => sale.id"
              label="nom"
              v-model="manager"
              placeholder="Sales"
              @input="getByManager"
              searchable
            >
            </vue-select>
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label"> Rubriques </label>
          <select
            class="form-control"
            v-model="rubrique"
            @change="getByRubrique"
          >
            <option value="">Toutes les rubriques</option>
            <option
              v-for="rubrique in rubriques"
              :key="rubrique"
              :value="rubrique"
            >
              {{ rubrique }}
            </option>
          </select>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
          <div class="card overflow-hidden">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="table-responsive">
                    <!-- <table class="table text-nowrap text-md-nowrap mb-0"> -->
                    <v-table
                      class="table text-nowrap text-md-nowrap mb-2 text-center"
                      :data="depenses"
                      :currentPage.sync="currentPage"
                      :pageSize="10"
                      @totalPagesChanged="totalPages = $event"
                    >
                      <!-- v-for="lead in this.filtredLeads" -->
                      <thead slot="head">
                        <tr>
                          <th>Lead</th>
                          <th>Titre</th>
                          <th>Sale Manager</th>
                          <th>Date</th>
                          <th>Rubriques & Amount</th>
                          <th>Total</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody
                        slot="body"
                        slot-scope="{ displayData }"
                        class="text-center"
                      >
                        <tr v-for="row in displayData" :key="row.guid">
                          <td class="va-baseline">
                            <div class="align-items-center d-flex">
                              <img
                                :src="row.school_logo"
                                alt="profile-lead"
                                class="avatar profile-user brround cover-image me-2"
                              />
                              <router-link :to="'leads/view/' + row.school_id">
                                {{ row.school_name }}</router-link
                              >
                            </div>
                          </td>
                          <td class="va-baseline">
                            {{ row.interv_label }}
                          </td>
                          <td class="va-baseline">
                            <div class="text-center">
                              <img
                                :src="row.cs"
                                alt="profile-lead"
                                class="avatar profile-user brround cover-image me-2"
                              />
                            </div>
                          </td>
                          <td class="va-baseline">{{ row.createdAt }}</td>
                          <td>
                            <div class="row">
                              <div class="col-md-6">
                                <li
                                  style="list-style: none"
                                  v-for="(rubrique, index) in row.rubriques"
                                  :key="index"
                                  :class="`rubrique-${row.id}`"
                                >
                                  {{ rubrique }}
                                </li>
                              </div>
                              <div class="col-md-3">
                                <li
                                  style="list-style: none"
                                  v-for="(amount, index) in row.amounts"
                                  :key="index"
                                >
                                  <span
                                    :class="`amount-${row.id}`"
                                    class="fw-bold text-primary"
                                    >{{ amount }}MAD</span
                                  >
                                </li>
                              </div>
                            </div>
                          </td>

                          <td class="va-baseline">
                            <span
                              :class="`total-${row.id}`"
                              class="badge bg-warning text-dark fw-bold"
                              style="font-size: 15px"
                              >{{ row.total }}MAD</span
                            >
                          </td>

                          <td class="va-baseline">
                            <span
                              :id="`state-${row.id}`"
                              class="badge"
                              :class="
                                row.state != 'non-validée'
                                  ? 'bg-success'
                                  : 'bg-danger'
                              "
                              >{{ row.state }}</span
                            >
                          </td>
                          <td class="va-baseline">
                            <a
                              @click.prevent="changeState(row.id, $event)"
                              v-if="row.state == 'non-validée' && row.admin"
                              class="btn btn-sm btn-primary"
                            >
                              Valider
                            </a>
                            <a
                              v-if="row.state == 'non-validée' && row.createdBy"
                              class="btn btn-sm btn-info text-light"
                              @click="getItem(row)"
                            >
                              Editér
                            </a>
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
    </Layout>

    <!---------------------------- UPDATE PROFILE USER ---------------------------->

    <transition
      @enter="startTransitionModal($refs.modalRubrique)"
      @after-enter="endTransitionModal($refs.modalRubrique)"
      @before-leave="endTransitionModal($refs.modalRubrique)"
      @after-leave="startTransitionModal($refs.modalRubrique)"
    >
      <div class="modal fade" v-if="modalRubrique" ref="modalRubrique">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit.prevent="updateRubriue">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Modifier les rubriques
                </h5>
                <button class="close btn" @click="modalRubrique = false">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-md-12 mt-3" id="rubriques">
                  <div class="row">
                    <!---------------------------- TRANSPORT ---------------------------->
                    <div class="col-md-12">
                      <div
                        class="row align-items-center mb-3"
                        v-for="(item, index) in depenseItem.rubriques"
                        :key="item"
                      >
                        <div class="col-md-6">
                          <select name="rubrique[]" id="" class="form-control">
                            <option
                              v-for="rubrique in rubriques"
                              :key="rubrique"
                              :selected="item == rubrique ? true : false"
                            >
                              {{ rubrique }}
                            </option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <input
                            type="number"
                            name="amount[]"
                            class="form-control total"
                            :value="depenseItem.amounts[index]"
                          />

                          <input
                            type="hidden"
                            name="id"
                            :value="depenseItem.id"
                          />
                        </div>
                        <!-- <div
                              class="col-md-1"
                              :class="index == 0 ? 'd-block' : 'd-none'"
                              @click="newRubrique"
                            >
                              <a
                                class="btn btn-rounded btn-light"
                              >
                                <i class="fa-solid fa-plus"></i>
                              </a>
                            </div>
                            <div
                              class="col-md-1"
                              :class="index == 0 ? 'd-none' : 'd-block'"
                            >
                              <a 
                                class="btn btn-rounded btn-danger text-light"
                                
                              >
                                <i class="fa-solid fa-trash-can"></i>
                              </a>
                            </div> -->
                      </div>
                    </div>

                    <!---------------------------- TOTAL ---------------------------->
                    <div class="row justify-content-center">
                      <div class="col-md-6 text-center">
                        <p class="fw-bold">Total</p>
                      </div>
                      <div class="col-md-6 text-center">
                        <p class="fw-bold">{{ depenseItem.total }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button
                  class="btn btn-secondary"
                  @click="modalRubrique = false"
                >
                  Close
                </button>
                <button class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>

    <div class="modal-backdrop fade d-none" ref="backdrop"></div>
  </div>
</template>
  
  
  
  <script>
import Layout from "../../components/Layout.vue";
import vSelect from "vue-select";
import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
// import DateRangePicker from "../../../src/components/DateRangePicker";

export default {
  components: {
    Layout,
    "vue-select": vSelect,
    DateRangePicker,
  },

  data() {
    return {
      depenses: [],
      salesManager: [],
      rubriques: [],
      totalPages: 0,
      currentPage: 1,
      rubrique: null,
      manager: null,
      modalRubrique: false,
      depenseItem: {
        rubriques: [],
        amounts: [],
        id: null,
        total: 0,
      },
      countRubrique: 0,
      state: "lead",
    };
  },
  filters: {
    dateCell(value) {
      let dt = new Date(value);

      return dt.getDate();
    },
    date(val) {
      return val ? val.toLocaleString() : "";
    },
  },
  methods: {
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
    getDepenses() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/depenses/leads", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.depenses = result.data;
          });
      }
    },
    changeState(id, event) {
      const token = localStorage.getItem("auth-token");
      let state = document.getElementById(`state-${id}`);
      if (token) {
        if (token) {
          this.$swal({
            title: "Vous êtes sûr de vouloir valider  cette depense",
            icon: "warning",
            showConfirmButton: false,
            showDenyButton: true,
            showCancelButton: true,
            denyButtonText: `valider`,
            cancelButtonText: `Annuler`,
          }).then(async (result) => {
            if (result.isDenied) {
              await axios
                .post(
                  "/api/validate/depense/" + id,
                  {},
                  {
                    headers: {
                      Authorization: "Bearer " + token,
                    },
                  }
                )
                .then((result) => {
                  event.target.classList.add("d-none");
                  state.classList.remove("bg-danger");
                  state.classList.add("bg-success");
                  state.innerText = "validée";
                })
                .catch(function (err) {
                  console.log(token);
                });
            }
          });
        }
      }
    },
    getRubriques() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/rubriques", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((response) => {
            this.rubriques = response.data;
          });
      }
    },
    getByManager() {
      const token = localStorage.getItem("auth-token");
      if (!this.manager) {
        this.getDepenses();
      } else {
        if (token) {
          axios
            .get(
              "/api/get/depenses/by_filter/" + this.manager + "/" + this.state,
              {
                headers: {
                  Authorization: "Bearer " + token,
                },
              }
            )
            .then(async (result) => {
              this.depenses = result.data;
            });
        }
      }
    },
    getSalesManager: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSalesManagers", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.salesManager = result.data;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    getByRubrique() {
      const token = localStorage.getItem("auth-token");
      if (this.rubrique == "") {
        this.getDepenses();
      } else {
        if (token) {
          axios
            .get(
              "/api/get/depenses/by_filter_rubrique/" +
                this.rubrique +
                "/" +
                this.state,
              {
                headers: {
                  Authorization: "Bearer " + token,
                },
              }
            )
            .then(async (result) => {
              this.depenses = result.data;
            });
        }
      }
    },
    getItem(depense) {
      this.modalRubrique = true;
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/get/item/" + depense.id, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.depenseItem.rubriques = result.data.rubriques;
            this.depenseItem.amounts = result.data.amounts;
            this.depenseItem.id = result.data.id;
            this.depenseItem.total = result.data.total;
          });
      }
    },
    updateRubriue(e) {
      const token = localStorage.getItem("auth-token");
      let amounts = document.querySelectorAll(`.amount-${this.depenseItem.id}`);
      let rubriques = document.querySelectorAll(
        `.rubrique-${this.depenseItem.id}`
      );
      let total = document.querySelector(`.total-${this.depenseItem.id}`);
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        axios
          .post("/api/update/depense", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.modalRubrique = false;
            for (let i = 0; i < result.data.rubriques.length; i++) {
              rubriques[i].innerHTML = result.data.rubriques[i];
              amounts[i].innerHTML = result.data.amounts[i];
            }
            total.innerHTML = result.data.total;
          });
      }
    },
  },
  mounted() {
    this.getDepenses();
    this.getRubriques();
    this.getSalesManager();
  },
  computed: {
    rows() {
      return this.depenses.length;
    },
  },
};
</script>
  
  
  <style scoped>
.va-baseline {
  vertical-align: middle;
}

.slot {
  background-color: #aaa;
  padding: 0.5rem;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.text-black {
  color: #000;
}
</style>