<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>Add new school</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendSchool">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Name"
                      v-model="formControls.name"
                    />
                  </div>
                  <div class="form-group col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="City"
                      v-model="formControls.city"
                    />
                  </div>
                  <div class="form-group col-md-4">
                    <vue-select
                      class=""
                      multiple
                      name="Types"
                      :options="types"
                      v-model="formControls.types"
                      placeholder="Types"
                      searchable
                    >
                    </vue-select>
                  </div>
                  <div class="form-group col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Année de lancement"
                      v-model="formControls.dateStart"
                    />
                  </div>
                  <div class="form-group col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Année d'intégration à Boti"
                      v-model="formControls.dateStartBoti"
                    />
                  </div>
                  <div class="form-group col-md-4">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Latitude,Longitude"
                      v-model="formControls.localisation"
                    />
                  </div>
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
                    <textarea
                      name=""
                      class="form-control"
                      placeholder="Presentation"
                      rows="3"
                      v-model="formControls.presentation"
                    ></textarea>
                  </div>
                  <div class="form-group col-md-6">
                    <vue-select
                      class=""
                      name="accountManager"
                      :reduce="(accountManager) => accountManager.id"
                      label="nom"
                      :options="accountManagers"
                      v-model="formControls.accountManager"
                      placeholder="Account Manager"
                      searchable
                    >
                    </vue-select>
                  </div>
                  <div class="form-group col-md-6">
                    <vue-select
                      class=""
                      multiple
                      name="modules"
                      :reduce="(cycle) => cycle.alias"
                      label="label"
                      :options="cycles"
                      v-model="formControls.cycles"
                      placeholder="Cycles"
                      searchable
                    >
                    </vue-select>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-12">
                        <h5> <b> Cycles & Pricing (effectif , scolarité , inscriptions) : </b> </h5>
                      </div>
                    </div>
                    <div
                      class="row"
                      v-if="formControls.cycles.includes('creche')"
                    >
                      <div class="col-md-3">
                        <label for=""> Crèche </label>
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Effectif Crèche"
                          v-model="formControls.effectifCreche"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Price Crèche"
                          v-model="formControls.priceCreche"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Inscription Crèche"
                          v-model="formControls.inscriptionCreche"
                        />
                      </div>
                    </div>
                    <div
                      class="row"
                      v-if="formControls.cycles.includes('maternelle')"
                    >
                      <div class="col-md-3">
                        <label for=""> Maternelle </label>
                      </div>
                      <div
                        class="form-group col-md-3"
                        v-if="formControls.cycles.includes('maternelle')"
                      >
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Effectif Maternelle"
                          v-model="formControls.effectifMaternelle"
                        />
                      </div>
                      <div
                        class="form-group col-md-3"
                        v-if="formControls.cycles.includes('maternelle')"
                      >
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Price Maternelle"
                          v-model="formControls.priceMaternelle"
                        />
                      </div>
                      <div
                        class="form-group col-md-3"
                        v-if="formControls.cycles.includes('maternelle')"
                      >
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Inscription Maternelle"
                          v-model="formControls.inscriptionMaternelle"
                        />
                      </div>
                    </div>
                    <div
                      class="row"
                      v-if="formControls.cycles.includes('primaire')"
                    >
                      <div class="col-md-3">
                        <label for=""> Primaire </label>
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Effectif Primaire"
                          v-model="formControls.effectifPrimaire"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Price Primaire"
                          v-model="formControls.pricePrimaire"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Inscription Primaire"
                          v-model="formControls.inscriptionPrimaire"
                        />
                      </div>
                    </div>
                    <div
                      class="row"
                      v-if="formControls.cycles.includes('college')"
                    >
                      <div class="col-md-3">
                        <label for=""> Collège </label>
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Effectif College"
                          v-model="formControls.effectifCollege"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Price College"
                          v-model="formControls.priceCollege"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Inscription College"
                          v-model="formControls.inscriptionCollege"
                        />
                      </div>
                    </div>
                    <div
                      class="row"
                      v-if="formControls.cycles.includes('lycee')"
                    >
                      <div class="col-md-3">
                        <label for=""> Lycee </label>
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Effectif Lycée"
                          v-model="formControls.effectifLycee"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Price Lycée"
                          v-model="formControls.priceLycee"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Insription Lycée"
                          v-model="formControls.inscriptionLycee"
                        />
                      </div>
                    </div>
                    <div
                      class="row"
                      v-if="formControls.cycles.includes('sup')"
                    >
                      <div class="col-md-3">
                        <label for=""> Campus </label>
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Effectif Campus"
                          v-model="formControls.effectifSup"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Price Campus"
                          v-model="formControls.priceSup"
                        />
                      </div>
                      <div class="form-group col-md-3">
                        <input
                          type="number"
                          class="form-control"
                          id=""
                          placeholder="Insription Campus"
                          v-model="formControls.inscriptionSup"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-12">
                        <h5> <b> Social Media : </b> </h5>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-12">
                        <input
                          type="text"
                          class="form-control"
                          id=""
                          placeholder="Facebook"
                          v-model="formControls.links.facebook"
                        />
                      </div>
                      <div class="form-group col-md-12">
                        <input
                          type="text"
                          class="form-control"
                          id=""
                          placeholder="Instagram"
                          v-model="formControls.links.instagram"
                        />
                      </div>
                      <div class="form-group col-md-12">
                        <input
                          type="text"
                          class="form-control"
                          id=""
                          placeholder="Youtube"
                          v-model="formControls.links.youtube"
                        />
                      </div>
                      <div class="form-group col-md-12">
                        <input
                          type="text"
                          class="form-control"
                          id=""
                          placeholder="Website"
                          v-model="formControls.links.website"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row align-items-center">
                  <div class="form-group col-md-4">
                    <label for="">Version IOS</label>
                    <input
                      name=""
                      class="form-control"
                      placeholder="Version IOS"
                      rows="3"
                      v-model="formControls.version_ios"
                    />
                  </div>
                  <div class="form-group col-md-4">
                    <label for="">Version Android</label>
                    <input
                      name=""
                      class="form-control"
                      placeholder="Version Android"
                      rows="3"
                      v-model="formControls.version_android"
                    />
                  </div>
                  <div class="form-group col-md-4">
                    <label for="">Web</label>
                    <input
                      name=""
                      class="form-control"
                      placeholder="Web"
                      rows="3"
                      v-model="formControls.web_link"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="" class="mb-3">Image</label>
                    <input
                      type="file"
                      class="form-control"
                      @change="selectImage"
                      id=""
                    />
                  </div>

                  <div class="form-group col-md-6">
                    <label for="" class="mb-3">Banner</label>
                    <input
                      type="file"
                      class="form-control"
                      @change="selectbanner"
                      id=""
                    />
                  </div>
                </div>
                <div class="form-group text-end">
                  <button
                    @click="
                      $event.preventDefault();
                      $router.back();
                    "
                    class="btn btn-primary-light"
                  >
                    annuler
                  </button>
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
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    Dropzone: vue2Dropzone,
    "vue-select": vSelect,
  },
  data() {
    return {
      school: {},
      accountManagers: [],
      types: [
        'kinder',
        'school',
        'campus'
      ],
      cycles: [
        { alias: "creche", label: "Crèche" },
        { alias: "creche", label: "Crèche" },
        { alias: "maternelle", label: "Maternelle" },
        { alias: "primaire", label: "Primaire" },
        { alias: "college", label: "Collège" },
        { alias: "lycee", label: "Lycée" },
        { alias: "sup", label: "Campus" },
      ],
      formControls: {
        types: [],
        name: "",
        localisation: "",
        effectifMaternelle: "",
        effectifPrimaire: "",
        effectifCollege: "",
        effectifLycee: "",
        effectifCreche: "",
        priceMaternelle: "",
        inscriptionMaternelle: "",
        pricePrimaire: "",
        inscriptionPrimaire: "",
        priceCollege: "",
        inscriptionCollege: "",
        priceLycee: "",
        inscriptionLycee: "",
        priceCreche: "",
        inscriptionCreche: "",
        priceSup: "",
        inscriptionSup: "",
        cycles: "",
        city: "",
        dateStart: "",
        dateStartBoti: "",
        presentation: "",
        adresse: "",
        accountManager: "",
        version_ios: "",
        version_android: "",
        web_link: "",
        links: {
          facebook: "",
          instagram: "",
          youtube: "",
          website: "",
        },
        logo: {},
        banner: {},
      },
      dropzoneOptions: {
        url: "http://localhost:8000",
        addRemoveLinks: true,
      },
    };
  },
  methods: {
    getSchool: async function (schoolId) {
      const token = localStorage.getItem("auth-token");
      if (schoolId && token) {
        await axios
          .get("/api/getSchoolFormInfo/" + schoolId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.school = result.data;
            this.formControls = {
              types: result.data.types,
              name: result.data.name,
              localisation: result.data.localisation,
              effectifCreche: result.data.effectif_creche,
              effectifMaternelle: result.data.effectif_maternelle,
              effectifPrimaire: result.data.effectif_primaire,
              effectifCollege: result.data.effectif_college,
              effectifLycee: result.data.effectif_lycee,
              effectifSup: result.data.effectif_sup,
              priceSup: result.data.price_sup,
              inscriptionSup: result.data.inscription_sup,
              priceCreche: result.data.price_creche,
              inscriptionCreche: result.data.inscription_creche,
              priceMaternelle: result.data.price_maternelle,
              inscriptionMaternelle: result.data.inscription_maternelle,
              pricePrimaire: result.data.price_primaire,
              inscriptionPrimaire: result.data.inscription_primaire,
              priceCollege: result.data.price_college,
              inscriptionCollege: result.data.inscription_college,
              priceLycee: result.data.price_lycee,
              inscriptionLycee: result.data.inscription_lycee,
              dateStart: result.data.dateStart,
              dateStartBoti: result.data.dateStartBoti,
              cycles: result.data.cycles,
              city: result.data.city,
              presentation: result.data.presentation,
              adresse: result.data.adresse,
              logo: result.data.logo,
              accountManager: result.data.accountManager,
              version_ios: result.data.version_ios,
              version_android: result.data.version_android,
              web_link: result.data.web_link,
              links: {
                facebook: result.data.links.facebook,
                instagram: result.data.links.instagram,
                youtube: result.data.links.youtube,
                website: result.data.links.website,
              },
            };
            if (this.formControls.logo) {
              document.getElementsByName["image"][0].files = [
                this.formControls.logo,
              ];
            }
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    getAccountManagers: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getAccountManagers", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.accountManagers = result.data;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendSchool: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");

      let formData = new FormData();
      const config = {
        headers: {
          Authorization: "Bearer " + token,
          "content-type": "multipart/form-data",
        },
      };

      if (this.school.id) formData.append("school", this.school.id);

      formData.append("name", this.formControls.name);
      formData.append("dateStart", this.formControls.dateStart);
      formData.append("dateStartBoti", this.formControls.dateStartBoti);
      formData.append("effectifCreche", this.formControls.effectifCreche);
      formData.append(
        "effectifMaternelle",
        this.formControls.effectifMaternelle
      );
      formData.append("effectifPrimaire", this.formControls.effectifPrimaire);
      formData.append("effectifCollege", this.formControls.effectifCollege);
      formData.append("effectifLycee", this.formControls.effectifLycee);
      formData.append("effectifSup", this.formControls.effectifSup);
      formData.append("cycles", this.formControls.cycles);
      formData.append("types", this.formControls.types);
      formData.append("city", this.formControls.city);
      formData.append("presentation", this.formControls.presentation);
      formData.append("adresse", this.formControls.adresse);
      formData.append("links", JSON.stringify(this.formControls.links));
      formData.append("accountManager", this.formControls.accountManager);
      formData.append("logo", this.formControls.logo);
      formData.append("banner", this.formControls.banner);
      formData.append("localisation", this.formControls.localisation);
      formData.append("version_android", this.formControls.version_android);
      formData.append("web_link", this.formControls.web_link);
      formData.append("version_ios", this.formControls.version_ios);

      let prices = {
        creche: {
          price: this.formControls.priceCreche,
          inscription: this.formControls.inscriptionCreche,
        },
        maternelle: {
          price: this.formControls.priceMaternelle,
          inscription: this.formControls.inscriptionMaternelle,
        },
        primaire: {
          price: this.formControls.pricePrimaire,
          inscription: this.formControls.inscriptionPrimaire,
        },
        college: {
          price: this.formControls.priceCollege,
          inscription: this.formControls.inscriptionCollege,
        },
        lycee: {
          price: this.formControls.priceLycee,
          inscription: this.formControls.inscriptionLycee,
        },
        sup: {
          price: this.formControls.priceSup,
          inscription: this.formControls.inscriptionSup,
        },
      };

      formData.append("pricing", JSON.stringify(prices));

      axios
        .post("/api/saveSchool", formData, config)
        .then((response) => {
          this.$router.push("/schools");
          // this.$router.back();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectImage: function (e) {
      let file = e.target.files[0];
      this.formControls.logo = file;
    },
    selectbanner: function (e) {
      let file = e.target.files[0];
      this.formControls.banner = file;
    },
    removeImage: function () {
      this.formControls.logo = {};
    },
  },
  mounted() {
    this.getSchool(this.$route.params.id);
    this.getAccountManagers();
  },
};
</script>