<template>
  <div class="row justify-content-end" id="user-profile">
    <div class="col-md-6"></div>
    <div class="col-md-3 text-right">
      <form @submit.prevent="toSchool">
        <button
          class="btn btn-danger mb-3 fw-bold"
          v-if="lead.state == 'on-boarding' && lead.is_converted == 0"
        >
          Convert to School
        </button>
      </form>
    </div>

    <div class="col-md-3 text-right mb-3" v-if="lead.is_admin">
      <button class="btn btn-danger" @click.prevent="deleteLead(lead.id)">
        Delete
      </button>
    </div>

    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="wideget-user mb-2">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="row">
                  <div class="panel profile-cover">
                    <div
                      class="profile-cover__action bg-img"
                      :style="'background-image: url(\'' + lead.banner + '\')'"
                    >
                   <span class="badge rounded-pill bg-warning text-dark" v-if="lead.contrat && lead.state == 'on-boarding' " style="font-size:20px;padding:5px">Client</span>
                  </div>
                    <div class="profile-cover__img">
                      <div class="profile-img-1">
                        <img :src="lead.logo" alt="img" /> <br />
                        <span
                          class="tag tag-rounded tag-primary text-nowrap"
                        ></span>
                      </div>
                      <div class="profile-img-content text-dark text-start">
                        <div class="text-dark d-flex align-items-baseline">
                          <h3 class="h3 mb-2">
                            {{ lead.name }}
                            <router-link
                              :to="'/leads/edit/' + lead.id"
                              class="btn btn-link"
                              ><i class="fa fa-edit"></i
                            ></router-link>
                          </h3>
                          <span v-if="lead.created_by" style="font-size: 14px"
                            >Ajouté le {{ lead.created_at }} par
                            <b>{{ lead.created_by.toUpperCase() }}</b>
                          </span>
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
                    <div
                      v-if="lead.links"
                      class="social social-profile-buttons mt-5 float-end"
                    >
                      <div class="mt-3">
                        <a
                          class="social-icon text-primary"
                          v-if="lead.links.facebook"
                          :href="lead.links.facebook"
                          ><i class="fab fa-facebook"></i
                        ></a>
                        <a
                          class="social-icon text-primary"
                          v-if="lead.links.instagram"
                          :href="lead.links.instagram"
                          ><i class="fab fa-instagram"></i
                        ></a>
                        <a
                          class="social-icon text-primary"
                          v-if="lead.links.youtube"
                          :href="lead.links.youtube"
                          ><i class="fab fa-youtube"></i
                        ></a>
                        <a
                          class="social-icon text-primary"
                          v-if="lead.links.website"
                          :href="lead.links.website"
                          ><i class="fa fa-globe"></i
                        ></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row d-flex align-items-center justify-content-between">
              <div class="col-md-4 text-center">
                <h5 class="text-muted">{{ lead.presentation }}</h5>
              </div>
              <div
                class="col-md-8 mt-5 d-flex justify-content-between align-items-center"
                style="padding-right: 0px"
              >
                <!-- v-if="lead.is_converted == 0" -->
                <div class="col-md-6">
                  <button v-if="lead.salesManager" class="btn btn-primary">
                    {{ lead.salesManager }}
                  </button>
                </div>
                <div class="col-md-6">
                  <select
                    name="prospection"
                    class="form-select badge rounded-pill"
                    style="
                      font-size: 14px;
                      font-weight: bold;
                      /* background-color: #6c5ffc; */
                      color: #6c5ffc !important;
                      cursor: pointer;
                      border: 1px solid #6c5ffc;
                    "
                    @change="changeState($event)"
                  >
                    <option
                      v-for="(state, index) in states"
                      :value="index"
                      :key="state"
                      :selected="index == lead.state ? true : false"
                      style="color: #6c5ffc !important"
                    >
                      {{ state }}
                    </option>
                  </select>
                </div>
                <!-- <span v-else class="d-inline-block  badge rounded-pill bg-success fw-bold" style="width:100%; font-size:18px;height:25px; line-height:20px">{{ lead.state }}</span> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header row">
              <div class="col-md-6">
                <div class="card-title">Type de contrat</div>
              </div>
              <div class="col-md-6 text-right">
                <div class="form-check form-check-inline">
                  <input
                    @change="natureContrat($event)"
                    name="contrat"
                    class="form-check-input  radio-elements"
                    type="radio"
                    id="inlineCheckbox1"
                    value="plateforme"
                    :checked="lead.contrat == 'plateforme' ? true : false "
                  />
                  <label class="form-check-label" for="inlineCheckbox1"
                    >Plateform</label
                  >
                </div>
                <div class="form-check form-check-inline">
                  <input
                    @change="natureContrat($event)"
                    name="contrat"
                    class="form-check-input radio-elements"
                    type="radio"
                    id="inlineCheckbox2"
                    value="bda"
                    :checked="lead.contrat == 'bda' ? true : false "
                  />
                  <label class="form-check-label" for="inlineCheckbox2"
                    >BDA</label
                  >
                </div>
                <div class="form-check form-check-inline">
                  <input
                    @change="natureContrat($event)"
                    name="contrat"
                    class="form-check-input radio-elements"
                    type="radio"
                    id="inlineCheckbox3"
                    value="bda & plateforme"
                    :checked="lead.contrat == 'bda & plateforme' ? true : false "
                  />
                  <label class="form-check-label" for="inlineCheckbox3"
                    >BDA & Plateform</label
                  >
                </div>
              </div>
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
                <div
                  v-for="contact in contacts"
                  :key="contact.id"
                  class="media overflow-visible align-items-center"
                >
                  <img
                    class="avatar brround avatar-md me-3"
                    :src="contact.picture"
                    alt="avatar-img"
                  />
                  <div class="media-body valign-middle mt-2">
                    <a class="fw-semibold text-dark"
                      >{{ contact.nom }}
                      <a
                        href="javascript:void(0)"
                        @click="showEditContact(contact.id)"
                        class="btn btn-link"
                        ><i class="fa fa-edit"></i
                      ></a>
                    </a>
                    <p class="text-muted mb-0">{{ contact.fonction }}</p>
                    <p class="text-muted fs-10 mb-0">{{ contact.email }}</p>
                  </div>
                  <div
                    class="s valign-middle text-end overflow-visible mt-2 d-grid"
                  >
                    <a
                      target="_blank"
                      :href="
                        'https://api.whatsapp.com/send?phone=' + contact.phone
                      "
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
                    <div class="pricing-divider text-center p-2 h-100">
                      <span class="text-primary">
                        <i class="fa fa-newspaper fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">
                        Nouveautés Partagées
                      </h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100">
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
                    <div class="pricing-divider text-center p-2 h-100">
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
                    <div class="pricing-divider text-center p-2 h-100">
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
                    <div class="pricing-divider text-center p-2 h-100">
                      <span class="text-primary">
                        <i class="fa fa-exclamation-circle fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">
                        Actions Disciplinaires
                      </h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100">
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
                    <div class="pricing-divider text-center p-2 h-100">
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
                    <div class="pricing-divider text-center p-2 h-100">
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
                    <div class="pricing-divider text-center p-2 h-100">
                      <span class="text-primary">
                        <i class="fab fa-readme fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">
                        Cahier de Textes Enregistré
                      </h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100">
                      <span class="text-primary">
                        <i class="fa fa-star fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">
                        Compétences Evaluées
                      </h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100">
                      <span class="text-primary">
                        <i class="fa fa-money-bill-wave fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">
                        Encaissements Realisés
                      </h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 mb-2">
                  <div class="princing-item border border-primary">
                    <div class="pricing-divider text-center p-2 h-100">
                      <span class="text-primary">
                        <i class="fa fa-bell fa-2x"></i>
                      </span>
                      <h4 class="display-6 text-primary fw-bold my-2">120</h4>
                      <h5 class="text-primary pe-0 mb-2">
                        Notifications Envoyées
                      </h5>
                    </div>
                  </div>
                </div>
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

      <div class="col-md-8 col-sm-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Planning</div>
            <div class="card-options">
              <button
                @click="showModalLead()"
                type="button"
                class="btn btn-icon btn-success"
              >
                <i class="fe fe-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <table class="table border vertical-align-middle table-hover mb-0">
              <tbody v-if="leads_interventions.length > 0">
                <tr
                  v-for="intervention in leads_interventions"
                  :key="intervention.id"
                >
                  <td style="width: 15%">
                    <span class="h2"
                      ><b> {{ intervention.date.day }} </b></span
                    >
                    <br />
                    {{ intervention.date.month }}
                  </td>
                  <td>
                    <h5>{{ intervention.label }}</h5>
                    <span v-html="intervention.details"></span>
                    <span
                      v-if="intervention.status"
                      class="badge rounded-pill bg-dark text-warning"
                      >{{ intervention.status }}</span
                    >
                  </td>
                  <td style="width: 25%; text-align: center">
                    <span
                      class="tag tag-rounded tag-primary text-nowrap d-inline-block mb-1"
                      >{{ intervention.nature }}</span
                    >
                    <span class="tag tag-rounded tag-warning text-dark">{{
                      intervention.sales
                    }}</span>
                    <span v-if="intervention.duration" class="tag tag-rounded tag-info">{{
                      intervention.duration
                    }}</span>
                  </td>
                  <td style="width: 5%">
                    <button
                      type="sumbit"
                      class="tag tag-white text-primary border-primary"
                      style="padding: 5px"
                      v-on:click.prevent="getIntervention(intervention)"
                    >
                      <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td class="text-center">
                    <p class="text-muted">Aucune Intervention</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- COL-END -->
    <transition
      @enter="startTransitionModal($refs.modalAddContact)"
      @after-enter="endTransitionModal($refs.modalAddContact)"
      @before-leave="
        endTransitionModal($refs.modalAddContact);
        clearForm();
      "
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
                        <input
                          type="text"
                          name="name"
                          v-model="contact.name"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Prenom </label>
                        <input
                          type="text"
                          required
                          name="last_name"
                          v-model="contact.last_name"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Email </label>
                        <input
                          type="text"
                          name="email"
                          v-model="contact.email"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Telephone </label>
                        <input
                          type="text"
                          name="phone"
                          v-model="contact.phone"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Fonction </label>
                        <input
                          type="text"
                          name="function"
                          v-model="contact.function"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Image </label>
                        <input
                          type="file"
                          name="picture"
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="contact" :value="contact.id" />
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
                <button class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>
    <!------------------------------  ADD INTERVATION ------------------------------>
    <transition
      @enter="startTransitionModal($refs.modalAddInterventionLead)"
      @after-enter="endTransitionModal($refs.modalAddInterventionLead)"
      @before-leave="endTransitionModal($refs.modalAddInterventionLead)"
      @after-leave="startTransitionModal($refs.modalAddInterventionLead)"
    >
      <div
        class="modal fade"
        v-if="showModalAddInterventionLead"
        ref="modalAddInterventionLead"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form
              @submit.prevent="addInterventionLead"
              enctype="multipart/form-data"
            >
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Ajouter une intervention
                </h5>
                <button
                  class="close btn"
                  @click="
                    showModalAddInterventionLead = !showModalAddInterventionLead
                  "
                >
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Titre </label>
                        <input
                          v-if="leadItem.label"
                          type="text"
                          required
                          name="label"
                          class="form-control"
                          v-model="leadItem.label"
                        />
                        <input
                          v-else
                          type="text"
                          required
                          name="label"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Sales Manager </label>
                        <select
                          name="sales_id"
                          required
                          class="form-control"
                          v-if="leadItem.sales_id"
                        >
                          <option
                            v-for="sale in salesManager"
                            :key="sale.id"
                            :value="sale.id"
                            :selected="
                              sale.id == leadItem.saled_id ? true : false
                            "
                          >
                            {{ sale.nom }}
                          </option>
                        </select>
                        <select
                          name="sales_id"
                          required
                          class="form-control"
                          v-else
                        >
                          <option
                            v-for="sale in salesManager"
                            :key="sale.id"
                            :value="sale.id"
                          >
                            {{ sale.nom }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Date </label>
                        <input
                          v-if="leadItem.date_item"
                          type="datetime-local"
                          class="form-control"
                          name="date"
                          v-model="leadItem.date_item"
                          required
                        />
                        <input
                          v-else
                          type="datetime-local"
                          class="form-control"
                          name="date"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Duration </label>
                        <input
                          v-if="leadItem.duration"
                          type="text"
                          class="form-control"
                          name="duration"
                          v-model="leadItem.duration"
                        />
                        <input
                          v-else
                          type="text"
                          class="form-control"
                          name="duration"
                        />
                      </div>
                    </div>
 

                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Nature </label>
                        <select name="nature" class="form-control">
                          <option
                            value="presentation-commercial"
                            :selected="
                              leadItem.nature == 'presentation-commercial'
                                ? true
                                : false
                            "
                          >
                            Présentation commerciale
                          </option>
                          <option
                            value="accompagnement"
                            :selected="
                              leadItem.nature == 'accompagnement' ? true : false
                            "
                          >
                            accompagnement
                          </option>
                          <option
                            value="formation"
                            :selected="
                              leadItem.nature == 'formation' ? true : false
                            "
                          >
                            Formation
                          </option>
                          <option
                            value="meeting"
                            :selected="
                              leadItem.nature == 'meeting' ? true : false
                            "
                          >
                            Meeting
                          </option>
                          <option
                            value="appel"
                            :selected="
                              leadItem.nature == 'appel' ? true : false
                            "
                          >
                            Appel
                          </option>

                          <option
                            value="rencontre"
                            :selected="
                              leadItem.nature == 'rencontre' ? true : false
                            "
                          >
                            Rencontre
                          </option>
                          <option
                            value="envoi-de-contrat"
                            :selected="
                              leadItem.nature == 'envoi-de-contrat'
                                ? true
                                : false
                            "
                          >
                            Envoi de Contrat
                          </option>
                          <option
                            value="signature-de-contrat"
                            :selected="
                              leadItem.nature == 'signature-de-contrat'
                                ? true
                                : false
                            "
                          >
                            Signature de contrat
                          </option>
                          <option
                            value="envoi-facture"
                            :selected="
                              leadItem.nature == 'envoi-facture' ? true : false
                            "
                          >
                            Envoi Facture
                          </option>
                          <option
                            value="paiement"
                            :selected="
                              leadItem.nature == 'paiement' ? true : false
                            "
                          >
                            Paiement
                          </option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Channel </label>
                        <select name="channel" class="form-control">
                          <option
                            value="online"
                            :selected="
                              leadItem.channel == 'online' ? true : false
                            "
                          >
                            Online
                          </option>
                          <option
                            value="presentiel"
                            :selected="
                              leadItem.channel == 'presentiel' ? true : false
                            "
                          >
                            Présentiel
                          </option>
                        </select>
                      </div>
                      <input
                        type="hidden"
                        name="lead_id"
                        v-model="leadItem.id"
                      />
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select name="status" class="form-control">
                          <option
                            value="programmé"
                            :selected="
                              leadItem.status == 'programmé' ? true : false
                            "
                          >
                            Programmé
                          </option>
                          <option
                            value="en-attente"
                            :selected="
                              leadItem.status == 'en-attente' ? true : false
                            "
                          >
                            En attente
                          </option>
                          <option
                            value="annulé"
                            :selected="
                              leadItem.status == 'annulé' ? true : false
                            "
                          >
                            Annulé
                          </option>
                          <option
                            value="réalisé"
                            :selected="
                              leadItem.status == 'réalisé' ? true : false
                            "
                          >
                            Réalisé
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <vue-editor
                        v-if="leadItem.details"
                        v-model="leadItem.details"
                      ></vue-editor>
                      <vue-editor
                        v-else
                        id=""
                        v-model="detailsLead"
                      ></vue-editor>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  class="btn btn-secondary"
                  @click="
                    showModalAddInterventionLead = !showModalAddInterventionLead
                  "
                >
                  Close
                </button>
                <button class="btn btn-primary" type="submit">
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
import vSelect from "vue-select";
import { VueEditor } from "vue2-editor";

import { fr } from "vuejs-datepicker/dist/locale";
// import LineChart from "../../../components/charts/LineChart.vue"

export default {
  components: {
    Datepicker,
    "vue-select": vSelect,
    "vue-editor": VueEditor,

    // "line-chart" : LineChar
  },
  data() {
    return {
      showModalAddInterventionLead: false,
      detailsLead: null,
      lead: {},
      contacts: [],
      localfr: fr,
      dateStatistique: new Date(),
      dataNotification: {},
      dataLogin: {},
      chartOption: {
        tension: 0.3,
      },
      contact: {
        id: null,
        name: "",
        last_name: "",
        email: "",
        phone: "",
        function: "",
      },
      showModalAddContact: false,
      salesManager: [],
      leads_interventions: [],
      leadItem: [],
      states: [],
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
            if (result.data.res) {
              this.$router.push({ path: `/schools/view/${result.data.res}` });
            } else {
              this.lead = result.data;
              this.states = result.data.types;
            }
          })
          .catch(function (err) {
            this.$router.push("/leads");
          });
      }
    },
    getLeadContacts: async function (leadId) {
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
          .catch(function (err) {});
      }
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
    },
    initNotificationChart: function () {
      let gradient = this.$refs.chartNotifications.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient.addColorStop(0, "#2cd9c540");
      gradient.addColorStop(0.3, "#2cd9c520");
      gradient.addColorStop(0.6, "#2cd9c500");

      let gradient1 = this.$refs.chartNotifications.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient1.addColorStop(0, "#6c5ffc40");
      gradient1.addColorStop(0.3, "#6c5ffc20");
      gradient1.addColorStop(0.6, "#6c5ffc00");

      let gradient2 = this.$refs.chartNotifications.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient2.addColorStop(0, "#d62d9940");
      gradient2.addColorStop(0.5, "#d62d9920");
      gradient2.addColorStop(1, "#d62d9900");

      let gradient3 = this.$refs.chartNotifications.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient3.addColorStop(0, "#ffc75e40");
      gradient3.addColorStop(0.3, "#ffc75e20");
      gradient3.addColorStop(0.6, "#ffc75e00");

      this.dataNotification = {
        labels: [
          "01/03",
          "02/03",
          "03/03",
          "04/03",
          "05/03",
          "06/03",
          "07/03",
          "08/03",
          "09/03",
          "10/03",
        ],
        datasets: [
          {
            label: "Élèves",
            backgroundColor: gradient,
            borderColor: "#2cd9c5",
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
            ],
          },
          {
            label: "Parents",
            backgroundColor: gradient1,
            borderColor: "#6c5ffc",
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
            ],
          },
          {
            label: "Enseignants",
            backgroundColor: gradient2,
            borderColor: "#d62d99",
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
            ],
          },
          {
            label: "Collaborateurs",
            backgroundColor: gradient3,
            borderColor: "#ffc75e",
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
            ],
          },
        ],
      };
    },
    initLoginChart: function () {
      let gradient = this.$refs.chartLogin.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient.addColorStop(0, "#2cd9c540");
      gradient.addColorStop(0.3, "#2cd9c520");
      gradient.addColorStop(0.6, "#2cd9c500");

      let gradient1 = this.$refs.chartLogin.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient1.addColorStop(0, "#6c5ffc40");
      gradient1.addColorStop(0.3, "#6c5ffc20");
      gradient1.addColorStop(0.6, "#6c5ffc00");

      let gradient2 = this.$refs.chartLogin.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient2.addColorStop(0, "#d62d9940");
      gradient2.addColorStop(0.5, "#d62d9920");
      gradient2.addColorStop(1, "#d62d9900");

      let gradient3 = this.$refs.chartLogin.$refs.canvas
        .getContext("2d")
        .createLinearGradient(0, 0, 0, 450);
      gradient3.addColorStop(0, "#ffc75e40");
      gradient3.addColorStop(0.3, "#ffc75e20");
      gradient3.addColorStop(0.6, "#ffc75e00");

      this.dataLogin = {
        labels: [
          "01/03",
          "02/03",
          "03/03",
          "04/03",
          "05/03",
          "06/03",
          "07/03",
          "08/03",
          "09/03",
          "10/03",
        ],
        datasets: [
          {
            label: "Élèves",
            backgroundColor: gradient,
            borderColor: "#2cd9c5",
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
            ],
          },
          {
            label: "Parents",
            backgroundColor: gradient1,
            borderColor: "#6c5ffc",
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
            ],
          },
          {
            label: "Enseignants",
            backgroundColor: gradient2,
            borderColor: "#d62d99",
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
            ],
          },
          {
            label: "Collaborateurs",
            backgroundColor: gradient3,
            borderColor: "#ffc75e",
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
            ],
          },
        ],
      };
    },
    initCharts: function () {
      this.initNotificationChart();
      this.initLoginChart();
    },
    addContact: async function (e) {
      e.preventDefault();

      const token = localStorage.getItem("auth-token");
      if (this.lead.id && token) {
        let form = e.target;
        let formdata = new FormData(form);
        await axios
          .post("/api/saveLeadContact/" + this.lead.id, formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.contacts = result.data;
            form.reset();
            this.showModalAddContact = !this.showModalAddContact;
          })
          .catch(function (err) {});
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
    showAddTicket: function () {
      this.$parent.$refs.btnAddTicket.click();
    },
    // showAddInterventionLead: function () {
    //   this.$parent.$refs.btnAddIntervention.click();
    // },
    showEditContact: async function (contactId) {
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
          .catch(function (err) {});
      }
    },
    clearForm: function (e) {
      this.contact = {
        id: null,
        name: "",
        last_name: "",
        email: "",
        phone: "",
        function: "",
      };
    },
    getSalesManager: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSalesManagers", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.salesManager = result.data;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    showModalLead() {
      this.showModalAddInterventionLead = true;
      this.leadItem = [];
    },
    addInterventionLead: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        if (this.leadItem.id) {
          formdata.append("details", this.leadItem.details);
          formdata.append("id", this.leadItem.id);
        } else {
          formdata.append("details", this.detailsLead);
        }
        formdata.append("lead_id", this.$route.params.id);
        await axios
          .post(
            this.leadItem.id
              ? "/api/new-intervention-lead/" + this.leadItem.id
              : "/api/new-intervention-lead",
            formdata,
            {
              headers: {
                Authorization: "Bearer " + token,
              },
            }
          )
          .then(async (result) => {
            if (this.leadItem.id) {
              this.leads_interventions.forEach((lead) => {
                if (lead.id == result.data.id) {
                  lead.channel = result.data.channel;
                  lead.type = result.data.type;
                  lead.date.day = result.data.date.day;
                  lead.date.month = result.data.date.month;
                  lead.sales = result.data.sales;
                  lead.nature = result.data.nature;
                  lead.status = result.data.status;
                  lead.duration = result.data.duration;
                }
              });
            } else {
              this.leads_interventions.push(result.data);
            }
            form.reset();
            this.showModalAddInterventionLead =
              !this.showModalAddInterventionLead;
            // this.$emit("intervention-added");
          })
          .catch(function (err) {});
      }
    },
    getLeads(id) {
      const token = localStorage.getItem("auth-token");
      console.log(id);
      if (token && id) {
        axios
          .get("/api/intervention-leads/" + id, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result.data, id);
            this.leads_interventions = result.data;
          });
      }
    },
    getIntervention(intervention) {
      console.log(intervention);
      this.showModalAddInterventionLead = true;
      this.leadItem = intervention;
      console.log(this.leadItem);
    },
    changeState(event) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formdata = new FormData();
        formdata.append("id", this.$route.params.id);
        formdata.append("state", event.target.value);
        this.$swal({
          title: "Vous êtes sûr de changer la prospection de cette lead",
          icon: "primary",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `oui`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post("/api/changeStateLead", formdata, {
                headers: {
                  Authorization: "Bearer " + token,
                },
              })
              .then((result) => {
                console.log(result.data.data);
                this.lead.state = result.data.data;
              })
              .catch(function (err) {
                console.log(token);
              });
          } else {
            let options = event.target.options;
            for (let i = 0; i < options.length; i++) {
              options[i].value == this.lead.state
                ? (options[i].selected = true)
                : (options[i].selected = false);
            }
          }
        });
      }
    },
    natureContrat(event) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formdata = new FormData();
        formdata.append("id", this.$route.params.id);
        formdata.append("state", event.target.value);
        this.$swal({
          title: "Valider la nature du contrat",
          icon: "primary",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `oui`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post("/api/nature_contrat", formdata, {
                headers: {
                  Authorization: "Bearer " + token,
                },
              })
              .then((result) => {
                console.log(result.data.data);
                this.lead.state = result.data.data;
              })
              .catch(function (err) {
                console.log(token);
              });
          } else {
            let radios = document.querySelectorAll('.radio-elements');
            radios.forEach( function(element) {
               console.log(element);
                event.target.value != element.value ? element.checked = true :  element.checked = false;
            });
          }
        });
      }
    },
    toSchool() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formData = new FormData();
        formData.append("lead_id", this.lead.id);
        axios
          .post("/api/lead_to_school", formData, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.$router.push({ path: `/schools/view/${result.data}` });
          });
      }
    },
    deleteLead(id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de Supprimer  cette lead ?",
          icon: "primary",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `oui`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post(
                "/api/deletes/lead/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.$router.back();
              })
              .catch(function (err) {
                console.log(token);
              });
          }
        });
      }
    },
  },
  mounted() {
    this.getLead(this.$route.params.id);
    this.getLeadContacts(this.$route.params.id);
    this.getSalesManager();
    this.getLeads(this.$route.params.id);
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
.princing-item {
  height: 100%;
  border-radius: 10px;
}
</style>