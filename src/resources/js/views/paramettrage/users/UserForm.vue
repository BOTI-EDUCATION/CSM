<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>Ajouter un utilisateur</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendUser" >
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Prénom"
                      v-model="formControls.prenom"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Nom"
                      v-model="formControls.nom"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Email"
                      v-model="formControls.email"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Téléphone"
                      v-model="formControls.telephone"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <select
                      name="country"
                      class="form-control form-select select2"
                      data-bs-placeholder="Role"
                      required
                      v-model="formControls.role"
                    >
                      <option
                        v-for="role in roles"
                        :key="role.id"
                        :value="role.id"
                      >
                        {{ role.label }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Fonction"
                      v-model="formControls.fonction"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <textarea
                      name=""
                      class="form-control"
                      placeholder="Adresse"
                      rows="3"
                      v-model="formControls.adresse"
                    ></textarea>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="" class="mb-3">Image</label>
                    <input type="file" class="form-control" @change="selectImage" id="">
                  </div>
                </div>
                <div class="form-group">
                  <label class="custom-switch form-switch me-5">
                    <span class="custom-switch-description me-4"
                      >Compte Actif</span
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
                  <router-link to="/paramettrage/users" class="btn btn-primary-light">annuler</router-link>
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
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
    
   
  components: {
      Dropzone: vue2Dropzone
  },
  data() {
    return {
      user: {},
      roles: [],
      formControls: {
        nom: "",
        prenom: "",
        email: "",
        telephone: "",
        role: "",
        fonction: "",
        adresse: "",
        enabled: "",
        image: {},
      },
      dropzoneOptions:{
          url : 'http://localhost:8000',
          addRemoveLinks: true
      }
    };
  },
  methods: {
    getRole: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getRolesList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.roles = result.data;
          })
          .catch(function (err) {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    getUser: async function (userId) {
      const token = localStorage.getItem("auth-token");
      if (userId && token) {
        await axios
          .get("/api/getUserFormInfo/" + userId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
              this.user = result.data;
            this.formControls = {
              nom: result.data.nom,
              prenom: result.data.prenom,
              email: result.data.email,
              telephone: result.data.telephone,
              role: result.data.role,
              fonction: result.data.fonction,
              adresse: result.data.adresse,
              enabled: result.data.enabled,
              image: result.data.image,
            };
            if(this.formControls.image){
                document.getElementsByName['image'][0].files = [this.formControls.image];
            }
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendUser: async function (e) {
        e.preventDefault();
      const token = localStorage.getItem("auth-token");
        
      let formData = new FormData();
      const config = {
        headers: {
            Authorization: "Bearer " + token,
            "content-type": "multipart/form-data"
        },
      };
        if(this.user.id)
       formData.append("user", this.user.id);

       formData.append("image", this.formControls.image);

      formData.append(
        "nom",
        this.formControls.nom
      );
      formData.append(
        "prenom",
        this.formControls.prenom
      );
      formData.append(
        "fonction",
        this.formControls.fonction
      );
      formData.append(
        "email",
        this.formControls.email
      );
      formData.append(
        "telephone",
        this.formControls.telephone
      );
      formData.append(
        "role",
        this.formControls.role
      );
      formData.append(
        "adresse",
        this.formControls.adresse
      );
      formData.append(
        "enabled",
        this.formControls.enabled
      );

      axios
        .post("/api/saveUser", formData, config)
        .then((response) => {
            this.$router.push("/paramettrage/users");
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectImage: function(e){
        let file = e.target.files[0];
        this.formControls.image = file;
    },
    removeImage: function(){
        this.formControls.image = {};
    }
  },
  mounted() {
    window.addEventListener("load", () => {
      $(".select2").select2();
    });
    this.getRole();
    this.getUser(this.$route.params.id).then(()=>{
      this.$emit('change-title',`${this.user.prenom} ${this.user.nom}`);
    });
  },
  destroyed() {
    this.$emit('change-title',`Family`);
    
  },
};
</script>