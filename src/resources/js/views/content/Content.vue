<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Content ({{this.contContent}})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/content"> Content Library </router-link>
            </li>
        </ol>
      </div>
    </div>
    <router-view @load-contents="updateCount" > </router-view>
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
      contContent : 0
    }
  },
  methods: {
    updateCount: function(count){
      this.contContent = count;
    }
  },
};
</script>