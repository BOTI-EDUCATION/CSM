import Vue from 'vue';
import Router from 'vue-router';
import Test from './views/Test.vue'
import Dashboard from './views/home/Dashboard.vue'
import DashboardV2 from './views/home/DashboardV2.vue'
import Nature from './views/Nature/nature-list.vue'
import Documentation from './views/documentation/Documentation.vue'
import DocumentationList from './views/documentation/DocumentationList.vue'
import DocumentationForm from './views/documentation/DocumentationForm.vue'

import Notifications from './views/notifications/Notifications.vue'
import Login from './views/auth/Login.vue'
import Page404 from './views/Page404.vue'
import UserList from './views/paramettrage/users/UserList.vue'
import UserForm from './views/paramettrage/users/UserForm.vue'
import User from './views/paramettrage/users/User.vue'

import Module from './views/paramettrage/modules/Module.vue'
import ModuleList from './views/paramettrage/modules/ModuleList.vue'
import ModuleForm from './views/paramettrage/modules/ModuleForm.vue'

import Checklist from './views/paramettrage/checklists/Checklist.vue'
import ChecklistList from './views/paramettrage/checklists/ChecklistList.vue'
import ChecklistForm from './views/paramettrage/checklists/ChecklistForm.vue'

import Theme from './views/paramettrage/themes/Theme.vue'
import ThemeList from './views/paramettrage/themes/ThemeList.vue'
import ThemeForm from './views/paramettrage/themes/ThemeForm.vue'

import School from './views/paramettrage/schools/School.vue'
import SchoolList from './views/paramettrage/schools/SchoolList.vue'
import SchoolForm from './views/paramettrage/schools/SchoolForm.vue'
import SchoolItem from './views/paramettrage/schools/SchoolItem.vue'
import SchoolMap from './views/paramettrage/schools/SchoolMap.vue'

import Config from './views/paramettrage/configs/Config.vue'
import ConfigList from './views/paramettrage/configs/ConfigList.vue'

// ! ABOUT TYPES,TEAMS AND RELEASES
import ReleaseType from './views/type_releases/releaseType.vue'
import Teams from './views/teams/team-list.vue'
import Releases from './views/releases/time-line.vue'
import NewRelease from './views/releases/add-time-line.vue'
import ReleaseList from './views/releases/time-list.vue'

import Tracking from './views/tracking/Tracking.vue'
import Newevaluation from './views/tracking/Newevaluation.vue'
import Evaluationschool from './views/tracking/Evaluationschool.vue'
import Evaluationmatrice from './views/tracking/Evaluationmatrice.vue'
import Matrice from './views/tracking/Matrice.vue'

import Section from './views/paramettrage/tracking/sections/Section.vue'
import SectionList from './views/paramettrage/tracking/sections/SectionList.vue'
import SectionForm from './views/paramettrage/tracking/sections/SectionForm.vue'

import Criteria from './views/paramettrage/tracking/criterias/Criteria.vue'
import CriteriaList from './views/paramettrage/tracking/criterias/CriteriaList.vue'
import CriteriaForm from './views/paramettrage/tracking/criterias/CriteriaForm.vue'

import Contacts from './views/Contacts'
import Demandes from './views/Demandes'

import Blog from './views/blog/Blog.vue'
import Bloglist from './views/blog/BlogList.vue'
import BlogItem from './views/blog/BlogItem.vue'
import BlogForm from './views/blog/BlogForm.vue'

import Content from './views/content/Content.vue'
import Contentlist from './views/content/ContentList.vue'
import ContentItem from './views/content/ContentItem.vue'
import ContentForm from './views/content/ContentForm.vue'

import Tutoriel from './views/tutoriel/Tutoriel.vue'
import Tutoriellist from './views/tutoriel/TutorielList.vue'
import TutorielItem from './views/tutoriel/TutorielItem.vue'
import TutorielForm from './views/tutoriel/TutorielForm.vue'

import Lead from './views/leads/Lead.vue'
import Leadlist from './views/leads/LeadList.vue'
import LeadItem from './views/leads/LeadItem.vue'
import LeadForm from './views/leads/LeadForm.vue'
import LeadMap from './views/leads/LeadMap.vue'

import Ticket from './views/tickets/Ticket.vue'
import TicketList from './views/tickets/TicketList.vue'
import TicketItem from './views/tickets/ticket-edit.vue'

import Checkup from './views/Checkup.vue'

import ErrorsTracker from './views/log/ErrorsTracker.vue'

import UsageTracking from './views/UsageTracking.vue'

