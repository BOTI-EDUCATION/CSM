<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Themes ({{ themes.length }})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">academy</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/academy/themes"> Themes </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <button
          class="btn btn-warning text-dark"
          @click="enabledTheDragabble"
          v-if="!saveBtn"
        >
          Change the ordres
        </button>
        <button class="btn btn-info" @click="saveTheChanges" v-if="saveBtn">
          <b>Save the changes ordres</b>
        </button>
        <a class="btn btn-primary" @click="addTheme" v-if="!saveBtn">
          <i class="fe fe-plus"></i>
          Add a new Theme
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <table
                  v-if="!enabled"
                  class="table text-nowrap text-md-nowrap mb-0"
                >
                  <thead>
                    <tr>
                      <th>Label</th>
                      <th>Alias</th>
                      <th>Order</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tr v-for="theme in themes" :key="theme.id">
                    <th scope="row">{{ theme.label }}</th>
                    <td>{{ theme.alias }}</td>
                    <td> <span class="badge bg-info">{{ theme.ordre }} </span></td>
                    <td>
                      <button
                        class="btn btn-warning me-1 text-dark"
                        @click="getTheme(theme.id)"
                      >
                        editer
                      </button>
                      <a
                        class="btn btn-danger text-white"
                        @click.prevent="deleteTheme(theme.id)"
                      >
                        supprimer
                      </a>
                    </td>
                  </tr>
                </table>
                <table
                  v-if="enabled"
                  class="table text-nowrap text-md-nowrap mb-0"
                >
                  <thead>
                    <tr>
                      <th></th>
                      <th>Label</th>
                      <th>Alias</th>
                      <th>Order</th>
                    </tr>
                  </thead>

                  <draggable v-model="themes" tag="tbody">
                    <tr
                      v-for="(theme, index) in themes"
                      :key="theme.id"
                      style="cursor: pointer"
                    >
                      <td>
                        <i
                          class="fa-solid fa-computer-mouse text-info"
                          style="cursor: pointer"
                        ></i>
                      </td>
                      <th scope="row">{{ theme.label }}</th>
                      <td>{{ theme.alias }}</td>
                      <td>
                        <span class="badge bg-info">{{ index + 1 }} </span>
                      </td>
                    </tr>
                  </draggable>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- New Modal -->
    <transition
      @enter="startTransitionModal($refs.theme)"
      @after-enter="endTransitionModal($refs.theme)"
      @before-leave="endTransitionModal($refs.theme)"
      @after-leave="startTransitionModal($refs.theme)"
    >
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        v-if="newModal"
        ref="theme"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ themeModalTitle }}
              </h5>
              <button
                @click="newModal = false"
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <form action="" @submit.prevent="newTheme">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Label</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="label"
                    v-model="editTheme.label"
                    required
                  />
                </div>

                <div class="mb-3" v-if="editTheme.order == ''">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Order</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    name="order"
                    v-model="editTheme.order"
                    required
                  />
                </div>
                <div v-else>
                  <input
                    type="hidden"
                    name="order"
                    v-model="editTheme.order"
                  />
                </div>

              </div>
              <div class="modal-footer">
                <button
                  @click="newModal = false"
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">
                  Save changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>
    <div class="modal-backdrop fade d-none" ref="backdrop"></div>
  </Layout>
</template>


<script>
import axios from "axios";
import Layout from "../../../components/Layout.vue";
import draggable from "vuedraggable";
export default {
  components: {
    Layout,
    draggable,
  },
  data() {
    return {
      newModal: false,
      themeModalTitle: "",
      themes: [],
      editTheme: {
        label: "",
        order: "",
        id: "",
      },
      ordres: [],
      enabled: false,
      saveBtn: false,
    };
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
    getThemes: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/themes", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.themes = result.data;
          })
          .then((error) => {
            error;
          });
      }
    },
    addTheme() {
      this.editTheme.label = "";
      this.editTheme.order = "";
      this.editTheme.id = "";
      this.themeModalTitle = "Ajouter nouveau theme";
      this.newModal = true;
    },
    newTheme: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formData = new FormData(form);
        if (this.editTheme.id != "") {
          formData.append("theme_id", this.editTheme.id);
          await axios
            .post("/api/new/themes", formData, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
            .then((result) => {
              this.newModal = false;
              this.themes.map((theme) => {
                if (theme.id == result.data.id) {
                  theme.label = result.data.label;
                  theme.ordre = result.data.ordre;
                  theme.alias = result.data.alias;
                }
              });
            })
            .catch((error) => {
              console.log(error);
            });
        } else {
          await axios
            .post("/api/new/themes", formData, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
            .then((result) => {
              this.themes.push(result.data);
              this.newModal = false;
            })
            .catch((error) => {
              console.log(error);
            });
        }
      }
    },
    getTheme(id) {
      this.newModal = true;
      this.themes.forEach((element) => {
        if (element.id == id) {
          this.editTheme.label = element.label;
          this.editTheme.order = element.ordre;
          this.editTheme.id = element.id;
          this.themeModalTitle = `editer le theme ${this.editTheme.label}`;
        }
      });
    },
    deleteTheme: async function (id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer ce theme",
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
                "/api/delete/theme/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.themes = this.themes.filter((theme) => {
                  return theme.id != id;
                });
              })
              .catch((error) => {});
          }
        });
      }
    },
    enabledTheDragabble() {
      this.enabled = true;
      this.saveBtn = true;
    },
    saveTheChanges() {
      this.enabled = false;
      this.saveBtn = false;
      let ids = [];

      this.themes.forEach((theme, index) => {
        ids.push(theme.id);
        if (theme.id) {
          theme.ordre = index + 1;
        }
      });

      let converted = JSON.stringify(ids);
      let formData = new FormData();
      formData.append("ids", converted);

      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .post("/api/update/ordres", formData, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.themes.forEach((theme, index) => {
              if (theme.id) {
                theme.ordre = index + 1;
              }
            });
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
  mounted() {
    this.getThemes();
  },
};
</script>


<style scoped>
</style>