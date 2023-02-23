<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Types ({{ types.length }})</h1>

      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/academy/courses"> Home </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <a class="btn btn-primary" @click="showModal">
          <i class="fe fe-plus"></i>
          Add new type
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table text-nowrap text-md-nowrap mb-0">
                    <thead>
                      <tr>
                        <th>Label</th>
                        <th>alias</th>
                        <th>actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="typy in types" :key="typy.id">
                        <td>{{ typy.name }}</td>
                        <td>{{ typy.type_alias }}</td>
                        <td>
                          <button
                            class="btn btn-warning"
                            @click="showItem(typy.id)"
                          >
                            Edit
                          </button>
                          <button
                            class="btn btn-danger"
                            @click="deleteType(typy.id)"
                          >
                            Delete
                          </button>
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

    <!-- New Modal -->
    <transition
      @enter="startTransitionModal($refs.type)"
      @after-enter="endTransitionModal($refs.type)"
      @before-leave="endTransitionModal($refs.type)"
      @after-leave="startTransitionModal($refs.type)"
    >
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        ref="type"
        v-if="typeModal"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ title }}
              </h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
                @click="typeModal = false"
              ></button>
            </div>
            <form @submit.prevent="newType">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Label</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="label"
                    v-model="typeItem.name"
                    required
                  />
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                  @click="typeModal = false"
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
import Layout from "../../components/Layout.vue";
export default {
  components: {
    Layout,
  },
  data() {
    return {
      title: "",
      types: [],
      typeItem: [],
      typeModal: false,
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
    getTypes() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/types", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result.data);
            this.types = result.data;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    showModal() {
      this.typeModal = true;
      this.typeItem = [];
      this.title = "Add new type";
    },
    newType(e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formData = new FormData(e.target);
        if (this.typeItem.id) {
          formData.append("id", this.typeItem.id);
          axios
            .post("/api/save/type", formData, {
              headers: {
                Authorization: "Bearer" + token,
              },
            })
            .then((result) => {
              this.types.forEach((type) => {
                if (type.id == result.data.id) {
                  type.name = result.data.name;
                  type.type_alias = result.data.type_alias;
                }
              });
              this.typeModal = false;
            });
        } else {
          axios
            .post("/api/save/type", formData, {
              headers: {
                Authorization: "Bearer" + token,
              },
            })
            .then((result) => {
              this.types.push(result.data);
              this.typeModal = false;
            })
            .catch((error) => {
              error;
            });
        }
      }
    },
    showItem(id) {
      const token = localStorage.getItem("auth-token");
      this.title = "Edit Type";
      if (token) {
        axios
          .get("/api/type/item/" + id, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.typeItem = result.data;
            this.typeModal = true;
          })
          .catch((error) => {
            error;
          });
      }
    },
    deleteType(id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "are you sure to delete this Type ?",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Delete`,
          cancelButtonText: `Cancel`,
        }).then((result) => {
          if (result.isDenied) {
            axios
              .post(
                "/api/type/delete/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer" + token,
                  },
                }
              )
              .then(() => {
                this.$swal({
                  icon: "success",
                  title: "Type deleted Successfully",
                  showConfirmButton: false,
                  timer: 1500,
                });
                this.types = this.types.filter((t) => {
                  return t.id != id;
                });
              });
          }
        });
      }
    },
  },
  mounted() {
    this.getTypes();
  },
};
</script>

<style>
</style>