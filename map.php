<?php
include "include.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps AJAX + mySQL/PHP Example</title>
    <script src="http://maps.google.com/maps/api/js?sensor=false"
            type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[
    <?php
		$pName = $_SESSION["pName"];
		$pAddress = $_SESSION["pAddress"];
		$pProfile = $_SESSION["pProfile"];
	   	$show = $_SESSION["show"];
		$pLat = $_SESSION["pLat"];
		$pLong = $_SESSION["pLong"];
		if(isset($show)){
			echo  "
			var pName = \"$pName\";
			var pAddress = \"$pAddress\";
			var pProfile =\"$pProfile\";
			var show = $show;
			var pLat = $pLat;
			var pLong = $pLong;";
		}
		else
			echo "var show = 0;";
	?>
    var customIcons = {
      blue: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      red: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };

    function load() {
	  
     /*	 var map = new google.maps.Map(document.getElementById("map"), {
   	   	  center: new google.maps.LatLng(pLat,pLong),
   	      zoom: 15,
   	      mapTypeId: 'roadmap'
     	 });*/
	  



/*		  var rectangle = new google.maps.Rectangle({
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    map: map,
    bounds: {
      north: 41.685,
      south: 40.671,
      east: -73.234,
      west: -75.251
    }
  });*/
	  
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("mapGet.php", function(data) {

	    var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");

	    var map = new google.maps.Map(document.getElementById("map"), {
   	   	  center: new google.maps.LatLng(
		  <?php
		    $pLat = 0;
			$pLong = 0;
			$pAverLat = 0;
			$pAverLong = 0;
		  	if(isset($_SESSION["pLat"]))
			{
		  		$pLat = $_SESSION["pLat"];
				$pLong = $_SESSION["pLong"];
			}
			if(isset( $_SESSION["pAverLat"]))
			{
				$pAverLat = $_SESSION["pAverLat"];
				$pAverLong = $_SESSION["pAverLong"];
			}
			if(isset($_SESSION["show"]))
			{
				$show = $_SESSION["show"];
			}
		  //	if($show==1)
				echo "$pLat,$pLong";
	//		else
		//		echo "$pAverLat,$pAverLong";
		  ?>),
   	      zoom: 15,
   	      mapTypeId: 'roadmap'
     	 });
		
     <?php
    include "include.php";
    $pNorth = $_SESSION["pNorth"];
    $pSouth = $_SESSION["pSouth"];
    $pEast = $_SESSION["pEast"];
    $pWest = $_SESSION["pWest"];
      $drawRectangle = $_SESSION["drawRectangle"];
    
    if($drawRectangle==1)
    {
      echo "
      var rectangle = new google.maps.Rectangle({
      strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        bounds: {
      north: $pNorth,
      south: $pSouth,
      east: $pEast,
      west: $pWest
  
        }
      });";
     }
     ?>

		if(show === 1){
		    var name = pName;
            var address = pAddress;
			var profile = pProfile;
            var point = new google.maps.LatLng(
                parseFloat(pLat),
                parseFloat(pLong));
            var html = "<b>" + name + "</b> <br/>" + address;
            var icon = customIcons["red"] || {};
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              icon: icon.icon,
              shadow: icon.shadow
            });
            bindInfoWindow(marker, map, infoWindow, html);

		}
		else{
		  for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var profile = markers[i].getAttribute("profile");
            var point = new google.maps.LatLng(
                parseFloat(markers[i].getAttribute("lat")),
                parseFloat(markers[i].getAttribute("lng")));
            var html = "<b>" + name + "</b> <br/>" + address;
            var icon = customIcons["red"] || {};
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              icon: icon.icon,
              shadow: icon.shadow
            });
            bindInfoWindow(marker, map, infoWindow, html);
          }
			
		}
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>
  </script>
  </head>

  <body onload="load()">
    <div id="map" style="width: 500px; height: 300px"></div>
  </body>
</html>
