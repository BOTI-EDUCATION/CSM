<template>
  <div class="row align-items-center">
    <div class="col-12">
      <h3 class="p-3 mb-5">{{label}}</h3>
    </div>
    <div class="col-2 col-md-4 text-right">
      <button class="btn btn-primary" @click="prevSchool">
        <i class="fa fa-chevron-right"></i>
      </button>
    </div>
    <div class="col-8 col-md-4">
      <div class="card hoverable full-row-card user-card overflow-hidden">
        <div class="card-body text-center">
          <img :src="schools[activeIndex].logo" class="rounded profile mb-2" alt="img" />
          <h5 class="card-title text-dark mb-2">{{schools[activeIndex].name}}</h5>
        </div>
      </div>
    </div>
    <div class="col-2 col-md-4 text-left">
      <button class="btn btn-primary" @click="nextSchool">
        <i class="fa fa-chevron-right"></i>
      </button>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-body">

        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      label: '',
      sections: [],
      date: new Date(),
      schools: [],
      activeIndex : 0
    }
  },
  methods: {
    setupData: function (dataJSON) {
      let data = JSON.parse(dataJSON);
      this.label = data.label;
      this.sections = data.sections;
      this.date = new Date(data.date);
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
          .catch(function (err) {});
      }
    },
    nextSchool: function(){
      if(this.activeIndex < (this.schools.lenght-1)){
        this.activeIndex++;
      }else{
        this.activeIndex = 0;
      }
    },
    prevSchool: function(){
      if(this.activeIndex < 0){
        this.activeIndex--;
      }else{
        this.activeIndex = this.schools.lenght-1;
      }
    }
  },
  async mounted() {
    
    if (!this.$route.query.data) {
      await this.$router.push("/tracking/evaluation");
    }

    await this.setupData(this.$route.query.data);
    await this.getSchoolsList();
    
  },
};
</script>

<style>
.text-right {
  text-align: right;
}
</style>

