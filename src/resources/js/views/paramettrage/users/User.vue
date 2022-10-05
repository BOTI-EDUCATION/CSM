<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">{{title}}</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/paramettrage/users"> Family </router-link>
            </li>
        </ol>
      </div>
    </div>
    <router-view @change-title="newTitle => this.title = newTitle"> </router-view>
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
      title : 'Family'
    }
  },
  methods: {
    changeTitle: function(title){
      this.title = title;
    }
  },
};
</script>