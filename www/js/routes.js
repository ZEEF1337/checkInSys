routes = [
  {
    path: '/',
    url: './index.html',
  },
  {
    name: 'currentDay',
    path: '/currentDay/',
    componentUrl: './pages/currentday.html',
  },
  {
    name: 'overview',
    path: '/overview/',
    componentUrl: './pages/overview.html',
  },
  {
    name: 'createuser',
    path: '/createuser/',
    componentUrl: './pages/createuser.html',
  },
  {
    name: 'createscanner',
    path: '/createscanner/',
    componentUrl: './pages/createscanner.html',
  },
  {
    name: 'editprofile',
    path: '/editprofile/',
    componentUrl: './pages/editprofile.html',
  },
  {
    name: 'userlist',
    path: '/userlist/:groupID?/',
    componentUrl: './pages/userlist.html',
    data: function () {
      return {
        groupID: routeTo.params.groupID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'usergrouplist',
    path: '/usergrouplist/',
    componentUrl: './pages/usergrouplist.html',
  },
  {
    name: 'unknowncardlist',
    path: '/unknowncardlist/',
    componentUrl: './pages/unknowncardlist.html',
  },
  {
    name: 'createusergroup',
    path: '/createusergroup/',
    componentUrl: './pages/createusergroup.html',
  },
  {
    name: 'groupoverview',
    path: '/groupoverview/',
    componentUrl: './pages/groupoverview.html',
  },
  {
    name: 'login',
    path: '/login/',
    componentUrl: './pages/login.html',
  },
  {
    name: 'usersoverview',
    path: '/usersoverview/:groupID?/',
    componentUrl: './pages/usersoverview.html',
    data: function () {
      return {
        projectID: routeTo.params.groupID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'inspectuser',
    path: '/inspectuser/:userID?/',
    componentUrl: './pages/inspectuser.html',
    data: function () {
      return {
        projectID: routeTo.params.userID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'inspectusertime',
    path: '/inspectusertime/:userID?/',
    componentUrl: './pages/inspectusertime.html',
    data: function () {
      return {
        projectID: routeTo.params.userID, // Der laves en ny Variabel som indeholder de parametre som der blev sendt med routet
      }
    }
  },
  {
    name: 'navbar', // Internt navn på routen
    path: '/navbar/', // Den sti som der skal kaldes når der routes i HTML koden, dvs der skal peges på denne sti i 'href'
    componentUrl: './pages/navbar.html', // Den path hvor i filen som vi prøver at indlæse ligger i og navnet på filen
  },
  {
    path: '/panel/',
    panel: {
      componentUrl: './pages/panelLeft.html',
    }
  },
  // Default route (404 page). MUST BE THE LAST
  {
    name: 'notfound',
    path: '(.*)',
    componentUrl: './pages/404.html',
    'data-view': 'view-main',
  }
];
