<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Paramettrage\ChecklistController;
use App\Http\Controllers\Paramettrage\ConfigController;
use App\Http\Controllers\Paramettrage\CriteriaController;
use App\Http\Controllers\Paramettrage\ModuleController;
use App\Http\Controllers\Paramettrage\SchoolController;
use App\Http\Controllers\Paramettrage\SectionController;
use App\Http\Controllers\Paramettrage\ThemeController;
use App\Http\Controllers\Paramettrage\UserController;
use App\Http\Controllers\SchoolJobs\HomeController;
use App\Http\Controllers\SchoolLife\BlogsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TutorielController;
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

Route::get('/',function(){
    return 'ok';
});

// Route::get('/createSymlinks',function(){
//     Artisan::call('storage:link');
// });

Route::get('/academy/getAcademyTree',[AcademyController::class,'getAcademyTree']);
Route::get('/academy/getCourse/{id}',[AcademyController::class,'getCourse']);
Route::get('/academy/getNextCourse/{id}',[AcademyController::class,'getNextCourse']);
Route::get('/academy/getPrevCourse/{id}',[AcademyController::class,'getPrevCourse']);
Route::get('/academy/getQuiz/{id}',[AcademyController::class,'getQuiz']);

Route::post('/loginCandidat',[HomeController::class,'loginCandidat']);
Route::get('/getAllOffers', [HomeController::class, 'getAllOffers']);
Route::post('/addCandidat', [HomeController::class, 'addCandidat']);

//School Life
Route::get('/getAllArticles', [BlogsController::class, 'getAllArticles']);
Route::get('/getArticleDetails/{id}', [BlogsController::class, 'getArticleDetails']);
Route::get('/getNewsDetails/{id}', [BlogsController::class, 'getNewsDetails']);
Route::get('/getQuoteDetails/{id}', [BlogsController::class, 'getQuoteDetails']);
Route::get('/getAllQuotes', [BlogsController::class, 'getAllQuotes']);
Route::get('/getAllNews', [BlogsController::class, 'getAllNews']);


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

// !  new request from botiKinder
Route::post('new/demande',[DashboardController::class, 'saveDemande']);

