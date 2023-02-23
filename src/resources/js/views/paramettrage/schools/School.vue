<template>
  <Layout>
    <div class="page-header">
      <h1 v-if=" this.$route.matched.some(({ name }) => name === 'SchoolsList')" class="page-title">Schools ({{this.countSchools}})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/schools"> Schools </router-link>
          </li>
        </ol>
      </div>
    </div>
    <router-view @load-schools="updateCount" > </router-view>
  </Layout>
</template>

<script>
import Layout from "../../../components/Layout.vue";
export default {
  components: {
    Layout,
  },
  data() {
    return {
      countSchools : 0
    }
  },
  methods: {
    updateCount: function(count){
      this.countSchools = count;
    }
  },
};
</script>