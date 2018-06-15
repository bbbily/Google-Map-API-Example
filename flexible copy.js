// Page Header .logo-box animation
var Vue = require('vue');

var SmoothScroll = require('smooth-scroll');

var wpApi = '/wp-json/wp/v2/pages/?slug=moda';

var flexContent = new Vue({
  // render: function(h) {
  //   return h('#flex-content', this);
  // },
  el: '#flex-content',

  data: {
      allProjects: [],
      currentPage: 1,
      showMap: false,
      modalProject: {}
  },

  computed: {
    total: function() {
      return this.allProjects.length;
    },
    pages: function() {
      return Math.floor((this.allProjects.length - 1) / 6) + 1;
    },
    projects: function() {
      return this.allProjects.slice((this.currentPage - 1) * 6, this.currentPage * 6);
    }
  },

  methods: {
    getProjects: function() {
      var that = this;
      return $.get(wpApi)
      .done(function(data) {
        that.allProjects = data[0].acf.flexible_content[1].projects;
        return that.allProjects;
      })
      .fail(function(error) {
        console.log("error", error);
      })
      .always(function() {
        console.log("complete");
      });
    },

    scrollTop: function() {
      var anchor = document.querySelector('.main-content');
      // var options = { offset: 70, speed: 700, easing: 'easeInOutCubic' };
      (new SmoothScroll()).animateScroll(anchor);
    },

    initMap: function() {
      var that = this;
      var center = {lat: 40.758701, lng: -111.876183};
      var zoom = 11;

      if (Foundation.MediaQuery.atLeast('medium')) {
          zoom = 12;
      }

      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: zoom,
          center: center,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          disableDefaultUI: true,
      })

      var label = 1;
      // var bounds = new google.maps.LatLngBounds();

      this.projects.forEach(function(project) {
        if (project.acf.project_address) {
          var position = new google.maps.LatLng(parseFloat(project.acf.project_address.lat),parseFloat(project.acf.project_address.lng));

          var marker = new google.maps.Marker({
            position: position,
            map: map,
            title: project.post_title,
            // icon: {
            //   url: '/wp-content/themes/solid/dist/images/dot.svg',
            //   scaledSize: new google.maps.Size(49, 49)
            // },
            // label: {text: `${label}`, color: 'white', fontSize: '18px'}
          });

          google.maps.event.addListener(marker, 'click', (e) => {
            that.openModal(project)
          })

          // bounds.extend(position)

          label++;
        }

        // map.fitBounds(bounds);
      })
    },

    openModal: function(project) {
      modal.project = project;
      $('#map-modal').foundation('open');
    }
  },

  mounted: function() {
    var that = this;
    this.getProjects().then(function(data) {
      that.initMap();
    })
  }

});

var modal = new Vue({
    el: '.map-modal-contain',
    data: {
      project: {}
    }
})

setTimeout(function() {
  $('.logo-box').addClass('flipInX');
}, 500);
