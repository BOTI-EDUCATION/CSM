<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Export Excel ({{ actions.length }})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Actions log</a>
          </li>
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Export Excel</a>
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
                    :data="actions"
                    :currentPage.sync="currentPage"
                    :pageSize="10"
                    @totalPagesChanged="totalPages = $event"
                  >
                    <thead slot="head">
                      <tr>
                        <td>Username</td>
                        <td>School</td>
                        <td>Detail</td>
                        <td>Type</td>
                        <td>Line Exported</td>
                        <td>Date</td>
                      </tr>
                    </thead>
                    <tbody slot="body" slot-scope="{ displayData }">
                      <tr v-for="row in displayData" :key="row.guid">
                        <td>{{ row.user }}</td>
                        <td><span class="badge bg-info">{{ row.school }} </span></td>
                        <td>
                          {{ row.detail }}
                        </td>
                        <td><span class="badge bg-primary">{{ row.type }}</span></td>
                        <td><span class="badge bg-warning text-dark fw-bold">{{ row.line }}</span></td>
                        <td>
                           {{ row.date }}
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
      actions: [],
      totalPages: 0,
      currentPage: 1,
    };
  },
  methods: {
    getList() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/actions/export", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.actions = result.data;
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
      return this.actions.length;
    },
  },
};
</script>
  
  <style scoped>
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