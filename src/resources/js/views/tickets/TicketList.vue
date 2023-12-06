<template>
  <div>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <form @submit="filters_tickets" class="row">
            <div class="col-md-3">
              <select name="school" class="form-control" id="">
                <option value="school">schools</option>
                <option
                  v-for="school in schools"
                  :key="school.id"
                  :value="school.id"
                >
                  {{ school.name }}
                </option>
              </select>
            </div>

            <div class="col-md-3">
              <select name="responsable" class="form-control" id="">
                <option value="responsable">responsables</option>
                <option
                  v-for="manager in accountManagers"
                  :key="manager.id"
                  :value="manager.id"
                >
                  {{ manager.nom }}
                </option>
              </select>
            </div>

            <div class="col-md-3">
              <select name="status" class="form-control" id="">
                <option value="status">status</option>
                <option
                  v-for="status in statuses"
                  :key="status"
                  :value="status"
                >
                  {{ status }}
                </option>
              </select>
            </div>

            <!-- <div class="col-md-3">
               <date-range-picker
                 ref="picker"
                 :opens="opens"
                 :locale-data="locale"
                 :minDate="minDate"
                 :maxDate="maxDate"
                 :singleDatePicker="singleDatePicker"
                 :timePicker="timePicker"
                 :timePicker24Hour="timePicker24Hour"
                 :showWeekNumbers="showWeekNumbers"
                 :showDropdowns="showDropdowns"
                 :autoApply="autoApply"
                 v-model="dateRange"
                 @update="updateValues"
                 @toggle="logEvent('event: open', $event)"
                 @start-selection="logEvent('event: startSelection', $event)"
                 @finish-selection="logEvent('event: finishSelection', $event)"
                 :linkedCalendars="linkedCalendars"
                 :dateFormat="dateFormat"
               >
                 <template v-slot:input="picker">
                   {{ picker.startDate | date }} - {{ picker.endDate | date }}
                 </template>
               </date-range-picker>
             </div> -->
            <div class="col-md-3">
              <button class="btn btn-primary w-100">search</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="card mt-2">
      <div class="card-body">
        <div class="table-responsive">
          <v-table
            :data="filteredTickets"
            :currentPage.sync="currentPage"
            :pageSize="10"
            @totalPagesChanged="totalPages = $event"
            class="table"
          >
            <thead slot="head">
              <tr>
                <td>school</td>
                <td>label</td>
                <td>Responsable</td>
                <td>date</td>
                <td>Infos</td>
                <td>Details</td>
                <td>status</td>
                <td>Actions</td>
              </tr>
            </thead>
            <tbody slot="body" slot-scope="{ displayData }">
              <tr
                v-for="row in displayData"
                :key="row.guid"
                :class="
                  row.infos.state.label == 'cloture' ? 'boti-bg-success' : ''
                "
              >
                <template>
                  <td>
                    <span
                      v-if="row.school"
                      class="avatar bg-white avatar-xxl bradius cover-image me-3"
                      :data-bs-image-src="row.school.img"
                      :style="
                        ' background: url(\'' +
                        row.school.img +
                        '\') center center'
                      "
                    ></span>
                  </td>
                  <td>{{ row.label }}</td>
                  <td>
                    <small class="text-muted d-flex align-items-center my-3">
                      <span
                        v-if="row.responsable"
                        class="avatar bg-white avatar-sm rounded cover-image mx-2"
                        :data-bs-image-src="row.responsable.img"
                        :style="
                          ' background: url(\'' +
                          row.responsable.img +
                          '\') center center'
                        "
                      ></span>
                      {{ row.responsable.name }} -
                    </small>
                  </td>
                  <td>{{ row.date }}</td>
                  <td>
                    <div class="infos d-flex">
                      <span
                        :class="`badge rounded-pill bg-${row.infos.channel.color} badge-sm me-1 mb-3`"
                      >
                        {{ row.infos.channel.label }}</span
                      >
                      <span
                        :class="`badge rounded-pill bg-${row.infos.nature.color} badge-sm me-1 mb-3`"
                        >{{ row.infos.nature.label }}</span
                      >
                      <span
                        :class="`badge rounded-pill bg-${row.infos.genre.color} badge-sm me-1 mb-3`"
                        >{{ row.infos.genre.label }}</span
                      >
                      <span
                        :class="`badge rounded-pill bg-${row.infos.priority.color} badge-sm me-1 mb-3`"
                        >{{ row.infos.priority.label }}</span
                      >
                    </div>
                  </td>
                  <td>
                    <div>
                      <b-button v-b-modal="`modal-${row.id}`"
                        >show Details
                      </b-button>

                      <b-modal
                        :id="`modal-${row.id}`"
                        :title="row.label"
                        size="sm"
                      >
                        <p v-html="row.details"></p>
                      </b-modal>
                    </div>
                  </td>
                  <td>
                    <div
                      class="ms-auto"
                      v-if="row.infos.state.label != 'cloture'"
                    >
                      <button
                        type="button"
                        :class="`btn btn-${row.infos.state.color} dropdown-toggle `"
                        data-bs-toggle="dropdown"
                        aria-expanded="true"
                      >
                        {{ row.infos.state.label }}
                        <span class="caret"></span>
                      </button>
                      <ul
                        class="dropdown-menu"
                        role="menu"
                        data-popper-placement="bottom-start"
                      >
                        <li>
                          <a
                            href="javascript:void(0)"
                            @click="updateState(row, 'nouveau')"
                            >Nouveau</a
                          >
                        </li>
                        <li>
                          <a
                            href="javascript:void(0)"
                            @click="updateState(row, 'en cours')"
                            >En Cours</a
                          >
                        </li>
                        <li>
                          <a
                            href="javascript:void(0)"
                            @click="updateState(row, 'bloque')"
                            >Bloqué</a
                          >
                        </li>
                        <li>
                          <a
                            href="javascript:void(0)"
                            @click="updateState(row, 'cloture')"
                            >Clôturé</a
                          >
                        </li>
                      </ul>
                    </div>
                  </td>
                  <td>
                    <b-button
                      class="btn btn-warning"
                      v-b-modal="`edit-${row.id}`"
                      @click="showSelectValue(row.school.id ?? null )"
                    >
                      <i class="fa-solid fa-pen"></i>
                    </b-button>

                    <!---------------------------------- START EDIT TICKET ---------------------------------->
                    <b-modal
                      :id="`edit-${row.id}`"
                      :title="row.label"
                      size="xl"
                      ref="modal"
                      hide-footer
                    >
                      <form ref="form" @submit="addTicket">
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
                                  :value="row.label"
                                />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-label"> Schooln </label>
                                <input
                                  type="hidden"
                                  name="id"
                                  :value="row.id"
                                />
                                <input
                                  type="hidden"
                                  name="school"
                                  :value="ticketSchool"
                                />
                                <vue-select
                                  name="accountManager"
                                  :reduce="(school) => school.id"
                                  label="name"
                                  :options="schools"
                                  v-model="ticketSchool"
                                  placeholder="School"
                                  @input="getDemanderBySchool"
                                  searchable
                                >
                                </vue-select>
                              </div>
                            </div>

                            <div class="col-md-4" style="margin-top: 0.4rem">
                              <div class="form-group">
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
                              <div class="form-group">
                                <label class="form-label"> Channel </label>
                                <select name="channel" class="form-control">
                                  <option
                                    value="whatsapp"
                                    :selected="
                                      row.infos.channel.label == 'whatsapp'
                                        ? true
                                        : false
                                    "
                                  >
                                    Whatsapp
                                  </option>
                                  <option
                                    value="phone"
                                    :selected="
                                      row.infos.channel.label == 'phone'
                                        ? true
                                        : false
                                    "
                                  >
                                    Appel
                                  </option>
                                  <option
                                    value="mail"
                                    :selected="
                                      row.infos.channel.label == 'mail'
                                        ? true
                                        : false
                                    "
                                  >
                                    Email
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-label"> Nature </label>
                                <select name="nature" class="form-control">
                                  <option
                                    v-for="nature in natures"
                                    :value="nature.name"
                                    :key="nature.id"
                                    :selected="
                                      row.infos.nature.label == nature.name
                                    "
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
                                  <option
                                    value="technique"
                                    :selected="
                                      row.infos.genre.label == 'technique'
                                        ? true
                                        : false
                                    "
                                  >
                                    Technique
                                  </option>
                                  <option
                                    value="cs"
                                    :selected="
                                      row.infos.genre.label == 'cs'
                                        ? true
                                        : false
                                    "
                                  >
                                    CS
                                  </option>
                                  <option
                                    value="marketing"
                                    :selected="
                                      row.infos.genre.label == 'marketing'
                                        ? true
                                        : false
                                    "
                                  >
                                    Marketing
                                  </option>
                                  <option
                                    value="bug (Web)"
                                    :selected="
                                      row.infos.genre.label == 'bug (Web)'
                                        ? true
                                        : false
                                    "
                                  >
                                    Bug (Web)
                                  </option>
                                  <option
                                    value="bug (Mobile)"
                                    :selected="
                                      row.infos.genre.label == 'bug (Mobile)'
                                        ? true
                                        : false
                                    "
                                  >
                                    Bug (Mobile)
                                  </option>
                                  <option
                                    value="hosting & tools"
                                    :selected="
                                      row.infos.genre.label == 'hosting & tools'
                                        ? true
                                        : false
                                    "
                                  >
                                    Hosting & tools
                                  </option>
                                  <option
                                    value="mobile versioning & deployment"
                                    :selected="
                                      row.infos.genre.label ==
                                      'mobile versioning & deployment'
                                        ? true
                                        : false
                                    "
                                  >
                                    Mobile Versioning & Deployment
                                  </option>
                                  <option
                                    value="data"
                                    :selected="
                                      row.infos.genre.label == 'hosting & tools'
                                        ? true
                                        : false
                                    "
                                  >
                                    Data
                                  </option>
                                  <option
                                    value="assistance (faire à sa place)"
                                    :selected="
                                      row.infos.genre.label ==
                                      'assistance (faire à sa place)'
                                        ? true
                                        : false
                                    "
                                  >
                                    Assistance (faire à sa place)
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-label"> Priority </label>
                                <select name="priority" class="form-control">
                                  <option
                                    value="high"
                                    :selected="
                                      row.infos.priority.label == 'high'
                                        ? true
                                        : false
                                    "
                                  >
                                    High
                                  </option>
                                  <option
                                    value="medium"
                                    :selected="
                                      row.infos.priority.label == 'medium'
                                        ? true
                                        : false
                                    "
                                  >
                                    Medium
                                  </option>
                                  <option
                                    value="low"
                                    :selected="
                                      row.infos.priority.label == 'low'
                                        ? true
                                        : false
                                    "
                                  >
                                    Low
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="" class="form-label"
                                  >Responsable
                                </label>
                                <select
                                  name="responsable_id"
                                  required
                                  class="form-control"
                                >
                                  <option
                                    v-for="accMan in accountManagers"
                                    :key="accMan.id"
                                    :value="accMan.id"
                                    :selected="
                                      accMan.id == row.responsable.id
                                        ? true
                                        : false
                                    "
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
                                    hourConverted
                                  }}</span>
                                </label>
                                <input
                                  type="time"
                                  v-model="getHour"
                                  @change="getValue"
                                  value="00:00"
                                  name="duree"
                                  class="form-control"
                                />
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="">Etat de traitement</label>
                                <select name="status" class="form-control">
                                  <option
                                    value="traité"
                                    :selected="
                                      row.infos.state.label == 'traité'
                                        ? true
                                        : false
                                    "
                                  >
                                    Traité
                                  </option>
                                  <option
                                    value="encours"
                                    :selected="
                                      row.infos.state.label == 'encours'
                                        ? true
                                        : false
                                    "
                                  >
                                    Encours
                                  </option>
                                  <option
                                    value="annulé"
                                    :selected="
                                      row.infos.state.label == 'annulé'
                                        ? true
                                        : false
                                    "
                                  >
                                    Annulé
                                  </option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-12">
                              <vue-editor
                                id="details"
                                v-model="detailsTicket"
                                :value="row.details"
                              >
                              </vue-editor>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="form-label">
                                  Pièces Jointes
                                </label>
                                <input
                                  type="file"
                                  name="pieces[]"
                                  multiple
                                  class="form-control"
                                />
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="form-group">
                                <button
                                  type="submit"
                                  class="btn btn-warning w-100 d-block"
                                >
                                  Update
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </b-modal>
                    <!-- END EDIT TICKET  -->

                    <a
                      href="javascript:void(0)"
                      class="btn btn-danger"
                      @click="deleteTicket(row.id)"
                      ><i class="fa-solid fa-trash-can"></i
                    ></a>


                  </td>
                </template>
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
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { VueEditor } from "vue2-editor";
import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";