//School Jobs
import IndexSchoolJobs from './views/schooljobs/indexSchoolJobs.vue'
import ListeCandidatOffer from './views/schooljobs/ListeCandidatOffer.vue'
import ListOffers from './views/schooljobs/ListeOffers.vue'
import ListeCandidats from './views/schooljobs/ListeCandidats.vue'
import FormeOffer from './views/schooljobs/FormeOffer.vue'
import OfferDetails from './views/schooljobs/OfferDetails.vue'
import CandidatDetails from './views/schooljobs/CandidatDetails.vue'
import FormeCandidat from './views/schooljobs/FormeCandidat.vue'

// School Life
import SchoolLifeBlogForm from './views/schoollife/BlogForm.vue'
import ListeBlogs from './views/schoollife/ListeBlogs.vue'
import BlogDetails from './views/schoollife/BlogDetails.vue'
import ListeQuotes from './views/schoollife/ListeQuotes.vue'
import ListeNews from './views/schoollife/ListeNews.vue'
import QuoteForme from './views/schoollife/QuoteForme.vue'
import NewsForme from './views/schoollife/NewsForme.vue'
import NewsDetails from './views/schoollife/NewsDetails.vue'
import QuoteDetails from './views/schoollife/QuoteDetails.vue'

// ! FOUNDER CONNEXIONS
import FounderConnexion from './views/founder/ListConx.vue'

// ! Schools
import DeletedSchool from './views/paramettrage/schools/DeletedSchools.vue'
import DisabledSchool from './views/paramettrage/schools/DisabledSchools.vue'

import acadmyThemes from './views/academy/themes/main-themes.vue'
import acadmyCourses from './views/academy/courses/courses-themes.vue'
Vue.use(Router);

