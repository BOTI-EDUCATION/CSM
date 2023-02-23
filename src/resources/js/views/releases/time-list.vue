<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 text-right">
        <router-link class="btn btn-primary btn-sm" to="/releases/add">
          <i class="fe fe-plus"></i>
        </router-link>
      </div>
    </div>

    <div class="row">
      <div v-if="releases.length <= 0">
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
          <symbol
            id="check-circle-fill"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path
              d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
            />
          </symbol>
          <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
              d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"
            />
          </symbol>
          <symbol
            id="exclamation-triangle-fill"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path
              d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
            />
          </symbol>
        </svg>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
          <svg
            class="bi flex-shrink-0 me-2"
            width="24"
            height="24"
            role="img"
            aria-label="Danger:"
          >
            <use xlink:href="#exclamation-triangle-fill" />
          </svg>
          <div>No Releases Available</div>
        </div>
      </div>
      <div v-else class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <ul class="notification">
          <li v-for="release in releases" :key="release.id">
            <div class="notification-time">
              <span class="date">{{ release.date }}</span>
              <!-- <span
                class="badge bg-primary"
                v-for="re in release.createdByUser"
                :key="re"
                style="display: inline-block; margin-right: 3px"
              >
                {{ re }}
              </span> -->

              <img 
                v-for="member in release.createdByUser" :key="member"
                :src="'https://boti.education/csm/src/public/contents/'+member"
                class="avatar profile-user brround cover-image"
                style="width:2.2rem;height:2.2rem;object-fit:cover;margin-right: 5px;"
                alt="profile_member"
              />

              <br />
              <span
                class="badge bg-success"
                v-for="tp in release.type"
                :key="tp"
                style="display: inline-block; margin-right: 3px"
              >
                {{ tp }}
              </span>
            </div>

            <div class="notification-icon">
              <a
                href="javascript:void(0);"
                :class="{
                  'bg-danger': release.status == 0,
                  'bg-success': release.status == 1,
                }"
                style="border: 1px solid transparent !important"
              ></a>
            </div>

            <div class="notification-time-date mb-2 d-block d-md-none">
              <span class="date">{{ release.date }}</span>
              <span class="time ms-2"></span>
            </div>
            <div class="notification-body">
              <div class="media mt-0">
                <div class="main-avatar avatar-md online">
                  <img
                    alt="image"
                    class="br-7"
                    :src="
                      'https://boti.education/csm/src/public/contents/' +
                      release.image
                    "
                  />
                </div>
                <div class="media-body ms-3 d-flex">
                  <div class="">
                    <div
                      class="d-flex justify-content-center align-items-center"
                    >
                      <span class="badge rounded-pill bg-success"></span>
                    </div>
                    <p class="fs-15 text-dark mb-0 me-1">
                      {{ release.label }}
                      <a
                        style="
                          width: 75%;
                          white-space: pre-line;
                          text-align: center;
                        "
                        :href="release.link"
                        target="_blank"
                        v-if="release.status == 1"
                      >
                        {{ release.link }}</a
                      >
                    </p>

                    <ul class="list-group mb-2 mt-2">
                      <li class="list-group-item active" aria-current="true">
                        Cs Description
                      </li>
                      <li
                        class="list-group-item"
                        v-html="release.desc_csm"
                      ></li>
                    </ul>
                    <ul class="list-group">
                      <li class="list-group-item active" aria-current="true">
                        Technique Description
                      </li>
                      <li
                        class="list-group-item"
                        v-html="release.desc_tech"
                      ></li>
                    </ul>
                  </div>
                  <div
                    class="
                      notify-time
                      d-flex
                      align-items-center
                      justify-content-center
                    "
                  >
                    <div class="btn-group">
                      <button
                        class="
                          btn
                          bckBtn
                          rounded-pill
                          btn-sm
                          dropdown-toggle
                          ms-1
                        "
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        action
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <router-link
                            class="dropdown-item"
                            :to="'/releases/edit/' + release.id"
                          >
                            <i class="fa-solid fa-pen text-warning"></i>
                            Edit</router-link
                          >
                        </li>
                        <li>
                          <a
                            @click.prevent="deleteRelease(release.id)"
                            class="dropdown-item"
                            style="cursor: pointer"
                            ><i class="fa-solid fa-trash text-danger"></i>
                            Delete</a
                          >
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="main-avatar avatar-md online"></div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  components: {},
  data() {
    return {
      releases: [],
    };
  },
  methods: {
    getReleases: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/releases/list", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then(async (result) => {
            this.releases = result.data;
          })
          .catch((error) => {
            error;
          });
      }
    },
    deleteRelease(id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "are you sure to delete this release ?",
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
                "/api/delete/release/" + id,
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
                  title: "Release deleted Successfully",
                  showConfirmButton: false,
                  timer: 1500,
                });
                this.releases = this.releases.filter((rel) => {
                  return rel.id != id;
                });
              })
              .catch((error) => {
                error;
              });
          }
        });
      }
    },
  },
  mounted() {
    this.getReleases();
  },
};
</script>


<style scoped>
.rounded_icon {
  height: 33px;
  width: 33px;
  border-radius: 50%;
  text-align: center;
}

.csm {
  background: #222222 !important;
  color: #ff8c25 !important;
}
.tech {
  color: #0f0f0f;
  background: #ff8c25 !important;
}

.eyeIcon {
  height: 33px;
  width: 33px;
  border-radius: 50%;
  text-align: center;
  line-height: 25px;
  color: #ff8c25 !important;

  border: 1px solid #ff8c25;
  margin-left: 3px;
}

.bckBtn {
  background: #222222 !important;
  color: #ff8c25 !important;
  font-weight: 500;
}

.crB {
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
}
</style>