Route::middleware('auth:api')->group(function () {
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

    Route::get('user', [AuthController::class , 'user']);
    Route::get('getCurUser', [AuthController::class , 'user']);
    Route::get('getUsersList', [UserController::class , 'usersList']);
    Route::get('getRolesList', [UserController::class , 'rolesList']);
    Route::get('getUserFormInfo/{id}', [UserController::class , 'getUserFormInfo']);
    Route::post('saveUser', [UserController::class , 'saveUser']);
    Route::get('getAccountManagers', [UserController::class , 'getAccountManagers']);
    Route::get('getSchoolsContact', [UserController::class , 'getSchoolsContact']);
    Route::get('getUserTickets', [UserController::class , 'getUserTickets']);
    Route::get('getUserInterventions', [UserController::class , 'getUserInterventions']);
    Route::post('changeUserPassword/{id}', [UserController::class , 'changeUserPassword']);
    Route::get('getSalesManagers', [UserController::class , 'getSalesManagers']);
    Route::get('getNotifList', [NotificationsController::class , 'getNotifList']);
    Route::get('getLastNotifications', [NotificationsController::class , 'getLastNotifications']);

    Route::get('getModulesList', [ModuleController::class , 'modulesList']);
    Route::post('saveModule', [ModuleController::class , 'saveModule']);
    Route::get('getModuleFormInfo/{id}', [ModuleController::class , 'getModuleFormInfo']);
    Route::post('deleteModule/{id}', [ModuleController::class , 'deleteModule']);

    Route::get('getChecklistsList', [ChecklistController::class , 'checklistsList']);
    Route::post('saveChecklist', [ChecklistController::class , 'saveChecklist']);
    Route::get('getChecklistFormInfo/{id}', [ChecklistController::class , 'getChecklistFormInfo']);
    Route::post('deleteChecklist/{id}', [ChecklistController::class , 'deleteChecklist']);
    Route::get('getChecklists', [ChecklistController::class , 'getChecklists']);
    Route::get('getCheckupMatrix', [ChecklistController::class , 'getCheckupMatrix']);

    Route::get('getThemesList', [ThemeController::class , 'themesList']);
    Route::post('saveTheme', [ThemeController::class , 'saveTheme']);
    Route::get('getThemeFormInfo/{id}', [ThemeController::class , 'getThemeFormInfo']);
    Route::post('deleteTheme/{id}', [ThemeController::class , 'deleteTheme']);

    Route::get('getSchoolsList', [SchoolController::class , 'schoolsList']);
    Route::post('saveSchool', [SchoolController::class , 'saveSchool']);
    Route::get('getSchoolFormInfo/{id}', [SchoolController::class , 'getSchoolFormInfo']);
    Route::post('deleteSchool/{id}', [SchoolController::class , 'deleteSchool']);
    Route::get('getSchoolContacts/{id}', [SchoolController::class , 'getSchoolContacts']);
    Route::get('getSchoolIntervention/{id}', [SchoolController::class , 'getSchoolIntervention']);
    Route::get('getSchoolTickets/{id}', [SchoolController::class , 'getSchoolTickets']);
    Route::post('saveSchoolContact/{id}', [SchoolController::class , 'saveSchoolContact']);
    Route::post('updateSchoolContact', [SchoolController::class , 'updateSchoolContact']);
    Route::get('getContactFormInfo/{id}', [SchoolController::class , 'getContactFormInfo']);
    Route::get('getSchoolsCoordinates', [SchoolController::class , 'getSchoolsCoordinates']);
    Route::get('getSchoolchecklists/{id}', [SchoolController::class , 'getSchoolchecklists']);
    Route::post('saveSchoolChecklist/{id}', [SchoolController::class , 'saveSchoolChecklist']);
    Route::post('updateStateCheckItem', [SchoolController::class , 'updateStateCheckItem']);
    Route::post('deleteChecklistSchool', [SchoolController::class , 'deleteChecklistSchool']);

    Route::get('deleted/schools',[SchoolController::class, 'deletedSchools']);
    Route::post('soft/school/{id}',[SchoolController::class, 'softSchool']);

    // ! disabled and enabled scchool
    Route::get('disabled/schools',[SchoolController::class, 'disabledSchools']);
    Route::post('disable/school/{id}',[SchoolController::class, 'disableSchool']);
    Route::post('enabled/school/{id}',[SchoolController::class, 'enabledSchool']);
    
    Route::get('getLeadsList', [LeadController::class , 'leadsList']);
    Route::post('saveLead', [LeadController::class , 'saveLead']);
    Route::get('getLeadFormInfo/{id}', [LeadController::class , 'getLeadFormInfo']);
    Route::post('deleteLead/{id}', [LeadController::class , 'deleteLead']);
    Route::get('getLeadContacts/{id}', [LeadController::class , 'getLeadContacts']);
    Route::post('saveLeadContact/{id}', [LeadController::class , 'saveLeadContact']);
    Route::get('getContactFormInfo/{id}', [LeadController::class , 'getContactFormInfo']);
    Route::get('getLeadsCoordinates', [LeadController::class , 'getLeadsCoordinates']);
    
    Route::get('getConfigsList', [ConfigController::class , 'getConfigsList']);
    Route::post('updateConfig', [ConfigController::class , 'updateConfig']);
    
    Route::get('getEtatTraitementIncidents', [DashboardController::class , 'getEtatTraitementIncidents']);
    Route::get('getLastIntervention', [DashboardController::class , 'getLastIntervention']);
    Route::get('getLastTicket', [DashboardController::class , 'getLastTicket']);
    Route::get('getDasboardTickets', [DashboardController::class , 'getDasboardTickets']);
    Route::get('getDasboardInterventions', [DashboardController::class , 'getDasboardInterventions']);
    Route::get('getDasboardPlanning', [DashboardController::class , 'getDasboardPlanning']);
    Route::get('getUserTicketBuffer', [DashboardController::class , 'getUserTicketBuffer']);
    Route::get('getDashboardStats', [DashboardController::class , 'getDashboardStats']);

    Route::get('getTrackingSections', [TrackingController::class , 'getTrackingSections']);
    Route::post('saveTrackingMatrix', [TrackingController::class , 'saveTrackingMatrix']);
    Route::get('getTrackingMatrice', [TrackingController::class , 'getTrackingMatrice']);
    
    Route::post('deleteTrackingSection/{id}', [SectionController::class , 'deleteTrackingSection']);
    Route::post('saveSection', [SectionController::class , 'saveSection']);
    Route::get('getSection/{id}', [SectionController::class , 'getSection']);
    
    Route::get('getTrackingCriterias', [CriteriaController::class , 'getTrackingCriterias']);
    Route::post('deleteTrackingCriteria/{id}', [CriteriaController::class , 'deleteTrackingCriteria']);
    Route::post('saveCriteria', [CriteriaController::class , 'saveCriteria']);
    Route::get('getCriteria/{id}', [CriteriaController::class , 'getCriteria']);
    
    Route::post('saveTicket', [TicketController::class , 'saveTicket']);
    Route::get('getAllTickets', [TicketController::class , 'getTickets']);
    Route::post('updateTicketState', [TicketController::class , 'updateTicketState']);
    
    Route::post('saveIntervention', [InterventionController::class , 'saveIntervention']);
    
    Route::get('getContacts', [DashboardController::class , 'getContacts']);
    Route::get('getDemandes', [DashboardController::class , 'getDemandes']);
    Route::post('handleQuotation', [DashboardController::class , 'handleQuotation']);

    Route::post('delete/demande/{id}',[DashboardController::class, 'deleteQuotation']);
    
    Route::get('getpostsList', [BlogController::class , 'getpostsList']);
    Route::post('savePost', [BlogController::class , 'savePost']);
    Route::get('getPostFormInfo/{id}', [BlogController::class , 'getPostFormInfo']);
    Route::post('changePostVisibility/{id}', [BlogController::class , 'changePostVisibility']);
    // Route::post('deleteSchool/{id}', [BlogController::class , 'deleteSchool']);

    Route::get('getcontentsList', [ContentController::class , 'getcontentsList']);
    Route::post('saveContent', [ContentController::class , 'saveContent']);
    Route::get('getContentFormInfo/{id}', [ContentController::class , 'getContentFormInfo']);
    Route::post('changeContentVisibility/{id}', [ContentController::class , 'changeContentVisibility']);
    
    Route::get('gettutorielsList', [TutorielController::class , 'gettutorielsList']);
    Route::post('saveTutoriel', [TutorielController::class , 'saveTutoriel']);
    Route::get('getTutorielFormInfo/{id}', [TutorielController::class , 'getTutorielFormInfo']);
    Route::get('getTutorielModules', [TutorielController::class , 'getTutorielModules']);
    
    Route::get('getDocumentationTreeData/{id?}', [DocumentationController::class , 'getTreeData']);
    Route::get('loadDocumentation/{id}', [DocumentationController::class , 'loadDocumentation']);
    Route::get('getDocumentation/{id}', [DocumentationController::class , 'getDocumentation']);
    Route::post('saveDocumentation', [DocumentationController::class , 'saveDocumentation']);
    
    Route::get('getErrorsList', [LogController::class , 'getErrorsList']);
    Route::get('markHandled', [LogController::class , 'markHandled']);

    Route::get('getUsageTracking', [SchoolController::class , 'getUsageTracking']);

    Route::post('logout', [AuthController::class , 'logout']);
});

