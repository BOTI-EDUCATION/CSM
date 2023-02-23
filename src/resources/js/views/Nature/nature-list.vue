<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Natures ({{ natures.length }})</h1>

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
        <a class="btn btn-primary" @click="showAddModal">
          <i class="fe fe-plus"></i>
          Add a new Nature
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
                        <th>Alias</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr v-for="nature in natures" :key="nature.id">
                            <td>{{nature.name}}</td>
                            <td>{{nature.alias}}</td>
                          <td>
                          <button
                            class="btn btn-warning me-1 text-dark"
                            @click="showNature(nature.id)" 
                          >
                            Edit
                          </button>
                          <a
                            class="btn btn-danger text-white"
                           @click.prevent="deleteNature(nature.id)"
                          >
                            Delete
                          </a>
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
      @enter="startTransitionModal($refs.nature)"
      @after-enter="endTransitionModal($refs.nature)"
      @before-leave="endTransitionModal($refs.nature)"
      @after-leave="startTransitionModal($refs.nature)"
    >
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        v-if="newModal"
        ref="nature"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ title }}
              </h5>
              <button
                @click="newModal = false"
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <form @submit.prevent="newNature">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Label</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="label"
                    v-model="natureItem.name"
                    required
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
import Layout from "../../components/Layout.vue";
export default {
  components: {
    Layout,
  },
  data() {
    return {
      natures: [],
      natureItem:[],
      title: null,
      newModal: false,
      newNatureItem:[],
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
    getList() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/natures", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.natures = result.data;
          })
          .catch((error) => {
            error;
          });
      }
    },
    showAddModal(){
      this.newModal = true;
      this.natureItem.length > 0 ? this.natureItem = [] : this.natureItem = [] ;
      this.title = "Add new Nature";
    },
    newNature(e) {
      const token = localStorage.getItem("auth-token");
      if(token){
        let form = e.target;
        let formData = new FormData(form);
        if(!this.natureItem.id){
          axios.post("/api/new/nature", formData,  {
          headers:{
            Authorization: "Bearer" + token,
          },
        }).then( result => {
          this.natures.push(result.data);
          this.newModal = false;
          this.newNatureItem.push(result.data);
        }).catch( error => {
          error;
        })
        }else{
          formData.append('id',this.natureItem.id);
          axios.post("/api/new/nature", formData,  {
          headers:{
            Authorization: "Bearer" + token,
          },
        }).then( result => {
          this.natures.forEach(nature => {
            if(nature.id == result.data.id){
              nature.name = result.data.name;
              nature.alias = result.data.alias;
            }
          });
          this.newModal = false;
        }).catch( error => {
          error;
        })
        }
       
      }
    },
    showNature(id){
      const token = localStorage.getItem('auth-token');
      this.title = 'Edit Nature';
      if(token){
        axios.get('/api/getNature/' + id,{
          headers:{
            Authorization: "Bearer" + token,
          }
        }).then(result => {
          this.natureItem = result.data;
          this.newModal = true;
        })
      }
    },
    deleteNature(id){
      const token = localStorage.getItem('auth-token');
      if(token){
        this.$swal({
          title:"Are you sure to delete this Nature ?",
          icon:'warning',
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Delete`,
          cancelButtonText: `Cancel`,
        }).then( result => {
          if(result.isDenied){
            axios.post('/api/delete/nature/' + id, {}, {
              headers:{
                Authorization: "Bearer" + token,
              }
            }).then(() => {
              this.natures = this.natures.filter( nature => {
                return nature.id != id;
              } )
            })
          }
        })
      }
    }
  },
  mounted() {
    this.getList();
  },

};
</script>

<style scoped>
</style>