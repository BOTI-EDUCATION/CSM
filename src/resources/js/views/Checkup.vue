<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Checkup</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/checkup"> Checkup </router-link>
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="container-fluid">
              <div class="row">
                  <div class="col-12 col-md-4">

                    <vue-select
                      class="bg-white"
                      multiple
                      name="modules"
                      label="label"
                      :reduce="(type) => type.alias"
                      :options="types"
                      v-model="selected_types"
                      placeholder="Types"
                      searchable
                    >
                    </vue-select>
                    
                  </div>
                  <div class="col-12 col-md-4">
                    

                    <vue-select
                      class="bg-white"
                      multiple
                      name="modules"
                      label="label"
                      :options="checklists"
                      v-model="selected_checklists"
                      placeholder="Checklists"
                      searchable
                    >
                    </vue-select>
                    
                  </div>
                  <div class="col-12 col-md-4">
                   
                   <button class="btn btn-primary" @click="getMatrice">
                     <i class="fe fe-filter"></i>
                   </button>
                    
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-12">
        <div class="card">
          <div class="card-body" style="overflow-x: scroll;">
            <table v-if="selected_checklists.length > 0"
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
                  <td class="school-td"  rowspan="2"></td>
                  <td
                    v-for="checklist in selected_checklists"
                    :key="checklist.id"
                    :colspan="checklist.items.length"
                  >
                    {{ checklist.label }}
                  </td>
                </tr>
                <tr>
                  <template v-for="checklist in selected_checklists">
                    <td v-for="item in checklist.items" :key="item.id">
                      {{ item.label }}
                    </td>
                  </template>
                </tr>
              </thead>
              <tbody>
                  <tr v-for="school in schools" :key="school.id">
                    <td class="school-td" > {{school.name}} </td>
                    <template v-for="checklist in selected_checklists">
                    <td v-for="item in checklist.items" :key="item.id">

                      <i v-if="matrixValues[`${school.id}_${item.id}`]" class="icon icon-check text-success"></i>
                      <i v-else class="icon icon-close text-danger"></i>

                    </td>
                  </template>
                  </tr>
              </tbody>
            </table>
            <h3 class="text-center" v-else>Please choose a checklist.</h3>
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
      schools: [],
      checklists: [],
      selected_checklists: [],
      types: [
        {
          'alias' : 'kinder',
          'label' : 'Kinder'
        },
        {
          'alias' : 'school',
          'label' : 'School'
        },
        {
          'alias' : 'campus',
          'label' : 'Campus'
        }
      ],
      selected_types: [],
      matrixValues: []
    };
  },
  methods: {
    getChecklistsList: async function () {
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
            console.log(this.checklists.length);
          })
          .catch(function (err) {});
      }
    },
    // getSchoolsList: async function () {
    //   const token = localStorage.getItem("auth-token");
    //   if (token) {
    //     await axios
    //       .get("/api/getSchoolsList", {
    //         headers: {
    //           Authorization: "Bearer " + token,
    //         },
    //       })
    //       .then(async (result) => {
    //         console.log(result);
    //         this.schools = result.data;
    //       })
    //       .catch(function (err) {});
    //   }
    // },
    getMatrice: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getCheckupMatrix", {
            params : {
              selected_types: this.selected_types
            },
            headers: {
              Authorization: "Bearer " + token,
            }
           } )
          .then(async (result) => {
            console.log(result);
            this.matrixValues = result.data.values;
            this.schools = result.data.schools;
          })
          .catch(function (err) {});
      }
    },
  },
  watch: {
    // selected_checklists(newVal, oldVal) {
    //   this.getMatrice();
    // },
    // selected_types(newVal, oldVal) {
    //   this.getMatrice();
    // },
  },
  mounted() {
    this.getChecklistsList();
    // this.getSchoolsList();
  },
};
</script>

<style scoped>
table td i {
  font-weight: bold;
}
table.table td {
  width: 85px !important;
  white-space: normal;
  vertical-align: middle;
  /* overflow-wrap: normal;
  word-wrap: normal; */
}
</style>