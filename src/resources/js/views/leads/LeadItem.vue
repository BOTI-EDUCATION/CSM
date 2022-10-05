<template>
  <div class="row" id="user-profile">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="wideget-user mb-2">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="row">
                  <div class="panel profile-cover">
                    <div class="profile-cover__action bg-img"
                      :style="'background-image: url(\''+lead.banner+'\')'"
                     ></div>
                    <div class="profile-cover__img">
                      <div class="profile-img-1">
                        <img :src="lead.logo" alt="img" /> <br />
                        <span class="tag tag-rounded tag-primary text-nowrap"
                          >ami</span
                        >
                      </div>
                      <div class="profile-img-content text-dark text-start">
                        <div class="text-dark">
                          <h3 class="h3 mb-2">
                            {{ lead.name }}
                            <router-link
                              :to="'/leads/edit/' + lead.id"
                              class="btn btn-link"
                              ><i class="fa fa-edit"></i
                            ></router-link>
                          </h3>
                          <h5 class="text-muted">{{ lead.presentation }}</h5>
                        </div>
                      </div>
                    </div>
                    <div v-if="false" class="btn-profile">
                      <button
                        class="btn btn-primary mt-1 mb-1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Tooltip on bottom"
                      >
                        <span class="h6"> <b> 23 </b> </span> <br />
                        <i class="fa fa-rss"></i>
                      </button>
                      <button
                        class="btn btn-secondary mt-1 mb-1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Tooltip on bottom"
                      >
                        <span class="h6"> <b> 23 </b> </span> <br />
                        <i class="fa fa-rss"></i>
                      </button>
                      <button
                        class="btn btn-success mt-1 mb-1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Tooltip on bottom"
                      >
                        <span class="h6"> <b> 23 </b> </span> <br />
                        <i class="fa fa-rss"></i>
                      </button>
                      <button
                        class="btn btn-danger mt-1 mb-1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="Tooltip on bottom"
                      >
                        <span class="h6"> <b> 23 </b> </span> <br />
                        <i class="fa fa-rss"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="">
                    <div v-if="lead.links" class="social social-profile-buttons mt-5 float-end">
                      <div class="mt-3">
                        <a class="social-icon text-primary" v-if="lead.links.facebook" :href="lead.links.facebook"
                          ><i class="fab fa-facebook"></i
                        ></a>
                        <a class="social-icon text-primary" v-if="lead.links.instagram" :href="lead.links.instagram"
                          ><i class="fab fa-instagram"></i
                        ></a>
                        <a class="social-icon text-primary" v-if="lead.links.youtube" :href="lead.links.youtube"
                          ><i class="fab fa-youtube"></i
                        ></a>
                        <a class="social-icon text-primary" v-if="lead.links.website" :href="lead.links.website"
                          ><i class="fa fa-globe"></i
                        ></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header">
              <div class="card-title">Effectif et Eleves</div>
            </div>
            <div class="card-body d-grid">
              <!-- <h6>Effectif</h6> -->
              <div class="main-profile-contact-list d-flex">
                <div class="">
                  <div class="media d-flex mb-4">
                    <div class="media-icon bg-secondary bradius me-3 mt-1">
                      <i class="fe fe-edit fs-20 text-white"></i>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Maternelle</span>
                      <div class="fw-semibold fs-25">{{lead.effectif_maternelle?lead.effectif_maternelle:0}}</div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 mt-md-0">
                  <div class="media mb-4">
                    <div
                      class="media-icon bg-danger bradius text-white me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-users fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Primaire</span>
                      <div class="fw-semibold fs-25">{{lead.effectif_primaire?lead.effectif_primaire:0}}</div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 mt-md-0">
                  <div class="media">
                    <div
                      class="media-icon bg-primary text-white bradius me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-cast fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">College</span>
                      <div class="fw-semibold fs-25">{{lead.effectif_college?lead.effectif_college:0}}</div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 mt-md-0">
                  <div class="media">
                    <div
                      class="media-icon bg-info text-white bradius me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-cast fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Lycée</span>
                      <div class="fw-semibold fs-25">{{lead.effectif_lycee?lead.effectif_lycee:0}}</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <h6>Eleves</h6>
              <div class="main-profile-contact-list d-flex">
                <div class="">
                  <div class="media d-flex mb-4">
                    <div class="media-icon bg-secondary bradius me-3 mt-1">
                      <i class="fe fe-edit fs-20 text-white"></i>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Posts</span>
                      <div class="fw-semibold fs-25">328</div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 mt-md-0">
                  <div class="media mb-4">
                    <div
                      class="media-icon bg-danger bradius text-white me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-users fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Followers</span>
                      <div class="fw-semibold fs-25">937k</div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 mt-md-0">
                  <div class="media">
                    <div
                      class="media-icon bg-primary text-white bradius me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-cast fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Following</span>
                      <div class="fw-semibold fs-25">2,876</div>
                    </div>
                  </div>
                </div>
                <div class="mt-5 mt-md-0">
                  <div class="media">
                    <div
                      class="media-icon bg-primary text-white bradius me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-cast fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Following</span>
                      <div class="fw-semibold fs-25">2,876</div>
                    </div>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header">
              <div class="card-title">Contacts</div>
              <div class="card-options">
               <button
               @click="showModalAddContact = !showModalAddContact"
                type="button"
                class="btn btn-icon p-1 btn-success"
              >
                <i class="fe fe-plus"></i>
              </button>
            </div>
            </div>
            <div class="card-body">
              <div v-if="contacts.length > 0" class="">
                <div v-for="contact in contacts" :key="contact.id" class="media overflow-visible align-items-center">
                  <img
                    class="avatar brround avatar-md me-3"
                    :src="contact.picture"
                    alt="avatar-img"
                  />
                  <div class="media-body valign-middle mt-2">
                    <a class="fw-semibold text-dark">{{contact.nom}} 
                      <a href="javascript:void(0)" @click="showEditContact(contact.id)" class="btn btn-link" ><i class="fa fa-edit"></i></a> 
                    </a>
                    <p class="text-muted mb-0">{{contact.fonction}}</p>
                    <p class="text-muted fs-10 mb-0">{{contact.email}}</p>
                  </div>
                  <div
                    class="
                      s
                      valign-middle
                      text-end
                      overflow-visible
                      mt-2
                      d-grid
                    "
                  >
                    <a target="_blank" 
                    :href="'https://api.whatsapp.com/send?phone='+contact.phone"
                      class="btn btn-sm btn-link text-success"
                    >
                      <i class="fab fa-xl fa-whatsapp"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="false" class="row">
        <div class="offset-md-9 col-md-3 mb-3">
          <div class="input-group">
            <button class="btn btn-primary">
              <i class="fa fa-arrow-left tx-16 lh-0 op-6"></i>
            </button>
            <Datepicker
              ref="datePickerStatistiques"
              :language="localfr"
              class="form-control"
              :value="dateStatistique"
            />
            <button class="btn btn-primary">
              <i class="fa fa-arrow-right tx-16 lh-0 op-6"></i>
            </button>
          </div>
        </div>
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">

                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-newspaper fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Nouveautés Partagées</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-images fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Albums Partagés</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-book fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Devoirs Partagés</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-user-times fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Absences Remontées</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-exclamation-circle fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Actions Disciplinaires</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-file-alt fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Notes Patagées</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-comment-alt fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Messages Recus</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-envelope-open-text fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Demandes Recus</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fab fa-readme fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Cahier de Textes Enregistré</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-star fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Compétences Evaluées</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-money-bill-wave fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Encaissements Realisés</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-bell fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Notifications Envoyées</h5>
                    </div>
                  </div>
                </div>
                <!-- <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-print fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Attestations Imprimées</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-puzzle-piece fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Jeux Educatifs Partagés</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-readme fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Cahier de Textes Enregistré</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-readme fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Cahier de Textes Enregistré</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-readme fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Cahier de Textes Enregistré</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100 ">
                      <span class="text-primary">
                        <i class="fa fa-readme fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">Cahier de Textes Enregistré</h5>
                    </div>
                  </div>
                </div> -->

              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="false" class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <div class="card-title">Notifications envoyées</div>
            </div>
            <div class="card-body">
              <!-- <line-chart ref="chartNotifications" :options="chartOption" :chart-data="dataNotification"></line-chart> -->
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <div class="card-title">Connexions d'utilisateurs</div>
            </div>
            <div class="card-body">
              <!-- <line-chart ref="chartLogin" :options="chartOption" :chart-data="dataLogin"></line-chart> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- COL-END -->
