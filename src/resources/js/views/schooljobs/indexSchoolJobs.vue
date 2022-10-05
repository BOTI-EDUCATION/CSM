<template>
    <Layout>
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Home</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0)">School Jobs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Liste des demandes
                    </li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->

        <!--Stats Cards-->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total Number of candidates</h6>
                                        <h2 class="mb-0 number-font">{{ this.totalNumberCandidates }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <div class="chartjs-size-monitor"
                                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                                <div class="chartjs-size-monitor-expand"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div
                                                        style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                                    </div>
                                                </div>
                                                <div class="chartjs-size-monitor-shrink"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                                    </div>
                                                </div>
                                            </div> <canvas id="saleschart"
                                                class="h-8 w-9 chart-dropshadow chartjs-render-monitor"
                                                style="display: block; width: 96px; height: 64px;" width="96"
                                                height="64"></canvas>
                                        </div>
                                    </div>
                                </div> <span class="text-muted fs-12"><span class="text-secondary"><i
                                            class="fe fe-arrow-up-circle  text-secondary"></i>
                                        5%</span> Last week</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Total number of job offers</h6>
                                        <h2 class="mb-0 number-font">{{ this.totalNumberOffers }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <div class="chartjs-size-monitor"
                                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                                <div class="chartjs-size-monitor-expand"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div
                                                        style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                                    </div>
                                                </div>
                                                <div class="chartjs-size-monitor-shrink"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                                    </div>
                                                </div>
                                            </div> <canvas id="leadschart"
                                                class="h-8 w-9 chart-dropshadow chartjs-render-monitor"
                                                style="display: block; width: 96px; height: 64px;" width="96"
                                                height="64"></canvas>
                                        </div>
                                    </div>
                                </div> <span class="text-muted fs-12"><span class="text-pink"><i
                                            class="fe fe-arrow-down-circle text-pink"></i>
                                        0.75%</span> Last 6 days</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">candidacies to validate</h6>
                                        <h2 class="mb-0 number-font">{{ this.candidaciesToValidate }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <div class="chartjs-size-monitor"
                                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                                <div class="chartjs-size-monitor-expand"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div
                                                        style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                                    </div>
                                                </div>
                                                <div class="chartjs-size-monitor-shrink"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                                    </div>
                                                </div>
                                            </div> <canvas id="profitchart"
                                                class="h-8 w-9 chart-dropshadow chartjs-render-monitor"
                                                style="display: block; width: 96px; height: 64px;" width="96"
                                                height="64"></canvas>
                                        </div>
                                    </div>
                                </div> <span class="text-muted fs-12"><span class="text-green"><i
                                            class="fe fe-arrow-up-circle text-green"></i>
                                        0.9%</span> Last 9 days</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Job Offer with 0 candidacies</h6>
                                        <h2 class="mb-0 number-font">{{ this.offerZeroCandidacies }}</h2>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="chart-wrapper mt-1">
                                            <div class="chartjs-size-monitor"
                                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                                <div class="chartjs-size-monitor-expand"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div
                                                        style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                                    </div>
                                                </div>
                                                <div class="chartjs-size-monitor-shrink"
                                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                                    </div>
                                                </div>
                                            </div> <canvas id="costchart"
                                                class="h-8 w-9 chart-dropshadow chartjs-render-monitor"
                                                style="display: block; width: 96px; height: 64px;" width="96"
                                                height="64"></canvas>
                                        </div>
                                    </div>
                                </div> <span class="text-muted fs-12"><span class="text-warning"><i
                                            class="fe fe-arrow-up-circle text-warning"></i>
                                        0.6%</span> Last year</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end Stats Cards-->

        <!--start pdf generator-->
        <!-- <div>
                <button @click="generateReport">Generte PDF File</button>
                <section id="element-to-print" style="display:none">
                        <CandidatDetails/>
                </section>
            </div> -->
        <!--end Pdf generator-->

        <!-- 10 Last Candidats-->
        <h3>{{ this.allCandidats.length ? this.allCandidats.length : 10 }} last candidates</h3>
        <!-- ROW-1 -->
        <!--Table-->
        <div class="row row-cards">
            <div class="col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header border-bottom-0 p-4"></div>
                    <div class="e-table px-5 pb-5">
                        <div class="table-responsive table-lg">
                            <table class="table border-top table-bordered mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="cursor: pointer;" v-for="candidat in allCandidats" @click="shareDataCandidat(candidat)">
                                        <!--<td class="align-middle text-center"><img alt="image"
                                                class="avatar avatar-md br-7" src="/csm/assets/images/user.png"></td>-->
                                        <td class="text-nowrap align-middle">{{ candidat.nom + " " + candidat.prenom }}
                                        </td>
                                        <td class="text-nowrap align-middle"><span>{{ candidat.email }}</span></td>
                                        <td class="text-nowrap align-middle"><span>{{ candidat.tel }}</span></td>
                                        <td class="text-center align-middle">
                                            <div>
                                                <button @click.stop="generateReport(candidat.nom +'-'+ candidat.prenom,'element-to-print'+candidat.id)" class="btn" style="background-color:transparent"><span class="text-primary" style="font-size:25px"><i class="fas fa-file-pdf"></i></span></button>
                                            </div>
                                            <div :id="'element-to-print'+candidat.id" style="display:none">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="card p-3 mx-auto" style="width: 700px;">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div
                                                                            class="details col-xl-12 col-lg-12 col-md-12 mt-4 mt-xl-0">
                                                                            <div class="mt-2 mb-4">
                                                                                <h3 class="mb-3 fw-semibold">Nom et
                                                                                    Prénom : {{ candidat.nom + " "+candidat.prenom }}</h3>
                                                                                <div class=" mt-4 mb-5"><span
                                                                                        class="fw-bold me-2">Email
                                                                                        :</span>{{ candidat.email }}</div>
                                                                                <div class=" mt-4 mb-5"><span
                                                                                        class="fw-bold me-2">CIN
                                                                                        :</span>{{ candidat.cin }}</div>
                                                                                <div class=" mt-4 mb-5"><span
                                                                                        class="fw-bold me-2">Nationalité
                                                                                        :</span>{{ candidat.nationality }}
                                                                                </div>
                                                                                <hr>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- COL-END -->
        </div>
        <!-- end 10 last candidats-->
        <!--10 last offers-->
        <h3>{{ this.allOffers.length ? this.allOffers.length : 10 }} last Offers</h3>
        <!--Table-->
        <div class="row">
            <div class="col-md-6" style="cursor: pointer;" v-for="(offer, index) in allOffers"
                @click="shareDataOffer(offer)">
                <div class="row card p-5">
                    <div class="row">
                        <div class="col-md-4">
                            <img alt="image" width="200" class="br-7" :src="offer.schoolLogo">
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3><strong>{{ offer.title }}</strong></h3>
                                </div>
                                <div class="col-md-12">
                                    <h5 class="text-muted"><i class="bi bi-building"></i> {{ offer.ecole_name }} {{ " | "}} <i class="bi bi-geo-alt-fill"></i> {{ offer.city }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end 10 last offers-->
    </Layout>
</template>

<script>
import Layout from "../../components/Layout.vue";
import html2pdf from 'html2pdf.js'
import CandidatDetails from "./CandidatDetails.vue";
export default {
    components: {
        Layout,
        CandidatDetails
    },
    data() {
        return {
            allCandidats: [],
            allOffers: [],
            totalNumberOffers: 0,
            totalNumberCandidates: 0,
            offerZeroCandidacies: 0,
            candidaciesToValidate: 0,
        }
    },
    methods: {
        shareDataCandidat(candidat) {
            this.$router.push({ name: "candidatDetails", params: { data: candidat } });
        },
        shareDataOffer(offer) {
            this.$router.push({ name: "offerDetails", params: { data: offer } });
        },
        generateReport(fileName,id) {
            // console.log("pdf clicked !!!")
            const element = document.getElementById(id);

            // clone the element
            var clonedElement = element.cloneNode(true);

            // change display of cloned element 
            $(clonedElement).css("display", "block");
            
            // Choose the clonedElement and save the PDF for our user.
            var opt = {
            margin:       1,
            filename:     fileName+'.pdf',
            // image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(clonedElement).save();
            

            // remove cloned element
            clonedElement.remove();

        }

    },
    async mounted() {
        const token = localStorage.getItem('auth-token')
        await axios
            .get('/api/getAllCandidats', {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
            .then(response => {
                this.allCandidats = response.data.reverse().slice(0, 10)
                this.totalNumberCandidates = [...response.data].length
            })

        await axios
            .get('/api/getAllOffersCandidacies2', {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
            .then(response => {
                response.data.map((o) => {
                    if (o.validation == 0) {
                        this.candidaciesToValidate += 1
                    }
                })
            })

        await axios
            .get('/api/getAllOffers', {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
            .then(response => {
                this.allOffers = response.data.reverse().slice(0, 10)
                this.totalNumberOffers = [...response.data].length
                response.data.map((o) => {
                    if (o.nbrCandidates === 0) {
                        this.offerZeroCandidacies += 1
                    }
                })

            })

    }
};
</script>
