<template>

  <div class="row">
    <div class="col-12 text-right mb-4">
      <router-link class="btn btn-primary" :to="'/blog/edit/'+post.id">
        <i class="fe fe-edit"></i> edit
      </router-link>
      <button v-if="post.enabled" class="btn btn-warning" @click="changeVisibility(false)">
        <i class="fe fe-eye-off"></i> hide
      </button>
      <button v-else class="btn btn-success" @click="changeVisibility(true)">
        <i class="fe fe-eye"></i> show
      </button>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h2 class="mb-0">{{post.label}} <small> ({{post.alias}}) - {{post.date}} </small> 
            <span v-if="post.enabled" class="badge bg-success badge-sm  me-1 mb-1 mt-1">Visible</span>
            <span v-else class="badge bg-danger badge-sm  me-1 mb-1 mt-1">Hidden</span>
          </h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-4">
              <h4> Image : </h4> 
              <img :src="post.image" alt="" class="img-fluids">
            </div>
            <div class="col-12 col-md-8" v-html="post.details"></div>
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
     post: {}
    };
  },
  methods: {
    getPost: async function (postId) {
      const token = localStorage.getItem("auth-token");
      if (postId && token) {
        await axios
          .get("/api/getPostFormInfo/" + postId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.post = result.data;
          })
          .catch(function (err) {
            this.$router.push("/blog");
          });
      }
    },
    changeVisibility: async function(visibility){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .post("/api/changePostVisibility/" + this.post.id, {
            visibility:visibility
          } , {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.post = result.data;
          })
          .catch(function (err) {
            this.$router.push("/blog");
          });
      }
    }
  },
  mounted() {

    this.getPost(this.$route.params.id);
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