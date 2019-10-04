routes = [
  {
    path: '/',
    url: './index.html',
  },
  {
    name: 'currentDay',
    path: '/currentDay/',
    componentUrl: './pages/currentday.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navcurrentDay");
      }
    }
  },
  {
    name: 'overview',
    path: '/overview/',
    componentUrl: './pages/overview.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navoverview");
      }
    }
  },
  {
    name: 'createuser',
    path: '/createuser/',
    componentUrl: './pages/createuser.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navcreateuser");
      }
    }
  },
  {
    name: 'createscanner',
    path: '/createscanner/',
    componentUrl: './pages/createscanner.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navcreatescanner");
      }
    }
  },
  {
    name: 'editprofile',
    path: '/editprofile/',
    componentUrl: './pages/editprofile.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("naveditprofile");
      }
    }
  },
  {
    name: 'userlist',
    path: '/userlist/:groupID?/',
    componentUrl: './pages/userlist.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navuserlist");
      }
    },
    data: function(){
      return{
        groupID: routeTo.params.groupID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'usergrouplist',
    path: '/usergrouplist/',
    componentUrl: './pages/usergrouplist.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navuserlist");
      }
    }
  },
  {
    name: 'unknowncardlist',
    path: '/unknowncardlist/',
    componentUrl: './pages/unknowncardlist.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navunknowncardlist");
      }
    }
  },
  {
    name: 'createusergroup',
    path: '/createusergroup/',
    componentUrl: './pages/createusergroup.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navcreateusergroup");
      }
    }
  },
  {
    name: 'groupoverview',
    path: '/groupoverview/',
    componentUrl: './pages/groupoverview.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navgroupoverview");
      }
    }
  },
  {
    name: 'login',
    path: '/login/',
    componentUrl: './pages/login.html',
    on:{
      pageInit: function (event, page){
        app.methods.logout();
      }
    }
  },
  {
    name: 'usersoverview',
    path: '/usersoverview/:groupID?/',
    componentUrl: './pages/usersoverview.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navgroupoverview");
      }
    },
    data: function(){
      return{
        projectID: routeTo.params.groupID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'inspectuser',
    path: '/inspectuser/:userID?/',
    componentUrl: './pages/inspectuser.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navuserlist");
      }
    },
    data: function(){
      return{
        projectID: routeTo.params.userID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'inspectusertime',
    path: '/inspectusertime/:userID?/',
    componentUrl: './pages/inspectusertime.html',
    on:{
      pageInit: function (event, page){
        app.methods.highlightli("navuserlist");
      }
    },
    data: function(){
      return{
        projectID: routeTo.params.userID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'navbar', // Internt navn på routen
    path: '/navbar/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/navbar.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  // Default route (404 page). MUST BE THE LAST
  {
    name: 'notfound',
    path: '(.*)',
    componentUrl: './pages/404.html',
    'data-view': 'view-main',
  },
];
