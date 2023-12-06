<template>
    <Layout>
        <div class="page-header">
            <h1 class="page-title">Article Details</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">School Life</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="javascript:void(0)">Article Details </a>
                    </li>
                </ol>
            </div>
        </div>

        <a class="mb-5 btn btn-primary" href="javascript:history.back()">Go Back</a>


        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <a class="mb-5 btn btn-primary" href="javascript:history.back()">delete</a> -->
                        <h2 class="mb-0 w-100">
                            <input v-model="article.label" type="text" id="example-email" name="label" class="form-control"
                                style="font-size: 25px; font-weight: bold;" placeholder="Titre" required>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row my-3">
                            <label for="" class="col-12 my-1 inputLabel">introduction</label>
                            <input type="text" v-model="article.introduction" class="form-control col-12"
                                name="introduction" placeholder="text">
                        </div>
                        <div class="row my-3">
                            <label for="" class="col-12 my-1 inputLabel">category</label>
                            <vue-select class="p-0" name="value" v-model="article.category" :reduce="info => info.id"
                                :options="categories" label="label" placeholder="Choose item" searchable />
                        </div>
                        <div class="row my-3" v-if="hasDateMethod(article.category)">
                            <div class="row mb-4">
                                <label class="col-12 col-md-4 form-label" for="example-email">date d'evenement</label>
                                <!-- @change="setDate($event)" -->
                                <b-form-input class="col-12 col-md-4 mb-1" id="type" type="date"
                                    v-model="article.date_event"></b-form-input>
                                <b-form-input class="col-12 col-md-4 mb-1" id="type" type="time"
                                    v-model="article.time_event" @change="setTime($event)"></b-form-input>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="row mb-4" id="divKeywords">
                                <label class="col-4 form-label" for="example-email">keywords</label>
                                <ImsKeywordBox v-model="article.keywords" @change="checklk($event)" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="mt-5">
                                    <label class="inputLabel"> Image : </label>
                                    <label class="upload-file" for="upload">
                                        <span aria-hidden="true" style="text-align: center;">
                                            <span class="glyphicon glyphicon-folder-open"></span>
                                            <span>Upload Image</span>
                                            <input type="file" id="upload" @change="uploadFile($event)"
                                                :v-model="uploadedImage" style="display: none;">
                                        </span>
                                        <img :src="article.image" alt="">
                                    </label>
                                </div>


                                <div class="my-5 videoparent" v-if="hasVideoMethod(article.category)">
                                
                                    <label class="inputLabel"> video : </label>

                                    <div class="upload-file">
                                        <span aria-hidden="true" style="text-align: center;">
                                            <span class="glyphicon glyphicon-folder-open"></span>
                                            <span>Upload video</span>
                                            <input ref="fileInput" type="file" id="upload_2"
                                                @change="uploadFileVideo($event)" :v-model="uploadedVideo"
                                                style="display: none;">
                                        </span>
                                        <video controls width="" height="" v-if="article.video">
                                            <source :src="article.video" type="video/mp4">
                                            no video
                                        </video>
                                    </div>

                                    <div style="margin-top: 6px;">
                                        <label class="labelVideo" for="upload_2">change Video</label>
                                    </div>
                                </div>

                            </div>


                            <div class="col-12 col-md-8">
                                <vue-editor required v-model="article.details" type="text" class="form-control"
                                    name="details" value="details"></vue-editor>
                            </div>
                            <div class="row">
                                <div class="col-9">
                                    <b-form-checkbox id="checkbox-1" v-model="article.visible" name="checkbox-1"
                                        :value="true" :unchecked-value="false">
                                        publier votre article
                                    </b-form-checkbox>
                                </div>
                                <div class="col-3 d-flex flex-row-reverse">
                                    <button class="btn btn-primary" @click.prevent="saveArticle()">Save Article
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../../components/Layout.vue';
import axios from "axios";
import { VueEditor } from "vue2-editor";
import vSelect from "vue-select";
import ImsKeywordBox from "ims-keyword-box";