export default {
  components: {
    "vue-select": vSelect,
    "vue-editor": VueEditor,
    DateRangePicker,
  },
  data() {
    return {
      // ================================ //
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
      filters: [],
      statuses: ["nouveau", "cloture", "en cours", "bloque"],
      dateRange: {
        startDate: null,
        endDate: null,
      },
      locale: {
        direction: "ltr",
        format: "mm/dd/yyyy",
        separator: " - ",
        applyLabel: "Apply",
        cancelLabel: "Cancel",
        weekLabel: "W",
        customRangeLabel: "Custom Range",
        daysOfWeek: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        monthNames: [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Oct",
          "Nov",
          "Dec",
        ],
        firstDay: 0,
      },
      totalPages: 0,
      currentPage: 1,
    };
  },
  methods: {
    // ================================ //

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
    filters_tickets: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        await axios
          .post("/api/tickets/filter", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.tickets = result.data;
            this.filteredTickets = this.tickets;
          })
          .catch(function (err) {});
      }
    },
    getDemanderBySchool() {
      console.log("test", this.ticketSchool);
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/getSchoolContactBySchool/" + this.ticketSchool, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.schoolContacts = result.data;
          });
      }
    },
    showSelectValue(school) {
        this.ticketSchool = school;
    
    },
    addTicket: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        formdata.append("details", this.detailsTicket);
        formdata.append("school_id", this.ticketSchoolID);

        await axios
          .post("/api/saveTicket", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            location.reload();
            console.log(result.data);
            // form.reset();
            // this.showModalAddTicket = !this.showModalAddTicket;
            // this.$emit("ticket-added");
          })
          .catch(function (err) {});
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
  computed: {
    rows() {
      return this.filteredTickets.length;
    },
  },
};
</script>

<style scoped>
.boti-bg-success {
  background-color: #e9ffe9;
}
</style>