<transition
      @enter="startTransitionModal($refs.modalAddContact)"
      @after-enter="endTransitionModal($refs.modalAddContact)"
      @before-leave="endTransitionModal($refs.modalAddContact);clearForm();"
      @after-leave="startTransitionModal($refs.modalAddContact)"
    >
      <div class="modal fade" v-if="showModalAddContact" ref="modalAddContact">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="addContact" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Ajouter un contact
              </h5>
              <button
                class="close btn"
                @click="showModalAddContact = !showModalAddContact"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Nom </label>
                        <input type="text"  name="name" v-model="contact.name" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Prenom </label>
                        <input type="text" required name="last_name" v-model="contact.last_name" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Email </label>
                        <input type="text"  name="email" v-model="contact.email" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Telephone </label>
                        <input type="text" name="phone" v-model="contact.phone" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Fonction </label>
                        <input type="text" name="function" v-model="contact.function" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Image </label>
                        <input type="file" name="picture" class="form-control">
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="contact" :value="contact.id">
                </div>
            </div>
            <div class="modal-footer">
              <button
              type="button"
                class="btn btn-secondary"
                @click="showModalAddContact = !showModalAddContact"
              >
                Close
              </button>
              <button class="btn btn-primary">
                Save changes
              </button>
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
import Datepicker from "vuejs-datepicker";
import { fr } from "vuejs-datepicker/dist/locale";
// import LineChart from "../../../components/charts/LineChart.vue"


