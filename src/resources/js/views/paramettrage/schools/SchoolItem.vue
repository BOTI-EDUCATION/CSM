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
                    <div class="actions col-md-12 mb-3 text-right" style="">
                      <a
                        @click="deleteSchool($event, school.id)"
                        href=""
                        class="btn btn-danger rounded-circle text-white"
                      >
                        <i class="fa-solid fa-trash-can"></i>
                      </a>

                      <a
                        @click.prevent="hideSchool($event, school.id)"
                        href=""
                        class="btn btn-warning text-dark rounded-circle"
                      >
                        <!-- <i class="fe fe-eye" style="font-size:16px"></i> -->
                        <i class="fa-sharp fa-solid fa-eye-slash"></i>
                      </a>
                    </div>

                    <div
                      class="profile-cover__action bg-img"
                      :style="
                        'background-image: url(\'' +
                        (school.banner ? school.banner : school.logo) +
                        '\')'
                      "
                    ></div>
                    <div class="profile-cover__img">
                      <div class="profile-img-1">
                        <img :src="school.logo" alt="img" />
                        <!-- <div class="tag tag-rounded tag-primary text-nowrap">
                          \'' +
                        (school.banner ? school.banner : school.logo) +
                        '\'
                          ami
                        </div> -->
                      </div>
                      <div
                        class="profile-img-content text-dark text-start w-100"
                      >
                        <div class="text-dark row w-100">
                          <div class="col-12 col-md-9">
                            <h3 class="h3 mb-2">
                              {{ school.name }}
                              <router-link
                                :to="'/schools/edit/' + school.id"
                                class="btn btn-link"
                                ><i class="fa fa-edit"></i
                              ></router-link>
                              <!-- <a
                                @click="deleteSchool($event, school.id)"
                                class="btn btn-link p-1 text-muted"
                                ><i class="fa fa-trash"></i
                              ></a> -->
                            </h3>
                            <h5 class="text-muted">
                              {{ school.presentation }}
                            </h5>
                          </div>
                          <div class="col-12 col-md-3 text-right">
                            <div
                              v-if="school.links"
                              class="social social-profile-buttons"
                            >
                              <div class="">
                                <a
                                  target="_blank"
                                  class="social-icon text-primary"
                                  v-if="school.links.facebook"
                                  :href="school.links.facebook"
                                  ><i class="fab fa-facebook"></i
                                ></a>
                                <a
                                  target="_blank"
                                  class="social-icon text-primary"
                                  v-if="school.links.instagram"
                                  :href="school.links.instagram"
                                  ><i class="fab fa-instagram"></i
                                ></a>
                                <a
                                  target="_blank"
                                  class="social-icon text-primary"
                                  v-if="school.links.youtube"
                                  :href="school.links.youtube"
                                  ><i class="fab fa-youtube"></i
                                ></a>
                                <a
                                  target="_blank"
                                  class="social-icon text-primary"
                                  v-if="school.links.website"
                                  :href="school.links.website"
                                  ><i class="fa fa-globe"></i
                                ></a>
                              </div>
                            </div>
                            <div class="app_versions text-center mt-3">
                              <ul
                                style="justify-content: flex-end"
                                class="unstyled-list d-flex"
                              >
                                <li
                                  v-if="
                                    school.version_android &&
                                    school.version_android != 'null'
                                  "
                                  class="me-3"
                                >
                                  <svg
                                    fill="#32de84"
                                    xmlns="http://www.w3.org/2000/svg"
                                    height="48"
                                    width="48"
                                  >
                                    <path
                                      d="M2 36.05q.4-5.3 3.25-9.825Q8.1 21.7 12.8 19.05L9.05 12.6q-.15-.45-.025-.9t.525-.7q.45-.25.975-.1.525.15.775.6l3.7 6.35Q19.3 16 24 16t9 1.85l3.75-6.35q.25-.45.775-.6.525-.15.975.1.4.25.575.725.175.475-.075.875l-3.8 6.45q4.7 2.65 7.55 7.175Q45.6 30.75 46 36.05Zm12-5.5q1 0 1.75-.75t.75-1.75q0-1-.75-1.75T14 25.55q-1 0-1.75.75t-.75 1.75q0 1 .75 1.75t1.75.75Zm20 0q1 0 1.75-.75t.75-1.75q0-1-.75-1.75T34 25.55q-1 0-1.75.75t-.75 1.75q0 1 .75 1.75t1.75.75Z"
                                    />
                                  </svg>
                                  <br />
                                  <span class="mt-2">{{
                                    school.version_android
                                  }}</span>
                                </li>
                                <li
                                  v-if="
                                    school.version_ios &&
                                    school.version_ios != 'null'
                                  "
                                  class="me-3"
                                >
                                  <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    version="1.1"
                                    id="Livello_1"
                                    x="0px"
                                    y="0px"
                                    width="48px"
                                    height="48px"
                                    viewBox="0 0 814 1000"
                                    enable-background="new 0 0 814 1000"
                                    xml:space="preserve"
                                  >
                                    <path
                                      d="M788.1,340.9c-5.8,4.5-108.2,62.2-108.2,190.5c0,148.4,130.3,200.9,134.2,202.2c-0.6,3.2-20.7,71.9-68.7,141.9  c-42.8,61.6-87.5,123.1-155.5,123.1s-85.5-39.5-164-39.5c-76.5,0-103.7,40.8-165.9,40.8s-105.6-57-155.5-127  C46.7,790.7,0,663,0,541.8c0-194.4,126.4-297.5,250.8-297.5c66.1,0,121.2,43.4,162.7,43.4c39.5,0,101.1-46,176.3-46  C618.3,241.7,720.7,244.3,788.1,340.9z M554.1,159.4c31.1-36.9,53.1-88.1,53.1-139.3c0-7.1-0.6-14.3-1.9-20.1  c-50.6,1.9-110.8,33.7-147.1,75.8c-28.5,32.4-55.1,83.6-55.1,135.5c0,7.8,1.3,15.6,1.9,18.1c3.2,0.6,8.4,1.3,13.6,1.3  C464,230.7,521.1,200.3,554.1,159.4z"
                                    />
                                  </svg>
                                  <br />
                                  <span class="mt-2">{{
                                    school.version_ios
                                  }}</span>
                                </li>
                                <li v-if="school.web_link" class="">
                                  <a
                                    target="_blank"
                                    :href="`https://boti.education/${school.web_link}/login`"
                                  >
                                    <img
                                      src="/csm/assets/images/brand/logo-small.png"
                                      alt=""
                                    />
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
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
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="card-title">Effectif et Eleves</div>
            </div>
            <div class="card-body d-grid">
              <!-- <h6>Effectif</h6> -->
              <div class="main-profile-contact-list d-flex">
                <div
                  v-if="
                    school.effectif_creche && school.effectif_creche != 'null'
                  "
                  class=""
                >
                  <div class="media d-flex mb-4">
                    <div class="media-icon bg-secondary bradius me-3 mt-1">
                      <i class="fe fe-edit fs-20 text-white"></i>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Crèche</span>
                      <div class="fw-semibold fs-25">
                        {{ school.effectif_creche }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="
                    school.effectif_maternelle &&
                    school.effectif_maternelle != 'null'
                  "
                  class="mt-5 mt-md-0"
                >
                  <div class="media d-flex mb-4">
                    <div class="media-icon bg-secondary bradius me-3 mt-1">
                      <i class="fe fe-edit fs-20 text-white"></i>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Maternelle</span>
                      <div class="fw-semibold fs-25">
                        {{ school.effectif_maternelle }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="
                    school.effectif_primaire &&
                    school.effectif_primaire != 'null'
                  "
                  class="mt-5 mt-md-0"
                >
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
                      <div class="fw-semibold fs-25">
                        {{ school.effectif_primaire }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="
                    school.effectif_college && school.effectif_college != 'null'
                  "
                  class="mt-5 mt-md-0"
                >
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
                      <div class="fw-semibold fs-25">
                        {{ school.effectif_college }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="
                    school.effectif_lycee && school.effectif_lycee != 'null'
                  "
                  class="mt-5 mt-md-0"
                >
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
                      <div class="fw-semibold fs-25">
                        {{ school.effectif_lycee }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="school.effectif_sup && school.effectif_sup != 'null'"
                  class="mt-5 mt-md-0"
                >
                  <div class="media">
                    <div
                      class="media-icon bg-info text-white bradius me-3 mt-1"
                    >
                      <span class="mt-3">
                        <i class="fe fe-cast fs-20"></i>
                      </span>
                    </div>
                    <div class="media-body">
                      <span class="text-muted">Supérieure</span>
                      <div class="fw-semibold fs-25">
                        {{ school.effectif_sup }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header">
              <div class="card-title">Checklists</div>
              <div class="card-options">
                <button
                  @click="showModalChecklist = true"
                  type="button"
                  class="btn btn-icon p-1 btn-success"
                >
                  <i class="fe fe-plus"></i>
                </button>
              </div>
            </div>
            <div
              class="card-body"
              v-if="schoolChecklists && schoolChecklists.length > 0"
            >
              <div
                class="card card-collapsed borders"
                v-for="checklist in schoolChecklists"
                :key="checklist.id"
              >
                <a
                  href="javascript:void(0)"
                  class="card-options-collapse card-header br-te-3 br-ts-3 br-be-3 br-bs-3"
                  data-bs-toggle="card-collapse"
                >
                  <div class="card-title w-100">
                    <h3 class="text-dark h4">
                      {{ checklist.label }}

                      <a
                        href="javascript:void(0)"
                        :class="`ms-4 text-danger h5`"
                        style="float: right"
                        @click.stop="deleteChecklist($event, checklist)"
                      >
                        <span class="fe fe-trash"></span>
                      </a>

                      <span
                        :class="`ms-auto text-${checklist.pourcentageClass} h5`"
                        style="float: right"
                        >{{ checklist.pourcentage }} %</span
                      >
                    </h3>
                    <div class="progress h-2 mt-2 mb-2">
                      <div
                        :class="`progress-bar bg-${checklist.pourcentageClass}`"
                        :style="`width: ${checklist.pourcentage}%`"
                        role="progressbar"
                      ></div>
                    </div>
                  </div>
                </a>
                <div class="card-body" v-if="checklist.items.length > 0">
                  <label
                    class="custom-control custom-checkbox-md"
                    v-for="item in checklist.items"
                    :key="item.id"
                  >
                    <input
                      type="checkbox"
                      class="custom-control-input"
                      :value="item.id"
                      v-model="item.state.done"
                      @change="updateStateCheckItem(item)"
                    />
                    <span class="custom-control-label">{{ item.label }}</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="card-body text-center" v-else>
              <h4>No checklists added !</h4>
            </div>
          </div>
        </div>

        <!-- EDIT -->
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
                    <a class="fw-semibold text-dark">
                      {{ contact.name }} {{ contact.last_name }}
                      <a @click="showContact(contact.id)" class="btn btn-link"
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
    </div>
    <!-- COL-END -->
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Tickets</div>
            <div class="card-options">
              <button
                @click="showAddTicket"
                type="button"
                class="btn btn-icon btn-success"
              >
                <i class="fe fe-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <table class="table border vertical-align-middle table-hover mb-0">
              <tbody v-if="tickets">
                <tr v-for="ticket in tickets" :key="ticket.id">
                  <td class="text-center">
                    <!-- <i class="fa fa-3x text-success fa-check-circle mb-2"></i>
                    <br> -->
                    <span
                      class="tag tag-sm tag-rounded tag-success bg-success text-nowrap"
                      >{{ ticket.nature }}</span
                    >
                  </td>
                  <td>
                    <h5>{{ ticket.label }}</h5>
                    <span v-html="ticket.details"></span>
                  </td>
                  <td class="text-center">
                    <span
                      class="avatar avatar-xl brround cover-image"
                      :style="
                        'background: url(\'' +
                        ticket.responsable.img +
                        '\') center center;'
                      "
                    ></span>
                  </td>
                  <td class="text-center">
                    <span class="tag tag-rounded tag-primary text-nowrap">{{
                      ticket.genre
                    }}</span>
                  </td>
                  <!-- <td  class="text-center">
                    <button
                      type="button"
                      class="btn btn-icon btn-primary-light"
                    >
                      <i class="fe fe-eye"></i>
                    </button>
                  </td> -->
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td class="text-center">
                    <p class="text-muted">Aucun Ticket</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Planning</div>
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
            <table class="table border vertical-align-middle table-hover mb-0">
              <tbody v-if="interventions.length > 0">
                <tr
                  v-for="intervention in interventions"
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
                    <h5>
                      {{ intervention.label }}
                    </h5>
                    <span v-html="intervention.details"></span>
                  </td>
                  <td style="width: 25%">
                    <span class="tag tag-rounded tag-primary text-nowrap">{{
                      intervention.nature
                    }}</span>
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
    <transition
      @enter="startTransitionModal($refs.modalAddContact)"
      @after-enter="endTransitionModal($refs.modalAddContact)"
      @before-leave="endTransitionModal($refs.modalAddContact)"
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

    <transition
      @enter="startTransitionModal($refs.modalChecklist)"
      @after-enter="endTransitionModal($refs.modalChecklist)"
      @before-leave="endTransitionModal($refs.modalChecklist)"
      @after-leave="startTransitionModal($refs.modalChecklist)"
    >
      <div class="modal fade" v-if="showModalChecklist" ref="modalChecklist">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="addChecklist" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Ajouter une checklist
                </h5>
                <a class="close btn" @click="showModalChecklist = false">
                  <span aria-hidden="true">×</span>
                </a>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6 border-right">
                      <div class="form-group">
                        <label class="form-label"> Checklist </label>
                        <vue-select
                          class=""
                          name="checklist"
                          label="label"
                          :options="checklists"
                          v-model="selectedAddChecklists"
                          placeholder="Checklist"
                          searchable
                        >
                        </vue-select>
                      </div>
                    </div>
                    <div
                      class="col-md-6"
                      v-if="
                        selectedAddChecklists && selectedAddChecklists.items
                      "
                    >
                      <label
                        class="custom-control custom-checkbox-md"
                        v-for="item in selectedAddChecklists.items"
                        :key="item.id"
                      >
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          :value="item.id"
                          checked
                          disabled
                        />
                        <span class="custom-control-label">{{
                          item.label
                        }}</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="showModalChecklist = false"
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

    <!-- MODAL EDIT CONTACT -->

    <transition
      @enter="startTransitionModal($refs.modal)"
      @after-enter="endTransitionModal($refs.modal)"
      @before-leave="endTransitionModal($refs.modal)"
      @after-leave="startTransitionModal($refs.modal)"
    >
      <div class="modal fade" v-if="modalStatus" ref="modal">
        <div v-bind:class="{ 'modal-lg': modalLarge, 'modal-dialog': true }">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Edit contact:
                <b>{{ contactInfo.name }} {{ contactInfo.last_name }}</b>
              </h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <form @submit="updateContact" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Nom </label>
                        <input
                          type="text"
                          name="name"
                          v-model="contactInfo.name"
                          class="form-control"
                        />
                        <input
                          type="hidden"
                          name="schoolId"
                          v-model="contactInfo.schoolId"
                        />
                        <input
                          type="hidden"
                          name="id"
                          v-model="contactInfo.id"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Prénom </label>
                        <input
                          type="text"
                          name="last_name"
                          v-model="contactInfo.last_name"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Email </label>
                        <input
                          type="email"
                          name="email"
                          v-model="contactInfo.email"
                          class="form-control"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Telephone </label>
                        <input
                          type="number"
                          name="phone"
                          v-model="contactInfo.phone"
                          class="form-control"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Fonction </label>
                        <input
                          type="text"
                          name="function"
                          v-model="contactInfo.fonction"
                          class="form-control"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label class="form-label"> Image </label>
                        <input
                          type="file"
                          name="picture"
                          @change="onFile"
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="modalStatus = !modalStatus"
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
  </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import { fr } from "vuejs-datepicker/dist/locale";
// import LineChart from "../../../components/charts/LineChart.vue"
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import axios from "axios";

export default {
  components: {
    Datepicker,
    "vue-select": vSelect,
    // "line-chart" : LineChart
  },
  data() {
    return {
      imgSrc: "",
      contactInfo: {
        id: null,
        name: "",
        last_name: "",
        email: "",
        phone: "",
        fonction: "",
        schoolId: null,
      },
      modalStatus: false,
      school: {},
      contacts: [],
      checklists: [],
      schoolChecklists: [],
      localfr: fr,
      dateStatistique: new Date(),
      dataNotification: {},
      dataLogin: {},
      chartOption: {
        tension: 0.3,
      },
      tickets: [],
      interventions: [],
      contact: {
        id: null,
        name: "",
        last_name: "",
        email: "",
        phone: "",
        function: "",
      },
      showModalChecklist: false,
      selectedAddChecklists: null,
      showModalAddContact: false,
    };
  },
  methods: {
    getSchool: async function (schoolId) {
      const token = localStorage.getItem("auth-token");
      if (schoolId && token) {
        await axios
          .get("/api/getSchoolFormInfo/" + schoolId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.school = result.data;
          })
          .catch(function (err) {
            this.$router.push("/schools");
          });
      }
    },
    getSchoolContacts: async function (schoolId) {
      const token = localStorage.getItem("auth-token");
      if (schoolId && token) {
        await axios
          .get("/api/getSchoolContacts/" + schoolId, {
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
    getSchoolIntervention: async function (schoolId) {
      const token = localStorage.getItem("auth-token");
      if (schoolId && token) {
        await axios
          .get("/api/getSchoolIntervention/" + schoolId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.interventions = result.data;
          })
          .catch(function (err) {});
      }
    },
    getSchoolTickets: async function (schoolId) {
      const token = localStorage.getItem("auth-token");
      if (schoolId && token) {
        await axios
          .get("/api/getSchoolTickets/" + schoolId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.tickets = result.data;
          })
          .catch(function (err) {});
      }
    },
    addContact: async function (e) {
      e.preventDefault();

      const token = localStorage.getItem("auth-token");
      if (this.school.id && token) {
        let form = e.target;
        let formdata = new FormData(form);
        await axios
          .post("/api/saveSchoolContact/" + this.school.id, formdata, {
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
    showAddIntervention: function () {
      this.$parent.$refs.btnAddIntervention.click();
    },

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

    getChecklists: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getChecklists", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.checklists = result.data;
          })
          .catch(function (err) {});
      }
    },

    getSchoolChecklists: async function (schoolId) {
      const token = localStorage.getItem("auth-token");
      if (schoolId && token) {
        await axios
          .get("/api/getSchoolchecklists/" + schoolId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.schoolChecklists = result.data;
          })
          .catch(function (err) {});
      }
    },

    addChecklist: async function (e) {
      e.preventDefault();

      const token = localStorage.getItem("auth-token");

      if (!this.selectedAddChecklists) {
        this.$swal({
          title: "Veuillez choisir une checklist !",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: false,
          denyButtonText: `Okey`,
        }).then(async (result) => {});
        return false;
      }

      if (this.school.id && token) {
        let form = e.target;
        let formdata = new FormData();

        form.querySelector('button[type="submit"]').disabled = true;

        formdata.append("checklist", this.selectedAddChecklists.id);
        await axios
          .post("/api/saveSchoolChecklist/" + this.school.id, formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.schoolChecklists = result.data;
            // e.target.querySelector('button').disabled = false;
            form.reset();
            this.showModalChecklist = false;
          })
          .catch(function (err) {});
      }
    },

    updateStateCheckItem: async function (item) {
      const token = localStorage.getItem("auth-token");
      if (this.school.id && token) {
        let formdata = new FormData();

        formdata.append("item", item.state.idState);
        formdata.append("state", item.state.done ? 1 : 0);

        await axios
          .post("/api/updateStateCheckItem", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.schoolChecklists = result.data;
          })
          .catch(function (err) {});
      }
    },

    deleteSchool: async function (e, id) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer cette école",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Supprimer`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post(
                "/api/deleteSchool/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.$router.push("/schools");
              })
              .catch(function (err) {
                console.log(token);
              });
          }
        });
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },

    hideSchool: async function (e, id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de vouloir désafficher cette école",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Désafficher`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          console.log(result);
          if (result.isDenied) {
            await axios
              .post(
                "api/disable/school/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.$router.push("/schools");
              })
              .catch(function (err) {
                console.log(token);
              });
          }
        });
      }
    },

    deleteChecklist: async function (e, checklist) {
      e.preventDefault();
      e.stopPropagation();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let school = this.school.id;
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer cette checklist",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Supprimer`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post(
                "/api/deleteChecklistSchool",
                {
                  checklist: checklist.id,
                  school: school,
                },
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.schoolChecklists = result.data;
              })
              .catch(function (err) {
                console.log(token);
              });
          }
        });
      } else {
        // localStorage.removeItem("auth-token");
        // this.$router.push("/login");
      }
    },

    showContact(id) {
      this.modalStatus = true;
      this.contacts.forEach((contact) => {
        if (id == contact.id) {
          this.contactInfo.schoolId = this.school.id;
          this.contactInfo.id = id;
          this.contactInfo.name = contact.name;
          this.contactInfo.last_name = contact.last_name;
          this.contactInfo.email = contact.email;
          this.contactInfo.phone = contact.phone;
          this.contactInfo.fonction = contact.fonction;
        }
      });
    },
    updateContact: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        await axios
          .post("/api/updateSchoolContact", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.contacts.forEach((contact) => {
              if (this.contactInfo.id == contact.id) {
                contact.name = this.contactInfo.name;
                contact.last_name = this.contactInfo.last_name;
                contact.email = this.contactInfo.email;
                contact.phone = this.contactInfo.phone;
                contact.fonction = this.contactInfo.fonction;
              }
            });
            // form.reset();
            this.modalStatus = !this.modalStatus;
            this.endTransitionModal(this.$refs.modal);
          })
          .catch(function (err) {});
      }
    },
    onFile(e) {
      const files = e.target.files;
      const reader = new FileReader();
      reader.readAsDataURL(files[0]);
      this.contacts.forEach((contact) => {
        if (contact.id == this.contactInfo.id) {
          reader.onload = () => (contact.picture = reader.result);
        }
      });
    },
  },
  mounted() {
    this.getSchool(this.$route.params.id);
    this.getSchoolContacts(this.$route.params.id);
    this.getSchoolIntervention(this.$route.params.id);
    this.getSchoolTickets(this.$route.params.id);
    this.getSchoolChecklists(this.$route.params.id);
    this.getChecklists();
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
  margin-top: -30px;
  background: #ffffff;
}
.profile-cover__img .profile-img-1 > div {
  /* position: absolute; */
  /* bottom: 0;
  left: 0; */
  right: 0;
  width: auto;
  margin: auto;
  /* margin-top: -10px; */
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
.profile-cover__img {
  display: flex;
  border-radius: 11px;
  color: #fff;
  position: unset;
  left: unset;
  top: -90px;
  text-align: center;
  z-index: 1;
}

.modal {
  z-index: 1059;
}

.w-100 {
  width: 100%;
}

.profile-cover__img .profile-img-content {
  margin-top: 20px;
}
</style>