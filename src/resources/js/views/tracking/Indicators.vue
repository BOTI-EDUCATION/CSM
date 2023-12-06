
<template>
  <div>
    <div class="page-header">
      <h1 class="page-title">Indicators ({{ indicators.length }})</h1>

      <div class="row mb-4">
        <div class="col-sm-12 text-right">
          <a class="btn btn-primary" @click="displayModel">
            <i class="fe fe-plus"></i>
            Add new Indicator
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table text-nowrap text-md-nowrap mb-0">
                    <thead>
                      <tr>
                        <th>Alias</th>
                        <th>Label</th>
                        <th>Presentation</th>
                        <th>Rubrique</th>
                        <th>Value type</th>
                        <th>Threshold</th>
                        <th>Order</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="indicator in indicators" :key="indicator.id">
                        <td>{{ indicator.alias }}</td>
                        <td>{{ indicator.label }}</td>
                        <td>{{ indicator.presentation }}</td>
                        <td>{{ indicator.rubrique }}</td>
                        <td>{{ indicator.value_type }}</td>
                        <td>{{ indicator.threshold }}</td>
                        <td>{{ indicator.order }}</td>

                        <td>
                          <button
                            class="btn btn-warning"
                            @click="showIndicator(indicator)"
                          >
                            Edit
                          </button>
                          <button
                            class="btn btn-danger"
                            @click="deleteIndicator(indicator.id)"
                          >
                            Delete
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- New Modal -->
    <transition
      @enter="startTransitionModal($refs.indicator)"
      @after-enter="endTransitionModal($refs.indicator)"
      @before-leave="endTransitionModal($refs.indicator)"
      @after-leave="startTransitionModal($refs.indicator)"
    >
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        ref="indicator"
        v-if="typeModal"
      >
        <div class="modal-dialog">
          <div class="modal-content" style="width: 700px">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ title }}
              </h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
                @click="typeModal = false"
              ></button>
            </div>
            <form @submit.prevent="newIndicator">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Label</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="label"
                    v-model="indicator.label"
                    required
                  />
                  <input type="hidden" name="id" v-model="indicator.id" />
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Presentation</label
                  >
                  <input
                    type="text"
                    name="presentation"
                    class="form-control"
                    v-if="indicator.presentation"
                    v-model="indicator.presentation"
                  />
                  <textarea
                    v-else
                    type="text"
                    class="form-control"
                    name="presentation"
                  ></textarea>
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Rubrique</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="rubrique"
                    v-model="indicator.rubrique"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >value Type</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="type"
                    v-model="indicator.value_type"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Threshold</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="thres"
                    v-model="indicator.threshold"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Order</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    name="order"
                    v-model="indicator.order"
                  />
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                  @click="typeModal = false"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">
                  Save changes
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </transition>

    <div class="modal-backdrop fade d-none" ref="backdrop"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      typeModal: false,
      indicators: [],
      indicator: [],
      title: "",
    };
  },
  methods: {
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
    getIndicators() {
      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .get("/api/indicators", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.indicators = result.data;
          });
      }
    },
    newIndicator(e) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        let formData = new FormData(e.target);
        axios
          .post("/api/save/indicator", formData, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            if (this.indicator.id) {
              this.$router.go();
            } else {
              this.indicators.push(result.data);
            }
            this.typeModal = false;
          });
      }
    },
    displayModel() {
      this.title = `Add new Indicator`;
      this.indicator = this.indicator.length > 1 ? [] : [];
      this.typeModal = true;
    },
    showIndicator(indicator) {
      this.typeModal = true;
      this.indicator = indicator;
      this.title = `Edit ${indicator.label} indicator`;
    },
    deleteIndicator(id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "are you sure to delete this Indicator ?",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Delete`,
          cancelButtonText: `Cancel`,
        }).then((result) => {
          if (result.isDenied) {
            axios
              .post(
                "/api/delete/indicator/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer" + token,
                  },
                }
              )
              .then(() => {
                this.$swal({
                  icon: "success",
                  title: "Type deleted Successfully",
                  showConfirmButton: false,
                  timer: 1500,
                });
                this.indicators = this.indicators.filter((t) => {
                  return t.id != id;
                });
              });
          }
        });
      }
    },
  },
  mounted() {
    this.getIndicators();
  },
};
</script>

<style scoped>
</style>