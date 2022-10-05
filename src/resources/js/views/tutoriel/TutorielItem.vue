<template>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h2 class="mb-0">{{tutoriel.label}} 
          </h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 ">
              <iframe :src="tutoriel.video" style="width: 100%;"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>


export default {
  components: {
  },
  data() {
    return {
     tutoriel: {}
    };
  },
  methods: {
    getTutoriel: async function (tutorielId) {
      const token = localStorage.getItem("auth-token");
      if (tutorielId && token) {
        await axios
          .get("/api/getTutorielFormInfo/" + tutorielId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.tutoriel = result.data;
          })
          .catch(function (err) {
          });
      }
    },
  },
  mounted() {

    this.getTutoriel(this.$route.params.id);
    // this.initCharts();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
.profile-cover__img .profile-img-1 {
  position: relative;
}
.profile-cover__img .profile-img-1 > img {
  margin-left: 0px;
}
.profile-cover__img .profile-img-1 > span {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  width: auto;
  margin: auto;
  display: block;
  text-align: center;
}
.profile-cover__img .profile-img-content {
  margin-left: 20px;
}
.main-profile-contact-list.d-flex > div {
  flex: 1;
}
.princing-item{
  height: 100%;
  border-radius: 10px;
}
</style>