<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CheckupTrackingController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\FounderConxController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\NatureController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Paramettrage\ChecklistController;
use App\Http\Controllers\Paramettrage\ConfigController;
use App\Http\Controllers\Paramettrage\CriteriaController;
use App\Http\Controllers\Paramettrage\ModuleController;
use App\Http\Controllers\Paramettrage\SchoolController;
use App\Http\Controllers\Paramettrage\SectionController;
use App\Http\Controllers\Paramettrage\ThemeController;
use App\Http\Controllers\Paramettrage\UserController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\SchoolJobs\HomeController;
use App\Http\Controllers\SchoolLife\articleCategoryController;
use App\Http\Controllers\SchoolLife\BlogsController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TutorielController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ActionsLogController;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return 'ok';
});

// Route::get('/createSymlinks',function(){
//     Artisan::call('storage:link');
// });

Route::get('/academy/getAcademyTree', [AcademyController::class, 'getAcademyTree']);
Route::get('/academy/getCourse/{id}', [AcademyController::class, 'getCourse']);
Route::get('/academy/getNextCourse/{id}', [AcademyController::class, 'getNextCourse']);
Route::get('/academy/getPrevCourse/{id}', [AcademyController::class, 'getPrevCourse']);
Route::get('/academy/getQuiz/{id}', [AcademyController::class, 'getQuiz']);

Route::post('/loginCandidat', [HomeController::class, 'loginCandidat']);
Route::get('/getAllOffers', [HomeController::class, 'getAllOffers']);
Route::post('/addCandidat', [HomeController::class, 'addCandidat']);

//School Life
Route::get('/getAllArticles', [BlogsController::class, 'getAllArticles']);
Route::get('/getArticleDetails/{id}', [BlogsController::class, 'getArticleDetails']);
Route::get('/getNewsDetails/{id}', [BlogsController::class, 'getNewsDetails']);
Route::get('/getQuoteDetails/{id}', [BlogsController::class, 'getQuoteDetails']);
Route::get('/getAllQuotes', [BlogsController::class, 'getAllQuotes']);
Route::get('/getAllNews', [BlogsController::class, 'getAllNews']);

// ! START OTHMAN SCHOOL LIFE
Route::get('/getLastArticles', [BlogsController::class, 'getLastArticles']);
Route::get('/getAllcategories', [articleCategoryController::class, 'getAllcategories']);
Route::post('/deleteCat/{id}', [articleCategoryController::class, 'deleteCat']);
Route::post('/updateCategory/{id}', [articleCategoryController::class, 'updateCategory']);
Route::post('/updateArticle/{id}', [BlogsController::class, 'updateArticle']);
Route::get('/getSchoolLifeDetail/{id}', [BlogsController::class, 'getSchoolLifeDetail']);
Route::post('/deleteArt/{id}', [articleCategoryController::class, 'deleteArt']);

// ! END OTHMAN SCHOOL LIFE

// * ACTIONS LOGS 
Route::post('tracking/export/excel', [ActionsLogController::class, 'save_tracking']);
Route::get('actions/export', [ActionsLogController::class, 'exports']);

Route::middleware('auth:apiCandidat')->group(function () {
    Route::post('/addJobOfferCandidacies', [HomeController::class, 'addJobOfferCandidacies']);
    Route::get('/getCandidat/{email}', [HomeController::class, 'getCandidat']);
    Route::post('/logoutCandidat', [HomeController::class, 'logoutCandidat']);
    Route::post('/postulerOffer/{id}', [HomeController::class, 'postulerOffer']);
    Route::get('/getConnectedCandidat', [HomeController::class, 'getConnectedCandidat']);
    Route::get('/getAllOffersCandidacies', [HomeController::class, 'getAllOffersCandidacies']);
});

Route::post('login', [AuthController::class, 'login']);
Route::get('refresh', [AuthController::class, 'refresh']);

// !  new request from boti
Route::post('new/demande', [DashboardController::class, 'saveDemande']);

