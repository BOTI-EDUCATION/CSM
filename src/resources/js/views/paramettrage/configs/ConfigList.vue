<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <button
          class="btn btn-primary"
          @click="showModalConfig = !showModalConfig"
        >
          <i class="fe fe-plus"></i>
          Ajouter un nouveau config
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row mb-4" v-for="config in configs" :key="config.id">
              <label class="col-md-3 form-label"
                >{{ config.label }} ({{ config.alias }})</label
              >
              <div class="col-md-9">
                <input
                  type="text"
                  v-model="config.value"
                  class="form-control"
                  placeholder="value"
                  @change="updateConfig($event, config.id)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <transition
      @enter="startTransitionModal($refs.modalConfig)"
      @after-enter="endTransitionModal($refs.modalConfig)"
      @before-leave="endTransitionModal($refs.modalConfig)"
      @after-leave="startTransitionModal($refs.modalConfig)"
    >
      <div class="modal fade" v-if="showModalConfig" ref="modalConfig">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Ajouter un config
              </h5>
              <button
                class="close btn"
                @click="showModalConfig = !showModalConfig"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <form @submit="addConfig">
                <div class="form-group">
                  <label class="form-label" required> Label </label>
                  <input type="text" class="form-control" placeholder="Label" />
                </div>
                <div class="form-group">
                  <label class="form-label" required> Type </label>
                  <select name="" id="" class="form-control">
                    <option selected disabled value=""></option>
                    <option v-for="type in types" :key="type.alias" value="">
                      {{ type.label }}
                    </option>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                class="btn btn-secondary"
                @click="showModalConfig = !showModalConfig"
              >
                Close
              </button>
              <button class="btn btn-primary" type="button">
                Save changes
              </button>
            </div>
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
      configs: [],
      showModalConfig: false,
      types: [
        {
          alias: "varchar",
          label: "Phrase",
        },
        {
          alias: "text",
          label: "Texte",
        },
        {
          alias: "html",
          label: "Bloc HTML",
        },
      ],
    };
  },
  methods: {
    getConfigsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getConfigsList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.configs = result.data;
            console.log(this.configs.length);
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    updateConfig: async function (event, id) {
      console.log(event.target.value + " : " + id);
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .post(
            "/api/updateConfig",
            {
              id: id,
              value: event.target.value,
            },
            {
              headers: {
                Authorization: "Bearer " + token,
              },
            }
          )
          .then(async (result) => {})
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
    addConfig: async function (e) {
        e.preventDefault();
        
      const token = localStorage.getItem("auth-token");
      if (token) {

        let formdata = new FormData(e.target); 

        await axios
          .post("/api/getConfigsList", formdata , {
            headers: {
              Authorization: "Bearer " + token,
            }
          })
          .then(async (result) => {
            console.log(this.configs.length);
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
  },
  mounted() {
    this.getConfigsList();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
</style>