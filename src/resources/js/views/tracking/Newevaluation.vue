<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">Nouvelle Evaluation</div>
        <div class="card-body">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-12 col-md-6 col-lg-4 offset-lg-1">
                <template v-for="section in sections">
                <div class="card shadow-none border mb-2" v-if="section.criterias.length>0" :key="section.id">
                  <div class="form-group m-0">
                    <div class="custom-controls-stacked">
                      <label class="custom-control custom-checkbox">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          name="sections"
                          :value="section.id"
                          v-model="section.checked"
                        />
                        <span class="custom-control-label">{{section.label}} ({{section.criterias.length}})</span>
                      </label>
                    </div>
                  </div>
                </div>
                </template>
              </div>
              <div class="col-12 col-md-6 col-lg-4 offset-lg-1">
                <div class="row mb-4">
                  <label class="form-label" for="">Label :</label>
                  <div class="">
                    <input class="form-control" type="text" v-model="label" />
                  </div>
                </div>
                <div class="row mb-4">
                  <label class="form-label" for="">Date :</label>
                  <div class="">
                    <input class="form-control" type="date" v-model="selected_date" />
                  </div>
                </div>
                <!-- <div class="row">
                  <label class="form-label" for="">Evaluation Mode :</label>
                  <div class="custom-controls-stacked" v-for="mode in modes" :key="mode.alias">
                    <label class="custom-control custom-radio">
                      <input
                        type="radio"
                        class="custom-control-input"
                        name="example-radios"
                        :value="mode.alias"
                        v-model="selected_mode"
                      />
                      <span class="custom-control-label">{{mode.label}}</span>
                    </label>
                  </div>
                </div> -->
              </div>

              <div class="col-12 col-md-6 offset-md-3 mt-6">
                <button class="btn btn-primary btn-block" @click="newEvaluation" >New Evaluation</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sections : [],
      modes: [
        {
          label : 'School By School',
          alias : 'schoolbyschool'
        },
        {
          label : 'Matrix',
          alias : 'matrice'
        }
      ],
      selected_mode: 'schoolbyschool',
      selected_date: new Date().toISOString().slice(0,10),
      label : 'Evaluation '+((new Date()).toLocaleDateString().split('T')[0])
    }
  },
  methods: {
    getTrackingSections: async function(){
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getTrackingSections", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            console.log(result);
            this.sections = result.data;
          })
          .catch((err) =>  {
            localStorage.removeItem("auth-token");
            this.$router.push("/login");
          });
      }
    },
    newEvaluation: function(){
      let checkedSections = this.sections.filter(section => (section.checked === true&&section.criterias.length>0));
      

      if(checkedSections.length == 0){
        this.$swal({
          title: 'You have to choose at least one section !',
          icon: 'warning',
          showConfirmButton: false,
          showDenyButton: false,
          showCancelButton: true,
          cancelButtonText: `undestood`,
        }).then( (result) => {
            return false
        });
      }else if( !this.selected_mode || this.selected_mode == '' || this.selected_mode == ' s'){
         this.$swal({
          title: 'You have to choose at least one mode !',
          icon: 'warning',
          showConfirmButton: false,
          showDenyButton: false,
          showCancelButton: true,
          cancelButtonText: `undestood`,
        }).then( (result) => {
            return false
        });
      }else{

        let dataJSON = JSON.stringify({
          sections : checkedSections,
          date : this.selected_date,
          label : this.label
        });


        // if(this.selected_mode == 'schoolbyschool'){
        //   this.$router.push({path : "evaluation/school/", query: { data: dataJSON }});
        // }else if(this.selected_mode == 'matrice'){
          this.$router.push({path : "evaluation/matrice/", query: { data: dataJSON}});
        // }
        
      }



      
    }
  },
  mounted() {
    this.getTrackingSections();
  },
};
</script>