export default {
  components: {
    Datepicker,
    // "line-chart" : LineChart
  },
  data() {
    return {
      lead: {},
      contacts: [],
      localfr: fr,
      dateStatistique: new Date(),
      dataNotification : {},
      dataLogin : {},
      chartOption: {
        tension: .3
      },
      contact: {
        id: null,
        name: '',
        last_name: '',
        email: '',
        phone: '',
        function: ''
      },
      showModalAddContact: false
    };
  },
  methods: {
    getLead: async function (leadId) {
      const token = localStorage.getItem("auth-token");
      if (leadId && token) {
        await axios
          .get("/api/getLeadFormInfo/" + leadId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.lead = result.data;
          })
          .catch(function (err) {
            this.$router.push("/leads");
          });
      }
    },
    getLeadContacts: async function(leadId){
      const token = localStorage.getItem("auth-token");
      if (leadId && token) {
        await axios
          .get("/api/getLeadContacts/" + leadId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.contacts = result.data;
          })
          .catch(function (err) {
          });
      }
    },
    getRandomInt () {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5
    },
    initNotificationChart: function(){
      let gradient = this.$refs.chartNotifications.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient.addColorStop(0, '#2cd9c540')
      gradient.addColorStop(0.3, '#2cd9c520');
      gradient.addColorStop(0.6, '#2cd9c500');

      let gradient1 = this.$refs.chartNotifications.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient1.addColorStop(0, '#6c5ffc40')
      gradient1.addColorStop(0.3, '#6c5ffc20');
      gradient1.addColorStop(0.6, '#6c5ffc00');

      let gradient2 = this.$refs.chartNotifications.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient2.addColorStop(0, '#d62d9940')
      gradient2.addColorStop(0.5, '#d62d9920');
      gradient2.addColorStop(1, '#d62d9900');

      let gradient3 = this.$refs.chartNotifications.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient3.addColorStop(0, '#ffc75e40')
      gradient3.addColorStop(0.3, '#ffc75e20');
      gradient3.addColorStop(0.6, '#ffc75e00');

      this.dataNotification = {
        labels: ['01/03', '02/03' , '03/03' , '04/03', '05/03' , '06/03', '07/03' , '08/03' , '09/03' , '10/03'],
        datasets: [
          {
            label: 'Élèves',
            backgroundColor: gradient,
            borderColor: '#2cd9c5',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
          {
            label: 'Parents',
            backgroundColor: gradient1,
            borderColor: '#6c5ffc',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
          {
            label: 'Enseignants',
            backgroundColor: gradient2,
            borderColor: '#d62d99',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
          {
            label: 'Collaborateurs',
            backgroundColor: gradient3,
            borderColor: '#ffc75e',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
        ]
      }
    },
    initLoginChart: function(){
       let gradient = this.$refs.chartLogin.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient.addColorStop(0, '#2cd9c540')
      gradient.addColorStop(0.3, '#2cd9c520');
      gradient.addColorStop(0.6, '#2cd9c500');

      let gradient1 = this.$refs.chartLogin.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient1.addColorStop(0, '#6c5ffc40')
      gradient1.addColorStop(0.3, '#6c5ffc20');
      gradient1.addColorStop(0.6, '#6c5ffc00');

      let gradient2 = this.$refs.chartLogin.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient2.addColorStop(0, '#d62d9940')
      gradient2.addColorStop(0.5, '#d62d9920');
      gradient2.addColorStop(1, '#d62d9900');

      let gradient3 = this.$refs.chartLogin.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
      gradient3.addColorStop(0, '#ffc75e40')
      gradient3.addColorStop(0.3, '#ffc75e20');
      gradient3.addColorStop(0.6, '#ffc75e00');

      this.dataLogin = {
        labels: ['01/03', '02/03' , '03/03' , '04/03', '05/03' , '06/03', '07/03' , '08/03' , '09/03' , '10/03'],
        datasets: [
          {
            label: 'Élèves',
            backgroundColor: gradient,
            borderColor: '#2cd9c5',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
          {
            label: 'Parents',
            backgroundColor: gradient1,
            borderColor: '#6c5ffc',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
          {
            label: 'Enseignants',
            backgroundColor: gradient2,
            borderColor: '#d62d99',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
          {
            label: 'Collaborateurs',
            backgroundColor: gradient3,
            borderColor: '#ffc75e',
            fill: true,
            data: [
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
              this.getRandomInt(),
            ]
          },
        ]
      }
    },
    initCharts : function(){
     this.initNotificationChart();
     this.initLoginChart();
    },
    addContact: async function(e){
      e.preventDefault();
      
      const token = localStorage.getItem("auth-token");
      if (this.lead.id && token) {
        let form = e.target;
        let formdata = new FormData(form);
        await axios
          .post("/api/saveLeadContact/"+this.lead.id , formdata ,{
            headers: {
              Authorization: "Bearer " + token,
            }
          })
          .then(async (result) => {
            this.contacts = result.data;
            form.reset();
            this.showModalAddContact = !this.showModalAddContact;
          })
          .catch(function (err) {
          });
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
    showAddTicket: function(){
      this.$parent.$refs.btnAddTicket.click();
    },
    showAddIntervention: function(){
      this.$parent.$refs.btnAddIntervention.click();
    },
    showEditContact: async function(contactId){
      const token = localStorage.getItem("auth-token");
      if (contactId && token) {
        await axios
          .get("/api/getContactFormInfo/" + contactId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.contact = result.data;
            this.showModalAddContact = !this.showModalAddContact;
          })
          .catch(function (err) {
          });
      }
    },
    clearForm: function(e){
       this.contact= {
        id: null,
        name: '',
        last_name: '',
        email: '',
        phone: '',
        function: ''
      };
    }
  },
  mounted() {

    this.getLead(this.$route.params.id);
    this.getLeadContacts(this.$route.params.id);
    // this.initCharts();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
.profile-cover__img .profile-img-1 {
  position: relative;
}
.profile-cover__img .profile-img-1 > img {
  margin-left: 0px;
}
.profile-cover__img .profile-img-1 > span {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  width: auto;
  margin: auto;
  display: block;
  text-align: center;
}
.profile-cover__img .profile-img-content {
  margin-left: 20px;
}
.main-profile-contact-list.d-flex > div {
  flex: 1;
}
.princing-item{
  height: 100%;
  border-radius: 10px;
}
</style>