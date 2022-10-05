<template>
  <Layout ref="layout">
    <div class="page-header">
      <h1 class="page-title">Documentation</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">
            Documentation
          </li>
        </ol>
      </div>
    </div>
    <router-view></router-view>
  </Layout>
</template>

<script>
  import Layout from '../../components/Layout.vue'

export default {
  components: {
    Layout
  },
  data() {
    return {
    };
  },
  methods: {
   
    
  },
  mounted() {
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
</style>
