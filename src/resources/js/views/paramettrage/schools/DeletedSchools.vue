<template>
   <Layout>
    <div class="page-header">
      <h1 class="page-title"> Deleted School {{ countData > 0 ? `(${countData})` : ''}}</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/schools"> Schools </router-link>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             <router-link to="/deleted/school"> Deleted Schools </router-link>
          </li>
        </ol>
      </div>
    </div>

   <div class="row">
     <div v-if="countData <= 0">
        <h1 class="alert alert-danger">No Deleted Schools</h1>
     </div>
     <div v-else class="col-md-3 col-sm-12"
        v-for="school in this.deletedSchools"
        :key="school.id"
     >
     <div class="card hoverable full-row-card user-card overflow-hidden">
            <div class="card-body text-center">
               <span
                  class="avatar bg-white avatar-xxl bradius cover-image  mb-2"
                  :data-bs-image-src="school.logo"
                  :style="
                    ' background: url(\'' +
                    school.logo +
                    '\') center center'
                  "
                >
                <span v-if="school.responsable" class="avatar-icons">
                    <img
                      :src="school.responsable.img"
                      class="rounded"
                      :alt="school.responsable.name"
                      data-bs-toggle="tooltip" data-bs-placement="bottom"
                      :data-bs-title="school.responsable.name"
                    /> 
                </span>
            </span>
              <!-- <img :src="school.logo" class="rounded profile mb-2" alt="img" /> -->
              <h5 class="card-title text-dark mb-2">{{ school.name }}</h5>

              <!-- <p>{{ school.presentation }}</p> -->
              <!-- <span class="tag tag-blue mb-2">{{ school.role }}</span> -->
              <div class="actions">
                <!-- <router-link :to="'/schools/edit/' + school.id">
                <i class="fe fe-edit-2"></i>
              </router-link> -->
              
              
              <a 
                @click.prevent="softSchool($event, school.id)" 
                href=""
                class="text-primary"
              >
              <i class="fa-solid fa-trash-can-arrow-up"></i>
              </a > 

              </div>
            </div>
          </div>
     </div>
   </div>
</Layout>

</template>

<script>
import Layout from '../../../components/Layout.vue'
export default {
  components: { Layout },
  data(){
    return{
        deletedSchools: [],
        countData: 0
    }
  },
  methods:{
    getDeletedSchools: async function(){
        const token = localStorage.getItem("auth-token");
        if(token){
            await axios.
            get(
                "/api/deleted/schools",{
                headers: {
              Authorization: "Bearer " + token,
            },
        })
        .then(async (result) => {
            this.deletedSchools = result.data;
            this.countData = result.data.length;
        })
        .catch(function (err){
            console.log(err);
        })
        }
    },

    softSchool: async function(e, id){
        const token = localStorage.getItem("auth-token");
        if(token){
        this.$swal({
          title: "Vous êtes sûr de vouloir restaurer cette école",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `restaurer`,
          cancelButtonText: `Annuler`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post(
                "/api/soft/school/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              ).
              then((result) => { 
                this.countData -= 1 ;
                this.deletedSchools = this.deletedSchools.filter( (school) => {
                    return school.id != id;
                } )
              }).catch(function (err) {
                console.log(token);
              });
          }
        });
        }
    }
  },
  mounted(){
    this.getDeletedSchools();
  }
}
</script>


<style scoped>

</style>