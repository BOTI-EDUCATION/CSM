<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-12 text-center">
              <h4>
                <b> {{ release.id ? "Edit Release" : "Add new Release" }} </b>
              </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
              <form @submit.prevent="newRelease">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <input
                      type="text"
                      class="form-control"
                      name="label"
                      required
                      placeholder="Label"
                      v-model="release.label"
                    />
                  </div>
                </div>

                <div class="form-row mb-3">
                  <div class="form-group col-md-12">
                    <p for="">Type</p>


                    <div
                      class="form-check-inline"
                      v-show="release"
                      v-for="ty in types"
                      :key="ty.id"
                    >
                      <input 
                        v-if="release.type_id"
                        class="form-check-input"
                        type="checkbox"
                        v-model="release.type_id"
                        :id="'inlineCheckbox' + ty.id"
                        :value="ty.type_alias"
                      />
                      <input
                        v-else
                        class="form-check-input"
                        type="checkbox"
                        v-model="typesId"
                        :id="'inlineCheckbox' + ty.id"
                        :value="ty.type_alias"
                      />
                      <label
                        class="form-check-label"
                        :for="'inlineCheckbox' + ty.id"
                      >
                        {{ ty.type_alias }}
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="mb-3" v-if="release.createdByUser">
                    <vue-select
                      class="form-control"
                      multiple
                      name="team"
                      :options="teams"
                      :reduce="(team) => team.avatar"
                      v-model="release.createdByUser"
                      label="fullname"
                      placeholder="Team Members"
                      searchable
                    >
                    </vue-select>
                  </div>

                  <div class="mb-3" v-else>
                    <vue-select
                      class="form-control"
                      multiple
                      name="team"
                      :options="teams"
                      :reduce="(team) => team.avatar"
                      v-model="members"
                      label="fullname"
                      placeholder="Team Members"
                      searchable
                    >
                    </vue-select>
                  </div>
                </div>

                <div class="col-md-12 mb-3">
                  <div class="row d-flex justify-content-between">
                    <div class="form-row col-md-12">
                      <div class="form-group text-center">
                        <label for="" class="fw-bold">Description CS</label>
                        <div v-if="release.desc_csm">
                          <vue-editor v-model="release.desc_csm"> </vue-editor>
                        </div>
                        <div v-else>
                          <vue-editor v-model="desc_cs"> </vue-editor>
                        </div>
                      </div>
                    </div>

                    <div class="form-row col-md-12">
                      <div class="form-group text-center">
                        <label for="" class="fw-bold">Description Tech </label>
                        <div v-if="release.desc_csm">
                          <vue-editor v-model="release.desc_tech"> </vue-editor>
                        </div>
                        <div v-else>
                          <vue-editor
                            v-model="desc_tech"
                            :value="release.desc_tech"
                          >
                          </vue-editor>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-row mb-3">
                  <div class="form-group col-md-12">
                    <label for="">Date</label>
                    <input
                      v-if="!release.date"
                      type="date"
                      class="form-control"
                      v-model="date"
                      @change="getDate"
                      required
                    />
                    <input
                      v-else
                      type="date"
                      class="form-control"
                      v-model="release.date"
                      required
                    />
                    <input
                      type="hidden"
                      name="id"
                      v-model="release.id"
                      v-if="release.id"
                    />
                  </div>
                </div>

                <div v-if="release.id">
                  <div class="form-row mb-3">
                    <label class="custom-switch form-switch me-5">
                      <span class="custom-switch-description me-4"
                        >Online
                      </span>
                      <input
                        type="checkbox"
                        name="custom-switch-radio1"
                        class="custom-switch-input"
                        v-model="release.status"
                        :checked="release.status"
                      />

                      <span
                        class="
                          custom-switch-indicator custom-switch-indicator-md
                        "
                      ></span>
                    </label>
                  </div>

                  <div class="form-row mb-3" v-if="release.status">
                    <div class="form-group col-md-12">
                      <div v-if="release.link">
                        <label for="">Link:</label>
                      </div>
                      <input
                        type="text"
                        name="link"
                        class="form-control"
                        placeholder="Link"
                        v-model="release.link"
                        required
                      />
                    </div>
                  </div>
                </div>
                <div v-if="!release.id">
                  <div class="form-row mb-3">
                    <label class="custom-switch form-switch me-5">
                      <span class="custom-switch-description me-4">Online</span>
                      <input
                        type="checkbox"
                        name="custom-switch-radio1"
                        class="custom-switch-input"
                        v-model="status"
                      />
                      <span
                        class="
                          custom-switch-indicator custom-switch-indicator-md
                        "
                      ></span>
                    </label>
                  </div>

                  <div class="form-row mb-3" v-if="status">
                    <div class="form-group col-md-12">
                      <input
                        type="text"
                        name="link"
                        class="form-control"
                        placeholder="Link"
                        required
                      />
                    </div>
                  </div>
                </div>

                <div class="form-row align-items-center mb-3">
                  <div class="form-group col-12">
                    <label for="" class="mb-3">Image</label>
                    <input
                      type="file"
                      class="form-control"
                      @change="selectImage"
                      accept="image/*.jpg,.png,"
                    />
                  </div>
                </div>

                <div class="form-group text-end">
                  <button
                    class="btn btn-primary-light"
                    @click.prevent="$router.back()"
                  >
                    cancel
                  </button>

                  <button class="btn btn-primary" v-if="!release.id">
                    enregistrer
                  </button>
                  <button class="btn btn-warning text-dark" v-if="release.id">
                    update
                  </button>
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
import { VueEditor } from "vue2-editor";
import vSelect, { VueSelect } from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    "vue-editor": VueEditor,
    "vue-select": VueSelect,
  },
  data() {
    return {
      types: [],
      teams: [],
      desc_tech: "",
      desc_cs: "",
      status: false,
      image: {},
      files: [],
      release: [],
      members: [],
      typesId: [],
      date:null,
      selectedDate:null,
    };
  },
  methods: {
    getDate(e){
      this.selectedDate = e.target.value;
      this.date = e.target.value;
    },
    getTypes() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/types", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.types = result.data;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    getTeams() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/teams", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.teams = result.data;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    newRelease(e) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formData = new FormData(e.target);
        this.release.id
          ? formData.append("members",this.release.createdByUser)
          : formData.append("members", this.members);
        this.release.type_id
          ? formData.append("types", this.release.type_id)
          : formData.append("types", this.typesId);
        this.release.desc_csm
          ? formData.append("desc_cs", this.release.desc_csm)
          : formData.append("desc_cs", this.desc_cs);
        this.release.desc_tech
          ? formData.append("desc_tech", this.release.desc_tech)
          : formData.append("desc_tech", this.desc_tech);
        this.release.id
          ? formData.append("status", this.release.status)
          : formData.append("status", this.status);
        this.release.date
          ? formData.append("date", this.release.date)
          : formData.append("date", this.date);
        formData.append("image", this.image);
        axios
          .post("/api/save/release", formData, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then(() => {
            this.$router.back();
          });
      }
    },
    selectImage(e) {
      let images = e.target.files[0];
      this.image = images;
    },
    getRelease(id) {
      const token = localStorage.getItem("auth-token");
      if (token && id) {
        axios
          .get("/api/release/item/" + id, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.release = result.data;
            console.log("test");
            console.log(result.data);
          })
          .catch((error) => {
            error;
          });
      }
    },
  },
  computed: {
    currentDay() {
      let date = new Date();
      let year = date.getFullYear();
      let month = date.getMonth() + 1 ;
      let day = date.getDate();
      day.toString().length == 1 ?  day = `0${day}` : day = day;
      month.toString().length == 1 ?  month = `0${month}` : month = month;
      let currentDay = `${year}-${month}-${day}`;
      this.date = currentDay;
    },
  },
  mounted() {
    this.getTypes();
    this.getRelease(this.$route.params.id);
    this.getTeams();
    this.currentDay();
  },
};
</script>


<style scoped>
</style>