<template>
  <div>
    <div
      class="card"
      style="width: 75%; margin: auto; padding: 30px"
      v-if="!block_1"
    >
      <div class="card-body text-center">
        <button
          v-if="twoWeeks.check"
          class="btn btn-warning text-dark fw-bold mb-2"
          @click="throwThececk('two_weeks')"
        >
          Checkup de ({{ twoWeeks.first }} / {{ twoWeeks.last }})
        </button>
        <br />
        <button
          class="btn btn-primary fw-bold"
          @click="throwThececk('new')"
          v-if="check == 'non-exist'"
        >
          Lancer un nouveau check
        </button>
        <button
          v-else
          class="btn btn-info fw-bold"
          @click="throwThececk('last')"
        >
          Afficher le dernier check de ({{ first }} / {{ last }})
          <br />
          par {{ user }} Le({{ date }})
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-center" v-if="loader">
      <div class="spinner-border text-success" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div class="row" v-if="isThrowit">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div
          class="d-flex justify-content-end text-right"
          v-if="state == 'new' || state == 'last'"
        >
          <button
            class="btn btn-warning text-dark fw-bold mb-2"
            @click="throwThececk('two_weeks')"
          >
            voir le checkup de : {{ twoWeeks.first }} / {{ twoWeeks.last }}
          </button>
        </div>

        <div
          class="d-flex justify-content-end text-right"
          v-if="state == 'two_weeks'"
        >
          <button
            class="btn btn-primary fw-bold mb-3"
            @click="throwThececk('new')"
            v-if="check == 'non-exist'"
          >
            Lancer un nouveau check
          </button>
          <button
            v-else
            class="btn btn-info fw-bold mb-3"
            @click="throwThececk('last')"
          >
            Afficher le dernier check de ({{ first }} / {{ last }})
          </button>
        </div>

        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-bordered border-dark text-center">
                    <thead>
                      <tr>
                        <th rowspan="2">SCHOOL</th>
                        <th
                          v-for="(count, rubrique) in rubriques"
                          :key="count"
                          class="text-center"
                          :colspan="count"
                        >
                          {{ rubrique }}
                        </th>
                      </tr>
                      <tr>
                        <td v-for="label in labels" :key="label">
                          {{ label }}
                        </td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(checkup, school) in checkups" :key="checkup">
                        <td>{{ school }}</td>
                        <td
                          style="font-size: 16px; font-weight: bold"
                          v-for="check in checkup"
                          :key="check"
                          :class="
                            check.value > 10
                              ? 'bg-success'
                              : 'bg-danger text-light'
                          "
                        >
                          {{ check.value_formated }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      block_1: false,
      isThrowit: false,
      checkups: [],
      indicators: [],
      rubriques: [],
      labels: [],
      loader: false,
      check: false,
      first: null,
      last: null,
      date: null,
      user: null,
      twoWeeks: {
        check: false,
        first: null,
        last: null,
        date: null,
        user: null,
      },
      state: null,
    };
  },
  methods: {
    throwThececk(state) {
      this.state =
        state == "last" ? "last" : state == "new" ? "new" : "two_weeks";

      this.block_1 = true;
      this.loader = true;
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get(
            state == "new"
              ? "/api/checkup/schools"
              : state == "last"
              ? "/api/last/checkups"
              : "/api/two_weeks/checkups",
            {
              headers: {
                Authorization: "Bearer" + token,
              },
            }
          )
          .then((result) => {
            this.checkups = result.data;
            this.loader = false;
            this.isThrowit = true;
          });
      }
    },
    getIndicators() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/indicators_by_rubrique", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result);
            this.indicators = result.data;
            this.rubriques = result.data.rubriques;
            this.labels = result.data.indicator_labels;
          });
      }
    },
    checkup() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/checkups/exists", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result.data);
            this.check = result.data.result;
            if (this.check == "exist") {
              this.first = result.data.first;
              this.last = result.data.last;
              this.date = result.data.date;
              this.user = result.data.user;
            }
          });
      }
    },
    two_weeks_ago() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/checkups/exists/two_weeks", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result.data);
            this.twoWeeks.check = result.data.result;
            if (this.twoWeeks.check == "exist") {
              this.twoWeeks.first = result.data.first;
              this.twoWeeks.last = result.data.last;
              this.twoWeeks.date = result.data.date;
              this.twoWeeks.user = result.data.user;
            }
          });
      }
    },
  },
  mounted() {
    this.checkup();
    this.two_weeks_ago();
    this.getIndicators();
  },
};
</script>


<style>
</style>