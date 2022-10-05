<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Usage Tracking</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/checkup"> Usage Tracking </router-link>
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-right">
      
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-body" style="overflow-x: scroll;">
            <table 
              class="
                table
                border
                table-bordered
                mb-0
                text-center
              "
            >
              <thead>
                <tr>
                  <th rowspan="2">Ecole</th>

                  <th colspan="2">Eleves</th>

                  <!-- <th colspan="3">M</th>
                  <th colspan="3">P</th>
                  <th colspan="3">C</th>
                  <th colspan="3">L</th> -->

                  <th colspan="2">Enseignants</th>

                  <th colspan="4">Transport</th>

                  <th colspan="4">Encaissement</th>

                  <th colspan="3">Matrice</th>

                </tr>
                <tr>

                  <th>T</th>
                  <th>A</th>

                  <!-- <th>E</th>
                  <th>C</th>
                  <th>EDT</th>
                  <th>E</th>
                  <th>C</th>
                  <th>EDT</th>
                  <th>E</th>
                  <th>C</th>
                  <th>EDT</th>
                  <th>E</th>
                  <th>C</th>
                  <th>EDT</th> -->

                  <th>T</th>
                  <th>A</th>

                  <th>T</th>
                  <th>E</th>
                  <th>C</th>
                  <th>A</th>

                  <th>S</th>
                  <th>G</th>
                  <th>I</th>
                  <th>S</th>

                  <th>E</th>
                  <th>EN</th>
                  <th>C</th>

                </tr>
              </thead>
              <tbody>
                 <tr v-for="school in schools" :key="school.school.id">
                    <td>
                        <div class="align-items-center d-flex" >
                          <img
                            :src="school.school.logo"
                            alt="profile-lead"
                            class="avatar profile-user brround cover-image me-2"
                          />
                          {{ school.school.name }}
                        </div>
                    </td>
                    <td> {{school.inscriptions_total}} </td>
                    <td> {{school.Inscriptions_sans_classe}} </td>
                    <!-- <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td>
                    <td> {{school.}} </td> -->
                    <td> {{school.enseignants_total}} </td>
                    <td> {{school.enseignants_affectes}} </td>
                    <td> {{school.trspt_trajets}} </td>
                    <td> {{school.trspt_vehicules}} </td>
                    <td> {{school.trspt_chauffeurs}} </td>
                    <td> {{school.trspt_aides}} </td>
                    <td> {{school.fnc_services}} </td>
                    <td> {{school.fnc_grille}} </td>
                    <td> {{school.fnc_import}} </td>
                    <td> {{school.fnc_saisie}} </td>
                    <td> {{school.matrice_eleves}} </td>
                    <td> {{school.matrice_enseignants}} </td>
                    <td> {{school.matrice_chauffeurs}} </td>
                 </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import Layout from "../components/Layout.vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    Layout,
    "vue-select": vSelect,
  },
  data() {
    return {
      schools: []
    };
  },
  methods: {
    getUsageTracking: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getUsageTracking", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.schools = result.data;
          })
          .catch(function (err) {});
      }
    }
  },
  mounted() {
    this.getUsageTracking()
  },
};
</script>

<style scoped>
table td i {
  font-weight: bold;
}
table.table td {
  /* width: 85px !important; */
  white-space: normal;
  vertical-align: middle;
  /* overflow-wrap: normal;
  word-wrap: normal; */
}
</style>