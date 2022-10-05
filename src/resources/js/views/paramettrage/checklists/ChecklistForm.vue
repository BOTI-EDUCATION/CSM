<template>
  <div class="row">
    <form @submit="sendChecklist">
      <div class="col-md-12 col-sm-12 text-right mb-3">
        <router-link to="/paramettrage/checklists" class="btn btn-primary-light"
          >annuler</router-link
        >
        <button class="btn btn-primary">Enregistrer</button>
      </div>
      <div class="col-md-12 col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <h4>Ajouter une nouvelle checklist</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      required
                      placeholder="Label"
                      v-model="formControls.label"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="Icone"
                      v-model="formControls.icone"
                    />
                  </div>
                  <div class="form-group col-md-12">
                    <input
                      type="text"
                      class="form-control"
                      id=""
                      placeholder="presentation"
                      v-model="formControls.presentation"
                    />
                  </div>
                  <div class="form-group col-md-12">
                    <input
                      type="checkbox"
                      class="form-control"
                      v-model="formControls.required"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row mb-4">
              <div class="col-md-4 offset-md-3 d-flex align-items-center" >
                <h4>Items</h4>
              </div>
              <div class="col-md-2" >
                <button
                  type="button"
                  class="btn btn-primary ms-auto"
                  @click="addQuestion"
                >
                  <i class="fe fe-add"></i> Add
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6 offset-md-3">
                <draggable v-model="formControls.questions">
                  <transition-group>
                    <div class="row mb-2 align-items-center"
                        v-for="(element, idx) in formControls.questions"
                        :key="element.id"
                      >
                      <div class="col-1">
                        <i class="fe fe-move" style="cursor: pointer;" ></i>&nbsp;&nbsp;&nbsp;<span class="me-auto">{{idx+1}}</span>
                      </div>
                      <div class="col-7">
                        <input
                          type="text"
                          v-model="element.label"
                          class="form-control"
                          required
                        />
                      </div>
                      <div class="col-2">
                        <button type="button" class="btn btn-danger" @click="removeQuestion(idx)" > <i class="fe fe-trash"></i> </button>
                      </div>
                    </div>
                  </transition-group>
                </draggable>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
  components: {
    draggable,
  },
  data() {
    return {
      checklist: {},
      idCheck: 0,
      formControls: {
        label: "",
        icone: "",
        presentation: "",
        required: "",
        questions: [],
      },
    };
  },
  methods: {
    getChecklist: async function (checklistId) {
      const token = localStorage.getItem("auth-token");
      if (checklistId && token) {
        await axios
          .get("/api/getChecklistFormInfo/" + checklistId, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.checklist = result.data;
            this.formControls = {
              label: result.data.label,
              icone: result.data.icone,
              presentation: result.data.presentation,
              required: result.data.required,
              questions: result.data.questions,
            };
            this.idCheck = this.formControls.questions.length - 1;
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    sendChecklist: async function (e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");

      let formData = new FormData();
      const config = {
        headers: {
          Authorization: "Bearer " + token,
          "content-type": "multipart/form-data",
        },
      };
      if (this.checklist.id) formData.append("checklist", this.checklist.id);

      formData.append("label", this.formControls.label);
      formData.append("icone", this.formControls.icone);
      formData.append("presentation", this.formControls.presentation);
      formData.append("required", this.formControls.required);
      formData.append("questions", JSON.stringify(this.formControls.questions));

      axios
        .post("/api/saveChecklist", formData, config)
        .then((response) => {
          this.$router.push("/paramettrage/checklists");
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addQuestion: function () {
      this.idCheck++;
      this.formControls.questions.push({
        id: this.idCheck++,
        item: null,
        label: "",
      });
    },
    removeQuestion: function(idx){
      this.formControls.questions.splice(idx, 1);
    }
  },
  mounted() {
    this.getChecklist(this.$route.params.id);
  },
};
</script>