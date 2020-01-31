// Dom7
var $$ = Dom7;

// Framework7 App main instance
var app = new Framework7({
  root: '#app', // App root element
  id: 'io.framework7.testapp', // App bundle ID
  name: 'Framework7', // App name
  theme: 'auto', // Automatic theme detection
  touch: {mdTouchRipple: false},
  // App root data
  data: function () {
    return {
      firstname: "Jimmie",
      lastname: "Andersen",
      userAvatar: "../images/default.png",
      userID: 6,
      userEmail: "",
      isAdmin: 1,
      loggedIn: true,
      serverIP: "http://192.168.0.200/checkIn/",
    };
  },
  // App root methods
  methods: {
    
    // Alt det her skal ændres.
    popNavbar: function(){
      if(app.data['isAdmin']) {
        $$('#navadminaccordion').show();
      }
      let navbarwidth = $$('#navbar')[0].clientWidth;
      document.getElementById('view-navbar').style.width = navbarwidth + "px";
      document.getElementById('view-home').style.left = navbarwidth + "px";
      $$('.usernamefield').html(app.data['firstname'] +" "+app.data['lastname']);
      $$('.profilepic').attr('src', app.data['userAvatar']);
    },

    highlightli: function(ID){
      let navbarheader = $$('#navbar').find('li>a');
      let navbarsubheader = $$('#navbar').find('li>div>div>a');
      for(let i = 0; i < navbarheader.length; i++) {
          if($$(navbarheader[i]).hasClass('active')){
              $$(navbarheader[i]).removeClass('active');
          }
      }
      for(let i = 0; i < navbarsubheader.length; i++) {
        if($$(navbarsubheader[i]).hasClass('active')){
            $$(navbarsubheader[i]).removeClass('active');
        }
    }

      $$(`#${ID}`).addClass('active');
    },

    logout: function(){
      app.data['firstname'] = "";
      app.data['lastname'] = "";
      app.data['userEmail'] = "";
      app.data['userAvatar'] = "../images/default.png";
      app.data['userID'] = 0;
      app.data['isAdmin'] = 0;
      app.data['loggedIn'] = false;
      //$$('#view-navbar').hide();
    },
    //Indtil den her kommentar


    statusMsg: function (statusmsg, type, headerclass) {
        let bkcolor;
        if (type == "success") {
            bkcolor = "#35a82a";
        } else {
            bkcolor = "#a82a2a";
        }
        let header = $$(headerclass);
        let ogData = {
          html: header.html(),
          backgroundColor: header.css('background-color'),
          color: header.css('color'),
          borderColor: header.css('border-top-color')
        }
        header.html(statusmsg);
        header.css({
            'color': 'white',
            'background-color': bkcolor
        });
        
        $$(header).parent().css('border-top-color', bkcolor);
        setTimeout(function () {
            header.html(ogData.html);
            header.css({
                'color': ogData.color,
                'background-color': ogData.backgroundColor
            });
            $$(header).parent().css('border-top-color', ogData.borderColor);
        }, 5000);
        return;
    },
    
    searchFunction: function (inputFieldID, tableID, rowCount) {

      // En constant som er den funktion der skal køres
      const search = (field, tableID, rowCount) => {
        //Definer lets som der skal bruges i functionen
        let input, filter, table, tr, td, txtValue;
        //Sætter input variablen til at være vores input field object
        input = field.target;
        //Sætter det som skal bruges til at filtre i databasen til værende selve input feltets value
        filter = input.value.toUpperCase();
        //Sætter Table variablen til at være vores table object som skal søges i
        table = $$(`#${tableID}`);
        //Sætter tr variablen til at være alle de elemener i tabellen som matcher vores kriteria som er tr
        tr = table.find('tr');
        //Start på for loop for at kigge igennem alle tabel rows og finde noget der matcher
        for (i = 0; i < tr.length; i++) {
          //Sætter td til at være det specifikke element i tr som vi vil søge igennem
          td = tr[i].getElementsByTagName("td")[rowCount];
          //Checker om der faktisk er sat noget til td
          if (td) {
            //Finder den text value som er i td
            txtValue = td.textContent || td.innerText;
            //Checker om text valuen passer med noget af det vi søger efter
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              //Sætter rowets display til ikke at være none, så det faktisk er synligt
              tr[i].style.display = "";
            } else {
              //Ellers sætter vi display til at være none, så det ikke kan ses af brugeren.
              tr[i].style.display = "none";
            }
          }
        }
      }
      
      //Vi bruger dom7 til at vælge vores input felt, og så reagere vi på keyup events via et callback
      $$(`#${inputFieldID}`).keyup(field => {
        // Kald til funktionen men de krævede variabler
        search(field, tableID, rowCount);
      });
      //Vi bruger dom7 til at vælge vores input felt, og så reagere vi på keyup events via et callback
      $$(`#${inputFieldID}`).click(field => {
        // Kald til funktionen men de krævede variabler
        search(field, tableID, rowCount);
      });
    }, //Slut searchFunction() funktion
    
  },
    // App routes
    routes: routes,

        on: {
      pageInit: function (page) {

        //Login Form to show
        if(app.data['loggedIn'] == false){
          //document.getElementById('view-navbar').style.display = "none";
          app.views.main.router.navigate("/login/", {
            reloadCurrent: true, // Sikrer at der kommer friskt data på siden
          });
          return;
        }

        // Dette er for at sikre os at den første side der bliver indlæst af systemet, er udlon siden
        //#BringNibeBack
        let currentpage = app.views.main.router.currentPageEl.dataset.name;
        if (currentpage == "home") {
          app.views.main.router.navigate("/currentDay/", {
            reloadCurrent: true, // Sikrer at der kommer friskt data på siden
          });
        };
        },
        pageAfterIn: function (page){

        }
      },

      //Panel specifik ting til eftertanke
      panel: {
        collapsedBreakpoint: 768,
        visibleBreakpoint: 1024,
      },
  });

// Init/Create views
var homeView = app.views.create('#view-home', {
  url: '/',
  animate: false,
  main: true,
  master: true,
});
// Navbarview som er det view component der håndtere selve navbaren og alt hvad den gør
var navbarView = app.views.create('#view-navbar', {
  url: '/navbar/',
  animate: false,
  linksView: homeView,
});

