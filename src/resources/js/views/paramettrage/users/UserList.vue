<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <router-link class="btn btn-primary" to="/paramettrage/users/add">
          <i class="fe fe-plus"></i>
          Family member
        </router-link>
      </div>
    </div>
    <div class="row">
      <div v-for="user in this.users" :key="user.id" class="col-sm-12 col-md-3">
        <router-link :to="'/paramettrage/users/edit/' + user.id">
          <div class="card user-card overflow-hidden">
            <div class="card-body text-center">
              <!-- <img :src="user.img" class="rounded profile mb-2" alt="img" /> -->
              <span
                class="avatar avatar-xxl brround cover-image"
                :data-bs-image-src="user.img"
                :style="`
                  background: url('${user.img}') center center;
                `"
              >
                <span :class="`avatar-status bg-${user.enabled?'success':'danger'}`"></span>
              </span>
              <h5 class="card-title my-2 text-dark">
                {{ user.prenom }} {{ user.nom }}
              </h5>
              <h6 class="mb-2 text-dark">{{ user.fonction }}</h6>
              <span class="tag tag-blue mb-0">{{ user.role }}</span>
              <div class="actions">
                <button
                  class="btn text-primary p-0"
                  @click="openModalResetPass(user.id, $event)"
                  style="font-size: 28px"
                >
                  <i class="mdi mdi-lock-reset"></i>
                </button>
              </div>
            </div>
          </div>
        </router-link>
      </div>
    </div>
    <!-- <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <table class="table text-nowrap text-md-nowrap mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Nom Complet</th>
                      <th>Role</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="this.users.length > 0">
                    <tr v-for="user in this.users" :key="user.id">
                      <td>{{ user.id }}</td>
                      <td>
                        <img
                          :src="user.img"
                          alt="profile-user"
                          class="avatar profile-user brround cover-image"
                        />
                      </td>
                      <td>{{ user.prenom }} {{ user.nom }}</td>
                      <td>{{ user.role }}</td>
                      <td>
                        <router-link
                          :to="'/paramettrage/users/edit/' + user.id"
                        >
                          <i class="fe fe-edit-2"></i>
                        </router-link>
                        <a href="" class="text-warning">
                          <i class="fe fe-x-circle"></i>
                        </a>
                        <a href="" class="text-danger">
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
    </div> -->
    <transition
      @enter="startTransitionModal($refs.modalResetPassword)"
      @after-enter="endTransitionModal($refs.modalResetPassword)"
      @before-leave="endTransitionModal($refs.modalResetPassword)"
      @after-leave="startTransitionModal($refs.modalResetPassword)"
    >
      <div
        class="modal fade"
        v-if="showModalResetPassword"
        ref="modalResetPassword"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="resetPassword" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Password Reset
                </h5>
                <button class="close btn" @click="closeModalResetPass">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-12 col-md-6">
                      <label for="">New Password</label>
                      <input
                        type="password"
                        name="new_password"
                        class="form-control"
                        required
                      />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="">Confirm New Password</label>
                      <input
                        type="password"
                        name="confirm_new_password"
                        class="form-control"
                        required
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-secondary" @click="closeModalResetPass">
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
export default {
  data() {
    return {
      users: [],
      showModalResetPassword: false,
      userReset: null,
    };
  },
  methods: {
    getUserList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getUsersList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.users = result.data;
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    resetPassword: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formData = new FormData(e.target);

        if (
          formData.get("new_password") !== formData.get("confirm_new_password")
        ) {
          return this.$swal({
            title: "Confirmation Invalide",
            icon: "warning",
            showConfirmButton: false,
            showDenyButton: false,
            showCancelButton: true,
            cancelButtonText: `understood`,
          }).then((result) => {
            return false;
          });
        }
        let userReset = this.userReset;
        await axios
          .post("/api/changeUserPassword/" + userReset, formData, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.$swal({
              title: "Password Changed !",
              icon: "success",
              showConfirmButton: true,
              showDenyButton: false,
              showCancelButton: false,
              confirmButtonText: `ok`,
            }).then((result) => {
              return false;
            });
            this.closeModalResetPass();
          })
          .catch(function (err) {});
      }
    },
    openModalResetPass(id, event) {
      event.preventDefault();
      event.stopPropagation();
      this.userReset = id;
      this.showModalResetPassword = true;
    },
    closeModalResetPass(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      this.showModalResetPassword = false;
      this.userReset = null;
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
  },
  mounted() {
    this.getUserList();
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
.modal {
  z-index: 1059;
}
</style>