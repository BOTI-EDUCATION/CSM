<template>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body overflow-y-scroll">
            <table class="table matrice-table table-bordered">
              <thead class="table-primary">
                <tr>
                  <th class="text-center align-middle " rowspan="2" > Ecole </th>
                  <th class="text-center align-middle " rowspan="2" > Notation </th>
                  <template v-for="section in sections">
                    <th class="text-center align-middle " v-if="section.criterias.length>0" 
                          :colspan="
                            section.criterias.length > 0
                              ? section.criterias.length
                              : 1
                          " :key="section.id">
                      {{section.label}}
                    </th>
                  </template>
                </tr>
                <tr>
                   <template v-for="section in sections">
                      <template v-if="section.criterias.length > 0">
                        <th
                          class="text-center align-middle "
                          v-for="criteria in section.criterias"
                          :key="criteria.id"
                        >
                          <b>
                            {{ criteria.short }}
                          </b>
                        </th>
                      </template>
                    </template>
                </tr>
              </thead>
              <tbody>
                <tr v-for="school in schools" :key="school.id">
                  <td class="d-flex align-items-center align-middle">
                    <span class="avatar cover-image me-2" :style="'background: url('+school.logo+') center center;'" ></span>
                    {{school.name}}
                  </td>
                  <td class="align-middle text-center">  
                    <h4 class="mb-0"> 
                      <!-- <span class="badge rounded-pill bg-success badge-sm me-1 mb-1 mt-1"> -->
                        <b>{{school.metricScore}}</b> 
                      <!-- </span> -->
                    </h4>  
                  </td>
                  <template v-for="section in sections">
                      <template v-if="section.criterias.length > 0">
                        <td
                          class="text-center align-middle"
                          v-for="criteria in section.criterias"
                          :key="criteria.id"
                        >
                          <h4 class="mb-0"> 
                            <!-- <span class="badge rounded-pill bg-success badge-sm me-1 mb-1 mt-1"> -->
                              <b>{{school.criterias[criteria.id]}}</b> 
                            <!-- </span> -->
                          </h4>
                        </td>
                      </template>
                    </template>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      sections : [],
      schools: []
      
    }
  },
  methods: {
    getTrackingSections: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getTrackingSections", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.sections = result.data;
          })
          .catch((err) =>  {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    getTrackingMatrice: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getTrackingMatrice", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.schools = result.data;
          })
          .catch((err) =>  {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
  },
  mounted() {
    this.getTrackingSections();
    this.getTrackingMatrice();
    
  },
};
</script>

<style scoped>

.matrice-table td,.matrice-table th{
  min-width: 50px;
  
}

.overflow-y-scroll{
  overflow-y: scroll;
}

.overflow-y-scroll::-webkit-scrollbar-thumb {
  background: #d2cdf9;
}

.overflow-y-scroll::-webkit-scrollbar-thumb:hover {
  background: #ab9ffa;
}

</style>