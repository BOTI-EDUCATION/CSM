<template>
  <layout>
    <!-- PAGE-HEADER -->
    <div class="page-header">
      <font-awesome-icon :icon="['fas', 'cloud-arrow-up']" bounce size="lg" />
      <h1 class="page-title">Category</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">School Life</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
      </div>
    </div>
    <!-- PAGE-HEADER END -->
    <!--Form-->
    <div class="row mb-5">
      <div class="col-xl-12 col-lg-12">
        <div class="row mb-5">
          <div class="col-xl-4 col-md-4"></div>
          <div class="col-xl-4 col-md-4"></div>
          <div class="col-xl-4 col-md-4">
            <div class="row d-flex flex-row-reverse">
              <div class="col-md-6">
                <div
                  class="text-center mb-5 btn btn-primary"
                  style="width: 100%"
                  @click="showAlert"
                >
                  New Category
                </div>
              </div>
              <div class="col-md-6">
                <input
                  type="text"
                  placeholder="Search ..."
                  class="form-control"
                />
              </div>
            </div>
          </div>
        </div>
        <!--end Button-->
        <!--Table-->
        <div class="row">
          <div
            class="col-md-6 card-parent"
            v-for="(categories, index) in categoryList"
            :key="index"
            @dblclick="showAlertUpdate(categories.id)"
          >
            <div
              class="row card p-5 position-relative"
              style="
                 {
                  cursor: pointer;
                }
              "
            >
              <transition name="fade">
                <span
                  class="position-absolute top-0 end-0 deletebutton"
                  @click="deleteCat(categories.id)"
                  >x</span
                >
              </transition>
              <div class="row align-items-center">
                <div class="col-md-4">
                  <img
                    alt="image"
                    width="200"
                    style="width: 75px; height: 75px; border-radius: 10px"
                    class="br-7"
                    :src="
                      'https://boti.education/csm/src/storage/app/public/' +
                      categories.icon
                    "
                  />
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="mb-0">
                        <strong>{{ categories.label }}</strong>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end Form-->
  </layout>
</template>   
<script>
import axios from "axios";
import Layout from "../../components/Layout.vue";

export default {
  name: "Listcategory",
  components: {
    Layout,
  },
  data() {
    return {
      categoryList: [],
    };
  },
  methods: {
    showAlert() {
      this.$swal({
        title: "select icon",
        html: "<input type='file' id='fileInput' class='form-control'/> <br/> <input type='text' id='textInput' class='form-control' /> <div class='mt-2 d-flex gap-5 justify-content-center flex-wrap'> <div><input class='form-check-input' type='checkbox' value='true' id='flexCheckDefault'> <label class='form-check-label' for='flexCheckDefault'> has video </label></div> <div><input class='form-check-input' type='checkbox' value='true' id='flexCheckDefault2'> <label class='form-check-label' for='flexCheckDefault2'> specific date </label></div> </div>",
        showCancelButton: true,
        confirmButtonText: "Look up",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          const file = document.querySelector("#fileInput").files[0];
          const hasVideo = document.querySelector("#flexCheckDefault").checked;
          const specificDate =
            document.querySelector("#flexCheckDefault2").checked;
          const text = document.querySelector("#textInput").value;
          if (file !== undefined && text !== "") {
            let filename = file.name;
            if (
              ["jpg", "jpeg", "png", "ico"].includes(filename.split(".").pop())
            ) {
              let file_Upload = new FileReader();
              file_Upload.addEventListener("load", async () => {
                let form_data = new FormData();

                form_data.append("label", text);
                form_data.append("icon", file);
                form_data.append("hasVideo", hasVideo);
                form_data.append("specific_date", specificDate);
                console.log(hasVideo), specificDate;
                const token = localStorage.getItem("auth-token");
                try {
                  let data = await axios.post("api/addArticleCat", form_data, {
                    headers: {
                      Authorization: "Bearer " + token,
                      Accept: "application/json",
                    },
                  });
                  this.categoryList = data.data.categories;
                } catch (error) {
                  console.log(error.response);
                  console.log("hzellp");
                }
              });
              file_Upload.readAsDataURL(file);
            } else {
              this.$swal.showValidationMessage(
                "the file extension must be PNG or JPG or ICO "
              );
            }
          } else {
            this.$swal.showValidationMessage("the file input is required");
          }
        },
      });
    },

    showAlertUpdate(id) {
      let cat = this.categoryList.find((e) => {
        return e.id === id;
      });
      console.log(cat);
      this.$swal({
        title: "select icon",
        html: `<div style="margin-bottom: 20px"><img alt="image" width="200" style="width: 75px; height: 75px; border-radius: 10px;" id="imageSwal" class="br-7" src="http://localhost/csm/src/storage/app/public/${cat.icon}"></div>  <input type='file' value='${cat.icon}' id='fileInput' class='form-control'/> <br/> <input type='text' id='textInput' value='${cat.label}' class='form-control' />`,
        showCancelButton: true,
        confirmButtonText: "Look up",
        showLoaderOnConfirm: true,
        preConfirm: async () => {
          const file = document.querySelector("#fileInput").files[0];
          // const hasVideo = document.querySelector("#flexCheckDefault").value
          // console.log(hasVideo);
          const text = document.querySelector("#textInput").value;
          if (text !== "") {
            let form_data = new FormData();
            // let objectToSend = {'label': text ,'icon': file}
            form_data.append("label", text);

            if (file !== undefined) form_data.append("icon", file);

            const token = localStorage.getItem("auth-token");
            try {
              let data = await axios.post(
                "api/updateCategory/" + cat.id,
                form_data,
                {
                  headers: {
                    Authorization: "Bearer " + token,
                    Accept: "application/json",
                  },
                }
              );
              this.categoryList = data.data.categories;
            } catch (error) {
              // console.log(error.response);
              // console.log('hzellp');
            }
          } else {
            this.$swal.showValidationMessage("the file input is required");
          }
        },
      });
    },

    async deleteCat(id) {
      const token = localStorage.getItem("auth-token");
      this.$swal({
        title: "Vous êtes sûr de vouloir supprimer cette categorie",
        icon: "warning",
        showConfirmButton: false,
        showDenyButton: true,
        showCancelButton: true,
        denyButtonText: `Supprimer`,
        cancelButtonText: `Annuler`,
      }).then(async (result) => {
        if (result.isDenied) {
          if (token) {
            let axi = await axios.post(`/api/deleteCat/${id}`, null, {
              headers: {
                Authorization: "Bearer " + token,
              },
            });
            this.categoryList = axi.data.categories;
          }
        }
      });
    },
    // here
  },
  async mounted() {
    let data = await axios.get("api/getAllcategories", null, {
      headers: {
        Accept: "application/json",
      },
    });
    this.categoryList = data.data.categories;
  },
};
</script>

<style scoped>
.card-parent {
  cursor: pointer;
}

.inputValue {
  font-weight: bold;
  font-size: 25px;
  max-width: 100%;
}

.deletebutton {
  width: fit-content;
  cursor: pointer;
  font-size: 20px;
  font-weight: bold;
  display: none;
  opacity: 0;
  transition: opacity 15s ease-in;
}

.card-parent:hover .deletebutton {
  display: inline;
  opacity: 1;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>