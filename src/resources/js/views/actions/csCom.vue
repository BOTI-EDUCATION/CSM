<template>
  <Layout>
    <div>
      <div class="card">
        <div class="card-body">
          <div class="row"></div>
        </div>
      </div>

      <div class="card mt-2">
        <div class="card-body">
          <div class="table-responsive">
            <v-table
              class="table text-nowrap text-md-nowrap mb-2 text-center"
              :data="interventions"
              :currentPage.sync="currentPage"
              :pageSize="10"
              @totalPagesChanged="totalPages = $event"
            >
              <thead slot="head">
                <tr>
                  <td>School</td>
                  <td>Manager</td>
                  <td>Image</td>
                  <td>Label</td>
                  <td>Durée</td>
                  <td>Infos</td>
                  <td>state</td>
                  <td>Date</td>
                  <td>Details</td>
                  <td>Actions</td>
                </tr>
              </thead>
              <tbody slot="body" slot-scope="{ displayData }">
                <tr v-for="row in displayData" :key="row.guid">
                  <td><img :src="row.school.image" /></td>

                  <td>
                    <img
                      :src="row.manager"
                      class="avatar bg-white avatar-xxl bradius cover-image me-3"
                    />
                  </td>

                  <td>
                    <div v-if="row.image">
                      <img
                      :src="row.image"
                      alt=""
                      srcset=""
                      class="avatar bg-white avatar-xxl bradius cover-image me-3"
                      />
                    </div>
                  </td>
                  
                  <td>{{ row.label }}</td>
                  <td>{{ row.duration }}</td>
                  <td>
                    <span
                      class="badge rounded-pill bg-primary badge-sm me-1 mb-3"
                    >
                      {{ row.type }}</span
                    >
                    <span
                      class="badge rounded-pill bg-primary badge-sm me-1 mb-3"
                    >
                      {{ row.nature }}</span
                    >
                    <span
                      class="badge rounded-pill bg-primary badge-sm me-1 mb-3"
                    >
                      {{ row.channel }}</span
                    >
                  </td>
                  <td> <span class="badge rounded-pill bg-info badge-sm me-1 mb-3">{{ row.state }}</span> </td>
                  <td>{{ row.date }}</td>
                  <td>{{ row.detail }}</td>
                  <td></td>
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
      interventions: [],
      totalPages: 0,
      currentPage: 1,
    };
  },
  methods: {
    getInterventions: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/actions/cs", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.interventions = result.data;
          })
          .catch((err) => {});
      }
    },
  },
  mounted() {
    this.getInterventions();
  },
  computed: {
    rows() {
      return this.interventions.length;
    },
  },
};
</script>
  


<style scoped>
</style>