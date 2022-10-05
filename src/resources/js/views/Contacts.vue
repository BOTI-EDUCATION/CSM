<template>
  <Layout>
    <div class="page-header">
      <h1 class="page-title">Contacts</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/contacts"> Contacts </router-link>
            </li>
        </ol>
      </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Nom</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Sujet</th>
                          <th>Date</th>
                          <th>message</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="contact in contacts" :key="contact.id">
                          <td>{{contact.nom}}</td>
                          <td>{{contact.email}}</td>
                          <td>{{contact.tel}}</td>
                          <td>{{contact.sujet}}</td>
                          <td>{{contact.date}}</td>
                          <td>
                            <a href="javascript:void(0)" @click="triggerMessageModal(contact)">
                              <i class="fe fe-mail"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <transition
      @enter="startTransitionModal($refs.messageModal)"
      @after-enter="endTransitionModal($refs.messageModal)"
      @before-leave="endTransitionModal($refs.messageModal)"
      @after-leave="startTransitionModal($refs.messageModal)"
    >
      <div class="modal fade" v-if="showMessageModal" ref="messageModal">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
             
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{showedContact.nom}} - {{showedContact.email}} - {{showedContact.tel}}
              </h5>
              <button
                class="close btn"
                @click="showMessageModal = !showMessageModal"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-12 col-md-2">
                        <h6> <b> Date : </b> </h6>                    
                    </div>
                    <div class="col-12 col-md-10">
                      <p>
                      {{showedContact.date}}
                      </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-md-2">
                        <h6> <b> Sujet : </b> </h6>                    
                    </div>
                    <div class="col-12 col-md-10">
                      <p>
                      {{showedContact.sujet}}
                      </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-md-2">
                      <h6> <b> Message : </b> </h6>                    
                    </div>
                    <div class="col-12 col-md-10">
                      <p>
                      {{showedContact.message}}
                      </p>
                    </div>
                  </div>
                    
                </div>
            </div>
            <div class="modal-footer">
              <button
                class="btn btn-secondary"
                @click="showMessageModal = !showMessageModal"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div class="modal-backdrop fade d-none" ref="backdrop"></div>
  </Layout>
</template>

<script>
import Layout from "../components/Layout.vue";
export default {
  components: {
    Layout,
  },
  data() {
    return {
      contacts: [],
      showMessageModal: false,
      showedContact: {},
    };
  },
  methods: {
    getContacts: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getContacts", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.contacts = result.data;
          })
          .catch((err) => {});
      } else {
        localStorage.removeItem("auth-token");
        this.$router.push("/login");
      }
    },
    triggerMessageModal: function (contact) {
      this.showedContact = contact;
      this.showMessageModal = true;
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
  },
  mounted() {
    this.getContacts();
  },
};
</script>