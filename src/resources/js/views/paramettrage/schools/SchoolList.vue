<template>
  <div>
    <div class="row mb-4">
      <div class="col-sm-12 col-12 offset-md-2 col-md-3 text-right">
        <vue-select
          class="bg-white border-none"
          multiple
          name="Account Managers"
          :options="accountManagers"
          :reduce="(manager) => manager.id"
          label="nom"
          v-model="filter.acc_manager"
          placeholder="Account Managers"
          @input="filterSchool"
          searchable
        >
        </vue-select>
      </div>
      <div class="col-sm-12 col-12 col-md-3 text-right">
        <vue-select
          class="bg-white border-none"
          multiple
          name="Types"
          :options="types"
          :reduce="(type) => type.alias"
          label="label"
          v-model="filter.type"
          placeholder="Types"
          @input="filterSchool"
          searchable
        >
        </vue-select>
      </div>

      <div class="col-sm-12 col-12 col-md-3 text-right">
        <input
          type="text"
          @keyup="filterSchool"
          v-model="filter.search"
          placeholder="Search ..."
          class="form-control"
        />
      </div>

      <div class="col-sm-12 col-md-1 text-right">
        <router-link class="btn btn-info btn-icon me-3" to="/schools/map">
          <i class="fe fe-map"></i>
        </router-link>
        <router-link class="btn btn-primary" to="/schools/add">
          <i class="fe fe-plus"></i>
        </router-link>
      </div>
    </div>

    <div class="row">
      <div
        v-for="school in this.filteredSchools"
        :key="school.id"
        class="col-sm-12 col-md-3"
      >
        <router-link class="text-info" :to="'/schools/view/' + school.id">
          <div class="card hoverable full-row-card user-card overflow-hidden">
            <div class="card-body text-center">
              <span
                class="avatar bg-white avatar-xxl bradius cover-image mb-2"
                :data-bs-image-src="school.logo"
                :style="
                  ' background: url(\'' + school.logo + '\') center center'
                "
              >
                <span v-if="school.responsable" class="avatar-icons">
                  <img
                    :src="school.responsable.img"
                    class="rounded"
                    :alt="school.responsable.name"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    :data-bs-title="school.responsable.name"
                  /> </span
              ></span>
              <h5 class="card-title text-dark mb-2">{{ school.name }}</h5>

              <div class="">
                <span
                  class="badge boti-bg-purple fw-bold"
                  style="font-size: 16px"
                  >{{ school.alias }}</span
                >
                <p
                  v-if="!school.web_link"
                  class="badge boti-bg-danger fw-bold"
                  style="font-size: 13px; margin-top: 5px"
                >
                  doesn't have the web link
                </p>
              </div>
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    "vue-select": vSelect,
  },
  emits: ["load-schools"],
  data() {
    return {
      schools: [],
      accountManagers: [],
      types: [
        {
          alias: "kinder",
          label: "Kinder",
        },
        {
          alias: "school",
          label: "School",
        },
        {
          alias: "campus",
          label: "Campus",
        },
      ],
      filter: {
        type: null,
        search: null,
        acc_manager: null,
      },
      filteredSchools: [],
    };
  },
  methods: {
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
            this.filteredSchools = this.schools;
            this.$emit("load-schools", this.schools.length);
            const tooltipTriggerList = document.querySelectorAll(
              '[data-bs-toggle="tooltip"]'
            );
            const tooltipList = [...tooltipTriggerList].map(
              (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
            );
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
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

    filterSchool() {
      let search = this.filter.search;
      let types = this.filter.type;
      let acc_manager = this.filter.acc_manager;
      if (search != null && types != null && acc_manager != null) {
        this.filteredSchools = this.schools.filter((school) => {
          let hasCommon = () => {
            if (types && types.length > 0) {
              for (let i = 0; i < types.length; i++) {
                for (let j = 0; j < school.types.length; j++) {
                  if (types[i] === school.types[j]) {
                    return true;
                  }
                }
              }
              return false;
            } else {
              return true;
            }
          };
          return (
            (search
              ? school.name.toLowerCase().includes(search.toLowerCase())
              : true) &&
            hasCommon() &&
            (acc_manager.length > 0
              ? school.responsable &&
                acc_manager.indexOf(school.responsable.id) > -1
                ? true
                : false
              : true)
          );
        });
      } else if (search == null && types != null && acc_manager == null) {
        if (types != "") {
          this.filteredSchools = this.schools.filter((school) => {
            return school.types[0] == types;
          });
          this.$emit("load-schools", this.filteredSchools.length);
        } else {
          this.$emit("load-schools", this.schools.length);
          return (this.filteredSchools = this.schools);
        }
      } else if (search != null && types == null && acc_manager == null) {
        this.filteredSchools = this.schools.filter((school) => {
          if (school.name.toLowerCase().includes(search.toLowerCase())) {
            this.filteredSchools = school;
            return this.filteredSchools;
          }
        });
        this.$emit("load-schools", this.filteredSchools.length);
      } else if (search == null && types == null && acc_manager != null) {
        if (acc_manager.length > 0) {
          this.filteredSchools = this.schools.filter((school) => {
            if (school.responsable.id) {
              return school.responsable.id == acc_manager;
            }
          });
          this.$emit("load-schools", this.filteredSchools.length);
        } else {
          this.$emit("load-schools", this.schools.length);
          return (this.filteredSchools = this.schools);
        }
      } else if (search == null && types != null && acc_manager != null) {
        this.filteredSchools = this.schools.filter((school) => {
          if (school.responsable.id) {
            return (
              school.types[0] == types && school.responsable.id == acc_manager
            );
          }
        });
        this.$emit("load-schools", this.filteredSchools.length);
      }
    },
  },
  mounted() {
    this.getSchoolsList();
    this.getAccountManagers();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
.profile {
  max-width: 100px;
}
.user-card .actions {
  display: flex;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}
.user-card .actions > * {
  flex: 1;
}
a .hoverable {
  transition: all ease-in-out 0.4s;
}
a .hoverable:hover {
  box-shadow: 0px 6px 15px 0px #33333344;
}

.boti-bg-purple {
  background-color: #f3f5ff !important;
  color: #3846ae !important;
}

.boti-bg-danger {
  background-color: #fff4f4 !important;
  color: #f65e40 !important;
}
</style>