export default {
    name: "blogDetailSchoolLife",
    components: {
        Layout,
        ImsKeywordBox,
        "vue-select": vSelect,
        "vue-editor": VueEditor,
    },
    data() {
        return {
            id: null,
            article: {},
            categories: [],
            uploadedImage: null,
            uploadedVideo: null,
            Time: null,
            Date: null,
        };
    },
    methods: {
        getArticle: async function (postId) {
            let x = await axios.get("/api/getArticleDetails/" + postId)
            let c = await axios.get("/api/getAllcategories")
            this.categories = c.data.categories;
            this.article = x.data;
            this.uploadedImage = x.data.image
            if (this.article.hasDate) {
                this.Date = this.article.date_event
                this.Time = this.article.Time_event
            }

            console.log('this: ', this.article);
        },

        uploadFile(e) {
            const imageUp = e.target.files[0]
            const reader = new FileReader()
            reader.addEventListener('load', () => {
                const imageUrl = reader.result
                this.article.image = imageUrl
                this.uploadedImage = imageUp
            })
            reader.readAsDataURL(imageUp)
        },


        // uploadFileVideo() {
        //     const videoUp = this.$refs.fileInput.files[0];
        //     const formData = new FormData();
        //     formData.append('video', videoUp);
        // },


        uploadFileVideo(e) {
            const videoUp = e.target.files[0]
            const readerVideo = new FileReader();
            this.article.video = null;
            readerVideo.addEventListener('load', () => {
                console.log(readerVideo, readerVideo);
                const videoUrl = readerVideo.result
                this.article.video = videoUrl;
                this.uploadedVideo = videoUp;
                console.log(this.article.video);
                console.log(this.uploadedVideo);
            });

            readerVideo.readAsDataURL(videoUp);
            console.log(this.article.video);
        },


        hasDateMethod(cat) {
            if (cat !== "" && cat !== null) {
                const x = this.categories.map((e) => {
                    return e.id == cat && e
                }).filter((e) => {
                    return e !== false
                })[0]
                this.article.hasDate = x.specific_date
                return x.specific_date
            }
            // this.formData.haseDate = false
            return false
        },
        hasVideoMethod(cat) {
            if (cat !== "" && cat !== null) {
                const x = this.categories.map((e) => {
                    return e.id == cat && e
                }).filter((e) => {
                    return e !== false
                })[0]
                this.article.hasVideo = x.hasVideo
                return x.hasVideo
            }
            // this.formData.hasVideo = false
            return false
        },
        // addFieldKey() {
        //     if (this.formData.keys.length < this.choices.length) {
        //         this.formData.keys.push({
        //         key: "",
        //         value: "",
        //         });
        //         console.log("added");
        //     }
        // },
        // deleteFieldKey(counter) {
        //     if (this.formData.keys.length > 1) {
        //         this.formData.keys.splice(counter, 1);
        //     }
        // },
        async saveArticle() {
            const config = {
                headers: { "content-type": "multipart/form-data" },
            };
            const formData = new FormData()
            let image = this.uploadedImage != null ? this.uploadedImage : this.article.image;
            let video = this.uploadedVideo != null ? this.uploadedVideo : this.article.video;
            console.log(video);
            console.log(this.uploadedVideo);
            // let video = this.uploadedImage != null ? this.uploadedImage: this.article.image;
            formData.append('category', this.article.category)
            formData.append('label', this.article.label)
            formData.append('introduction', this.article.introduction)
            formData.append('details', this.article.details)
            formData.append('image', image)
            formData.append('keywords', JSON.stringify(this.article.keywords))
            formData.append('hasDate', this.article.hasDate)
            formData.append('video', video)
            formData.append('hasVideo', this.article.hasVideo)
            formData.append('visible', this.article.visible)
            if (this.article.hasDate) {
                formData.append('date_event', this.article.date_event)
                formData.append('time_event', this.article.time_event)
            }
            try {
                await axios.post(`api/updateArticle/${this.id}`, formData).then(res => {
                    this.$swal('Post save with succes');
                    this.getArticle(this.id)
                })
            } catch (error) {
                console.log("hjezcbjez");
            }

        },
        checklk() {
            console.log(this.article.keywords);
        }
    },
    mounted() {
        this.getArticle(this.$route.params.id);
        this.id = this.$route.params.id
    },
};
</script>

<style scoped>
.text-right {
    text-align: right;
}

.profile-cover__img .profile-img-1 {
    position: relative;
}

.profile-cover__img .profile-img-1>img {
    margin-left: 0px;
}

.profile-cover__img .profile-img-1>span {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    width: auto;
    margin: auto;
    display: block;
    text-align: center;
}

.profile-cover__img .profile-img-content {
    margin-left: 20px;
}

.main-profile-contact-list.d-flex>div {
    flex: 1;
}

.princing-item {
    height: 100%;
    border-radius: 10px;
}

.card .card-body .inputLabel {
    font-weight: bold;
    font-size: 16px;
    text-transform: uppercase;
}

/* upload Image */
.upload-file {
    background-color: rgb(238, 238, 238);
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 30px;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}

.upload-file img {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.videoparent {
    background-color: #eee;
    padding: 10px;
    border-radius: 20px;
}

.videoparent .upload-file video {
    position: absolute;
    width: 100%;
    height: 100%;
    min-width: 100%;
    max-height: 100%;
}

.videoparent div label {
    width: 100%;
    padding: 10px 15px;
    background-color: #6c5ffc;
    color: white;
    font-weight: bold;
    font-size: 18px;
    text-align: center;
    border-radius: 9999px;
    cursor: pointer;
}

/* .upload-file img::after {
    content: "upload new Image File";
} */
/* upload Image */
</style>