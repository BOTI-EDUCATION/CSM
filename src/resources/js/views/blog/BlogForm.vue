<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-12 text-center">
              <h4> <b> Add new post </b> </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
              <form @submit="sendPost" >
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Name"
                      v-model="formControls.label"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <input
                      type="date"
                      class="form-control"
                      id=""
                      required
                      placeholder="Date Publication"
                      v-model="formControls.date"
                    />
                  </div>
                </div>
                <div class="form-row mb-3">
                  <vue-editor
                    id="details"
                    v-model="formControls.details"
                  >
                  </vue-editor> 
                </div>
                <div class="form-row align-items-center">
                  
                  <div class="form-group col-12">
                    <label for="" class="mb-3">Image</label>
                    <input type="file" class="form-control" @change="selectImage" id="">
                  </div>
                  
                </div>

                <div class="form-row">
                  <label class="custom-switch form-switch me-5">
                    <span class="custom-switch-description me-4"
                      >Visible</span
                    >
                    <input
                      type="checkbox"
                      name="custom-switch-radio1"
                      class="custom-switch-input"
                      v-model="formControls.enabled"
                    />
                    <span
                      class="custom-switch-indicator custom-switch-indicator-md"
                    ></span>
                  </label>
                </div>
                <div class="form-group text-end">
                  <button @click="$event.preventDefault(); $router.back()" class="btn btn-primary-light">annuler</button>
                  <button class="btn btn-primary">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import { VueEditor } from "vue2-editor";

export default {
    
   
  components: {
    "vue-select":vSelect,
    "vue-editor": VueEditor
  },
  data() {
    return {
      post: {},
      formControls: {
        label: "",
        details: "",
        date: new Date().toISOString().split('T')[0],
        enabled: false,
        image: {},
        files: [],
      },
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
            this.formControls = {
              label: this.post.label,
              details: this.post.details,
              date: this.post.date,
              enabled: this.post.enabled,
              image: {},
              files: [],
            };
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendPost: async function (e) {
        e.preventDefault();
      const token = localStorage.getItem("auth-token");
        
      let formData = new FormData();
      const config = {
        headers: {
            Authorization: "Bearer " + token,
            "content-type": "multipart/form-data"
        },
      };

      if(this.post.id)
       formData.append("post", this.post.id);

      formData.append(
        "label",
        this.formControls.label
      );
      formData.append(
        "details",
        this.formControls.details
      );
      formData.append(
        "date",
        this.formControls.date
      );
      formData.append(
        "enabled",
        (this.formControls.enabled?1:0)
      );
      formData.append(
        "image",
        this.formControls.image
      );
      if(this.formControls.files){
        for (let index = 0; index < this.formControls.files.length; index++) {
          const element = this.formControls.files[index];
        formData.append(
          "files[]",
          element
        );
          
        }
      }


      axios
        .post("/api/savePost", formData, config)
        .then((response) => {
            // this.$router.push("/paramettrage/posts");
            this.$router.back();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectImage: function(e){
        let file = e.target.files[0];
        this.formControls.image = file;
    },
    selectFiles: function(e){
      // let files = [];
      // e.target.files.forEach(file => {
      //   files.push(file);
      // });
      //   this.formControls.files = files;
        this.formControls.files = e.target.files;
    },
    removeImage: function(){
        this.formControls.image = {};
    }
  },
  mounted() {
    this.getPost(this.$route.params.id);
  },
};
</script>

<style>

.ql-container{
  height: auto !important;
}

</style>