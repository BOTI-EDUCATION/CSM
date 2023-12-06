<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Founders connexion</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Founders</a>
          </li>
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">logs</a>
          </li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div
                  class="table-responsive"
                  style="scrollbar-color: red yellow"
                >
                  <v-table
                    class="table text-nowrap text-md-nowrap mb-2 text-center"
                    :data="connexions"
                    :currentPage.sync="currentPage"
                    :pageSize="10"
                    @totalPagesChanged="totalPages = $event"
                  >
                    <thead slot="head">
                      <tr>
                        <td>Username</td>
                        <td>School alias</td>
                        <td>IP adress</td>
                        <td>Device</td>
                        <td>Navigateur</td>
                        <td>Date</td>
                        <td style="width: 120px">User agent</td>
                      </tr>
                    </thead>
                    <tbody slot="body" slot-scope="{displayData}">
                      <tr v-for="row in displayData" :key="row.guid">
                        <td>{{ row.username }}</td>
                        <td>{{ row.school_alias }}</td>
                        <td>
                          <span class="badge bg-info">{{ row.ip }}</span>
                        </td>
                        <td>{{ row.device }}</td>
                        <td>{{ row.navigateur }}</td>
                        <td>
                          <span class="badge bg-success"> {{ row.date }}</span>
                        </td>
                        <td>
                          {{ row.user_agent }}
                        </td>
                      </tr>
                    </tbody>
                  </v-table>
                  <smart-pagination
                    :currentPage.sync="currentPage"
                    :totalPages="totalPages"
                    class="mb-2"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import Layout from "../../components/Layout.vue";
export default {
  components: {
    Layout,
  },
  data() {
    return {
      connexions: [],
      totalPages: 0,
      currentPage: 1,
    };
  },
  methods: {
    getList() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/founder/connexions", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.connexions = result.data;
            console.log(result.data);
          })
          .catch((error) => {
            error;
          });
      }
    },
  },
  mounted() {
    this.getList();
  },
  computed: {
    rows() {
      return this.connexions.length;
    },
  },
};
</script>

<style scoped>
/* tbody,
td,
tfoot,
th,
thead,
tr {
  border-color: inherit;
  border-style: solid;
  border-width: 3px;
} */

td p {
  word-break: break-all;
}



body::-webkit-scrollbar {
    width: 1em;
  }
   
  body::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  }
   
  body::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
  }
</style>