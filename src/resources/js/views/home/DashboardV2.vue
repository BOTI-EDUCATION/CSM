<template>
  <Layout
    ref="layout"
    @intervention-added="
      getinterventions();
      getLastIntervention();
    "
    @ticket-added="
      getTickets();
      getLastTicket();
    "
  >
    <div class="page-header">
      <h1 class="page-title">Dashboard</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Schools</h6>
                    <h2 class="mb-0 number-font">{{ schoolsStats.count }}</h2>
                  </div>
                  <div class="ms-auto"></div>
                </div>
                <span class="text-muted fs-12"
                  ><span class="text-secondary"
                    ><i class="fe fe-arrow-up-circle text-secondary"></i>
                    {{ schoolsStats.upratio }}%</span
                  >
                  Last week</span
                >
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Tickets Buffer</h6>
                    <h2 class="mb-0 number-font">{{ ticketsStats.count }}</h2>
                  </div>
                  <div class="ms-auto"></div>
                </div>
                <span class="text-muted fs-12"
                  ><span class="text-pink"
                    ><i class="fe fe-arrow-down-circle text-pink"></i>
                    {{ ticketsStats.upratio }}%</span
                  >
                  Last 6 days</span
                >
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Planned Actions</h6>
                    <h2 class="mb-0 number-font">
                      {{ interventionStats.count }}
                    </h2>
                  </div>
                  <div class="ms-auto"></div>
                </div>
                <span class="text-muted fs-12"
                  ><span class="text-green"
                    ><i class="fe fe-arrow-up-circle text-green"></i>
                    {{ interventionStats.upratio }}%</span
                  >
                  Last 9 days</span
                >
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Content Created</h6>
                    <h2 class="mb-0 number-font">0</h2>
                  </div>
                  <div class="ms-auto"></div>
                </div>
                <span class="text-muted fs-12"
                  ><span class="text-warning"
                    ><i class="fe fe-arrow-up-circle text-warning"></i> 0%</span
                  >
                  Last year</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-xl-4 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title fw-semibold">Last Notifications</h4>
          </div>
          <div class="card-body pb-0">
            <ul class="task-list">
              <li
                class="d-sm-flex"
                v-for="intervention in interventions"
                :key="intervention.id"
              >
                <div>
                  <i class="task-icon bg-primary"></i>
                  <h6 class="fw-semibold">
                    {{ intervention.label }} <br />
                    <span class="text-muted fs-11 mx-2 fw-normal">{{
                      intervention.date.brut
                    }}</span>
                  </h6>
                  <p class="text-muted fs-12">
                    {{ intervention.details }}
                  </p>
                </div>
                <div class="ms-auto d-md-flex">
                  <a
                    href="javascript:void(0)"
                    class="text-muted me-2"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title=""
                    aria-label="Edit"
                    data-bs-original-title="Edit"
                    ><span class="fe fe-edit"></span
                  ></a>
                  <a href="javascript:void(0)" class="text-muted"
                    ><span class="fe fe-trash-2"></span
                  ></a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div> -->
      <div class="col-xl-4 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title fw-semibold">CS Interventions :</h4>
          </div>
          <div class="card-body pb-0 max-card-height">
            <div class="table-responsive">
              <div class="table text-center">
                <tr>
                  <td>School</td>
                  <td>Cs-manager</td>
                  <td>Title</td>
                  <td>Date</td>
                </tr>
                <tr v-for="cs in dashboard.csIntervention" :key="cs">
                  <td>
                    <div class="align-items-center d-flex">
                      <img
                        :src="cs.school.img"
                        alt="profile-lead"
                        class="avatar profile-user brround cover-image me-2"
                      />
                    </div>
                  </td>
                  <td>
                    <div class="align-items-center d-flex">
                      <img
                        :src="cs.user"
                        alt="csmanager"
                        class="avatar profile-user brround cover-image me-2"
                        srcset=""
                      />
                    </div>
                  </td>
                  <td style="vertical-align: middle">{{ cs.label }}</td>
                  <td style="vertical-align: middle">
                    {{ cs.date.day }}{{ cs.date.month }}
                  </td>
                </tr>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="col-xl-4 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title fw-semibold">Last requests</h4>
          </div>
          <div class="card-body pb-0">
            <ul class="task-list">
              <li class="d-sm-flex" v-for="ticket in tickets" :key="ticket.id">
                <div>
                  <i class="task-icon bg-primary"></i>
                  <h6 class="fw-semibold">
                    {{ ticket.label }} <br />
                    <span class="text-muted fs-11 fw-normal">{{
                      ticket.date
                    }}</span>
                  </h6>
                  <p class="text-muted fs-12" v-html="ticket.details"></p>
                </div>
                <div class="ms-auto d-md-flex">
               
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div> -->
      <div class="col-xl-4 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title fw-semibold">Sales Interventions :</h4>
          </div>
          <div class="card-body pb-0 max-card-height">
            <div class="table-responsive">
              <div class="table text-center">
                <tr>
                  <td>School</td>
                  <td>Sale-manager</td>
                  <td>Title</td>
                  <td>Date</td>
                </tr>
                <tr v-for="sale in dashboard.salesIntervention" :key="sale">
                  <td>
                    <div class="align-items-center d-flex">
                      <img
                        :src="sale.school.img"
                        alt="profile-lead"
                        class="avatar profile-user brround cover-image me-2"
                      />
                    </div>
                  </td>
                  <td>
                    <div class="align-items-center d-flex">
                      <img
                        :src="sale.user"
                        alt="csmanager"
                        class="avatar profile-user brround cover-image me-2"
                        srcset=""
                      />
                    </div>
                  </td>
                  <td style="vertical-align: middle">{{ sale.label }}</td>
                  <td style="vertical-align: middle">
                    {{ sale.date.day }}{{ sale.date.month }}
                  </td>
                </tr>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="col-xl-4 col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title fw-semibold">Ticket's Buffer</h4>
          </div>
          <div class="card-body">
            <div class="browser-stats">
              <div
                class="row mb-4"
                v-for="ticketBuffer in ticketsBuffers"
                :key="ticketBuffer.id"
              >
                <div class="col-sm-2 col-lg-3 col-xl-3 col-xxl-2 mb-sm-0 mb-3">
                  <img :src="ticketBuffer.img" class="img-fluid" alt="img" />
                </div>
                <div class="col-sm-10 col-lg-9 col-xl-9 col-xxl-10 ps-sm-0">
                  <div
                    class="d-flex align-items-end justify-content-between mb-1"
                  >
                    <h6 class="mb-1">{{ ticketBuffer.nom }}</h6>
                    <h6 class="fw-semibold mb-1">
                      {{ ticketBuffer.tickets }}
                      <span class="text-success fs-11"
                        >(<i class="fe fe-arrow-up"></i
                        >{{ ticketBuffer.closing_percentage }}%)</span
                      >
                    </h6>
                  </div>
                  <div class="progress h-2 mb-3">
                    <div
                      class="progress-bar bg-primary"
                      :style="'width: ' + ticketBuffer.closing_percentage + '%'"
                      role="progressbar"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <div class="col-xl-4 col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title fw-semibold">Last Tickets:</h4>
          </div>
          <div class="card-body max-card-height">
            <div class="table-responsive">
              <div class="table text-center">
                <tr>
                  <td>School</td>
                  <td>Cs-manager</td>
                  <td>Title</td>
                  <td>Date</td>
                </tr>
                <template v-if="dashboard.lastTicket">
                <tr  v-for="ticket in dashboard.lastTicket" :key="ticket">
                  <td>
                    <div class="align-items-center d-flex">
                      <img
                        :src="ticket.school.img"
                        alt="profile-lead"
                        class="avatar profile-user brround cover-image me-2"
                      />
                    </div>
                  </td>
                  <td>
                    <div class="align-items-center d-flex">
                      <img
                        :src="ticket.user"
                        alt="csmanager"
                        class="avatar profile-user brround cover-image me-2"
                        srcset=""
                      />
                    </div>
                  </td>
                  <td style="vertical-align: middle">{{ ticket.label }}</td>
                  <td style="vertical-align: middle">
                    {{ ticket.date.day }}{{ ticket.date.month }}
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="4"> <span class="alert alert-danger">No Available Tickets</span> </td>
                </tr>
              </template>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Deleted Schools</h6>
                    <h2 class="mb-0 number-font">{{  dashboard.activeSchool - schoolsStats.count }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">New Requests</h6>
                    <h2 class="mb-0 number-font">{{ dashboard.requests }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Leads</h6>
                    <h2 class="mb-0 number-font">{{ dashboard.leads }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex">
                  <div class="mt-2">
                    <h6 class="">Pending Tickets</h6>
                    <h2 class="mb-0 number-font">{{ dashboard.ticket }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import { fr } from "vuejs-datepicker/dist/locale";
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
// import {Swiper , SwiperSlide} from 'swiper/vue';

import Layout from "../../components/Layout.vue";
import Event from "../../components/calender/Event.vue";
import Vue from "vue";

export default {
  components: {
    Layout,
    Datepicker,
    FullCalendar,
  },
  data() {
    return {
      dashboard: {
        csIntervention: [],
        salesIntervention: [],
        lastTicket: [],
        activeSchool: 0,
        requests: 0,
        leads: 0,
        ticket: 0,
      },
      schoolsStats: {},
      ticketsStats: {},
      interventionStats: {},
      ticketsBuffers: [],
      users: [],
      tickets: [],
      interventions: [],
      lastTicket: {},
      lastIntervention: {},
      filtreTickets: {
        nature: "",
        genre: "",
        delai: "",
        etat: "",
        priorite: "",
      },
      localfr: fr,
      dateStatistique: new Date(),
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        events: (info, successCallback, failureCallback) => {
          const token = localStorage.getItem("auth-token");
          if (token) {
            return axios
              .get("/api/getDasboardPlanning", {
                params: {
                  info,
                },
                headers: {
                  Authorization: "Bearer " + token,
                },
              })
              .then(async (result) => {
                return successCallback(result.data);
              })
              .catch(function (err) {
                failureCallback(err);
              });
          } else {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          }
        },
        eventBackgroundColor: "transparent !important",
        eventColor: "#000 !important",
        eventContent: (arg) => {
          let EventComponent = Vue.extend(Event);
          let ev = new EventComponent({
            propsData: { eventItem: arg.event },
          });
          ev.$mount();
          return { domNodes: [ev.$el] };
        },
      },
      showModalPlanning: false,
      showModalEvents: false,
      traitementIncidents: {
        traite: 100,
        nontraite: 0,
      },
    };
  },
  methods: {
    getEtatTraitementIncidents: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getEtatTraitementIncidents", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.traitementIncidents = {
              traite: result.data.traite,
              nontraite: result.data.non_traite,
            };
            console.log(result);
          })
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
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
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    updateDate: function () {
      console.log(this.$refs.datePickerStatistiques.selectedDate);
      this.dateStatistique = new Date(
        this.$refs.datePickerStatistiques.selectedDate
      );
    },
    updateDatePicker: function () {
      this.$refs.datePickerStatistiques.setDate(this.dateStatistique);
    },
    previousStatistiques: function () {
      this.dateStatistique.setDate(this.dateStatistique.getDate() - 1);
      this.updateDatePicker();
    },
    nextStatistiques: function () {
      this.dateStatistique.setDate(this.dateStatistique.getDate() + 1);
      this.updateDatePicker();
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
    getTickets: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let filtre = this.filtreTickets;
        await axios
          .get("/api/getDasboardTickets", {
            params: filtre,
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.tickets = result.data;
          })
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    getLastTicket: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let filtre = this.filtreTickets;
        await axios
          .get("/api/getLastTicket", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.lastTicket = result.data;
          })
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    getLastIntervention: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getLastIntervention", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.lastIntervention = result.data;
          })
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    getinterventions: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getDasboardInterventions", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.interventions = result.data;
          })
          .catch(function (err) {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    getUserTicketBuffer: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getUserTicketBuffer", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.ticketsBuffers = result.data;
          })
          .catch(function (err) {});
      } else {
      }
    },
    getDashboardStats: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getDashboardStats", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            let data = result.data;
            this.schoolsStats = data.schools;
            this.ticketsStats = data.tickets;
            this.interventionStats = data.intervention;
          })
          .catch(function (err) {});
      } else {
      }
    },
    showAddTicket: function () {
      this.$refs.layout.$refs.btnAddTicket.click();
    },
    showAddIntervention: function () {
      this.$refs.layout.$refs.btnAddIntervention.click();
    },
    cs_interventions() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/cs_interventions", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.dashboard.csIntervention = result.data;
          });
      }
    },
    sales_interventions() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/sales_interventions", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.dashboard.salesIntervention = result.data;
          });
      }
    },
    lastTickets() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/dash/tickets", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.dashboard.lastTicket = result.data;
          });
      }
    },
    counts() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/dashborad/counts", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.dashboard.leads = result.data.leads;
            this.dashboard.requests = result.data.requests;
            this.dashboard.activeSchool = result.data.schools;
            this.dashboard.ticket = result.data.pendings;
          });
      }
    },
  },
  mounted() {
    this.getUserList();
    this.getEtatTraitementIncidents();
    this.getTickets();
    this.getinterventions();
    this.getLastTicket();
    this.getLastIntervention();
    this.getUserTicketBuffer();
    this.getDashboardStats();
    this.cs_interventions();
    this.sales_interventions();
    this.lastTickets();
    this.counts();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
</style>

<style>
.vdp-datepicker.form-control input {
  border: none;
  outline: none;
}
.vdp-datepicker__calendar {
  left: -7rem;
}
table.vertical-align-middle td {
  vertical-align: middle;
}
.dashboard-calendar .fc-daygrid-event.fc-event {
  background-color: transparent !important;
  color: #000 !important;
}
.dashboard-calendar .fc-daygrid-event.fc-event .fc-event-main {
  overflow: auto;
  color: #000 !important;
  padding: 2px;
  text-align: center;
}

.full-row-card {
  height: calc(100% - 1.5rem);
}

.max-card-height {
  max-height: 300px;
  overflow: auto;
}

.va-middle {
  vertical-align: middle;
}
</style>