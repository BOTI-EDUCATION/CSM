<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Teams ({{ teams.length }})</h1>

      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/"> Home </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <a class="btn btn-primary" @click="showModal">
          <i class="fe fe-plus"></i>
          Add a new team member
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
                        <th>Fullname</th>
                        <th>Fonction</th>
                        <th>actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="team in teams" :key="team.id">
                        <td class="d-flex align-items-center">
                          <img
                            class="avatar profile-user brround cover-image"
                            :src="'https://boti.education/csm/src/public/contents/'+team.avatar"
                            style="width:2.6rem;height:2.6rem;object-fit:cover;margin-right: 5px;"
                            :title="team.fullname"
                            alt="profile member"
                          />
                          <span>{{ team.fullname }}</span>
                        </td>
                        <td>{{ team.fonction }}</td>
                        <td>
                          <button
                            class="btn btn-warning"
                            @click="showItem(team.id)"
                          >
                            Edit
                          </button>
                          <button
                            class="btn btn-danger"
                            @click="deleteTeam(team.id)"
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
      @enter="startTransitionModal($refs.team)"
      @after-enter="endTransitionModal($refs.team)"
      @before-leave="endTransitionModal($refs.team)"
      @after-leave="startTransitionModal($refs.team)"
    >
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        ref="team"
        v-if="teamModal"
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
                @click="teamModal = false"
              ></button>
            </div>
            <form @submit.prevent="newTeamMember">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Fullname</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="fullname"
                    v-model="teamItem.fullname"
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Fonction</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="fonction"
                    v-model="teamItem.fonction"
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Image</label
                  >
                  <input
                    type="file"
                    class="form-control"
                    @change="selectImage"
                    required
                  />
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                  @click="teamModal = false"
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
      teams: [],
      teamItem: [],
      teamModal: false,
      image: {},
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
    selectImage(e) {
      this.image = e.target.files[0];
    },
    getTeams() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/teams", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.teams = result.data;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    showModal() {
      this.teamModal = true;
      this.teamItem = [];
      this.title = "Add a new team-member ";
    },
    newTeamMember(e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formData = new FormData(e.target);
        formData.append("image", this.image);
        if (this.teamItem.id) {
          formData.append("id", this.teamItem.id);
          axios
            .post("/api/save/team", formData, {
              headers: {
                Authorization: "Bearer" + token,
              },
            })
            .then((result) => {
              this.teams.forEach((team) => {
                if (team.id == result.data.id) {
                  team.fullname = result.data.fullname;
                  team.fonction = result.data.fonction;
                }
              });
              this.teamModal = false;
            });
        } else {
          axios
            .post("/api/save/team", formData, {
              headers: {
                Authorization: "Bearer" + token,
              },
            })
            .then((result) => {
              this.teams.push(result.data);
              this.teamModal = false;
            })
            .catch((error) => {
              error;
            });
        }
      }
    },
    showItem(id) {
      const token = localStorage.getItem("auth-token");
      this.title = `Edit informations`;

      if (token) {
        axios
          .get("/api/team/item/" + id, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.teamItem = result.data;
            this.teamModal = true;
          })
          .catch((error) => {
            error;
          });
      }
    },
    deleteTeam(id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "are you sure to delete this Member ?",
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
                "/api/team/delete/" + id,
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
                  title: "Member deleted Successfully",
                  showConfirmButton: false,
                  timer: 1500,
                });
                this.teams = this.teams.filter((member) => {
                  return member.id != id;
                });
              });
          }
        });
      }
    },
  },
  mounted() {
    this.getTeams();
  },
};
</script>
  
  <style>
</style>