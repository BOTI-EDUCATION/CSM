<template>
  <div>
    <div class="row mb-4">
      <div class="col-12 offset-md-7 col-md-3 text-right">
        <input
          type="text"
          @keyup="filterTickets"
          placeholder="Search ..."
          class="form-control"
        />
      </div>
      <div class="col-12 col-md-2 col-lg-2 col-xl-2 text-right">
        <button
          class="btn btn-block btn-primary"
          @click="showModalAddTicket = true"
        >
          <i class="fe fe-plus"></i>
          Add a new ticket
        </button>
      </div>
    </div>
    <div class="row">
      <div
        v-for="ticket in filteredTickets"
        :key="ticket.id"
        class="col-xl-4 col-lg-6 col-md-6 col-12"
      >
        <div class="card full-row-card">
          <div class="card-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 text-right">
                  <div class="btn-group mt-2 mb-2  ms-auto">
                      <button
                        type="button"
                        :class="`btn btn-${ticket.infos.state.color} dropdown-toggle `"
                        data-bs-toggle="dropdown"
                        aria-expanded="true"
                      >
                        {{ ticket.infos.state.label }} <span class="caret"></span>
                      </button>
                      <ul
                        class="dropdown-menu"
                        role="menu"
                        data-popper-placement="bottom-start"
                      >
                        <li><a href="javascript:void(0)" @click="updateState(ticket,'nouveau')" >Nouveau</a></li>
                        <li><a href="javascript:void(0)" @click="updateState(ticket,'en cours')" >En Cours</a></li>
                        <li><a href="javascript:void(0)" @click="updateState(ticket,'bloque')" >Bloqué</a></li>
                        <li><a href="javascript:void(0)" @click="updateState(ticket,'cloture')" >Clôturé</a></li>
                      </ul>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex align-items-center">
                    <span
                      class="
                        avatar
                        bg-white
                        avatar-xxl
                        bradius
                        cover-image
                        me-3
                      "
                      :data-bs-image-src="ticket.school.img"
                      :style="
                        ' background: url(\'' +
                        ticket.school.img +
                        '\') center center'
                      "
                    ></span>
                    <div>
                      <h5 class="mb-0">
                        {{ ticket.label }}
                        <!-- <span
                          :class="`badge bg-${ticket.infos.state.color} badge-sm ms-2 me-1`"
                          >{{ ticket.infos.state.label }}</span
                        > -->
                      </h5>
                      <small class="text-muted d-flex align-items-center my-3">
                        <span
                          class="
                            avatar
                            bg-white
                            avatar-sm
                            rounded
                            cover-image
                            mx-2
                          "
                          :data-bs-image-src="ticket.responsable.img"
                          :style="
                            ' background: url(\'' +
                            ticket.responsable.img +
                            '\') center center'
                          "
                        ></span>
                        {{ ticket.responsable.name }} - {{ ticket.date }}
                      </small>
                      <div class="infos d-flex">
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.channel.color} badge-sm me-1 mb-3`"
                        >
                          {{ ticket.infos.channel.label }}</span
                        >
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.nature.color} badge-sm me-1 mb-3`"
                          >{{ ticket.infos.nature.label }}</span
                        >
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.genre.color} badge-sm me-1 mb-3`"
                          >{{ ticket.infos.genre.label }}</span
                        >
                        <span
                          :class="`badge rounded-pill bg-${ticket.infos.priority.color} badge-sm me-1 mb-3`"
                          >{{ ticket.infos.priority.label }}</span
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 mt-5">
                  <h5>Details :</h5>
                  <p>
                    {{ ticket.details || "-" }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <transition
      @enter="startTransitionModal($refs.modalAddTicket)"
      @after-enter="endTransitionModal($refs.modalAddTicket)"
      @before-leave="endTransitionModal($refs.modalAddTicket)"
      @after-leave="startTransitionModal($refs.modalAddTicket)"
    >
      <div class="modal fade" v-if="showModalAddTicket" ref="modalAddTicket">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <form @submit="addTicket" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Ajouter un ticket
                </h5>
                <button
                  class="close btn"
                  @click="showModalAddTicket = !showModalAddTicket"
                >
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Titre </label>
                        <input
                          type="text"
                          required
                          name="label"
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> School </label>
                        <select name="school" required class="form-control">
                          <option
                            v-for="school in schools"
                            :key="school.id"
                            :value="school.id"
                          >
                            {{ school.name }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Channel </label>
                        <select name="channel" class="form-control">
                          <option value="whatsapp">Whatsapp</option>
                          <option value="phone">Appel</option>
                          <option value="mail">Email</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Nature </label>
                        <select name="nature" class="form-control">
                          <option value="incident">Incident</option>
                          <option value="demande">Demande</option>
                          <option value="evolution">Evolution</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Genre </label>
                        <select name="genre" class="form-control">
                          <option value="technique">Technique</option>
                          <option value="cs">CS</option>
                          <option value="marketing">Marketing</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label"> Priority </label>
                        <select name="priority" class="form-control">
                          <option value="high">High</option>
                          <option value="medium">Medium</option>
                          <option value="low">Low</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-label"> Détails </label>
                        <textarea
                          name="details"
                          rows="2"
                          class="form-control"
                        ></textarea>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-label"> Pièces Jointes </label>
                        <input
                          type="file"
                          name="pieces[]"
                          multiple
                          class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  class="btn btn-secondary"
                  @click="showModalAddTicket = !showModalAddTicket"
                >
                  Close
                </button>
                <button class="btn btn-primary">Save changes</button>
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
      filteredTickets: [],
      tickets: [],
      schools: [],
      showModalAddTicket: false,
    };
  },
  methods: {
    getticketsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getAllTickets", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.tickets = result.data;
            this.filteredTickets = this.tickets;
            // this.$emit('load-tutoriels',this.tutoriels.length)
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    filterTickets: function (e) {
      let value = e.target.value;
      this.filteredTickets = this.tickets.filter((tutoriel) => {
        return (
          tutoriel.label.toLowerCase().includes(value.toLowerCase()) ||
          tutoriel.responsable.name
            .toLowerCase()
            .includes(value.toLowerCase()) ||
          tutoriel.school.name.toLowerCase().includes(value.toLowerCase())
        );
      });
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
    getSchoolsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSchoolsList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.schools = result.data;
          })
          .catch((err) => {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    addTicket: async function (e) {
      e.preventDefault();

      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formdata = new FormData(form);
        await axios
          .post("/api/saveTicket", formdata, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            // this.tickets = result.data;
            form.reset();
            this.showModalAddTicket = !this.showModalAddTicket;
            this.getticketsList();
          })
          .catch(function (err) {});
      }
    },
    updateState: async function(ticket , state){
      const token = localStorage.getItem("auth-token");
      if(ticket.infos.state.alias != state && token){
        await axios
          .post("/api/updateTicketState", {
            ticket: ticket.id,
            state: state
          }, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {

            ticket.infos.state.label = state;
            
          })
          .catch(function (err) {});

      }

    }
  },
  mounted() {
    this.getticketsList();
    this.getSchoolsList();
  },
};
</script>

<style scoped>
</style>