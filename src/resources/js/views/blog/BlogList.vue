<template>
  <div>
    <div class="row mb-4">
      <div class="col-12 offset-md-7 col-md-3 text-right">
        <input type="text" @keyup="filterPosts" placeholder="Search ..." class="form-control">
      </div>
      <div class="col-12 col-md-2 col-lg-2 col-xl-2 text-right">
        <router-link class="btn btn-block btn-primary" to="/blog/add">
          <i class="fe fe-plus"></i>
          Add a new post
        </router-link>
      </div>
    </div>
    <div class="row">
      <div
        v-for="post in this.filteredPosts"
        :key="post.id"
        class="col-sm-12 col-md-3"
      >
      <router-link class="text-info" :to="'/blog/view/' + post.id">
        <div class="card hoverable full-row-card user-card overflow-hidden">
          <div class="card-body text-center">
            <img :src="post.image" class="img-fluid profile mb-2" alt="img" />
            <br>
            <span v-if="post.enabled" class="badge bg-success badge-sm  me-1 mb-1 mt-1">Visible</span>
            <span v-else class="badge bg-danger badge-sm  me-1 mb-1 mt-1">Hidden</span>

            <br>

            <h5 class="card-title text-dark mb-2">{{ post.label }}</h5>
            <!-- <p>{{ post.presentation }}</p> -->
            <!-- <span class="tag tag-blue mb-2">{{ post.role }}</span> -->
            <div class="text-center">
            <span class="badge bg-primary badge-sm  me-1 mb-1 mt-1"><i class="fe fe-eye me-1"></i> {{post.views}}</span>
              
              <!-- <router-link :to="'/paramettrage/posts/edit/' + post.id">
                <i class="fe fe-edit-2"></i>
              </router-link>
              
              <a
                @click="deletepost($event, post.id)"
                href=""
                class="text-danger"
              >
                <i class="fe fe-trash-2"></i>
              </a> -->
            </div>
          </div>
        </div>    
      </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  emits:[ 'load-posts' ],
  data() {
    return {
      posts: [],
      filteredPosts: []
    };
  },
  methods: {
    getpostsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getpostsList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.posts = result.data;
            this.filteredPosts = this.posts;
            this.$emit('load-posts',this.posts.length)
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    deletepost: async function (e, id) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer ce post",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Supprimer`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post("/api/deletepost/" + id, {} , {
                headers: {
                  Authorization: "Bearer " + token,
                }
              })
              .then((result) => {
                this.posts = result.data;
              })
              .catch(function (err) {
                console.log(token)
              });
          }
        });
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    filterPosts: function(e){
      let value = e.target.value;
      this.filteredPosts = this.posts.filter((post=>{
        return post.label.toLowerCase().includes(value.toLowerCase());
      }))
    }
  },
  mounted() {
    this.getpostsList();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
.profile {
  max-height: 165px;
}
.user-card .actions {
  display: flex;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}
.user-card .actions > * {
  flex: 1;
}
a .hoverable{
  transition: all ease-in-out .4s;
}
a .hoverable:hover{
      box-shadow: 0px 6px 15px 0px #33333344;
}
</style>