<?php
include_once('core/configuration.php');
require('core/location.php');

$subdomain = array_shift((explode(".", $_SERVER['HTTP_HOST'])));

if(!empty($subdomain)){
//CHECK USER
$result = mysqli_query($CON,"SELECT * FROM `users` WHERE `Subdomain`='$subdomain'");
$valid_user = mysqli_num_rows($result);

//GET USER DATA
if($valid_user == 1){
while($row = mysqli_fetch_array($result)) {
    $USERNAME = $row['Username'];
    $FULLNAME = $row['Fullname'];
}
}else{
  
}

$result = mysqli_query($CON,"SELECT * FROM `logs` WHERE `User`='$USERNAME'");
while($row = mysqli_fetch_array($result)) {
    $lat = $row['Lat'];
    $long = $row['Long'];
}

$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat  . "," . $long;

$json = file_get_contents($url);
$data = json_decode($json, TRUE);

$results = $data['results'];
$zero = $results[0];
$address_components = $zero['address_components'];
$city = $address_components[3];
$city_long = $city['long_name'];

}
?>
<html>
    <head>
        <title><?php echo $FULLNAME; ?> Is Where?</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- Include roboto.css to use the Roboto web font, material.css to include the theme and ripples.css to style the ripple effect -->
        <link href="css/roboto.min.css" rel="stylesheet">
        <link href="css/material.min.css" rel="stylesheet">
        <link href="css/ripples.min.css" rel="stylesheet">
        <style>
            #map-canvas {
              width: 100%;
              height: calc(100% - 60px);
            }
            .no-padding{
              margin-top:-20px;
              padding:0;
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
                      maxZoom: 12,
                      minZoom: 12,
                      keyboardShortcuts: false,
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
                <a class="navbar-brand" href="javascript:void(0)"><?php echo $FULLNAME; ?> Is Where?</a>
            </div>
            <div class="navbar-collapse collapse navbar-inverse-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="javascript:void(0)">Home</a></li>
                    <li><a href="javascript:void(0)">Download App</a></li>
                    <li><a href="javascript:void(0)">History</a></li>
                </ul>
                <form class="navbar-form navbar-left">
                    <input type="text" name="s" class="form-control col-lg-8" placeholder="<?php if(isset($FULLNAME)){ echo $FULLNAME; }else{ echo "Search"; } ?>">
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="javascript:void(0)">Register</a></li>
                </ul>
            </div>
        </div>
        <div class=".container-fluid">
          <div class="col-md-9 no-padding">
            <div id="map-canvas"></div>
          </div>
          <div class="col-md-3">
            <div class="list-group">
              <?php
              $result = mysqli_query($CON,"SELECT * FROM `logs` WHERE `User`='$USERNAME'");
              while($row = mysqli_fetch_array($result)) {
                  echo '<div class="list-group-item"><div class="row-action-primary"><i class="mdi-file-folder"></i></div><div class="row-content"><div class="least-content">';
                  echo '</div><h4 class="list-group-item-heading">';
                  echo $FULLNAME;
                  echo '</h4><p class="list-group-item-text">';
                  echo 'Checked in @ ' . $city_long;
                  echo '</p></div></div><div class="list-group-separator"></div>';
              }
              
              if(isset($_GET['s'])){
                $search_q = $_GET['s'];
                $result = mysqli_query($CON,"SELECT * FROM `users` WHERE Username LIKE '%" . $search_q . "%' OR Fullname LIKE '%" . $search_q  ."%'");
                while($row = mysqli_fetch_array($result)) {
                  echo '<div class="list-group-item"><div class="row-action-primary"><i class="mdi-file-folder"></i></div><div class="row-content"><div class="least-content">';
                  echo '</div><h4 class="list-group-item-heading">';
                  echo '<a href="//' . $row['Subdomain'] . '.whereami-villnoweric.c9.io">' . $row['Fullname'] . '</a>';
                  echo '</h4><p class="list-group-item-text">';
                  echo 'Checked in @ ' . $city_long;
                  echo '</p></div></div><div class="list-group-separator"></div>';
              }
              }
              ?>
            </div>
          </div>
        </div>
    </body>
</html>