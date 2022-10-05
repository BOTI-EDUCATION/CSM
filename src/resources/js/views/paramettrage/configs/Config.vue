<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Configs</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/paramettrage/configs"> Configs </router-link>
            </li>
        </ol>
      </div>
    </div>
    <router-view> </router-view>
  </Layout>
</template>

<script>
import Layout from "../../../components/Layout.vue";
export default {
  components: {
    Layout,
  },
};
</script>