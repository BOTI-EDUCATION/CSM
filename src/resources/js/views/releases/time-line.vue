<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Releases</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">
            Releases
          </li>
        </ol>
      </div>
    </div>


    <router-view></router-view>

  


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
      releases: [],
    };
  },
  methods: {
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
  },
  mounted() {

  },
};
</script>

<style scoped>
</style>