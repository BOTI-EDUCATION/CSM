<template>
  <div class="row">
    <div class="col-12">
      <h3 class="p-3 mb-5">{{ label }}</h3>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="conatainer-fluid">
            <div class="row">
              <div class="col-12">
                <form action="">
                  <table
                    class="
                      table
                      border
                      text-nowrap text-md-nowrap
                      table-bordered
                      mb-0
                    "
                  >
                    <thead>
                      <tr>
                        <th rowspan="2"></th>
                        <th
                          class="text-center"
                          v-for="section in sections"
                          :colspan="
                            section.criterias.length > 0
                              ? section.criterias.length
                              : 1
                          "
                          :key="section.id"
                        >
                          <b>
                            {{ section.label }}
                          </b>
                        </th>
                      </tr>
                      <tr>
                        <template v-for="section in sections">
                          <template v-if="section.criterias.length > 0">
                            <th
                              class="text-center"
                              v-for="criteria in section.criterias"
                              :key="criteria.id"
                            >
                              <b>
                                {{ criteria.short }}
                              </b>
                            </th>
                          </template>
                          <template v-else>
                            <th class="text-center" :key="section.id">
                              <b> * </b>
                            </th>
                          </template>
                        </template>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="school in schools" :key="school.id">
                        <td>
                          <b>
                            {{ school.name }}
                          </b>
                        </td>
                        <template v-for="section in sections">
                          <template v-if="section.criterias.length > 0">
                            <td
                              class="text-center"
                              v-for="criteria in section.criterias"
                              :key="(school.id+'_'+section.id+'_'+criteria.id)"
                            >
                              <select class="form-control form-select" v-model="matrixValues[school.id+'_'+section.id+'_'+criteria.id]">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                              </select>
                            </td>
                          </template>
                          <template v-else >
                            <td class="text-center" :key="(school.id+'_'+section.id)">
                              <select class="form-control form-select" v-model="matrixValues[school.id+'_'+section.id]">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                              </select>
                            </td>
                          </template>
                        </template>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-2 offset-md-10  text-right">
      <button class="btn btn-primary btn-block ms-auto" @click="saveMatrix">Save</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      label: "",
      sections: [],
      date: new Date(),
      schools: [],
      matrixValues: {}
    };
  },
  methods: {
    setupData: function (dataJSON) {
      let data = JSON.parse(dataJSON);
      this.label = data.label;
      this.sections = data.sections;
      this.date = new Date(data.date);
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
          .catch(function (err) {});
      }
    },
    initiateMatrix: function(){
      
      let values = {};
      let schools = this.schools;
      let sections = this.sections;

      for (let i = 0; i < schools.length; i++) {
        const school = schools[i];
        for (let j = 0; j < sections.length; j++) {
          const section = sections[j];
          let criterias = section.criterias;
          if(criterias.length>0){
            for (let h = 0; h < criterias.length; h++) {
              const criteria = criterias[h];

              this.matrixValues[school.id+'_'+section.id+'_'+criteria.id] = '0';
            }
          }else{

            this.matrixValues[school.id+'_'+section.id] = '0';

          }
          
        }
      }

      console.log(this.matrixValues)

      // this.matrixValues = values.filter(element => element !== undefined)
    },
    saveMatrix: async function(){
      
      const token = localStorage.getItem("auth-token");
      if (token) {
        const config = {
          headers: {
              Authorization: "Bearer " + token,
              "content-type": "multipart/form-data"
          },
        };

        let formData = new FormData();
        formData.append( "matrice", JSON.stringify(this.matrixValues));
        formData.append( "label", this.label);
        formData.append( "date", this.date);
        await axios
        .post("/api/saveTrackingMatrix", formData , config)
        .then((response) => {
          this.$router.push('/tracking')
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    }
  },
  async mounted() {
    if (!this.$route.query.data) {
      await this.$router.push("/tracking/evaluation");
    }

    console.log(this.$route.query.data)

    await this.setupData(this.$route.query.data);
    await this.getSchoolsList();
    await this.initiateMatrix();
  },
};
</script>

<style>
td{
  min-width: 85px !important;
}
</style>