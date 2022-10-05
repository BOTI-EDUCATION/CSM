<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Leads  <span v-if="showCount">({{this.countLeads}})</span></h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/leads"> Leads </router-link>
            </li>
        </ol>
      </div>
    </div>
    <router-view @load-leads="updateCount" > </router-view>
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
      showCount : false,
      countLeads : 0
    }
  },
  methods: {
    updateCount: function(count){
      this.showCount = true;
      this.countLeads = count;
    }
  },
};
</script>