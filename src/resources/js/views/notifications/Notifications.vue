<template>
  <Layout ref="layout">
    <div class="page-header">
      <h1 class="page-title">Notifications</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">
            Notifications
          </li>
        </ol>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <ul class="notification">
          <li v-for="notif in notifications" :key="notif.id">
            <div class="notification-time">
              <span class="date">{{notif.date}}</span> <span class="time">{{notif.time}}</span>
            </div>
            <div class="notification-icon">
              <a href="javascript:void(0);"></a>
            </div>
            <div class="notification-time-date mb-2 d-block d-md-none">
              <span class="date">{{notif.date}}</span>
              <span class="time ms-2">{{notif.time}}</span>
            </div>
            <div class="notification-body">
              <div class="media mt-0">
                <div class="main-avatar avatar-md online">
                  <img
                    alt="avatar"
                    class="br-7"
                    :src="notif.school.img"
                  />
                </div>
                <div class="media-body ms-3 d-flex">
                  <div class="">
                    <p class="fs-15 text-dark fw-bold mb-0">{{notif.label}}</p>
                    <p class="mb-0 fs-13 text-dark" v-html="notif.details"></p>
                    <div v-if="notif.dure">
                      <p class="badge bg-info">Duration:
                        {{ notif.dure }} 
                      </p>
                    </div>
                 
                  </div>
                  <div class="notify-time d-flex align-items-center justify-content-center">

                  </div>
                </div>
                <div class="main-avatar avatar-md online">
                  <img
                    alt="avatar"
                    class="br-7"
                    :src="notif.collaborateur.img"
                    />
                </div>
              </div>
            </div>
          </li>
        </ul>
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
      notifications : {}
    };
  },
  methods: {
    getNotifications : async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getNotifList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.notifications = result.data;
            console.log(this.notifications)
          })
          .catch(function (err) {

          });
      }
    },
  },
  computed:{
      
  },
  mounted() {
    this.getNotifications();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
</style>
