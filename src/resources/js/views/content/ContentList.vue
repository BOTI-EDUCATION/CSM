<template>
  <div>
    <div class="row mb-4">
      <div class="col-12 offset-md-4 col-md-3 text-right">
        <vue-select
          class="bg-white border-none"
          multiple
          name="Types"
          :options="types"
          :reduce="(type) => type.alias"
          label="label"
          v-model="filter.type"
          placeholder="Types"
          @input="filterContents"
          searchable
        >
        </vue-select>
      </div>
      <div class="col-12 col-md-3 text-right">
        <input
          type="text"
          @keyup="filterContents"
          v-model="filter.search"
          placeholder="Search ..."
          class="form-control"
        />
      </div>
      <div class="col-12 col-md-2 col-lg-2 col-xl-2 text-right">
        <router-link class="btn btn-block btn-primary" to="/content/add">
          <i class="fe fe-plus"></i>
        </router-link>
      </div>
    </div>
    <div class="row">
      <div
        v-for="content in this.filteredContents"
        :key="content.id"
        class="col-sm-12 col-md-3"
      >
        <router-link class="text-info" :to="'/content/view/' + content.id">
          <div class="card hoverable full-row-card user-card overflow-hidden">
            <div class="card-body text-center">
              <img
                v-if="content.image"
                :src="content.image"
                class="img-fluid profile mb-2"
                alt="img"
              />
              <br />

              <h5 class="card-title text-dark mb-2">{{ content.label }}</h5>
              <!-- <p>{{ content.presentation }}</p> -->
              <!-- <span class="tag tag-blue mb-2">{{ content.role }}</span> -->
              <div class="text-center">
                <span
                  v-if="content.visible"
                  class="badge bg-success badge-sm me-1 mb-1 mt-1"
                  >Visible</span
                >
                <span v-else class="badge bg-danger badge-sm me-1 mb-1 mt-1"
                  >Hidden</span
                >
                <br />
                <span
                  v-for="type in content.type"
                  :key="type"
                  class="badge bg-primary badge-sm me-1 mb-1 mt-1"
                  >{{ type }}</span
                >

                <!-- <router-link :to="'/paramettrage/contents/edit/' + content.id">
                <i class="fe fe-edit-2"></i>
              </router-link>
              
              <a
                @click="deletecontent($event, content.id)"
                href=""
                class="text-danger"
              >
                <i class="fe fe-trash-2"></i>
              </a> -->
              </div>
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
  import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
  components: {
    "vue-select":vSelect,
  },
  emits: ["load-contents"],
  data() {
    return {
      contents: [],
      types: [
        {
          alias:'kinder',
          label:'Kinder',
        },
        {
          alias:'school',
          label:'School',
        },
        {
          alias:'campus',
          label:'Campus',
        }
      ],
      filter: {
        type: null,
        search: null
      },
      filteredContents: [],
    };
  },
  methods: {
    getcontentsList: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getcontentsList", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.contents = result.data;
            this.filteredContents = this.contents;
            this.$emit("load-contents", this.contents.length);
            console.log(this.contents.length);
          })
          .catch(function (err) {
            // localStorage.removeItem("auth-token");
            // this.$router.push("/login");
          });
      }
    },
    deletecontent: async function (e, id) {
      e.preventDefault();
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "Vous êtes sûr de vouloir supprimer ce content",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Supprimer`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .content(
                "/api/deletecontent/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.contents = result.data;
              })
              .catch(function (err) {
                console.log(token);
              });
          }
        });
      } else {
        // localStorage.removeItem("auth-token");
        // this.$router.push("/login");
      }
    },
    filterContents: function () {
      let search = this.filter.search;
      let types = this.filter.type;

      

      this.filteredContents = this.contents.filter((content) => {
        let hasCommon = () =>{
          if(types.length > 0){
            for(let i = 0; i < types.length; i++) {
                for(let j = 0; j < content.type.length; j++) {
                    if(types[i] === content.type[j]) {
                        return true;
                    }
                }
            }
            return false;

          }else{
            return true;
          }
        }
        return (search?content.label.toLowerCase().includes(search.toLowerCase()):true) && hasCommon();
      });
    },
  },
  mounted() {
    this.getcontentsList();
  },
};
</script>

<style scoped>
.text-right {
  text-align: right;
}
.profile {
  max-height: 165px;
}
.user-card .actions {
  display: flex;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}
.user-card .actions > * {
  flex: 1;
}
a .hoverable {
  transition: all ease-in-out 0.4s;
}
a .hoverable:hover {
  box-shadow: 0px 6px 15px 0px #33333344;
}
</style>