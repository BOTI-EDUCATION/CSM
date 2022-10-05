<template>
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Employee
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Fist Name</th>
                                <th>Last Name</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
                                <td><button class="btn btn-default btn-xs"><span
                                            class="glyphicon glyphicon-eye-open"></span></button></td>
                                <td>Carlos</td>
                                <td>Mathias</td>
                                <td>Leme</td>
                                <td>SP</td>
                                <td>new</td>
                            </tr>

                            <tr>
                                <td colspan="12" class="hiddenRow">
                                    <div class="accordian-body collapse" id="demo1">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="info">
                                                    <th>Job</th>
                                                    <th>Company</th>
                                                    <th>Salary</th>
                                                    <th>Date On</th>
                                                    <th>Date off</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <tr data-toggle="collapse" class="accordion-toggle"
                                                    data-target="#demo10">
                                                    <td> <a href="#">Enginner Software</a></td>
                                                    <td>Google</td>
                                                    <td>U$8.00000 </td>
                                                    <td> 2016/09/27</td>
                                                    <td> 2017/09/27</td>
                                                    <td>
                                                        <a href="#" class="btn btn-default btn-sm">
                                                            <i class="glyphicon glyphicon-cog"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="12" class="hiddenRow">
                                                        <div class="accordian-body collapse" id="demo10">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <td><a href="#"> XPTO 1</a></td>
                                                                        <td>XPTO 2</td>
                                                                        <td>Obs</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>item 1</th>
                                                                        <th>item 2</th>
                                                                        <th>item 3 </th>
                                                                        <th>item 4</th>
                                                                        <th>item 5</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>item 1</td>
                                                                        <td>item 2</td>
                                                                        <td>item 3</td>
                                                                        <td>item 4</td>
                                                                        <td>item 5</td>
                                                                        <td>
                                                                            <a href="#" class="btn btn-default btn-sm">
                                                                                <i class="glyphicon glyphicon-cog"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Scrum Master</td>
                                                    <td>Google</td>
                                                    <td>U$8.00000 </td>
                                                    <td> 2016/09/27</td>
                                                    <td> 2017/09/27</td>
                                                    <td> <a href="#" class="btn btn-default btn-sm">
                                                            <i class="glyphicon glyphicon-cog"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>Back-end</td>
                                                    <td>Google</td>
                                                    <td>U$8.00000 </td>
                                                    <td> 2016/09/27</td>
                                                    <td> 2017/09/27</td>
                                                    <td> <a href="#" class="btn btn-default btn-sm">
                                                            <i class="glyphicon glyphicon-cog"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>Front-end</td>
                                                    <td>Google</td>
                                                    <td>U$8.00000 </td>
                                                    <td> 2016/09/27</td>
                                                    <td> 2017/09/27</td>
                                                    <td> <a href="#" class="btn btn-default btn-sm">
                                                            <i class="glyphicon glyphicon-cog"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>

                                    </div>
                                </td>
                            </tr>



                            <tr data-toggle="collapse" data-target="#demo2" class="accordion-toggle">
                                <td><button class="btn btn-default btn-xs"><span
                                            class="glyphicon glyphicon-eye-open"></span></button></td>
                                <td>Silvio</td>
                                <td>Santos</td>
                                <td>São Paulo</td>
                                <td>SP</td>
                                <td> new</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="hiddenRow">
                                    <div id="demo2" class="accordian-body collapse">Demo2</div>
                                </td>
                            </tr>
                            <tr data-toggle="collapse" data-target="#demo3" class="accordion-toggle">
                                <td><button class="btn btn-default btn-xs"><span
                                            class="glyphicon glyphicon-eye-open"></span></button></td>
                                <td>John</td>
                                <td>Doe</td>
                                <td>Dracena</td>
                                <td>SP</td>
                                <td> New</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="hiddenRow">
                                    <div id="demo3" class="accordian-body collapse">Demo3 sadasdasdasdasdas</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>


</template>

<!--                    <div class="e-table px-5 pb-5">
                        <div class="table-responsive table-lg">
                            <table class="table border-top table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">école</th>
                                        <th>Poste Cherché</th>
                                        <th>Date</th>
                                        <th>Nombre candidatures</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="offer in allOffers">
                                        <td class="align-middle text-center">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img alt="image" width="200" class="br-7" :src="offer.schoolLogo">
                                                </div>
                                                <div class="col-md-6 text-uppercase">
                                                    <h5><strong>école {{ offer.schoolName }}</strong></h5>
                                                </div>
                                            </div>
                                        </td>
                                        "/csm/assets/images/user.png"
                                        <td class="text-nowrap align-middle">{{ offer.title }}
                                        </td>
                                        <td class="text-nowrap align-middle"><span>{{ offer.date }}</span></td>
                                        <td class="text-nowrap align-middle"><span>{{ offer.nbrCandidates }}</span></td>
                                        <td class="text-center align-middle">

                                            <router-link :to="{ path: '/offerDetails', params: {data:offer}}"  class="side-menu__item"
                                                    data-bs-toggle="slide" >
                                                    Details
                                                </router-link>
                                            <button @click="shareData(offer)" class="mx-4 btn btn-primary">Applicants
                                                for this offer</button>
                                           <button @click="deleteOffer(offer.id)"
                                                class="mx-4 btn btn-danger">Delete</button>
                                        </td>
                                        <p>
                                            <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample"
                                                role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="bi bi-caret-down-fill"></i> </a>
                                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseExample" aria-expanded="false"
                                                aria-controls="collapseExample">
                                                <i class="bi bi-caret-down-fill"></i> </button>
                                        </p>
                                    <tr style="position:absolute;" class="collapse" id="collapseExample">
                                        <div class="card card-body">
                                            Some placeholder content for the collapse component. This panel is
                                            hidden by default but revealed when the user activates the relevant
                                            trigger.
                                        </div>
                                    </tr>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>-->

<!------------------------------------------------------------------------------->

<!--
        <div v-for="(offer, index) in this.filteredOffers" class="row">
            <div class="row">
                <div class="col-md-12">
                    <div class="mx-auto card mb-3" style="max-width: 740px;">
                        <div class="row no-gutters">
                            <div class="p-3 col-md-4 d-flex flex-wrap align-items-center">
                                <img :src="offer.schoolLogo" class="card-img" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body pb-1">
                                    <h5 class="card-title text-uppercase">
                                        <h4><strong>école {{ offer.schoolName }}</strong></h4>
                                    </h5>
                                    <p class="text-nowrap align-middle"><strong>Offer Date : </strong><span>{{
                                            offer.date
                                    }}</span></p>
                                    <p class="text-nowrap align-middle"><strong>Candidacies Number : </strong><span>{{
                                            offer.nbrCandidates
                                    }}</span></p>
                                    <p class="text-nowrap align-middle"><strong>City : </strong><span>{{
                                            offer.city
                                    }}</span></p>
                                    <div class="d-flex flex-row-reverse">
                                        <p>
                                            <a class="" data-bs-toggle="collapse" :href="'#collapseExample' + index"
                                                role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="bi bi-caret-down-fill"></i> </a>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div :id="'collapseExample' + index" class="collapse">
                        <ListeCandidatOffer :offer="offer"></ListeCandidatOffer>
                    </div>
                </div>
            </div>
        </div>
-->


