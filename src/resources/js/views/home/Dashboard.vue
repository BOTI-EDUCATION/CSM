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
          <div
            :class="
              'col-sm-12 col-md-' +
              ((lastTicket.label ? 0 : 3) +
                (lastIntervention.label ? 0 : 3) +
                6)
            "
          >
            <div class="card full-row-card overflow-hidden">
              <div class="card-header pb-0 border-bottom-0">
                <h3 class="card-title">Traitement des incidents :</h3>
              </div>
              <div class="card-body">
                <div class="progress progress-lg">
                  <div
                    class="progress-bar bg-green"
                    :style="{ width: traitementIncidents.traite + '%' }"
                  >
                    {{ traitementIncidents.traite }}% Traités
                  </div>
                  <div
                    class="progress-bar bg-red"
                    :style="{ width: traitementIncidents.nontraite + '%' }"
                  >
                    {{ traitementIncidents.nontraite }}% Non Traités
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-sm-6 text-center">
                    <h5>
                      <span class="dot-label bg-green"></span>
                      Traités
                    </h5>
                  </div>
                  <div class="col-sm-6 text-center">
                    <h5>
                      <span class="dot-label bg-red"></span>
                      Non Traités
                    </h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            v-if="lastTicket.label"
            class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
          >
            <div class="card bg-danger full-row-card img-card box-info-shadow">
              <div class="card-body">
                <div class="d-flex">
                  <div class="text-white">
                    <h5 class="mb-0 number-font">
                      {{ lastTicket.label }}
                    </h5>
                    <p class="text-white mb-0"></p>
                  </div>
                  <div class="ms-auto">
                    <span
                      class="avatar bg-white avatar-xl bradius cover-image"
                      :data-bs-image-src="lastTicket.school"
                      :style="
                        ' background: url(\'' +
                        lastTicket.school +
                        '\') center center'
                      "
                    >
                      <span class="avatar-icons">
                        <img
                          :src="lastTicket.responsable.img"
                          class="rounded"
                          alt=""
                        /> </span
                    ></span>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-sm-6">
                    <p class="text-white mb-0">Demandes Urgente</p>
                  </div>
                  <div class="col-sm-6 text-right">
                    <small class="text-white">{{
                      lastTicket.responsable.name
                    }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            v-if="lastIntervention.label"
          >
            <div class="card bg-info full-row-card img-card box-info-shadow">
              <div class="card-body">
                <div class="d-flex">
                  <div class="text-white">
                    <h2 class="mb-0 number-font">
                      {{ lastIntervention.date.day }}
                    </h2>
                    <p class="text-white mb-0">
                      {{ lastIntervention.date.month }}
                    </p>
                  </div>
                  <div class="ms-auto">
                    <span
                      class="avatar bg-white avatar-xl bradius cover-image"
                      :data-bs-image-src="lastIntervention.school"
                      :style="
                        ' background: url(\'' +
                        lastIntervention.school +
                        '\') center center'
                      "
                    >
                      <span class="avatar-icons">
                        <img
                          :src="lastIntervention.responsable.img"
                          class="rounded"
                          alt=""
                        /> </span
                    ></span>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-sm-6">
                    <p class="text-white mb-0">{{ lastIntervention.label }}</p>
                  </div>
                  <div class="col-sm-6 text-right">
                    <small class="text-white">{{
                      lastIntervention.responsable.name
                    }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="false" class="row">
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Statistiques Générales</h3>
            <div class="card-options">
              <div class="input-group">
                <button @click="previousStatistiques" class="btn btn-primary">
                  <i class="fa fa-arrow-left tx-16 lh-0 op-6"></i>
                </button>
                <Datepicker
                  ref="datePickerStatistiques"
                  :language="localfr"
                  class="form-control"
                  :value="dateStatistique"
                  @selected="updateDate"
                />
                <button @click="nextStatistiques" class="btn btn-primary">
                  <i class="fa fa-arrow-right tx-16 lh-0 op-6"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row pb-5">
              <div class="col-md-6 border-end">
                <div class="row">
                  <div class="col-12 text-center">
                    <h3>Incidents</h3>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-success">Traités</h2>
                        <span class="text-success">
                          <i class="fa fa-check fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-success fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-warning">En cours</h2>
                        <span class="text-warning">
                          <i class="fa fa-ellipsis fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-warning fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-danger">Urgents</h2>
                        <span class="text-danger">
                          <i class="fa fa-warning fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-danger fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 border-start">
                <div class="row">
                  <div class="col-12 text-center">
                    <h3>Formations & assistances</h3>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-success">Réalisées</h2>
                        <span class="text-success">
                          <i class="fa fa-check fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-success fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-primary">En attente</h2>
                        <span class="text-primary">
                          <i class="fa fa-cogs fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-primary fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-info">Planifiées</h2>
                        <span class="text-info">
                          <i class="fa fa-calendar fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-info fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-md-6 border-end">
                <div class="row">
                  <div class="col-12 text-center">
                    <h3>Demandes</h3>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-success">Traités</h2>
                        <span class="text-success">
                          <i class="fa fa-check fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-success fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-warning">En cours</h2>
                        <span class="text-warning">
                          <i class="fa fa-ellipsis fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-warning fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-danger">Stand by</h2>
                        <span class="text-danger">
                          <i class="fa fa-warning fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-danger fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 border-start">
                <div class="row">
                  <div class="col-12 text-center">
                    <h3>Ecoles</h3>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-success">Visités</h2>
                        <span class="text-success">
                          <i class="fa fa-check fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-success fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-primary">Formées</h2>
                        <span class="text-primary">
                          <i class="fa fa-chalkboard-teacher fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-primary fw-bold my-3">120</h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="princing-item">
                      <div class="pricing-divider text-center pt-5">
                        <h2 class="text-info">Assistées</h2>
                        <span class="text-info">
                          <i class="fa fa-hand-holding-medical fa-3x"></i>
                        </span>
                        <h4 class="display-5 text-info fw-bold my-3">120</h4>
                      </div>
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
      <div class="col-sm-12 col-md-4 offset-md-8 text-right mb-4">
        <a
          @click="showAddTicket"
          href="javascript:void(0)"
          class="text-white btn bg-success px-3 py-2 "
          ><i class="fa fa-plus me-2"></i
        >Ajouter un ticket</a>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tickets :</h3>
            <div class="card-options">
              <div class="form-group mx-2 mb-0">
                <select
                  name="country"
                  class="form-control form-select"
                  data-bs-placeholder="Select Country"
                  v-model="filtreTickets.nature"
                  @change="getTickets"
                >
                  <option selected value="">Nature</option>
                  <option value="incident">Incident</option>
                  <option value="demande">Demande</option>
                  <option value="evolution">Evolution</option>
                </select>
              </div>
              <div class="form-group mx-2 mb-0">
                <select
                  name="country"
                  class="form-control form-select"
                  data-bs-placeholder="Select Country"
                  v-model="filtreTickets.genre"
                  @change="getTickets"
                >
                  <option selected value="">Genre</option>
                  <option value="technique">Technique</option>
                  <option value="cs">CS</option>
                  <option value="marketing">Marketing</option>
                </select>
              </div>
              <!-- <div class="form-group mx-2 mb-0">
                <select
                  name="country"
                  class="form-control form-select"
                  data-bs-placeholder="Select Country"
                  v-model="filtreTickets.delai"
                >
                  <option selected value="">Délai</option>
                  <option value="cz">Czech Republic</option>
                  <option value="de">Germany</option>
                  <option value="pl">Poland</option>
                </select>
              </div> -->
              <div class="form-group mx-2 mb-0">
                <select
                  name="country"
                  class="form-control form-select"
                  data-bs-placeholder="Select Country"
                  v-model="filtreTickets.etat"
                  @change="getTickets"
                >
                  <option selected value="">Etat</option>
                  <option value="nouveau">Nouveau</option>
                  <option value="encours">En cours</option>
                  <option value="traite">Traités</option>
                </select>
              </div>
              <div class="form-group mx-2 mb-0">
                <select
                  name="country"
                  class="form-control form-select"
                  data-bs-placeholder="Select Country"
                  v-model="filtreTickets.priorite"
                  @change="getTickets"
                >
                  <option selected value="">Importance</option>
                  <option value="high">High</option>
                  <option value="medium">Medium</option>
                  <option value="low">Low</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table class="table border vertical-align-middle table-hover mb-0">
              <tbody v-if="tickets.length > 0">
                <tr v-for="ticket in tickets" :key="ticket.id">
                  <td>
                    <span
                      class="avatar avatar-xl bradius cover-image"
                      :data-bs-image-src="ticket.school.img"
                      :style="
                        ' background: url(\'' +
                        ticket.school.img +
                        '\') center center'
                      "
                    ></span>
                  </td>
                  <td>
                    <h5>{{ ticket.label }}</h5>
                    <p>{{ ticket.details }}</p>
                  </td>
                  <td>
                    <span
                      class="avatar avatar-xl bradius cover-image"
                      :data-bs-image-src="ticket.responsable"
                      :style="
                        ' background: url(\'' +
                        ticket.responsable +
                        '\') center center'
                      "
                    ></span>
                  </td>
                  <td>
                    <span
                      :class="
                        'tag tag-rounded tag-' +
                        ticket.nature.color +
                        ' text-nowrap'
                      "
                      style="text-transform: capitalize"
                      >{{ ticket.nature.label }}</span
                    >
                  </td>
                  <td>
                    <span
                      :class="
                        'tag tag-rounded tag-' +
                        ticket.genre.color +
                        ' text-nowrap'
                      "
                      style="text-transform: capitalize"
                      >{{ ticket.genre.label }}</span
                    >
                  </td>
                  <td class="text-nowrap" align="center">
                    {{ ticket.date.label }}
                  </td>
                  <td>
                    <span
                      :class="
                        'tag tag-rounded tag-' +
                        ticket.etat.color +
                        ' text-nowrap'
                      "
                      style="text-transform: capitalize"
                      >{{ ticket.etat.label }}</span
                    >
                  </td>
                  <td>
                    <span
                      :class="
                        'tag tag-rounded tag-' +
                        ticket.priorite.color +
                        ' text-nowrap'
                      "
                      style="text-transform: capitalize"
                      >{{ ticket.priorite.label }}</span
                    >
                  </td>
                  <td>
                    <router-link
                      :to="'schools/view/' + ticket.school.id"
                      class="btn btn-icon btn-primary-light"
                    >
                      <i class="fe fe-eye"></i>
                    </router-link>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td class="text-center">
                    <p class="text-muted">No Tickets</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Planning :</h3>
            <div class="card-options">
              <button
                @click="showAddIntervention"
                type="button"
                class="btn btn-icon btn-success"
              >
                <i class="fe fe-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body dashboard-calendar">
            <FullCalendar ref="planningCalendar" :options="calendarOptions" />
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Next Events :</h3>
            <div class="card-options">
              <button
                @click="showAddIntervention"
                type="button"
                class="btn btn-icon btn-success"
              >
                <i class="fe fe-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <table
              v-if="interventions.length > 0"
              class="table border vertical-align-middle table-hover mb-0"
            >
              <tr v-for="intervention in interventions" :key="intervention.id">
                <td>
                  <span
                    class="avatar avatar-xl bradius cover-image"
                    :data-bs-image-src="intervention.school.img"
                    :style="
                      ' background: url(\'' +
                      intervention.school.img +
                      '\') center center'
                    "
                  >
                    <span class="avatar-icons">
                      <img
                        :src="intervention.responsable"
                        class="rounded"
                        alt=""
                      />
                    </span>
                  </span>
                </td>
                <td>{{ intervention.label }}</td>
                <td>
                  <span
                    :class="
                      'tag tag-rounded tag-' +
                      intervention.nature.color +
                      ' text-nowrap'
                    "
                    >{{ intervention.nature.label }}</span
                  >
                </td>
                <td class="text-center">
                  <h2 class="mb-0">
                    <b>{{ intervention.date.day }}</b> <br />
                    <p class="h5">{{ intervention.date.month }}</p>
                  </h2>
                </td>
              </tr>
            </table>
            <table
              v-else
              class="table border vertical-align-middle table-hover mb-0"
            >
              <tr>
                <td class="text-center">
                  <p class="text-muted">Aucune Intervention Plannifiée</p>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- <transition
      @enter="startTransitionModal($refs.modalEvents)"
      @after-enter="endTransitionModal($refs.modalEvents)"
      @before-leave="endTransitionModal($refs.modalEvents)"
      @after-leave="startTransitionModal($refs.modalEvents)"
    >
      <div class="modal fade" v-if="showModalEvents" ref="modalEvents">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Ajouter un event
              </h5>
              <button
                class="close btn"
                @click="showModalEvents = !showModalEvents"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label class="form-label"> Titre </label>
                  <input type="text" class="form-control" placeholder="Titre" />
                </div>
                <div class="form-group">
                  <label class="form-label"> Date </label>
                  <input type="date" class="form-control" placeholder="Date" />
                </div>
                <div class="form-group">
                  <label class="form-label"> School </label>
                  <select name="" id="" class="form-control form-select">
                    <option selected disabled value="">School</option>
                    <option value="alexandre">Alexandre Fleming</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label"> Raison </label>
                  <select name="" id="" class="form-control form-select">
                    <option selected disabled value="">Raison</option>
                    <option value="Onboarding">Onboarding</option>
                    <option value="Fomation">Fomation</option>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                class="btn btn-secondary"
                @click="showModalEvents = !showModalEvents"
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
    <transition
      @enter="startTransitionModal($refs.modalPlanning)"
      @after-enter="endTransitionModal($refs.modalPlanning)"
      @before-leave="endTransitionModal($refs.modalPlanning)"
      @after-leave="startTransitionModal($refs.modalPlanning)"
    >
      <div class="modal fade" v-if="showModalPlanning" ref="modalPlanning">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Ajouter un planning
              </h5>
              <button
                class="close btn"
                @click="showModalPlanning = !showModalPlanning"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label class="form-label"> Titre </label>
                  <input type="text" class="form-control" placeholder="Titre" />
                </div>
                <div class="form-group">
                  <label class="form-label"> Date </label>
                  <input
                    type="date"
                    class="form-control"
                    placeholder="Company name"
                  />
                </div>
                <div class="form-group">
                  <label class="form-label"> Genre </label>
                  <select name="" id="" class="form-control form-select">
                    <option selected disabled value="">Genre</option>
                    <option value="Technique">Technique</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label"> Account Manager </label>
                  <select name="" id="" class="form-control form-select">
                    <option selected disabled value="">Account Manager</option>
                    <option
                      v-for="user of users"
                      :key="user.id"
                      :value="user.id"
                    >
                      {{ user.prenom }} {{ user.nom }}
                    </option>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                class="btn btn-secondary"
                @click="showModalPlanning = !showModalPlanning"
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
    <div class="modal-backdrop fade d-none" ref="backdrop"></div> -->
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
    showAddTicket: function(){
      this.$refs.layout.$refs.btnAddTicket.click();
    },
    showAddIntervention: function(){
      this.$refs.layout.$refs.btnAddIntervention.click();
    },
  },
  mounted() {
    this.getUserList();
    this.getEtatTraitementIncidents();
    this.getTickets();
    this.getinterventions();
    this.getLastTicket();
    this.getLastIntervention();
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
</style>