<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Blog ({{this.countBlog}})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/blog"> Blog </router-link>
            </li>
        </ol>
      </div>
    </div>
    <router-view @load-posts="updateCount" > </router-view>
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
      countBlog : 0
    }
  },
  methods: {
    updateCount: function(count){
      this.countBlog = count;
    }
  },
};
</script>