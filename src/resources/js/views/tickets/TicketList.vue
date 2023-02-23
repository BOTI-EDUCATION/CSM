<template>
  <div>
    <div class="row mb-4">
      <div class="col-12 offset-md-7 col-md-3 text-right">
        <input
          type="text"
          @keyup="filterTickets"
          placeholder="Search ..."
          class="form-control"
        />
      </div>
      <div class="col-12 col-md-2 col-lg-2 col-xl-2 text-right">
        <button class="btn btn-block btn-primary" @click="addNew">
          <i class="fe fe-plus"></i>
          Add a new ticket
        </button>
      </div>
    </div>
    <div class="row">
      <div
        v-for="ticket in filteredTickets"
        :key="ticket.id"
        class="col-xl-4 col-lg-6 col-md-6 col-12"
      >
        <div class="card full-row-card">
          <div class="card-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 d-flex justify-content-between mb-5">
                  <div class="">
                    <button
                      type="button"
                      class="btn bg-warning text-dark dropdown-toggle"
                      data-bs-toggle="dropdown"
                      aria-expanded="true"
                    >
                      Actions <span class="caret"></span>
                    </button>
                    <ul
                      class="dropdown-menu"
                      role="menu"
                      data-popper-placement="bottom-start"
                    >
                      <li>
                        <a href="javascript:void(0)" @click="showItem(ticket)"
                          >Editer</a
                        >
                      </li>
                      <li>
                        <a
                          href="javascript:void(0)"
                          @click="deleteTicket(ticket.id)"
                          >Supprimer</a
                        >
                      </li>
                    </ul>
                  </div>
                  <div class="ms-auto">
                    <button
                      type="button"
                      :class="`btn btn-${ticket.infos.state.color} dropdown-toggle `"
                      data-bs-toggle="dropdown"
                      aria-expanded="true"
                    >
                      {{ ticket.infos.state.label }} <span class="caret"></span>
                    </button>
                    <ul
                      class="dropdown-menu"
                      role="menu"
                      data-popper-placement="bottom-start"
                    >
                      <li>
                        <a
                          href="javascript:void(0)"
                          @click="updateState(ticket, 'nouveau')"
                          >Nouveau</a
                        >
                      </li>
                      <li>
                        <a
                          href="javascript:void(0)"
                          @click="updateState(ticket, 'en cours')"
                          >En Cours</a
                        >
                      </li>
                      <li>
                        <a
                          href="javascript:void(0)"
                          @click="updateState(ticket, 'bloque')"
                          >Bloqué</a
                        >
                      </li>
                      <li>
                        <a
                          href="javascript:void(0)"
                          @click="updateState(ticket, 'cloture')"
                          >Clôturé</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex align-items-center">
                    <span
                      class="
                        avatar
                        bg-white
                        avatar-xxl
                        bradius
                        cover-image
                        me-3
                      "
                      :data-bs-image-src="ticket.school.img"
                      :style="
                        ' background: url(\'' +
                        ticket.school.img +
                        '\') center center'
                      "
                    ></span>
                    <div>
                      <h5 class="mb-0">
                        {{ ticket.label }}
                        <!-- <span
                          :class="`badge bg-${ticket.infos.state.color} badge-sm ms-2 me-1`"
                          >{{ ticket.infos.state.label }}</span
                        > -->
                      </h5>
                      <small class="text-muted d-flex align-items-center my-3">
                        <span
                          class="
                            avatar
                            bg-white
                            avatar-sm
                            rounded
                            cover-image
                            mx-2
                          "
                          :data-bs-image-src="ticket.responsable.img"
                          :style="
                            ' background: url(\'' +
                            ticket.responsable.img +
                            '\') center center'
                          "
                        ></span>
                        {{ ticket.responsable.name }} - {{ ticket.date }}
                      </small>
                      <div class="infos d-flex">
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.channel.color} badge-sm me-1 mb-3`"
                        >
                          {{ ticket.infos.channel.label }}</span
                        >
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.nature.color} badge-sm me-1 mb-3`"
                          >{{ ticket.infos.nature.label }}</span
                        >
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.genre.color} badge-sm me-1 mb-3`"
                          >{{ ticket.infos.genre.label }}</span
                        >
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.priority.color} badge-sm me-1 mb-3`"
                          >{{ ticket.infos.priority.label }}</span
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 mt-5">
                  <h5>Details :</h5>
                  <p v-html="ticket.details"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!------------------------------  ADD TICKET ------------------------------>
    <transition
      @enter="startTransitionModal($refs.modalAddTicket)"
      @after-enter="endTransitionModal($refs.modalAddTicket)"
      @before-leave="endTransitionModal($refs.modalAddTicket)"
      @after-leave="startTransitionModal($refs.modalAddTicket)"
    >
      <div class="modal fade" v-if="showModalAddTicket" ref="modalAddTicket">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="addTicket" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Ajouter un ticket
                </h5>
                <button
                  class="close btn"
                  @click="showModalAddTicket = !showModalAddTicket"
                >
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Titre </label>
                        <input
                          type="text"
                          required
                          name="label"
                          class="form-control"
                          v-model="ticketItem.label"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group" v-if="ticketItem">
                        <label class="form-label"> School </label>
                        <input
                          type="hidden"
                          name="school"
                          :value="ticketSchool"
                        />
                        <vue-select
                          class="form-contro"
                          name="accountManager"
                          :reduce="(school) => school.id"
                          label="name"
                          :options="schools"
                          v-model="ticketItem.school"
                          placeholder="School"
                          searchable
                        >
                        </vue-select>
                      </div>
                      <div class="form-group" v-else>
                        <label class="form-label"> School </label>
                        <input
                          type="hidden"
                          name="school"
                          :value="ticketSchool"
                        />
                        <vue-select
                          class="form-contro"
                          name="accountManager"
                          :reduce="(school) => school.id"
                          label="name"
                          :options="schools"
                          v-model="ticketSchool"
                          placeholder="School"
                          searchable
                        >
                        </vue-select>
                      </div>
                    </div>

                    <div class="col-md-4" style="margin-top: 0.4rem">
                      <div class="form-group" v-if="ticketItem">
                        <label for="form-label"> Demandeur </label>
                        <input
                          type="hidden"
                          name="contact_id"
                          :value="demandeur"
                        />
                        <vue-select
                          class="form-contro"
                          name="contact_id"
                          :reduce="(accMan) => accMan.id"
                          label="name"
                          :options="schoolContacts"
                          v-model="demandeur"
                          placeholder="Demandeur"
                          searchable
                        >
                        </vue-select>
                      </div>
                      <div v-else>
                        <label for="form-label"> Demandeur </label>
                        <input
                          type="hidden"
                          name="contact_id"
                          :value="demandeur"
                        />
                        <vue-select
                          class="form-contro"
                          name="contact_id"
                          :reduce="(accMan) => accMan.id"
                          label="name"
                          :options="schoolContacts"
                          v-model="demandeur"
                          placeholder="Demandeur"
                          searchable
                        >
                        </vue-select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group" v-if="ticketItem">
                        <label class="form-label"> Channel </label>
                        <select name="channel" class="form-control">
                          <option :selected="ticketItem.infos.channel.label == 'whatsapp'" value="whatsapp">Whatsapp</option>
                          <option :selected="ticketItem.infos.channel.label == 'phone'"    value="phone">Appel</option>
                          <option :selected="ticketItem.infos.channel.label == 'mail'" value="mail">Email</option>
                        </select>
                      </div>
                      <div class="form-group" v-else>
                        <label class="form-label"> Channel </label>
                        <select name="channel" class="form-control">
                          <option value="whatsapp">Whatsapp</option>
                          <option value="phone">Appel</option>
                          <option value="mail">Email</option>
                        </select>
                      </div>
                      
                    </div>

                    <div class="col-md-6">

                      <div class="form-group" v-if="ticketItem">
                        <label class="form-label"> Nature </label>
                        <select name="nature" class="form-control">
                          <option
                            v-for="nature in natures"
                            :selected="ticketItem.infos.nature.label == nature.name"
                            :value="nature.name"
                            :key="nature.id"
                          >
                            {{ nature.name }}
                          </option>
                        </select>
                      </div>
                      <div class="form-group" v-else>
                        <label class="form-label"> Nature </label>
                        <select name="nature" class="form-control">
                          <option
                            v-for="nature in natures"
                            :value="nature.name"
                            :key="nature.id"
                          >
                            {{ nature.name }}
                          </option>
                        </select>
                      </div>

                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Genre </label>
                        <select name="genre" class="form-control">
                          <option value="technique">Technique</option>
                          <option value="cs">CS</option>
                          <option value="marketing">Marketing</option>
                          <option value="bug (Web)">Bug (Web)</option>
                          <option value="bug (Mobile)">Bug (Mobile)</option>
                          <option value="hosting & tools">
                            Hosting & tools
                          </option>
                          <option value="mobile versioning & deployment">
                            Mobile Versioning & Deployment
                          </option>
                          <option value="data">Data</option>
                          <option value="assistance (faire à sa place)">
                            Assistance (faire à sa place)
                          </option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Priority </label>
                        <select name="priority" class="form-control">
                          <option value="high">High</option>
                          <option value="medium">Medium</option>
                          <option value="low">Low</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="" class="form-label">Responsable </label>
                        <select
                          name="responsable_id"
                          required
                          class="form-control"
                        >
                          <option
                            v-for="accMan in accountManagers"
                            :key="accMan.id"
                            :value="accMan.id"
                          >
                            {{ accMan.nom }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="" class="form-label">Heure: </label>
                        <input
                          type="time"
                          required
                          name="hour"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="" class="form-label">
                          Durée de traitement:
                          <span class="text-info fw-bold">{{
                            this.hourConverted
                          }}</span>
                        </label>
                        <input
                          type="time"
                          v-model="getHour"
                          @change="getValue"
                          value="00:00"
                          required
                          name="duree"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Etat de traitement</label>
                        <select name="status" class="form-control">
                          <option value="traité">Traité</option>
                          <option value="encours">Encours</option>
                          <option value="annulé">Annulé</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <vue-editor
                        id="details"
                        v-model="detailsTicket"
                      ></vue-editor>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-label"> Pièces Jointes </label>
                        <input
                          type="file"
                          name="pieces[]"
                          multiple
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  class="btn btn-secondary"
                  @click="showModalAddTicket = !showModalAddTicket"
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { VueEditor } from "vue2-editor";
export default {
  components: {
    "vue-select": vSelect,
    "vue-editor": VueEditor,
  },
  data() {
    return {
      getHour: null,
      ticketSchool: null,
      hourConverted: null,
      demandeur: null,
      filteredTickets: [],
      tickets: [],
      schools: [],
      showModalAddTicket: false,
      ticketItem: [],
      accountManagers: [],
      schoolContacts: [],
      natures: [],
      detailsTicket: "",
    };
  },
  methods: {
    getValue() {
      if (this.getHour.substr(0, 2) == "00") {
        this.hourConverted = this.getHour.substr(3, 4) + "min";
      } else {
        this.hourConverted =
          this.getHour.substr(0, 2) + "H:" + this.getHour.substr(3, 4) + "min";
      }
    },
    getticketsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getAllTickets", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.tickets = result.data;
            this.filteredTickets = this.tickets;
            // this.$emit('load-tutoriels',this.tutoriels.length)
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    filterTickets: function (e) {
      let value = e.target.value;
      this.filteredTickets = this.tickets.filter((tutoriel) => {
        return (
          tutoriel.label.toLowerCase().includes(value.toLowerCase()) ||
          tutoriel.responsable.name
            .toLowerCase()
            .includes(value.toLowerCase()) ||
          tutoriel.school.name.toLowerCase().includes(value.toLowerCase())
        );
      });
    },
    getAccountManagers: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getAccountManagers", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.accountManagers = result.data;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    getSchoolsContact: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSchoolsContact", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.schoolContacts = result.data;
            console.log(this.schoolContacts);
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    getNatures() {
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
            console.log(this.natures);
          })
          .catch((error) => {
            error;
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
    getSchoolsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSchoolsList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.schools = result.data;
          })
          .catch((err) => {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    addTicket: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        formdata.append("details", this.detailsTicket);
        await axios
          .post("/api/saveTicket", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            // this.tickets = result.data;
            form.reset();
            this.showModalAddTicket = !this.showModalAddTicket;
            this.getticketsList();
          })
          .catch(function (err) {});
      }
    },
    updateState: async function (ticket, state) {
      const token = localStorage.getItem("auth-token");
      if (ticket.infos.state.alias != state && token) {
        await axios
          .post(
            "/api/updateTicketState",
            {
              ticket: ticket.id,
              state: state,
            },
            {
              headers: {
                Authorization: "Bearer " + token,
              },
            }
          )
          .then(async (result) => {
            ticket.infos.state.label = state;
          })
          .catch(function (err) {});
      }
    },
    addNew() {
      this.ticketItem = [];
      this.showModalAddTicket = true;
    },
    showItem(ticket) {
      console.log(ticket);
      this.ticketItem = ticket;
      this.showModalAddTicket = true;
    },
    deleteTicket(id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "are you sure to delete this Ticket ?",
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
                "/api/delete/ticket/" + id,
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
                  title: "Ticket deleted Successfully",
                  showConfirmButton: false,
                  timer: 1500,
                });
                this.filteredTickets = this.filteredTickets.filter((t) => {
                  return t.id != id;
                });
              });
          }
        });
      }
    },
  },
  mounted() {
    this.getticketsList();
    this.getSchoolsList();
    this.getSchoolsContact();
    this.getAccountManagers();
    this.getNatures();
  },
};
</script>

<style scoped>
</style>