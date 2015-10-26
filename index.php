<?php
require('location.php');
?>
<html>
    <head>
        <title>Where am I?</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- Include roboto.css to use the Roboto web font, material.css to include the theme and ripples.css to style the ripple effect -->
        <link href="css/roboto.min.css" rel="stylesheet">
        <link href="css/material.min.css" rel="stylesheet">
        <link href="css/ripples.min.css" rel="stylesheet">
        <style>
            .navbar{
              margin-bottom:0px;
            }
            #map-canvas {
              width:calc(100% - 60px);
              height: calc(100% - 60px);
              position: relative;
            }
        </style>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script>
                  var map;
                  function initialize() {
                    map = new google.maps.Map(document.getElementById('map-canvas'), {
                      zoom: 12,
                      center: new google.maps.LatLng(<?php echo $lat . ", " . $long; ?>),
                      mapTypeId: google.maps.MapTypeId.ROADMAP,
                      disableDefaultUI: true,
                      draggable: false,
                      styles: [
                        {
                          featureType: 'poi',
                          stylers: [
                            { visibility: 'off' }
                          ]
                        }
                      ]
                    });
                    var iconBase = 'https://maps.google.com/mapfiles/kml/paddle/';
                    var icons = {
                      location: {
                        name: 'HITS',
                        icon: iconBase + 'red-circle.png'
                      },
                    };
                    function addMarker(feature) {
                      var marker = new google.maps.Marker({
                        position: feature.position,
                        icon: icons[feature.type].icon,
                        map: map,
                        title: feature.title
                      });
                    }
                    var features = [
                      {
                        position: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $long; ?>),
                        type: 'location',
                        title: 'Eric Villnow',
                        infowindow: 'Hello World'
                      },
                    ];
                    for (var i = 0, feature; feature = features[i]; i++) {
                      addMarker(feature);
                    }
                    var legend = document.getElementById('legend');
                    for (var key in icons) {
                      var type = icons[key];
                      var name = type.name;
                      var icon = type.icon;
                      var div = document.createElement('div');
                      div.innerHTML = '<img src="' + icon + '"> ' + name;
                      legend.appendChild(div);
                    }
                  }
                  google.maps.event.addDomListener(window, 'load', initialize);
            </script>
    </head>
    <body>
        <div class="navbar navbar-inverse">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="javascript:void(0)">Where am I?</a>
            </div>
            <div class="navbar-collapse collapse navbar-inverse-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="javascript:void(0)">App</a></li>
                    <li><a href="javascript:void(0)">History</a></li>
                </ul>
                <form class="navbar-form navbar-left">
                    <input type="text" class="form-control col-lg-8" placeholder="Search">
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="javascript:void(0)">Register</a></li>
                </ul>
            </div>
        </div>
        <div class=""
        <div id="map-canvas"></div>
        <div class="container">
        </div>
    </body>
</html>