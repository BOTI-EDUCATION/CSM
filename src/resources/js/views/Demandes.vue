<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Requests</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/demandes"> Requests  </router-link>
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Date</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="demande in demandes" :key="demande.id" 
                    :class="demande.handling.handled && demande.handling.comment != '' && demande.handling.comment != null ? ['bg-success','text-white']  : '' "
                  >
                  
                  <td>{{ demande.nom }}</td>
                  <td>{{ demande.email }}</td>
                  <td>{{ demande.tel }}</td>
                  <td>{{ demande.date }}</td>
                  <td class="text-center">
                    <a
                      href="javascript:void(0)"
                      @click="triggerDemandeModal(demande)"
                    >
                      <i 
                      class="fe fe-eye"
                      :class="demande.handling.handled && demande.handling.comment != '' && demande.handling.comment != null ? 'text-white' : '' "
                      ></i>
                    </a>
                  </td>  
                
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <transition
      @enter="startTransitionModal($refs.demandeModal)"
      @after-enter="endTransitionModal($refs.demandeModal)"
      @before-leave="endTransitionModal($refs.demandeModal)"
      @after-leave="startTransitionModal($refs.demandeModal)"
    >
      <div class="modal fade" v-if="showDemandeModal" ref="demandeModal">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ showedDemande.nom }} ({{ showedDemande.ecole }}) -
                {{ showedDemande.tel }} - {{ showedDemande.email }}
              </h5>
              <button
                class="close btn"
                @click="showDemandeModal = !showDemandeModal"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <div class="row">
                  <div class="col-12 col-md-3">
                    <h6><b> Type : </b></h6>
                  </div>
                  <div class="col-12 col-md-9">
                    <p>
                      {{ showedDemande.type }}
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-3">
                    <h6><b> niveaux : </b></h6>
                  </div>
                  <div class="col-12 col-md-9">
                    <p>
                      {{ showedDemande.niveaux }}
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-3">
                    <h6><b> nombre d'élèves : </b></h6>
                  </div>
                  <div class="col-12 col-md-9">
                    <p>
                      {{ showedDemande.nombre }}
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-3">
                    <h6><b> Date : </b></h6>
                  </div>
                  <div class="col-12 col-md-9">
                    <p>
                      {{ showedDemande.date }}
                    </p>
                  </div>
                </div>
                <div class="row" v-if="!showedDemande.handling.handled || !showedDemande.handling.saved">
                  <div class="col-12">
                    <label class="custom-control custom-checkbox-md">
                      <input
                        type="checkbox"
                        class="custom-control-input"
                        v-model="showedDemande.handling.handled"
                      />
                      <span class="custom-control-label"
                        >Handled by sales team</span
                      >
                    </label>
                    <input
                      v-if="showedDemande.handling.handled"
                      type="text"
                      v-model="showedDemande.handling.comment"
                      class="form-control"
                    />
                  </div>
                </div>
                <div class="row" v-else>
                  <div class="col-12 col-md-3">
                    <h6><b> Handled : </b></h6>
                  </div>
                  <div class="col-12 col-md-9">
                    <p>
                      {{ showedDemande.handling.handled?'Yes':'No' }}
                    </p>
                  </div>
                  <div class="col-12 col-md-3">
                    <h6><b> Handling Comment : </b></h6>
                  </div>
                  <div class="col-12 col-md-9">
                    <p>
                      {{ showedDemande.handling.comment?showedDemande.handling.comment:'-' }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <template v-if="showedDemande.handling.handled && showedDemande.handling.saved" >
                <button
                  class="btn btn-secondary"
                  @click="showDemandeModal = !showDemandeModal"
                >
                  Ok
                </button>
              </template>
              <template v-else>
                <button
                  class="btn btn-secondary"
                  @click="handleQuotation"
                >
                  Enregistrer 
                </button>
              </template>
              <button class="btn btn-danger" @click="deleteDemande($event, showedDemande.id)" >
                Supprimer
              </button>
    
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div class="modal-backdrop fade d-none" ref="backdrop"></div>
  </Layout>
</template>

<script>
import axios from 'axios';
import Layout from "../components/Layout.vue";
export default {
  components: {
    Layout,
  },
  data() {
    return {
      demandes: [],
      showDemandeModal: false,
      showedDemande: {},
    };
  },
  methods: {
    getDemandes: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getDemandes", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.demandes = result.data;
          })
          .catch((err) => {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    triggerDemandeModal: function (demande) {
      this.showedDemande = demande;
      this.showDemandeModal = true;
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },

    handleQuotation: async function() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .post("/api/handleQuotation", {
            demande: this.showedDemande.id,
            handles: this.showedDemande.handling.handled,
            comment: this.showedDemande.handling.comment
          } ,{
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.showDemandeModal = false;
          })
          .catch((err) => {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    deleteDemande:async function(e, id){
      const token = localStorage.getItem('auth-token');
      if(token){
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer cette demande",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `supprimer`,
          cancelButtonText: `Annuler`,
        }).then( async (result) => {
           if(result.isDenied){
            
            await axios.post(
              "api/delete/demande/" + id,
            {},
            {
              headers: {
                  Authorization: "Bearer " + token,
                }
            }
            ).then((result) => {
              this.demandes = this.demandes.filter( (dem) => {
                return dem.id != id ;
              });
              this.showDemandeModal = false;
            }).catch(function (err) {
                console.log(token);
              });
           }
        })
      }
    }
  },
  mounted() {
    this.getDemandes();
  },
  
};
</script>

<style>
  .swal2-container {
    z-index: 10000 !important;
  }
</style>