Route::get('getDocumentationsHelp/{id?}', [DocumentationController::class , 'getDocumentationHelp']);
Route::get('searchDocumentationHelp', [DocumentationController::class , 'searchDocumentationHelp']);

Route::get('getFounderSchool', function(Request $request){
    
    // return response($request->alias);

    if($request->alias){
        $school = App\Models\School::where('founder_alias','LIKE',$request->alias)->first();

        if(!$school)
        return response('not found',404);

        $result = [
            'logo' => $school->getLogo(),
            'name' => $school->name,
            'link' => $school->web_link,
            'color' => ($school->color?$school->color:'#241752')
        ];

        return response()->json($result);
    }else{
        return response('alias required',500);
    }
});

Route::get('get_schools_colors',function(){
    
    foreach (App\Models\School::whereNotNull('web_link')->get() as $key => $school) {
        try {
            $response = Http::get('https://boti.education/'.$school->web_link.'/get_color');
            $school->color = $response->body();
            $school->save();
            echo($school->name.'(https://boti.education/'.$school->web_link.'/get_color'.') : '.$school->color.'<br>');
            
        } catch (\Exception $e) {
            echo($school->name.'(https://boti.education/'.$school->web_link.'/get_color'.') : Failed '.$e->getMessage().'<br>');
        }
    }
});

Route::get('get_schools_aliases',function(){
    
    foreach (App\Models\School::whereNotNull('web_link')->get() as $key => $school) {
        $alias = strtolower(substr(str_replace(' ','',$school->web_link),2,5)).rand(1000,9999) ;

        $school->founder_alias = $alias;
        $school->save();
    }
});

Route::middleware('boti')->group(function () {
    Route::post('/logError', [LogController::class, 'logError']);
});

