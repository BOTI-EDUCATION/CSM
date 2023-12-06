<template>
  <div class="row">
    <div
      class="col-md-12 text-right mb-5"
      style="display: flex; justify-content: flex-end"
    >
      <div class="col-md-4">
        <vue-select
          name="city"
          :reduce="(city) => city.city"
          label="city"
          :options="cities"
          v-model="city"
          placeholder="cities"
          searchable
          @input="getKanbanByCity"
        >
        </vue-select>
      </div>

      <div class="col-md-4">
        <select
          class="form-control"
          placeholder="Sales Manager"
          v-model="user_sale"
          @change="getKanbanByUser"
        >
          <option value="">All Sales</option>
          <option v-for="sale in salesManager" :key="sale" :value="sale.id">
            {{ sale.nom }}
          </option>
        </select>
      </div>
    </div>
    <div
      class="col-md-2 mb-2"
      v-for="(leadKanban, index) in kanban"
      :key="leadKanban"
    >
      <div
        class="accordion"
        id="accordionExample"
        :class="index == 'Abandon' ? 'd-none' : ''"
      >
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button
              class="accordion-button"
              type="button"
              data-bs-toggle="collapse"
              :data-bs-target="'#collapseOne-' + index"
              aria-expanded="false"
              aria-controls="collapseOne"
            >
              <div class="d-flex">
                <span class="d-inline-block" style="font-size: 16px">
                  <b> {{ index }}</b></span
                >

                <span class="ms-3 d-inline-block"
                  >({{ leadKanban.length }})
                </span>
              </div>
            </button>
          </h2>
          <div
            :id="'collapseOne-' + index"
            class="accordion-collapse collapse show"
            aria-labelledby="headingOne"
            data-bs-parent="#accordionExample"
          >
            <div class="accordion-body" style="background-color: #ebebee">
              <div
                class="card"
                v-for="infos in leadKanban"
                :key="infos"
                style="border-radius: 5px !important"
              >
                <router-link
                  :to="
                    infos.is_converted == 0
                      ? '/leads/view/' + infos.id
                      : '/schools/view/' + infos.id
                  "
                >
                  <div class="card-body">
                    <template style="margin-bottom: 5px; display: inline-block">
                      <img
                        :src="infos.logo"
                        alt="profile-lead"
                        class="avatar profile-user brround cover-image me-2"
                      />
                      {{ infos.name }}
                    </template>
                    <div class="mt-3 mb-3 text-center">
                      <span
                        v-if="infos.last_contact"
                        class="badge rounded-pill bg-success"
                      >
                        {{ infos.last_contact }}</span
                      >
                      <span class="badge rounded-pill bg-info">
                        {{ infos.city }}
                      </span>
                    </div>
                    <div
                      class="d-flex align-items-center justify-content-between"
                    ></div>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";

export default {
  components: {
    "vue-select": vSelect,
  },
  data() {
    return {
      kanban: [],
      salesManager: [],
      cities: [],
      user_sale: "",
      city: "",
    };
  },
  methods: {
    kanBanLeads() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/get/kanban", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((response) => {
            this.kanban = response.data;
          });
      }
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
    getKanbanByUser() {
      const token = localStorage.getItem("auth-token");

      if (this.user_sale == "") {
        this.kanBanLeads();
      } else {
        if (token) {
          axios
            .get("/api/get/kanban/by_user/" + this.user_sale, {
              headers: {
                Authorization: "Bearer " + token,
              },
            })
            .then(async (result) => {
              this.kanban = result.data;
            });
        }
      }
    },
    getKanbanByCity() {
      const token = localStorage.getItem("auth-token");
      if (this.city == "") {
        this.kanBanLeads();
      } else {
        if (token) {
          axios
            .get("/api/get/kanban/" + this.city, {
              headers: {
                Authorization: "Bearer " + token,
              },
            })
            .then(async (result) => {
              this.kanban = result.data;
            });
        }
      }
    },
    getCities() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/cities/of/lead", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.cities = result.data;
          });
      }
    },
  },

  mounted() {
    this.kanBanLeads();
    this.getSalesManager();
    this.getCities();
  },
};
</script>



<style>
.accordion-body {
  padding: 10px !important;
  max-height: 500px;
  overflow: auto;
}
</style>