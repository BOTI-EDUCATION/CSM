<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <h4>
                <template v-if="!lead"> Add new </template>
                <template v-else> Edit </template>
                lead
              </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <form @submit="sendLead">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Name"
                      v-model="formControls.name"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="City"
                      v-model="formControls.city"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <small>First contact date</small>
                    <input
                      type="date"
                      class="form-control"
                      id=""
                      required
                      placeholder="First Contact"
                      v-model="formControls.first_contact_date"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <small>Last contact date</small>
                    <input
                      type="date"
                      class="form-control"
                      id=""
                      placeholder="Last Contact"
                      v-model="formControls.last_contact_date"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Année de lancement"
                      v-model="formControls.dateStart"
                    />
                  </div>
                  <div class="form-group col-md-6">
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
                  <div class="form-group col-md-6">
                    <vue-select
                      class=""
                      name="salesManager"
                      :reduce="(manager) => manager.id"
                      label="nom"
                      :options="salesManagers"
                      v-model="formControls.salesManager"
                      placeholder="Sales Manager"
                      searchable
                    >
                    </vue-select>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-12">
                        <h5>
                          <b>
                            Cycles & Pricing (effectif , scolarité ,
                            inscriptions) :
                          </b>
                        </h5>
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
                          v-model="formControls.iffnsriptionLycee"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-12">
                        <h5><b> Social Media : </b></h5>
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
      lead: {},
      salesManagers: [],
      cycles: [
        { alias: "creche", label: "Crèche" },
        { alias: "maternelle", label: "Maternelle" },
        { alias: "primaire", label: "Primaire" },
        { alias: "college", label: "Collège" },
        { alias: "lycee", label: "Lycée" },
        { alias: "sup", label: "sup" },
      ],
      formControls: {
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
        cycles: "",
        city: "",
        dateStart: "",
        presentation: "",
        adresse: "",
        salesManager: "",
        first_contact_date: "",
        last_contact_date: "",
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
    getLead: async function (leadId) {
      const token = localStorage.getItem("auth-token");
      if (leadId && token) {
        await axios
          .get("/api/getLeadFormInfo/" + leadId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.lead = result.data;
            this.formControls = {
              name: result.data.name,
              localisation: result.data.localisation,
              effectifCreche: result.data.effectif_creche,
              effectifMaternelle: result.data.effectif_maternelle,
              effectifPrimaire: result.data.effectif_primaire,
              effectifCollege: result.data.effectif_college,
              effectifLycee: result.data.effectif_lycee,
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
              cycles: result.data.cycles,
              city: result.data.city,
              presentation: result.data.presentation,
              adresse: result.data.adresse,
              logo: result.data.logo,
              salesManager: result.data.salesManager,
              first_contact_date: result.data.first_contact_date,
              last_contact_date: result.data.last_contact_date,
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
    sendLead: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");

      let formData = new FormData();
      const config = {
        headers: {
          Authorization: "Bearer " + token,
          "content-type": "multipart/form-data",
        },
      };

      if (this.lead.id) formData.append("lead", this.lead.id);

      formData.append("name", this.formControls.name);
      formData.append("dateStart", this.formControls.dateStart);
      formData.append("effectifCreche", this.formControls.effectifCreche);
      formData.append(
        "effectifMaternelle",
        this.formControls.effectifMaternelle
      );
      formData.append("effectifPrimaire", this.formControls.effectifPrimaire);
      formData.append("effectifCollege", this.formControls.effectifCollege);
      formData.append("effectifLycee", this.formControls.effectifLycee);
      formData.append("cycles", this.formControls.cycles);
      formData.append("city", this.formControls.city);
      formData.append("presentation", this.formControls.presentation);
      formData.append("adresse", this.formControls.adresse);
      formData.append("links", JSON.stringify(this.formControls.links));
      formData.append("logo", this.formControls.logo);
      formData.append("banner", this.formControls.banner);
      formData.append("localisation", this.formControls.localisation);
      formData.append("salesManager", this.formControls.salesManager);
      formData.append(
        "first_contact_date",
        this.formControls.first_contact_date
      );
      formData.append("last_contact_date", this.formControls.last_contact_date);

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
      };

      formData.append("pricing", JSON.stringify(prices));

      axios
        .post("/api/saveLead", formData, config)
        .then((response) => {
          // this.$router.push("/paramettrage/leads");
          this.$router.back();
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
    getSalesManagers: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSalesManagers", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.salesManagers = result.data;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
  },
  mounted() {
    this.getLead(this.$route.params.id);
    this.getSalesManagers();
  },
};
</script>