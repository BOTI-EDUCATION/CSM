<template>
  <div class="page">
    <div class="page-main">
      <!-- app-Header -->
      <div class="app-header header fixed-header visible-title">
        <div class="container-fluid main-container">
          <div class="d-flex">
            <a
              aria-label="Hide Sidebar"
              class="app-sidebar__toggle"
              @click="toggleSideBar"
              href="javascript:void(0)"
            ></a>
            <!-- sidebar-toggle-->
            <router-link exact class="logo-horizontal" to="/">
              <img
                src="/csm/assets/images/brand/logo.png"
                class="header-brand-img desktop-logo"
                alt="logo"
              />
            </router-link>
            <!-- LOGO -->
            <!-- <div class="main-header-center ms-3 d-none d-lg-block">
              <input
                class="form-control"
                placeholder="Search for results..."
                type="search"
              />
              <button class="btn px-0 pt-2">
                <i class="fe fe-search" aria-hidden="true"></i>
              </button>
            </div> -->
            <div class="d-flex order-lg-2 ms-auto header-right-icons">
              <div class="dropdown d-none">
                <a
                  href="javascript:void(0)"
                  class="nav-link icon"
                  data-bs-toggle="dropdown"
                >
                  <i class="fe fe-search"></i>
                </a>
                <div class="dropdown-menu header-search dropdown-menu-start">
                  <div class="input-group w-100 p-2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Search...."
                    />
                    <div class="input-group-text btn btn-primary">
                      <i class="fe fe-search" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- SEARCH -->
              <router-link class="nav-link icon" to="/notifications">
                <i class="fa fa-bell"></i>
              </router-link>
              <button
                class="navbar-toggler navresponsive-toggler d-lg-none ms-auto"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent-4"
                aria-controls="navbarSupportedContent-4"
                aria-expanded="false"
                aria-label="Toggle navigation"
              >
                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
              </button>
              <div class="navbar navbar-collapse responsive-navbar p-0">
                <div
                  class="collapse navbar-collapse"
                  id="navbarSupportedContent-4"
                >
                  <div class="d-flex order-lg-2">
                    <div class="dropdown d-lg-none d-flex">
                      <a
                        href="javascript:void(0)"
                        class="nav-link icon"
                        data-bs-toggle="dropdown"
                      >
                        <i class="fe fe-search"></i>
                      </a>
                      <div
                        class="dropdown-menu header-search dropdown-menu-start"
                      >
                        <div class="input-group w-100 p-2">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Search...."
                          />
                          <div class="input-group-text btn btn-primary">
                            <i class="fa fa-search" aria-hidden="true"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- SEARCH -->

                    <div class="dropdown d-flex notifications">
                      <a class="nav-link icon" data-bs-toggle="dropdown"
                        ><i class="fa fa-receipt"></i>
                        <!-- <span class="pulse"></span> -->
                      </a>
                      <div
                        class="
                          dropdown-menu dropdown-menu-end dropdown-menu-arrow
                        "
                      >
                        <div class="drop-heading border-bottom">
                          <div class="d-flex">
                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">
                              Tickets
                            </h6>
                            <!-- <div class="ms-auto">
                              <button class="btn btn-link"><i class="fa fa-plus"></i></button>
                            </div> -->
                            <div class="ms-auto">
                              <a
                                ref="btnAddTicket"
                                @click="
                                  showModalAddTicket = !showModalAddTicket
                                "
                                href="javascript:void(0)"
                                class="text-white bg-success px-2 py-1 bradius"
                                ><i class="fa fa-plus"></i
                              ></a>
                            </div>
                          </div>
                        </div>
                        <div
                          v-if="tickets.length > 0"
                          class="notifications-menu"
                          style="max-height: 400px; overflow-y: scroll"
                        >
                          <template v-for="ticket in tickets">
                            <router-link
                              v-if="ticket.school"
                              :key="ticket.id"
                              class="dropdown-item d-flex"
                              :to="'schools/view/' + ticket.school.id"
                            >
                              <div
                                class="
                                  me-3
                                  notifyimg
                                  brround
                                  box-shadow-primary
                                "
                              >
                                <img :src="ticket.school.img" alt="" />
                              </div>
                              <div class="mt-1">
                                <h5 class="notification-label mb-1">
                                  {{ ticket.label }}
                                </h5>
                                <span class="notification-subtext">{{
                                  ticket.date
                                }}</span>
                              </div>
                            </router-link>
                          </template>
                        </div>
                        <div v-else class="notifications-menu text-center">
                          <div class="dropdown-item text-muted">
                            Aucun Ticket
                          </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <router-link
                          to="/ticket"
                          class="dropdown-item text-center p-3 text-muted"
                          >View all Tickets</router-link
                        >
                      </div>
                    </div>

                    <div class="dropdown d-flex notifications">
                      <a class="nav-link icon" data-bs-toggle="dropdown"
                        ><i class="fa fa-calendar"></i>
                        <!-- <span class="pulse"></span> -->
                      </a>
                      <div
                        class="
                          dropdown-menu dropdown-menu-end dropdown-menu-arrow
                        "
                      >
                        <div class="drop-heading border-bottom">
                          <div class="d-flex">
                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">
                              Interventions
                            </h6>
                            <!-- <div class="ms-auto">
                              <button class="btn btn-link"><i class="fa fa-plus"></i></button>
                            </div> -->
                            <div class="ms-auto">
                              <a
                                ref="btnAddIntervention"
                                @click="
                                  showModalAddIntervention =
                                    !showModalAddIntervention
                                "
                                href="javascript:void(0)"
                                class="text-white bg-success px-2 py-1 bradius"
                                ><i class="fa fa-plus"></i
                              ></a>
                            </div>
                          </div>
                        </div>
                        <div
                          v-if="interventions.length > 0"
                          class="notifications-menu"
                          style="max-height: 400px; overflow-y: scroll"
                        >
                          <template v-for="intervention in interventions">
                            <router-link
                              v-if="intervention.school"
                              :key="intervention.id"
                              class="dropdown-item d-flex"
                              :to="'schools/view/' + intervention.school.id"
                            >
                              <div
                                class="
                                  me-3
                                  notifyimg
                                  brround
                                  box-shadow-primary
                                "
                              >
                                <img :src="intervention.school.img" alt="" />
                              </div>
                              <div class="mt-1">
                                <h5 class="notification-label mb-1">
                                  {{ intervention.label }}
                                </h5>
                                <span class="notification-subtext">{{
                                  intervention.date
                                }}</span>
                              </div>
                            </router-link>
                          </template>
                        </div>
                        <div v-else class="notifications-menu text-center">
                          <div class="dropdown-item text-muted">
                            Aucune Intervention Plannifiée
                          </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <!-- <a
                          href="notify-list.html"
                          class="dropdown-item text-center p-3 text-muted"
                          >View all Notification</a
                        > -->
                      </div>
                    </div>
                    <!-- MESSAGE-BOX -->
                    <div class="dropdown d-flex header-settings">
                      <a
                        href="javascript:void(0);"
                        class="nav-link icon"
                        data-bs-toggle="sidebar-right"
                        data-target=".sidebar-right"
                      >
                        <i class="fe fe-align-right"></i>
                      </a>
                    </div>
                    <!-- SIDE-MENU -->
                    <div class="dropdown d-flex profile-1">
                      <a
                        href="javascript:void(0)"
                        data-bs-toggle="dropdown"
                        class="nav-link leading-none d-flex"
                      >
                        <img
                          :src="user.img"
                          alt="profile-user"
                          class="avatar profile-user brround cover-image"
                          style="object-fit: cover"
                        />
                      </a>
                      <div
                        class="
                          dropdown-menu dropdown-menu-end dropdown-menu-arrow
                        "
                      >
                        <div class="drop-heading">
                          <div class="text-center">
                            <h5 class="text-dark mb-0 fs-14 fw-semibold">
                              {{ user.prenom }} {{ user.nom }}
                            </h5>
                            <small class="text-muted">{{ user.role }}</small>
                          </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>

                        <button
                          class="dropdown-item"
                          @click="showModalUser = true"
                        >
                          <i class="dropdown-icon fe fe-user"></i> Profile
                        </button>

                        <button class="dropdown-item" @click="logout">
                          <i class="dropdown-icon fe fe-alert-circle"></i>
                          Sign out
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /app-Header -->
      <!--APP-SIDEBAR-->
      <div class="sticky">
        <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
        <div class="app-sidebar ps ps--active-y">
          <div class="side-header">
            <router-link exact class="header-brand1" to="/">
              <img
                src="/csm/assets/images/brand/logo.png"
                class="header-brand-img desktop-logo"
                alt="logo"
              />
              <img
                src="/csm/assets/images/brand/logo.png"
                class="header-brand-img toggle-logo"
                alt="logo"
              />
              <img
                src="/csm/assets/images/brand/logo-small.png"
                class="header-brand-img light-logo"
                alt="logo"
              />
              <img
                src="/csm/assets/images/brand/logo.png"
                class="header-brand-img light-logo1"
                alt="logo"
              />
            </router-link>
            <!-- LOGO -->
          </div>
          <div class="main-sidemenu is-expanded">
            <div class="slide-left disabled active" id="slide-left">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="#7b8191"
                width="24"
                height="24"
                viewBox="0 0 24 24"
              >
                <path
                  d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"
                ></path>
              </svg>
            </div>
            <ul class="side-menu open" style="margin-right: 0px">
              <li class="sub-category"><h3>CSM</h3></li>
              <li class="slide">
                <router-link
                  exact
                  to="/"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-home"></i
                  ><span class="side-menu__label">Dashboard</span>
                </router-link>
              </li>
              <!-- <li class="slide">
                <router-link
                  exact
                  to="/dashboard"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-home"></i
                  ><span class="side-menu__label">Dashboard V2</span>
                </router-link>
              </li> -->
              <li class="sub-category"><h3>Gestion du relationnel</h3></li>
              <li class="slide">
                <router-link
                  exact
                  to="/demandes"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-file-text"></i
                  ><span class="side-menu__label">Requests</span>
                </router-link>
                <router-link
                  exact
                  to="/leads"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-filter"></i
                  ><span class="side-menu__label">Leads</span>
                </router-link>
                <router-link
                  exact
                  to="/checkup"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-check-circle"></i
                  ><span class="side-menu__label">Checkup</span>
                </router-link>
                <router-link
                  exact
                  to="/usagetracking"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-trending-up"></i
                  ><span class="side-menu__label">Usage Tracking</span>
                </router-link>
              </li>

              <li class="slide">
                <a
                  class="side-menu__item"
                  data-bs-toggle="slide"
                  href="javascript:void(0)"
                  ><i class="side-menu__icon fe fe-grid"></i
                  ><span class="side-menu__label">Schools</span
                  ><i class="angle fe fe-chevron-right"></i
                ></a>
                <ul class="slide-menu">
                  <li class="side-menu-label1">
                    <a href="javascript:void(0)">List Schools</a>
                  </li>
                  <li>
                    <router-link exact to="/schools" class="slide-item">
                      <i class="side-menu__icon fe fe-users"></i
                      ><span class="side-menu__label">List Schools</span>
                    </router-link>
                  </li>
                  <li class="side-menu-label1">
                    <a href="javascript:void(0)">Deleted School</a>
                  </li>
                  <li>
                    <router-link exact to="/deleted/schools" class="slide-item">
                      <i class="side-menu__icon fe fe-users"></i
                      ><span class="side-menu__label">Deleted School</span>
                    </router-link>
                  </li>
                  <li class="side-menu-label1">
                    <a href="javascript:void(0)">Disabled School</a>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/disabled/schools"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-server"></i
                      ><span class="side-menu__label">Disabled School</span>
                    </router-link>
                  </li>
                </ul>
              </li>

              <li class="sub-category"><h3>School Jobs</h3></li>
              <li class="slide">
                <router-link
                  exact
                  to="/homeSchoolJobs"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-file-text"></i
                  ><span class="side-menu__label">Home</span>
                </router-link>
                <router-link
                  exact
                  to="/listeCandidats"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-file-text"></i
                  ><span class="side-menu__label">Candidates List</span>
                </router-link>
                <router-link
                  exact
                  to="/listeOffres"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-mail"></i
                  ><span class="side-menu__label">Offers List</span>
                </router-link>
              </li>
              <li class="sub-category"><h3>School Life</h3></li>
              <li class="slide">
                <router-link
                  exact
                  to="/listeBlogs"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-layout"></i
                  ><span class="side-menu__label">Articles List</span>
                </router-link>
                <router-link
                  exact
                  to="/ListeQuotes"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-layout"></i
                  ><span class="side-menu__label">Quotes</span>
                </router-link>
                <router-link
                  exact
                  to="/ListeNews"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-layout"></i
                  ><span class="side-menu__label">News</span>
                </router-link>
              </li>
              <li class="sub-category"><h3>FOUNDERS</h3></li>

              <li class="slide">
                <router-link
                  exact
                  to="/founder/connexion"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-layout"></i
                  ><span class="side-menu__label">Logs</span>
                </router-link>
              </li>
              <li class="sub-category"><h3>Gestion du contenu</h3></li>

              <li class="slide">
                <router-link
                  exact
                  to="/content"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-layout"></i
                  ><span class="side-menu__label">Content Library</span>
                </router-link>
                <router-link
                  exact
                  to="/blog"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-layout"></i
                  ><span class="side-menu__label">Blog</span>
                </router-link>
                <router-link
                  exact
                  to="/tutoriel"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-film"></i
                  ><span class="side-menu__label">Tutoriel</span>
                </router-link>
                <router-link
                  exact
                  to="/documentation"
                  class="side-menu__item"
                  data-bs-toggle="slide"
                >
                  <i class="side-menu__icon fe fe-book-open"></i
                  ><span class="side-menu__label">Documentation</span>
                </router-link>
              </li>
              <li class="slide">
                <a
                  class="side-menu__item"
                  data-bs-toggle="slide"
                  href="javascript:void(0)"
                  ><i class="side-menu__icon fe fe-grid"></i
                  ><span class="side-menu__label">Tracking</span
                  ><i class="angle fe fe-chevron-right"></i
                ></a>
                <ul class="slide-menu">
                  <li class="side-menu-label1">
                    <a href="javascript:void(0)">Tracking</a>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/tracking/evaluation"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-users"></i
                      ><span class="side-menu__label">Evaluation</span>
                    </router-link>
                  </li>
                  <li>
                    <router-link exact to="/tracking" class="slide-item">
                      <i class="side-menu__icon fe fe-server"></i
                      ><span class="side-menu__label">Matrice</span>
                    </router-link>
                  </li>
                </ul>
              </li>
              <li class="slide">
                <a
                  class="side-menu__item"
                  data-bs-toggle="slide"
                  href="javascript:void(0)"
                  ><i class="side-menu__icon fe fe-grid"></i
                  ><span class="side-menu__label">Releases</span
                  ><i class="angle fe fe-chevron-right"></i
                ></a>
                <ul class="slide-menu">
                  <li class="side-menu-label1">
                    <a href="javascript:void(0)">Releasses</a>
                  </li>
                  <li>
                    <router-link exact to="/types" class="slide-item">
                      <i class="side-menu__icon fe fe-server"></i
                      ><span class="side-menu__label">Types</span>
                    </router-link>
                  </li>
                  <li>
                    <router-link exact to="/teams" class="slide-item">
                      <i class="side-menu__icon fe fe-users"></i
                      ><span class="side-menu__label">Teams</span>
                    </router-link>
                  </li>
                  <li>
                    <router-link exact to="/releases" class="slide-item">
                      <i class="side-menu__icon fe fe-server"></i
                      ><span class="side-menu__label">Release</span>
                    </router-link>
                  </li>
                </ul>
              </li>
              <li class="sub-category"><h3>Administration</h3></li>
              <li class="slide">
                <a
                  class="side-menu__item"
                  data-bs-toggle="slide"
                  href="javascript:void(0)"
                  ><i class="side-menu__icon fe fe-grid"></i
                  ><span class="side-menu__label">Paramettrage</span
                  ><i class="angle fe fe-chevron-right"></i
                ></a>
                <ul class="slide-menu">
                  <li class="side-menu-label1">
                    <a href="javascript:void(0)">Paramettrage</a>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/paramettrage/users"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-users"></i
                      ><span class="side-menu__label">Family</span>
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/paramettrage/section"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-layout"></i
                      ><span class="side-menu__label">Tracking Sections</span>
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/paramettrage/criteria"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-sliders"></i
                      ><span class="side-menu__label">Tracking Criterias</span>
                    </router-link>
                  </li>

                  <li>
                    <router-link
                      exact
                      to="/paramettrage/modules"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-server"></i
                      ><span class="side-menu__label">Modules</span>
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/paramettrage/themes"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-layers"></i
                      ><span class="side-menu__label">Thèmes</span>
                    </router-link>
                  </li>
                  <!-- <li>
                    <router-link exact to="/" class="slide-item">
                        <i class="side-menu__icon fe fe-users"></i
                        ><span class="side-menu__label">Account Managers</span>
                    </router-link>
                  </li> -->
                  <!-- <li>
                    <router-link
                      exact
                      to="/paramettrage/schools"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-home"></i
                      ><span class="side-menu__label">Schools</span>
                    </router-link>
                  </li> -->
                  <li class="slide">
                    <a
                      class="side-menu__item"
                      data-bs-toggle="slide"
                      href="javascript:void(0)"
                      style="padding: 8px"
                      ><i class="side-menu__icon fe fe-grid"></i
                      ><span class="side-menu__label">Academy</span
                      ><i class="angle fe fe-chevron-right"></i
                    ></a>
                    <ul class="slide-menu">
                      <li class="side-menu-label1">
                        <a href="javascript:void(0)">Themes</a>
                      </li>
                      <li>
                        <router-link
                          exact
                          to="/academy/themes"
                          class="slide-item"
                        >
                          <i class="side-menu__icon fe fe-layers"></i
                          ><span class="side-menu__label">Themes</span>
                        </router-link>
                      </li>

                      <li class="side-menu-label1">
                        <a href="javascript:void(0)">Courses</a>
                      </li>
                      <li>
                        <router-link
                          exact
                          to="/academy/courses"
                          class="slide-item"
                        >
                          <i class="side-menu__icon fe fe-film"></i
                          ><span class="side-menu__label">Courses</span>
                        </router-link>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <router-link
                      exact
                      to="/paramettrage/configs"
                      class="slide-item"
                    >
                      <i class="side-menu__icon fe fe-settings"></i
                      ><span class="side-menu__label">Configurations</span>
                    </router-link>
                  </li>
                </ul>
              </li>
              <router-link
                exact
                to="/errorstracker"
                class="side-menu__item"
                data-bs-toggle="slide"
              >
                <i class="side-menu__icon fe fe-alert-octagon"></i
                ><span class="side-menu__label">Errors Tracking</span>
              </router-link>
            </ul>
            <div class="slide-right" id="slide-right">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="#7b8191"
                width="24"
                height="24"
                viewBox="0 0 24 24"
              >
                <path
                  d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"
                ></path>
              </svg>
            </div>
          </div>
          <div class="ps__rail-x" style="left: 0px; bottom: 0px">
            <div
              class="ps__thumb-x"
              tabindex="0"
              style="left: 0px; width: 0px"
            ></div>
          </div>
          <div class="ps__rail-y" style="top: 0px; height: 756px; right: 0px">
            <div
              class="ps__thumb-y"
              tabindex="0"
              style="top: 0px; height: 327px"
            ></div>
          </div>
        </div>
        <!--/APP-SIDEBAR-->
      </div>
      <!--app-content open-->
      <div class="main-content app-content mt-0">
        <div class="side-app">
          <!-- CONTAINER -->
          <div
            class="main-container container-fluid"
            style="padding-top: 74px !important"
          >
            <!-- PAGE-HEADER -->
            <slot />
            <!-- PAGE-HEADER END -->
          </div>
          <!-- CONTAINER CLOSED -->
        </div>
      </div>
      <!--app-content closed-->
    </div>
    <!-- Sidebar-right -->
    <div class="sidebar sidebar-right sidebar-animate ps ps--active-y">
      <div class="panel panel-primary card mb-0 shadow-none border-0">
        <div class="tab-menu-heading border-0 d-flex p-3">
          <div class="card-title mb-0">
            <i class="fe fe-bell me-2"></i
            ><span class="pulse"></span>Notifications
          </div>
          <div class="card-options ms-auto">
            <a
              href="javascript:void(0);"
              class="sidebar-icon text-end float-end me-3 mb-1"
              data-bs-toggle="sidebar-right"
              data-target=".sidebar-right"
              ><i class="fe fe-x text-white"></i
            ></a>
          </div>
        </div>
        <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
          <div class="tabs-menu border-bottom">
            <!-- Tabs -->
            <ul class="nav panel-tabs">
              <!-- <li class="">
                <a href="#side1" data-bs-toggle="tab"
                  ><i class="fe fe-settings me-1"></i>Feeds</a
                >
              </li>
              <li>
                <a href="#side2" data-bs-toggle="tab"
                  ><i class="fe fe-message-circle"></i> Chat</a
                >
              </li> -->
              <li>
                <a href="#side3" class="active" data-bs-toggle="tab"
                  ><i class="fe fe-anchor me-1"></i>Timeline</a
                >
              </li>
            </ul>
          </div>
          <div class="tab-content">
            <!-- <div class="tab-pane" id="side1">
              <div class="p-3 fw-semibold ps-5">Feeds</div>
              <div class="card-body pt-2">
                <div class="browser-stats">
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle
                          brround
                          bg-primary-transparent
                        "
                        ><i class="fe fe-user text-primary"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">New user registered</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-x"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-secondary
                          brround
                          bg-secondary-transparent
                        "
                        ><i class="fe fe-shopping-cart text-secondary"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">New order delivered</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-x"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-danger
                          brround
                          bg-danger-transparent
                        "
                        ><i class="fe fe-bell text-danger"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">You have pending tasks</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-x"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-warning
                          brround
                          bg-warning-transparent
                        "
                        ><i class="fe fe-gitlab text-warning"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">New version arrived</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-x"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-pink
                          brround
                          bg-pink-transparent
                        "
                        ><i class="fe fe-database text-pink"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">Server #1 overloaded</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-x"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-info
                          brround
                          bg-info-transparent
                        "
                        ><i class="fe fe-check-circle text-info"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">New project launched</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-x"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="p-3 fw-semibold ps-5">Settings</div>
              <div class="card-body pt-2">
                <div class="browser-stats">
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle
                          brround
                          bg-primary-transparent
                        "
                        ><i class="fe fe-settings text-primary"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">General Settings</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-secondary
                          brround
                          bg-secondary-transparent
                        "
                        ><i class="fe fe-map-pin text-secondary"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">Map Settings</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-danger
                          brround
                          bg-danger-transparent
                        "
                        ><i class="fe fe-headphones text-danger"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">Support Settings</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-warning
                          brround
                          bg-warning-transparent
                        "
                        ><i class="fe fe-credit-card text-warning"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">Payment Settings</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-sm-2 mb-sm-0 mb-3">
                      <span
                        class="
                          feeds
                          avatar-circle avatar-circle-pink
                          brround
                          bg-pink-transparent
                        "
                        ><i class="fe fe-bell text-pink"></i
                      ></span>
                    </div>
                    <div class="col-sm-10 ps-sm-0">
                      <div
                        class="
                          d-flex
                          align-items-end
                          justify-content-between
                          ms-2
                        "
                      >
                        <h6 class="">Notification Settings</h6>
                        <div>
                          <a href="javascript:void(0)"
                            ><i class="fe fe-settings me-1"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="side2">
              <div class="list-group list-group-flush">
                <div class="pt-3 fw-semibold ps-5">Today</div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/2.jpg"
                      style="
                        background: url('/assets/images/users/2.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Addie Minstra
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Hey! there I' am available....
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/11.jpg"
                      style="
                        background: url('/assets/images/users/11.jpg') center
                          center;
                      "
                      ><span class="avatar-status bg-success"></span
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Rose Bush
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Okay...I will be waiting for you
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/10.jpg"
                      style="
                        background: url('/assets/images/users/10.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Claude Strophobia
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Hi we can explain our new project......
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/13.jpg"
                      style="
                        background: url('/assets/images/users/13.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Eileen Dover
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        New product Launching...
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/12.jpg"
                      style="
                        background: url('/assets/images/users/12.jpg') center
                          center;
                      "
                      ><span class="avatar-status bg-success"></span
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Willie Findit
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Okay...I will be waiting for you
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/15.jpg"
                      style="
                        background: url('/assets/images/users/15.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Manny Jah
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Hi we can explain our new project......
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/4.jpg"
                      style="
                        background: url('/assets/images/users/4.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Cherry Blossom
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Hey! there I' am available....
                      </p></a
                    >
                  </div>
                </div>
                <div class="pt-3 fw-semibold ps-5">Yesterday</div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/7.jpg"
                      style="
                        background: url('/assets/images/users/7.jpg') center
                          center;
                      "
                      ><span class="avatar-status bg-success"></span
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Simon Sais
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Schedule Realease......
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/9.jpg"
                      style="
                        background: url('/assets/images/users/9.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Laura Biding
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Hi we can explain our new project......
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/2.jpg"
                      style="
                        background: url('/assets/images/users/2.jpg') center
                          center;
                      "
                      ><span class="avatar-status bg-success"></span
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Addie Minstra
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Contact me for details....
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/9.jpg"
                      style="
                        background: url('/assets/images/users/9.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Ivan Notheridiya
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Hi we can explain our new project......
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/14.jpg"
                      style="
                        background: url('/assets/images/users/14.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Dulcie Veeta
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        Okay...I will be waiting for you
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/11.jpg"
                      style="
                        background: url('/assets/images/users/11.jpg') center
                          center;
                      "
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Florinda Carasco
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        New product Launching...
                      </p></a
                    >
                  </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                  <div class="me-2">
                    <span
                      class="avatar avatar-md brround cover-image"
                      data-bs-image-src="/csm/assets/images/users/4.jpg"
                      style="
                        background: url('/assets/images/users/4.jpg') center
                          center;
                      "
                      ><span class="avatar-status bg-success"></span
                    ></span>
                  </div>
                  <div class="">
                    <a href="chat.html">
                      <div
                        class="fw-semibold text-dark"
                        data-bs-toggle="modal"
                        data-target="#chatmodel"
                      >
                        Cherry Blossom
                      </div>
                      <p class="mb-0 fs-12 text-muted">
                        cherryblossom@gmail.com
                      </p></a
                    >
                  </div>
                </div>
              </div>
            </div> -->
            <div class="tab-pane active" id="side3">
              <ul
                v-if="notifications.length > 0"
                class="task-list timeline-task mt-4"
              >
                <li
                  v-for="notification in notifications"
                  :key="notification.id"
                  class="d-sm-flex"
                >
                  <div>
                    <i class="task-icon1"></i>
                    <h6 class="fw-semibold">
                      {{ notification.label }} <br />
                      <span class="text-muted fs-11 ms-2 fw-normal">{{
                        notification.date
                      }}</span>
                    </h6>
                    <p
                      class="text-muted fs-12"
                      v-html="notification.details"
                    ></p>
                  </div>
                  <!-- <div class="ms-auto d-md-flex me-3">
                    <a href="javascript:void(0)" class="text-muted me-2"
                      ><span class="fe fe-edit"></span
                    ></a>
                    <a href="javascript:void(0)" class="text-muted"
                      ><span class="fe fe-trash-2"></span
                    ></a>
                  </div> -->
                </li>
              </ul>
              <h4 v-else class="text-center mt-4">Aucune notification.</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="ps__rail-x" style="left: 0px; bottom: 0px">
        <div
          class="ps__thumb-x"
          tabindex="0"
          style="left: 0px; width: 0px"
        ></div>
      </div>
      <div class="ps__rail-y" style="top: 0px; height: 756px; right: 0px">
        <div
          class="ps__thumb-y"
          tabindex="0"
          style="top: 0px; height: 740px"
        ></div>
      </div>
    </div>
    <!--/Sidebar-right-->
    <!-- FOOTER -->
    <footer class="footer">
      <div class="container">
        <div class="row align-items-center flex-row-reverse">
          <div class="col-md-12 col-sm-12 text-center">
            Copyright © 2022 . All rights reserved.
          </div>
        </div>
      </div>
    </footer>
    <!-- FOOTER CLOSED -->

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
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
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
                          <option value="whatsapp">Whatsapp</option>
                          <option value="phone">Appel</option>
                          <option value="mail">Email</option>
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

    <!------------------------------  ADD INTERVATION ------------------------------>
    <transition
      @enter="startTransitionModal($refs.modalAddIntervention)"
      @after-enter="endTransitionModal($refs.modalAddIntervention)"
      @before-leave="endTransitionModal($refs.modalAddIntervention)"
      @after-leave="startTransitionModal($refs.modalAddIntervention)"
    >
      <div
        class="modal fade"
        v-if="showModalAddIntervention"
        ref="modalAddIntervention"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="addIntervention" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Ajouter une intervention
                </h5>
                <button
                  class="close btn"
                  @click="showModalAddIntervention = !showModalAddIntervention"
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
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> School </label>
                        <input
                          type="hidden"
                          name="school"
                          :value="interventionSchool"
                        />
                        <vue-select
                          class="form-contro"
                          name="accountManager"
                          :reduce="(school) => school.id"
                          label="name"
                          :options="schools"
                          v-model="interventionSchool"
                          placeholder="School"
                          searchable
                        >
                        </vue-select>
                        <!-- <select name="school"  required class="form-control">
                          <option v-for="school in schools" :key="school.id" :value="school.id">{{school.name}}</option>
                        </select> -->
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Account Manager </label>
                        <select
                          name="account_manager"
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
                        <label class="form-label"> Date </label>
                        <input
                          type="datetime-local"
                          class="form-control"
                          name="date"
                          required
                        />
                        <!-- <Datepicker
                            :language="localfr"
                            format="yyyy-MM-dd"
                            class="form-control"
                            name="date"
                            :value="datePlanning"
                        /> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Duration </label>
                        <input
                          type="number"
                          name="duration"
                          min="1"
                          value="1"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Type </label>
                        <select name="type" class="form-control">
                          <option value="preventive">Preventive</option>
                          <option value="curative">Curative</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Nature </label>
                        <select name="nature" class="form-control">
                          <option value="formation">Formation</option>
                          <option value="accompagnement">Accompagnement</option>
                          <option value="meeting">Meeting</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label"> Channel </label>
                        <select name="channel" class="form-control">
                          <option value="online">Online</option>
                          <option value="presentiel">Présentiel</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <vue-editor
                        id="details"
                        v-model="detailsInter"
                      ></vue-editor>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  class="btn btn-secondary"
                  @click="showModalAddIntervention = !showModalAddIntervention"
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

    <!---------------------------- UPDATE PROFILE USER ---------------------------->

    <transition
      @enter="startTransitionModal($refs.modalUser)"
      @after-enter="endTransitionModal($refs.modalUser)"
      @before-leave="endTransitionModal($refs.modalUser)"
      @after-leave="startTransitionModal($refs.modalUser)"
    >
      <div class="modal fade" v-if="showModalUser" ref="modalUser">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="updateUser" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  {{ user.nom }} {{ user.prenom }}
                </h5>
                <button
                  class="close btn"
                  @click="showModalUser = !showModalUser"
                >
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Nom</label>
                        <input
                          type="text"
                          v-model="user.nom"
                          required
                          name="nom"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Prenom</label>
                        <input
                          type="text"
                          v-model="user.prenom"
                          required
                          name="prenom"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Email</label>
                        <input
                          type="text"
                          v-model="user.email"
                          required
                          name="email"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Telephone</label>
                        <input
                          type="text"
                          v-model="user.telephone"
                          required
                          name="telephone"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="">Adress</label>
                        <input
                          type="text"
                          v-model="user.adresse"
                          required
                          name="adresse"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Role</label>
                        <input
                          type="text"
                          v-model="user.role"
                          class="form-control"
                          disabled
                        />
                        <input
                          type="hidden"
                          v-model="user.role_id"
                          name="role_id"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Fonction</label>
                        <input type="hidden" name="user" v-model="user.id" />
                        <input
                          type="text"
                          v-model="user.fonction"
                          class="form-control"
                          disabled
                        />
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <input
                          type="file"
                          @change="onFile"
                          name="image"
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
                  @click="showModalUser = !showModalUser"
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
import { loadScript, unloadScript } from "vue-plugin-load-script";
import Datepicker from "vuejs-datepicker";
import { fr } from "vuejs-datepicker/dist/locale";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { VueEditor } from "vue2-editor";

export default {
  components: {
    "vue-select": vSelect,
    "vue-editor": VueEditor,
    Datepicker,
  },
  emits: ["ticket-added", "intervention-added"],
  data() {
    return {
      getHour: null,
      hourConverted: null,
      user: {
        nom: "",
        prenom: "",
        role: "",
        img: "",
      },
      ticketSchool: null,
      demandeur: null,
      interventionSchool: null,
      interventions: [],
      tickets: [],
      schools: [],
      notifications: [],
      accountManagers: [],
      showModalAddTicket: false,
      showModalAddIntervention: false,
      showModalUser: false,
      datePlanning: new Date(),
      localfr: fr,
      detailsTicket: "",
      schoolContacts: [],
      detailsTicket: "",
      detailsInter: "",
      natures: [],
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
    getUser: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getCurUser", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.user = result.data.data;
          })
          .catch((err) => {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      } else {
        this.$router.push("/login");
      }
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
    initLayout: function () {
      loadScript("/csm/assets/plugins/sidemenu/sidemenu.js")
        .then(() => {})
        .catch(() => {});
      loadScript("/csm/assets/plugins/treeview/treeview.js")
        .then(() => {})
        .catch(() => {});
      loadScript("/csm/assets/plugins/p-scroll/perfect-scrollbar.js")
        .then(() => {})
        .catch(() => {});
      loadScript("/csm/assets/plugins/p-scroll/pscroll.js")
        .then(() => {})
        .catch(() => {});
      loadScript("/csm/assets/plugins/p-scroll/pscroll-1.js")
        .then(() => {})
        .catch(() => {});
      // loadScript("/csm/assets/plugins/p-scroll/pscroll-1.js")
      //   .then(() => {})
      //   .catch(() => {});
    },
    logout: function (e) {
      e.preventDefault();

      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/logout", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          })
          .catch(function (err) {
            console.log(err);
          });
      } else {
        this.$router.push("/login");
      }
    },
    getInterventions: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getUserInterventions", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.interventions = result.data;
          })
          .catch(function (err) {});
      } else {
        this.$router.push("/login");
      }
    },
    getTickets: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getUserTickets", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.tickets = result.data;
          })
          .catch(function (err) {});
      } else {
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
            this.tickets = result.data;
            form.reset();
            this.showModalAddTicket = !this.showModalAddTicket;
            this.$emit("ticket-added");
          })
          .catch(function (err) {});
      }
    },
    addIntervention: async function (e) {
      e.preventDefault();

      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        formdata.append("details", this.detailsInter);
        await axios
          .post("/api/saveIntervention", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.interventions = result.data;
            form.reset();
            this.showModalAddIntervention = !this.showModalAddIntervention;
            this.$emit("intervention-added");
          })
          .catch(function (err) {});
      }
    },
    toggleSideBar: function () {
      document
        .getElementsByClassName("app")[0]
        .classList.toggle("sidenav-toggled");
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
    getNotifications: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getLastNotifications", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.notifications = result.data;
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
          })
          .catch((error) => {
            error;
          });
      }
    },
    onFile(e) {
      const files = e.target.files;
      const reader = new FileReader();
      reader.readAsDataURL(files[0]);
      reader.onload = () => (this.user.img = reader.result);
      console.log(this.user);
    },
    updateUser: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");

      if (token) {
        let form = e.target;
        let formData = new FormData(form);
        formData.append("fonction", this.user.fonction);
        formData.append("role_id", this.user.role_id);
        formData.append("user", this.user.id);
        await axios
          .post("api/saveUser", formData, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then(async (result) => {
            this.showModalUser = false;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
  mounted() {
    this.initLayout();
    this.getUser();
    this.getTickets();
    this.getInterventions();
    this.getSchoolsList();
    this.getNotifications();
    this.getSchoolsContact();
    this.getAccountManagers();
    this.getNatures();
  },
  beforeDestroy() {
    unloadScript("/csm/assets/plugins/sidemenu/sidemenu.js")
      .then(() => {})
      .catch(() => {});
    unloadScript("/csm/assets/plugins/treeview/treeview.js")
      .then(() => {})
      .catch(() => {});
    unloadScript("/csm/assets/plugins/p-scroll/perfect-scrollbar.js")
      .then(() => {})
      .catch(() => {});
    unloadScript("/csm/assets/plugins/p-scroll/pscroll.js")
      .then(() => {})
      .catch(() => {});
    unloadScript("/csm/assets/plugins/p-scroll/pscroll-1.js")
      .then(() => {})
      .catch(() => {});
  },
};
</script>

<style>
.table-responsive {
  cursor: pointer;
}
.table-responsive:-webkit-scrollbar-track {
  background-color: darkgrey;
  border-radius: 20px;
}
.table-responsive::-webkit-scrollbar-thumb {
  box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}
</style>