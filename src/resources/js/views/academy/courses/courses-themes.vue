<template>
  <Layout>
    
    <div class="page-header">
      <h1 class="page-title">Courses ({{ courses.length }})</h1>
      <div>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Paramettrage</a>
          </li>
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">academy</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <router-link to="/academy/courses"> Courses </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-sm-12 text-right">

        <button
          class="btn btn-warning text-dark"
          @click="enabledTheDragabble"
          v-if="!saveBtn"
        >
         Change the ordres
        </button>

        <button class="btn btn-info" @click="saveTheChanges" v-if="saveBtn">
          <b>Save the changes ordres</b>
        </button>

        <a v-if="!saveBtn" class="btn btn-primary" @click="adding">
          <i class="fe fe-plus"></i>
          Add a new course
        </a>
        

      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table v-if="!enabled"  class="table text-nowrap text-md-nowrap mb-0">
                    <thead>
                      <tr>
                        <th>Theme</th>
                        <th>Label</th>
                        <th>Alias</th>
                        <th>Description</th>
                        <th>Ordre</th>
                        <th>Type</th>
                        <th>Min</th>
                        <th>Content</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="course in courses" :key="course.id">
                        <th scope="row">{{ course.theme }}</th>
                        <th scope="row">{{ course.label }}</th>
                        <td>{{ course.alias }}</td>
                        <td>{{ course.description }}</td>
                        <td>
                          <span class="badge bg-info">{{ course.ordre }}</span>
                        </td>
                        <td>
                          <span
                            class="badge"
                            :class="{
                              'bg-primary': course.type == 'video',
                              'bg-success': course.type == 'html',
                            }"
                          >
                            {{ course.type }}</span
                          >
                        </td>
                         <td> {{ course.min }} min</td>
                        <td>
                          <a
                            :href="course.video"
                            target="_blank"
                            v-if="course.type == 'video'"
                          >
                            {{ course.video }}
                          </a>
                          <span
                            v-if="course.type == 'html'"
                            v-html="course.content"
                          ></span>
                        </td>
                  
                        <td>
                          <button
                            class="btn btn-warning me-1 text-dark"
                            @click="showCourse(course.id)"
                          >
                            editer
                          </button>
                          <a
                            class="btn btn-danger text-white"
                            @click.prevent="deleteCourse(course.id)"
                          >
                            supprimer
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <table v-if="enabled" class="table text-nowrap text-md-nowrap mb-0">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Theme</th>
                        <th>Label</th>
                        <th>Alias</th>
                        <th>Description</th>
                        <th>Ordre</th>
                        <th>Type</th>
                        <th>Min</th>
                        <th>Content</th>
                      </tr>
                    </thead>
                      <draggable v-model="courses" tag="tbody">
                      <tr v-for="(course, index) in courses" :key="course.id" style="cursor: pointer;">
                        <td><i class="fa-solid fa-computer-mouse text-info" style="cursor: pointer;"></i></td>
                        <th scope="row">{{ course.theme }}</th>
                        <th scope="row">{{ course.label }}</th>
                        <td>{{ course.alias }}</td>
                        <td>{{ course.description }}</td>
                        <td>
                          <span class="badge bg-info">{{( index + 1)}}</span>
                        </td>
                        <td>
                          <span
                            class="badge"
                            :class="{
                              'bg-primary': course.type == 'video',
                              'bg-success': course.type == 'html',
                            }"
                          >
                            {{ course.type }}</span
                          >
                        </td>
                        <td> {{ course.min }} min</td>

                        <td>
                          <a
                            :href="course.video"
                            target="_blank"
                            v-if="course.type == 'video'"
                          >
                            {{ course.video }}
                          </a>
                          <span
                            v-if="course.type == 'html'"
                            v-html="course.content"
                          ></span>
                        </td>
                  
                        <!-- <td>
                          <button
                            class="btn btn-warning me-1 text-dark"
                            @click="showCourse(course.id)"
                          >
                            editer
                          </button>
                          <a
                            class="btn btn-danger text-white"
                            @click.prevent="deleteCourse(course.id)"
                          >
                            supprimer
                          </a>
                        </td> -->
                      </tr>
                    </draggable>
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
      @enter="startTransitionModal($refs.course)"
      @after-enter="endTransitionModal($refs.course)"
      @before-leave="endTransitionModal($refs.course)"
      @after-leave="startTransitionModal($refs.course)"
    >
      <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        v-if="newModal"
        ref="course"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                {{ courseModalTitle }}
              </h5>
              <button
                @click="newModal = false"
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <form @submit.prevent="newCourse">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Themes</label
                  >
                  <select
                    class="form-select"
                    aria-label="Default select example"
                    name="theme"
                  >
                    <option
                      :value="theme.id"
                      v-for="theme in themes"
                      :key="theme.id"
                      :selected="theme.label == course.theme"
                    >
                      {{ theme.label }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Label</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="label"
                    v-model="course.label"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Description</label
                  >
                  <textarea
                    class="form-control"
                    name="desc"
                    v-model="course.description"
                    required
                  ></textarea>
                </div>

                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Chosir le type</label
                  >

                  <select
                    @change="getSelectdOption($event)"
                    class="form-select"
                    aria-label="Default select example"
                    name="type"
                    required
                  >
                    <option :selected="course.type == ''">
                      Choose the kind
                    </option>
                    <option value="html" :selected="course.type == 'html'">
                      Html
                    </option>
                    <option value="video" :selected="course.type == 'video'">
                      Video
                    </option>
                  </select>
                </div>

                <div v-if="selected == 'video'" class="mb-3">
                  <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label"
                      >youtube link</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      name="link"
                      v-model="course.video"
                      required
                    />
                  </div>

                  <div>
                      <label for="min" class="form-label"> Minute  </label>
                      <input type="number" name="min" class="form-control">
                  </div>

                </div>

                <div
                  v-if="course.type == 'video' && selected == ''"
                  class="mb-3"
                >
                  <label for="exampleFormControlInput1" class="form-label"
                    >youtube link</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="link"
                    v-model="course.video"
                    required
                  />

                  <div class="mt-3">
                      <label for="min" class="form-label"> Minute  </label>
                      <input type="number" name="min" class="form-control">
                  </div>

                </div>

                <div v-if="selected == 'html'" class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label"
                    >Content</label
                  >
                  <vue-editor id="details" v-model="course.content">
                  </vue-editor>
                </div>

                <div
                  v-if="course.type == 'html' && selected == ''"
                  class="mb-3"
                >
                  <label for="exampleFormControlInput1" class="form-label"
                    >Content</label
                  >
                  <vue-editor
                    id="details"
                    v-model="course.content"
                  ></vue-editor>
                </div>

                
                <div
                  v-if="course.ordre == ''"
                  class="mb-3"
                >
                  <label for="exampleFormControlInput1" class="form-label"
                    >Ordre</label
                  >
                  <input
                  class="form-control"
                    type="number"
                    name="ordre"
                    v-model="course.ordre"
                  />
                </div>
                <div v-else>
                  <input
                    type="hidden"
                    name="ordre"
                    v-model="course.ordre"
                  />
                </div>

              </div>
              <div class="modal-footer">
                <button
                  @click="newModal = false"
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
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
  </Layout>
</template>
  
  
  <script>
import axios from "axios";
import Layout from "../../../components/Layout.vue";
import { VueEditor } from "vue2-editor";
import draggable from "vuedraggable";
export default {
  components: {
    Layout,
    VueEditor,
    draggable
  },
  data() {
    return {
      newModal: false,
      courseModalTitle: "",
      courses: [],
      themes: [],
      selected: "",
      content: "",
      course: {
        label: "",
        theme: "",
        description: "",
        type: "",
        video: "",
        content: "",
        ordre: "",
        id: "",
        alias: "",
        min:''
      },
      enabled: false,
      saveBtn: false,
    };
  },
  methods: {
    getSelectdOption(e) {
      console.log(e.target.value);
      this.selected = e.target.value;
    },
    startTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("d-block");
      ref.classList.toggle("d-block");
    },
    endTransitionModal(ref) {
      this.$refs.backdrop.classList.toggle("show");
      ref.classList.toggle("show");
    },
    getCourses: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/courses", {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            this.courses = result.data.datas;
            this.themes = result.data.themes;
          })
          .then((error) => {
            error;
          });
      }
    },
    adding() {
      this.courseModalTitle = "Add new Course";
      this.course.id = "";
      this.course.label = "";
      this.course.theme = "";
      this.course.description = "";
      this.course.type = "";
      this.course.video = "";
      this.course.content = "";
      this.course.ordre = "";
      this.course.alias = "";
      this.course.min = "";
      this.newModal = true;
    },
    newCourse: async function (e) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        let form = e.target;
        let formData = new FormData(form);
        formData.append("content", this.course.content);

        if (this.course.id != "") {
          formData.append("course_id", this.course.id);
          await axios
            .post("/api/new/course", formData, {
              headers: {
                Authorization: "Bearer" + token,
              },
            })
            .then((result) => {
              this.newModal = false;
              this.courses.map( (cour) => {
                    if(cour.id == result.data.id){
                      cour.label = result.data.label;
                      cour.ordre = result.data.ordre;
                      cour.alias = result.data.alias;
                      cour.id = result.data.id;
                      cour.description = result.data.description;
                      cour.theme  = result.data.theme;
                      cour.video  = result.data.video;
                      cour.content  = result.data.content;
                      cour.type  = result.data.type;
                      cour.mi  = result.data.min;
                    }
                } );
            })
            .catch((error) => {
              error;
            });
        } else {
            await axios
          .post("/api/new/course", formData, {
            headers: {
              Authorization: "Bearer" + token,
            },
          })
          .then((result) => {
            console.log(result.data);
            this.courses.push(result.data);
            this.newModal = false;
          })
          .catch((error) => {
            error;
          });
        }
      }
    },
    showCourse(id) {
      this.newModal = true;
      this.courses.forEach((cour) => {
        if (cour.id == id) {
          this.course.id = id;
          this.course.label = cour.label;
          this.course.theme = cour.theme;
          this.course.description = cour.description;
          this.course.type = cour.type;
          this.course.video = cour.video;
          this.course.content = cour.content;
          this.course.ordre = cour.ordre;
          this.course.alias = cour.alias;
          this.course.min = cour.min;
        }
        this.courseModalTitle = `Editer le cours ${this.course.label} `;
        this.selected = "";
      });
    },
    deleteCourse: async function (id) {
      const token = localStorage.getItem("auth-token");
      if (token) {
        this.$swal({
          title: "are you sure to delete this course",
          icon: "warning",
          showConfirmButton: false,
          showDenyButton: true,
          showCancelButton: true,
          denyButtonText: `Delete`,
          cancelButtonText: `Cancel`,
        }).then(async (result) => {
          if (result.isDenied) {
            await axios
              .post(
                "/api/delete/course/" + id,
                {},
                {
                  headers: {
                    Authorization: "Bearer " + token,
                  },
                }
              )
              .then((result) => {
                this.courses = this.courses.filter((course) => {
                  return course.id != id;
                });
              })
              .catch((error) => {
                console.log(error);
              });
          }
        });
      }
    },
    enabledTheDragabble() {
      this.enabled = true;
      this.saveBtn = true;
    },
    saveTheChanges() {
      this.enabled = false;
      this.saveBtn = false;
      let ids = [];

      this.courses.forEach((course, index) => {
        ids.push(course.id);
        if (course.id) {
          course.ordre = index + 1;
        }
      });

      let converted = JSON.stringify(ids);
      let formData = new FormData();
      formData.append("ids", converted);

      const token = localStorage.getItem("auth-token");
      if (token) {
        axios
          .post("/api/update/courses/ordres", formData, {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then((result) => {
            this.courses.forEach((course, index) => {
              if (course.id) {
                course.ordre = index + 1;
              }
            });
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
  mounted() {
    this.getCourses();
  },
};
</script>
  
  
  <style scoped>
</style>