Route::middleware('auth:api')->group(function () {



    // ! ABOUT ACADEMY COURSES
    Route::get('courses', [AcademyController::class, 'courses']);
    Route::get('delete/course/{id}', [AcademyController::class, 'courses']);
    Route::post('new/course', [AcademyController::class, 'newCourse']);
    Route::post('update/courses/ordres', [AcademyController::class, 'updateCoursesOrdres']);

    // ! ABOUT ACADEMY THEMES
    Route::get('themes', [AcademyController::class, 'themes']);
    Route::get('delete/theme//{id}', [AcademyController::class, 'deleteTheme']);
    Route::post('new/themes', [AcademyController::class, 'newTheme']);
    Route::post('update/ordres', [AcademyController::class, 'updateOrder']);


    // ! ABOUT NATURE
    Route::get('natures', [NatureController::class, 'index']);
    Route::get('getNature/{id}', [NatureController::class, 'getNature']);
    Route::post('new/nature', [NatureController::class, 'save']);
    Route::post('delete/nature/{id}', [NatureController::class, 'delete']);

    // !  ABOUT ACADEMY 
    // START SCHOOL JOBS
    Route::get('/getOffer/{id}', [HomeController::class, 'getOffer']);
    Route::post('/InsertArticle', [BlogsController::class, 'insertArticle']);
    Route::post('/addJobOfferCandidacies2', [HomeController::class, 'addJobOfferCandidacies2']);
    Route::get('/getAllOffersCandidacies2', [HomeController::class, 'getAllOffersCandidacies2']);
    Route::get('/getInfosList', [HomeController::class, 'getInfosList']);
    Route::post('validateOfferCandidacy', [HomeController::class, 'validateOfferCandidacy']);
    Route::post('/deleteOffer/{id}', [HomeController::class, 'deleteOffer']);
    Route::get('getAllCandidats', [HomeController::class, 'getAllCandidats']);
    Route::get('getAllCandidatsOffer/{id}', [HomeController::class, 'getAllCandidatsOffer']);
    Route::post('insertNewOffer', [HomeController::class, 'insertNewOffer']);
    // END SCHOOL JOBS

    //START SCHOOL LIFE
    Route::post('/InsertArticle', [BlogsController::class, 'insertArticle']);
    Route::post('/InsertNewQuote', [BlogsController::class, 'InsertNewQuote']);
    Route::post('/InsertNews', [BlogsController::class, 'InsertNews']);
    //END SCHOOL LIFE

    Route::get('user', [AuthController::class, 'user']);
    Route::get('getCurUser', [AuthController::class, 'user']);
    Route::get('getUsersList', [UserController::class, 'usersList']);
    Route::get('getRolesList', [UserController::class, 'rolesList']);
    Route::get('getUserFormInfo/{id}', [UserController::class, 'getUserFormInfo']);
    Route::post('saveUser', [UserController::class, 'saveUser']);
    Route::get('getAccountManagers', [UserController::class, 'getAccountManagers']);
    Route::get('getSalesManagers', [UserController::class, 'getSalesManagers']);
    Route::get('getManagers', [UserController::class, 'getManagers']);
    Route::get('getSchoolsContact', [UserController::class, 'getSchoolsContact']);
    Route::get('getSchoolContactBySchool/{id}', [UserController::class, 'getSchoolContactBySchool']);
    Route::get('getUserTickets', [UserController::class, 'getUserTickets']);
    Route::get('getUserInterventions', [UserController::class, 'getUserInterventions']);
    Route::post('changeUserPassword/{id}', [UserController::class, 'changeUserPassword']);
    Route::get('getNotifList', [NotificationsController::class, 'getNotifList']);
    Route::get('getLastNotifications', [NotificationsController::class, 'getLastNotifications']);

    // ! ACTIONS MANAGER
    Route::get('actions/sales', [InterventionController::class, 'sales_intervention']);
    Route::get('actions/cs', [InterventionController::class, 'cs_intervention']);


    Route::get('getModulesList', [ModuleController::class, 'modulesList']);
    Route::post('saveModule', [ModuleController::class, 'saveModule']);
    Route::get('getModuleFormInfo/{id}', [ModuleController::class, 'getModuleFormInfo']);
    Route::post('deleteModule/{id}', [ModuleController::class, 'deleteModule']);

    Route::get('getChecklistsList', [ChecklistController::class, 'checklistsList']);
    Route::post('saveChecklist', [ChecklistController::class, 'saveChecklist']);
    Route::get('getChecklistFormInfo/{id}', [ChecklistController::class, 'getChecklistFormInfo']);
    Route::post('deleteChecklist/{id}', [ChecklistController::class, 'deleteChecklist']);
    Route::get('getChecklists', [ChecklistController::class, 'getChecklists']);
    Route::get('getCheckupMatrix', [ChecklistController::class, 'getCheckupMatrix']);

    Route::get('getThemesList', [ThemeController::class, 'themesList']);
    Route::post('saveTheme', [ThemeController::class, 'saveTheme']);
    Route::get('getThemeFormInfo/{id}', [ThemeController::class, 'getThemeFormInfo']);
    Route::post('deleteTheme/{id}', [ThemeController::class, 'deleteTheme']);

    Route::get('getSchoolsList', [SchoolController::class, 'schoolsList']);
    Route::post('saveSchool', [SchoolController::class, 'saveSchool']);
    Route::get('getSchoolFormInfo/{id}', [SchoolController::class, 'getSchoolFormInfo']);
    Route::post('deleteSchool/{id}', [SchoolController::class, 'deleteSchool']);
    Route::get('getSchoolContacts/{id}', [SchoolController::class, 'getSchoolContacts']);
    Route::get('getSchoolIntervention/{id}', [SchoolController::class, 'getSchoolIntervention']);
    Route::get('getSchoolTickets/{id}', [SchoolController::class, 'getSchoolTickets']);
    Route::post('saveSchoolContact/{id}', [SchoolController::class, 'saveSchoolContact']);
    Route::post('updateSchoolContact', [SchoolController::class, 'updateSchoolContact']);
    Route::get('getContactFormInfo/{id}', [SchoolController::class, 'getContactFormInfo']);
    Route::get('getSchoolsCoordinates', [SchoolController::class, 'getSchoolsCoordinates']);
    Route::get('getSchoolchecklists/{id}', [SchoolController::class, 'getSchoolchecklists']);
    Route::post('saveSchoolChecklist/{id}', [SchoolController::class, 'saveSchoolChecklist']);
    Route::post('updateStateCheckItem', [SchoolController::class, 'updateStateCheckItem']);
    Route::post('deleteChecklistSchool', [SchoolController::class, 'deleteChecklistSchool']);

    Route::get('deleted/schools', [SchoolController::class, 'deletedSchools']);
    Route::post('soft/school/{id}', [SchoolController::class, 'softSchool']);

    // ! tickets filter 
    Route::post('tickets/filter', [TicketController::class, 'filters_ticket']);

    // ! disabled and enabled scchool
    Route::get('disabled/schools', [SchoolController::class, 'disabledSchools']);
    Route::post('disable/school/{id}', [SchoolController::class, 'disableSchool']);
    Route::post('enabled/school/{id}', [SchoolController::class, 'enabledSchool']);


    // ! SCHOOL LINK
    Route::get('school_links', [SchoolController::class, 'school_links']);

    Route::get('getLeadsList', [LeadController::class, 'leadsList']);
    Route::post('saveLead', [LeadController::class, 'saveLead']);
    Route::get('getLeadFormInfo/{id}', [LeadController::class, 'getLeadFormInfo']);
    Route::post('deleteLead/{id}', [LeadController::class, 'deleteLead']);
    Route::get('getLeadContacts/{id}', [LeadController::class, 'getLeadContacts']);
    Route::post('saveLeadContact/{id}', [LeadController::class, 'saveLeadContact']);
    Route::get('getContactFormInfo/{id}', [LeadController::class, 'getContactFormInfo']);
    Route::get('getLeadsCoordinates', [LeadController::class, 'getLeadsCoordinates']);
    Route::get('cities/of/lead', [LeadController::class, 'cities']);
    Route::get('leads', [LeadController::class, 'leads']);
    Route::post('deletes/lead/{id}', [LeadController::class, 'deletesLead']);

    // ! Intervention about Leads
    Route::post('new-intervention-lead/{id?}', [LeadController::class, 'save_intervention']);
    Route::get('intervention-leads/{id}', [LeadController::class, 'getInterventions']);
    Route::get('intervention-lead/{id}', [LeadController::class, 'getIntervention']);
    Route::post('changeStateLead', [LeadController::class, 'changeProsp']);
    Route::post('nature_contrat', [LeadController::class, 'contrat_type']);
    Route::get('getConfigsList', [ConfigController::class, 'getConfigsList']);
    Route::post('updateConfig', [ConfigController::class, 'updateConfig']);
    Route::get('get/kanban/{city?}', [LeadController::class, 'kanban']);
    Route::get('get/kanban/by_user/{id}', [LeadController::class, 'kanban_user']);
    Route::post('lead_to_school', [LeadController::class, 'lead_to_school']);

    Route::get('getEtatTraitementIncidents', [DashboardController::class, 'getEtatTraitementIncidents']);
    Route::get('getLastIntervention', [DashboardController::class, 'getLastIntervention']);
    Route::get('getLastTicket', [DashboardController::class, 'getLastTicket']);
    Route::get('getDasboardTickets', [DashboardController::class, 'getDasboardTickets']);
    Route::get('getDasboardInterventions', [DashboardController::class, 'getDasboardInterventions']);
    Route::get('getDasboardPlanning', [DashboardController::class, 'getDasboardPlanning']);
    Route::get('getUserTicketBuffer', [DashboardController::class, 'getUserTicketBuffer']);
    Route::get('getDashboardStats', [DashboardController::class, 'getDashboardStats']);

    Route::get('getTrackingSections', [TrackingController::class, 'getTrackingSections']);
    Route::post('saveTrackingMatrix', [TrackingController::class, 'saveTrackingMatrix']);
    Route::get('getTrackingMatrice', [TrackingController::class, 'getTrackingMatrice']);

    Route::post('deleteTrackingSection/{id}', [SectionController::class, 'deleteTrackingSection']);
    Route::post('saveSection', [SectionController::class, 'saveSection']);
    Route::get('getSection/{id}', [SectionController::class, 'getSection']);

    Route::get('getTrackingCriterias', [CriteriaController::class, 'getTrackingCriterias']);
    Route::post('deleteTrackingCriteria/{id}', [CriteriaController::class, 'deleteTrackingCriteria']);
    Route::post('saveCriteria', [CriteriaController::class, 'saveCriteria']);
    Route::get('getCriteria/{id}', [CriteriaController::class, 'getCriteria']);

    Route::post('saveTicket', [TicketController::class, 'saveTicket']);
    Route::get('getAllTickets', [TicketController::class, 'getTickets']);
    Route::post('updateTicketState', [TicketController::class, 'updateTicketState']);
    Route::post('delete/ticket/{id}', [TicketController::class, 'deleteTicket']);
    Route::get('edit/ticket/{id}', [TicketController::class, 'showTicket']);
    Route::post('saveIntervention', [InterventionController::class, 'saveIntervention']);

    // ! ------------------ DASHBOARD
    Route::get('getContacts', [DashboardController::class, 'getContacts']);
    Route::get('getDemandes', [DashboardController::class, 'getDemandes']);
    Route::post('handleQuotation', [DashboardController::class, 'handleQuotation']);
    Route::post('delete/demande/{id}', [DashboardController::class, 'deleteQuotation']);
    Route::get('cs_interventions', [DashboardController::class, 'cs_interventions']);
    Route::get('sales_interventions', [DashboardController::class, 'sales_interventions']);
    Route::get('dash/tickets', [DashboardController::class, 'tickets']);
    Route::get('dashborad/counts', [DashboardController::class, 'counts']);


    Route::get('getpostsList', [BlogController::class, 'getpostsList']);
    Route::post('savePost', [BlogController::class, 'savePost']);
    Route::get('getPostFormInfo/{id}', [BlogController::class, 'getPostFormInfo']);
    Route::post('changePostVisibility/{id}', [BlogController::class, 'changePostVisibility']);
    // Route::post('deleteSchool/{id}', [BlogController::class , 'deleteSchool']);

    Route::get('getcontentsList', [ContentController::class, 'getcontentsList']);
    Route::post('saveContent', [ContentController::class, 'saveContent']);
    Route::get('getContentFormInfo/{id}', [ContentController::class, 'getContentFormInfo']);
    Route::post('changeContentVisibility/{id}', [ContentController::class, 'changeContentVisibility']);
    Route::get('delete/content/{id}', [ContentController::class, 'deleteContent']);

    Route::get('gettutorielsList', [TutorielController::class, 'gettutorielsList']);
    Route::post('saveTutoriel', [TutorielController::class, 'saveTutoriel']);
    Route::get('getTutorielFormInfo/{id}', [TutorielController::class, 'getTutorielFormInfo']);
    Route::get('getTutorielModules', [TutorielController::class, 'getTutorielModules']);

    Route::get('TreeDDocumentationTreeData/{id?}', [DocumentationController::class, 'TreegetTreeData']);

    // ! BY TYPES
    Route::get('getDocsByTypes/{type_1}/{type_2}/{id?}', [DocumentationController::class, 'getTreeDataByTypes']);

    Route::get('loadDocumentationBritish/{id}', [DocumentationController::class, 'loadDocumentationBritish']);
    Route::get('loadDocumentationSchool/{id}', [DocumentationController::class, 'loadDocumentationSchool']);
    Route::get('loadDocumentationKinder/{id}', [DocumentationController::class, 'loadDocumentationKinder']);
    Route::get('loadDocumentationNoType/{id}', [DocumentationController::class, 'loadDocumentationNoType']);
    Route::get('loadDocumenataionByTypes/{id}', [DocumentationController::class, 'loadDocsByTypes']);
    Route::get('trees/docs', [DocumentationController::class, 'parentsTree']);

    Route::get('getDocumentation/{id}', [DocumentationController::class, 'getDocumentation']);
    Route::post('saveDocumentation', [DocumentationController::class, 'saveDocumentation']);
    Route::post('doc/delete/{id}', [DocumentationController::class, 'deleteDoc']);
    Route::get('getErrorsList', [LogController::class, 'getErrorsList']);
    Route::get('markHandled', [LogController::class, 'markHandled']);

    Route::get('getUsageTracking', [SchoolController::class, 'getUsageTracking']);

    // ! ------------------ get the founder logs
    Route::get('founder/connexions', [FounderConxController::class, 'index']);

    // ! ------------------ TYPES
    Route::get('types', [TypeController::class, 'index']);
    Route::post('save/type', [TypeController::class, 'saveType']);
    Route::post("type/delete/{id}", [TypeController::class, 'deleteType']);
    Route::get('type/item/{id}', [TypeController::class, 'getItem']);

    // ! ------------------ TEAMS
    Route::get('teams', [TeamMemberController::class, 'index']);
    Route::post('save/team', [TeamMemberController::class, 'saveTeam']);
    Route::post("team/delete/{id}", [TeamMemberController::class, 'deleteMember']);
    Route::get('team/item/{id}', [TeamMemberController::class, 'getItem']);

    // ! ------------------ RELEASES
    Route::get('releases/list', [ReleaseController::class, 'index']);
    Route::post('save/release', [ReleaseController::class, 'save']);
    Route::post('delete/release/{id}', [ReleaseController::class, 'delete']);
    Route::get('release/item/{id}', [ReleaseController::class, 'getItem']);


    // ! ------------------ INDICATORS
    Route::get('indicators', [IndicatorController::class, 'index']);
    Route::post('save/indicator', [IndicatorController::class, 'save']);
    Route::post('delete/indicator/{id}', [IndicatorController::class, 'delete']);
    Route::get('indicators_by_rubrique', [IndicatorController::class, 'by_rubrique']);

    // ! ------------------ CHECKUP
    Route::get('checkup/schools', [CheckupTrackingController::class, 'schools']);
    Route::get('last/checkups', [CheckupTrackingController::class, 'last_checkups']);
    Route::get('two_weeks/checkups', [CheckupTrackingController::class, 'two_weeks_ago']);
    Route::get('checkups/exists', [CheckupTrackingController::class, 'check']);
    Route::get('checkups/exists/two_weeks', [CheckupTrackingController::class, 'check_two_weeks']);

    // ! ------------------ ARTCILE
    Route::post('/addArticleCat', [articleCategoryController::class, 'insertCategory']);

    // ! ------------------ DEPENSES
    Route::get('rubriques', [DepenseController::class, 'rubriques']);
    Route::get('depenses', [DepenseController::class, 'depenses']);
    Route::get('depenses/leads', [DepenseController::class, 'depensesLeads']);
    Route::post('validate/depense/{id}', [DepenseController::class, 'validatTheRequest']);
    Route::get('get/depenses/by_filter/{needel}/{state?}', [DepenseController::class, 'filter']);
    Route::get('get/depenses/by_filter_rubrique/{needel}/{state?}', [DepenseController::class, 'filter_rubrique']);
    Route::get('get/item/{id}', [DepenseController::class, 'getDepense']);
    Route::post('update/depense', [DepenseController::class, 'updateDepense']);

    // ! ------------------ LOGOUT
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('getDocumentationsHelp/{id?}', [DocumentationController::class, 'getDocumentationHelp']);
Route::get('searchDocumentationHelp', [DocumentationController::class, 'searchDocumentationHelp']);
Route::get('indicators', [IndicatorController::class, 'index']);
Route::get('getFounderSchool', function (Request $request) {

    // return response($request->alias);

    if ($request->alias) {
        $school = App\Models\School::where('founder_alias', 'LIKE', $request->alias)->first();

        if (!$school)
            return response('not found', 404);

        $result = [
            'logo' => $school->getLogo(),
            'name' => $school->name,
            'link' => $school->web_link,
            'color' => ($school->color ? $school->color : '#241752')
        ];

        return response()->json($result);
    } else {
        return response('alias required', 500);
    }
});

Route::get('get_schools_colors', function () {

    foreach (App\Models\School::whereNotNull('web_link')->get() as $key => $school) {
        try {
            $response = Http::get('https://boti.education/' . $school->web_link . '/get_color');
            $school->color = $response->body();
            $school->save();
            echo ($school->name . '(https://boti.education/' . $school->web_link . '/get_color' . ') : ' . $school->color . '<br>');
        } catch (\Exception $e) {
            echo ($school->name . '(https://boti.education/' . $school->web_link . '/get_color' . ') : Failed ' . $e->getMessage() . '<br>');
        }
    }
});

//  ! ABOUT FOUNDER CONNEXIONS
Route::post('save/logs', [FounderConxController::class, 'save']);

Route::get('get_schools_aliases', function () {

    foreach (App\Models\School::whereNull('founder_alias')->get() as $key => $school) {
        $alias = strtolower(substr(str_replace(' ', '', $school->web_link), 2, 5)) . rand(1000, 9999);
        $school->founder_alias = $alias;
        $school->save();
    }

    $alias = [];
    foreach (App\Models\School::select('founder_alias')->get() as $key => $value) {
        array_push($alias, $value->founder_alias);
    }

    $schools_alias = App\Models\School::get();
    return response()->view('aliases', ['schools_aliases' =>  $schools_alias]);
});


Route::middleware('boti')->group(function () {
    Route::post('/logError', [LogController::class, 'logError']);
});


// ! GET THE CONTENTS FOR BOTI APP
Route::get('list/contents', [ContentController::class, 'getcontentsListToApp']);
