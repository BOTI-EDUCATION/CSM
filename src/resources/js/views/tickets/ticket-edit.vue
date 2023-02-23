<template>
  <div class="card">
    <div class="card-body">
      <form @submit="addTicket" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ajouter un ticket</h5>
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
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label"> Titre </label>
                  <input
                    type="text"
                    required
                    name="label"
                    class="form-control"
                    v-model="ticketItem.label"
                  />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group" v-if="ticketItem">
                  <label class="form-label"> School </label>
                  <input type="hidden" name="school" :value="ticketSchool" />
                  <vue-select
                    class="form-contro"
                    name="accountManager"
                    :reduce="(school) => school.id"
                    label="name"
                    :options="schools"
                    v-model="ticketItem.school"
                    placeholder="School"
                    searchable
                  >
                  </vue-select>
                </div>
                <div class="form-group" v-else>
                  <label class="form-label"> School </label>
                  <input type="hidden" name="school" :value="ticketSchool" />
                  <vue-select
                    class="form-contro"
                    name="accountManager"
                    :reduce="(school) => school.id"
                    label="name"
                    :options="schools"
                    v-model="ticketSchool"
                    placeholder="School"
                    searchable
                  >
                  </vue-select>
                </div>
              </div>

              <div class="col-md-4" style="margin-top: 0.4rem">
                <div class="form-group" v-if="ticketItem">
                  <label for="form-label"> Demandeur </label>
                  <input type="hidden" name="contact_id" :value="demandeur" />
                  <vue-select
                    class="form-contro"
                    name="contact_id"
                    :reduce="(accMan) => accMan.id"
                    label="name"
                    :options="schoolContacts"
                    v-model="demandeur"
                    placeholder="Demandeur"
                    searchable
                  >
                  </vue-select>
                </div>
                <div v-else>
                  <label for="form-label"> Demandeur </label>
                  <input type="hidden" name="contact_id" :value="demandeur" />
                  <vue-select
                    class="form-contro"
                    name="contact_id"
                    :reduce="(accMan) => accMan.id"
                    label="name"
                    :options="schoolContacts"
                    v-model="demandeur"
                    placeholder="Demandeur"
                    searchable
                  >
                  </vue-select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group" v-if="ticketItem">
                  <label class="form-label"> Channel </label>
                  <select name="channel" class="form-control">
                    <option
                      :selected="ticketItem.infos.channel.label == 'whatsapp'"
                      value="whatsapp"
                    >
                      Whatsapp
                    </option>
                    <option
                      :selected="ticketItem.infos.channel.label == 'phone'"
                      value="phone"
                    >
                      Appel
                    </option>
                    <option
                      :selected="ticketItem.infos.channel.label == 'mail'"
                      value="mail"
                    >
                      Email
                    </option>
                  </select>
                </div>
                <div class="form-group" v-else>
                  <label class="form-label"> Channel </label>
                  <select name="channel" class="form-control">
                    <option value="whatsapp">Whatsapp</option>
                    <option value="phone">Appel</option>
                    <option value="mail">Email</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group" v-if="ticketItem">
                  <label class="form-label"> Nature </label>
                  <select name="nature" class="form-control">
                    <option
                      v-for="nature in natures"
                      :selected="ticketItem.infos.nature.label == nature.name"
                      :value="nature.name"
                      :key="nature.id"
                    >
                      {{ nature.name }}
                    </option>
                  </select>
                </div>
                <div class="form-group" v-else>
                  <label class="form-label"> Nature </label>
                  <select name="nature" class="form-control">
                    <option
                      v-for="nature in natures"
                      :value="nature.name"
                      :key="nature.id"
                    >
                      {{ nature.name }}
                    </option>
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
                    <option value="bug (Web)">Bug (Web)</option>
                    <option value="bug (Mobile)">Bug (Mobile)</option>
                    <option value="hosting & tools">Hosting & tools</option>
                    <option value="mobile versioning & deployment">
                      Mobile Versioning & Deployment
                    </option>
                    <option value="data">Data</option>
                    <option value="assistance (faire à sa place)">
                      Assistance (faire à sa place)
                    </option>
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

              <div class="col-md-6">
                <div class="form-group">
                  <label for="" class="form-label">Responsable </label>
                  <select name="responsable_id" required class="form-control">
                    <option
                      v-for="accMan in accountManagers"
                      :key="accMan.id"
                      :value="accMan.id"
                    >
                      {{ accMan.nom }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="" class="form-label">Heure: </label>
                  <input
                    type="time"
                    required
                    name="hour"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="" class="form-label">
                    Durée de traitement:
                    <span class="text-info fw-bold">{{
                      this.hourConverted
                    }}</span>
                  </label>
                  <input
                    type="time"
                    v-model="getHour"
                    @change="getValue"
                    value="00:00"
                    required
                    name="duree"
                    class="form-control"
                  />
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Etat de traitement</label>
                  <select name="status" class="form-control">
                    <option value="traité">Traité</option>
                    <option value="encours">Encours</option>
                    <option value="annulé">Annulé</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12">
                <vue-editor id="details" v-model="detailsTicket"></vue-editor>
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
</template>

<style scoped>
</style>


<script>
export default {
  data() {},
  methods: {
    getTicket(id) {
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
  mounted() {
    
  },
};
</script>