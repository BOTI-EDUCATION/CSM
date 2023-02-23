<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Tutoriel ({{this.countTutoriel}})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/blog"> Tutoriel </router-link>
            </li>
        </ol>
      </div>
    </div>
    <router-view @load-tutoriels="updateCount" > </router-view>
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
      countTutoriel : 0
    }
  },
  methods: {
    updateCount: function(count){
      this.countTutoriel = count;
    }
  },
};
</script>