const routes = [
     { path: '/Dashboard', name: 'Dashboard', component: Dashboard },
     { path: '/', name: 'Dashboard', component: DashboardV2 },
    // { path: '/dashboard', name: 'dashboardv2', component: DashboardV2 },
    { path: '/notifications', name: 'Notifications', component: Notifications },
    { path: '/contacts', name: 'Contacts', component: Contacts },
    { path: '/demandes', name: 'Demandes', component: Demandes },
    { path: '/checkup', name: 'Checkup', component: Checkup },
    { path: '/listeCandidats', name: 'listeCandidats', component: ListeCandidats },
    { path: '/homeSchoolJobs', name: 'homeSchoolJobs', component: IndexSchoolJobs },
    { path: '/listeOffres', name: 'listeOffres', component: ListOffers },
    { path: '/formeOffer', name: 'formeOffer', component: FormeOffer },
    { path: '/formeCandidat', name: 'formeCandidat', component: FormeCandidat },
    { path: '/listeOffres/offerDetails/:id', name: 'offerDetails', component: OfferDetails },
    { path: '/listeCandidats/candidatDetails/:id', name: 'candidatDetails', component: CandidatDetails },
    { path: '/listeCandidatsOffer', name: 'listeCandidatsOffer', component: ListeCandidatOffer },
    { path: '/listeBlogs', name: 'ListeBlogs', component: ListeBlogs },
    { path: '/listeBlogs/:id', name: "BlogDetails", component: BlogDetails },
    { path: '/formeBlog', name: 'SchoolLifeBlogForm', component: SchoolLifeBlogForm },
    { path: '/ListeQuotes', name: 'ListeQuotes', component: ListeQuotes },
    { path: '/ListeNews', name: 'ListeNews', component: ListeNews },
    { path: '/FormeQuote', name: 'FormeQuote', component: QuoteForme },
    { path: '/FormeNews', name: 'FormeNews', component: NewsForme },
    { path: '/listeNews/newsDetailSchoolLife/:id', name: "newsDetailSchoolLife", component: NewsDetails },
    { path: '/listeBlogs/quoteDetailSchoolLife/:id', name: "quoteDetailSchoolLife", component: QuoteDetails },
    { path: '/errorstracker', name: "ErrorsTracker", component: ErrorsTracker },
    { path: '/usagetracking', name: "UsageTracking", component: UsageTracking },

    { 
        path: '/documentation', name: 'Documentation', component: Documentation , children: [
            { path: '', component: DocumentationList },
            { path: 'add/:id?', component: DocumentationForm },
            { path: 'edit/:id', component: DocumentationForm },
        ]
    },
    { 
        path: '/ticket', name: 'Ticket', component: Ticket , children: [
            { path: '', component: TicketList },
            { path: 'edit/ticket/:id', component: TicketItem },
        ]

    },
    { path: '/formeCandidat', name: 'formeCandidat', component: FormeCandidat },
    
    {
        path: '/schools', name: 'Schools', component: School, children: [
            { path: '', component: SchoolList , name: 'SchoolsList' },
            { path: 'add', component: SchoolForm },
            { path: 'map', component: SchoolMap },
            { path: 'view/:id', component: SchoolItem },
            { path: 'edit/:id', component: SchoolForm },
        ]
    },

    {
        path: '/deleted/schools', name:"DeletedSchools", component: DeletedSchool
    },
    {
        path: '/disabled/schools', name:"DisabledSchools", component: DisabledSchool
    },
    
    {
        path: '/leads', name: 'Lead', component: Lead, children: [
            { path: '', component: Leadlist },
            { path: 'add', component: LeadForm },
            { path: 'view/:id', component: LeadItem },
            { path: 'edit/:id', component: LeadForm },
            { path: 'map', component: LeadMap }
        ]
    },
    {
        path: '/tracking', name: 'Tracking', component: Tracking, children: [
            { path: '', component: Matrice },
            { path: 'evaluation', component: Newevaluation },
            { path: 'evaluation/school', component: Evaluationschool },
            { path: 'evaluation/matrice', component: Evaluationmatrice }
        ]
    },
    {
        path: '/blog', name: 'Blog', component: Blog, children: [
            { path: '', component: Bloglist },
            { path: 'view/:id', component: BlogItem },
            { path: 'add', component: BlogForm },
            { path: 'edit/:id', component: BlogForm }
        ]
    },
    {
        path: '/content', name: 'Content', component: Content, children: [
            { path: '', component: Contentlist },
            { path: 'view/:id', component: ContentItem },
            { path: 'add', component: ContentForm },
            { path: 'edit/:id', component: ContentForm }
        ]
    },
    {
        path: '/tutoriel', name: 'Tutoriel', component: Tutoriel , children: [
            { path: '', component: Tutoriellist },
            { path: 'view/:id', component: TutorielItem },
            { path: 'add', component: TutorielForm },
            { path: 'edit/:id', component: TutorielForm }
        ]
    },
    { path: '/login', name: 'Login', component: Login },
    {
        path: '/paramettrage/users', name: 'Users', component: User, children: [
            { path: '', component: UserList },
            { path: 'add', component: UserForm },
            { path: 'edit/:id', component: UserForm }
        ]
    },
    {
        path: '/paramettrage/modules', name: 'Modules', component: Module, children: [
            { path: '', component: ModuleList },
            { path: 'add', component: ModuleForm },
            { path: 'edit/:id', component: ModuleForm }
        ]
    },
    {
        path: '/paramettrage/checklists', name: 'Checklists', component: Checklist, children: [
            { path: '', component: ChecklistList },
            { path: 'add', component: ChecklistForm },
            { path: 'edit/:id', component: ChecklistForm }
        ]
    },
    {
        path: '/paramettrage/themes', name: 'Themes', component: Theme, children: [
            { path: '', component: ThemeList },
            { path: 'add', component: ThemeForm },
            { path: 'edit/:id', component: ThemeForm }
        ]
    },
    {
        path: '/paramettrage/configs', name: 'Configs', component: Config, children: [
            { path: '', component: ConfigList },
        ]
    },
    {
        path: '/paramettrage/section', name: 'Section', component: Section, children: [
            { path: '', component: SectionList },
            { path: 'add', component: SectionForm },
            { path: 'edit/:id', component: SectionForm }
        ]
    },
    {
        path: '/paramettrage/criteria', name: 'Criteria', component: Criteria, children: [
            { path: '', component: CriteriaList },
            { path: 'add', component: CriteriaForm },
            { path: 'edit/:id', component: CriteriaForm }
        ]
    },

    {
        path:'/academy/themes',
        component: acadmyThemes
    },

    {
        path:'/academy/courses',
        component:acadmyCourses,
    },

    {
        path:'/founder/connexion',
        component:FounderConnexion
    },

    {
        path:'/nature',
        component:Nature
    },


    {
        path:'/types',
        component:ReleaseType
    },
    {
        path:'/teams',
        component:Teams
    },


    {
        path:'/releases', component:Releases, children:[
            { path:'',    component:ReleaseList },
            { path:'add', component:NewRelease },
            { path:'edit/:id', component:NewRelease },
        ]
    },

    
    { path: '*', name: '404', component: Page404 },

];

const router = new Router({
    base: '/csm',
    linkActiveClass: 'active',
    mode: 'history',
    routes,
    scrollBehavior(to, from, savedPosition){
        console.log(savedPosition);
        if(savedPosition){
            return savedPosition;
        }
        return {left: 0, top: 0};
    }
});


router.beforeEach((to, from, next) => {
    let tkn = localStorage.getItem('auth-token');
    if (to.path !== '/login' && !tkn) next({ name: 'Login' })
    else if (to.path == '/login' && tkn) next({ path: '/' })
    else next()
})

export default router;