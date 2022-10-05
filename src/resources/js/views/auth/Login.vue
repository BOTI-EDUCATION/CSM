<template>
  <div>
    <div class="login-img">
      <!-- GLOABAL LOADER -->
      <div id="global-loader" style="display: none">
        <img
          src="/assets/images/loader.svg"
          class="loader-img"
          alt="Loader"
        />
      </div>
      <!-- /GLOABAL LOADER --> 
      <!-- PAGE -->
      <div class="page">
        <div class="">
          <!-- Theme-Layout -->
          <!-- CONTAINER OPEN -->
          <div class="col col-login mx-auto mt-7">
            <div class="text-center">
              <img
                src="/assets/images/brand/logo-white.png"
                class="header-brand-img"
                alt=""
              />
            </div>
          </div>
          <div class="container-login100">
            <div class="wrap-login100 p-6">
              <span class="login100-form-title pb-5"> Customer Management System </span>
              <div class="alert alert-danger" v-if="this.loginError" role="alert"> 
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                  {{this.loginError}} 
                </div>
              <div class="panel panel-primary">
                <form>
                    <div
                    class="wrap-input100 validate-input input-group"
                    data-bs-validate="Valid email is required: ex@abc.xyz"
                    >
                    <a
                        href="javascript:void(0)"
                        class="input-group-text bg-white text-muted"
                    >
                        <i
                        class="zmdi zmdi-email text-muted"
                        aria-hidden="true"
                        ></i>
                    </a>
                    <input
                        class="input100 border-start-0 form-control ms-0"
                        type="text"
                        placeholder="Email"
                        v-model="email"
                        :rules="loginRules.email"
                        @keyup="validateForm"
                    />
                    </div>
                    <div
                    class="wrap-input100 validate-input input-group"
                    id="Password-toggle"
                    >
                    <a
                        href="javascript:void(0)"
                        class="input-group-text bg-white text-muted"
                    >
                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                    </a>
                    <input
                        class="input100 border-start-0 form-control ms-0"
                        type="password"
                        placeholder="Password"
                        v-model="password"
                        :rules="loginRules.password"
                        @keyup="validateForm"
                    />
                    </div>
                    <div class="container-login100-form-btn">
                    <button
                        :disabled="!valid"
                        @click="attemptLogin"
                        class="login100-form-btn btn-primary"
                    >
                        Login
                    </button>
                    </div>
                </form>
              </div>
            </div>
          </div>
          <!-- CONTAINER CLOSED -->
        </div>
      </div>
      <!-- End PAGE -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
        loginError : '',
      valid: false,
      email: "",
      password: "",
      loginRules: {
        email: [
          (v) => !!v || "E-mail is required",
          // (v) => /.+@.+/.test(v) || "E-mail must be valid",
        ],
        password: [
            (v) => !!v || 'Required.',
            (v) => v.length >= 4 || 'Min 4 characters',
        ]
      },
    };
  },
  methods: {
    attemptLogin: async function(e){
        e.preventDefault();
        
        await axios.post('/api/login',{
            email: this.email,
            password: this.password
        }).then( async (result)=>{
            await localStorage.setItem('auth-token',result.data.token);
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth-token');
            this.checklogin();
        }).catch((err)=>{
            this.loginError = err.response.data.error;
        });
    },
    validateForm: function (e) {
      if(!!this.email&&!!this.password){
        this.valid = true;
      }else{
          this.valid = false;

      }
    },
    checklogin : async function(){
        const token = localStorage.getItem('auth-token')
        if(token){
            await axios.get('/api/user',{
                headers:{
                    Authorization : 'Bearer '+token
                }
            }).then(async (result)=>{
                console.log(result);
                this.$router.push('/');
            }).catch(function(err){
                console.log(err);
            });
        }
    } ,
  },
};
</script>