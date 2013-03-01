<html>

<!-- 
//////////////////////////////////////////////////////
// Yanbin Luo - Database assigment
// Nov,19,2012
//
// 1-c) if the user clicks on a branch displayed as output of step b), you 
// should display the mid, title, and genre of any movie currently available 
// in that branch, together with the number of copies available at the branch.
//
//////////////////////////////////////////////////////
-->

<head>
<title>Problem 1-c</title>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>

<script>
//Declare the variable that will store the geocode object
var geocoder;
var map;

var maddress = "new york";
function setAddress(input) {
  maddress = input + ", new york";
}

function codeAddress() {
    geocoder.geocode( {'address': maddress}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map, 
				title: maddress,
                position: results[0].geometry.location
            });
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
      });
}

function initialize()
{
  //Set the geocoder variable equal to an instance of the google maps geocoder object as new google.maps.Geocoder()
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
  
  var mapProp = {
    center:latlng,
    zoom:15,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  
  codeAddress();
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>

</head>
<body>
<font face="Courier">

<?php

if (isset($_GET['bid']))
{
  $bid = $_GET['bid'];
  print("bid = ".$bid."<br>");
  
  $user="localhost";
  $password="";
  $database="test";

  $link = mysql_connect('localhost',$user,$password);
  
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  }

  @mysql_select_db($database) or die( "Unable to select database");
  
  $query="SELECT mid, title, genre, count(*) as num_copies
          FROM movie JOIN copy USING (mid)
		  WHERE bid = ".$bid." AND copyid NOT IN
		  (SELECT DISTINCT copyid FROM rented WHERE returndate IS NULL)
          GROUP BY mid, title, genre;";
		  
  print("query = ".$query."<br>");

  $results = mysql_query($query);
  $rows = mysql_num_rows($results);
  if($rows > 0) {
  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 20, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	
	print("<br>| ");
	print(str_replace('-','&nbsp;',str_pad("mid", 10, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("title", 40, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("genre", 20, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("num_copies", 10, "-", STR_PAD_BOTH))." | ");
	
  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 20, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	
    while($obj = mysql_fetch_object($results)){
	  //print("<br>| ");
	  //print(str_replace('-','&nbsp;',str_pad($obj->mid, 10, "-", STR_PAD_BOTH))." | ");
	  print('<br>| '.str_replace($obj->mid, 
	         '<a href="http://localhost/dbForm2.php?mid='.$obj->mid.'">'.$obj->mid.'</a>',
	         str_replace('-','&nbsp;',str_pad($obj->mid, 10, '-', STR_PAD_BOTH))).' | ');
	  print(str_replace('-','&nbsp;',str_pad($obj->title, 40, "-", STR_PAD_BOTH))." | ");
	  print(str_replace('-','&nbsp;',str_pad($obj->genre, 20, "-", STR_PAD_BOTH))." | ");
	  print(str_replace('-','&nbsp;',str_pad($obj->num_copies, 10, "-", STR_PAD_BOTH))." | ");
    }

  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 20, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
  }
  else {
	print("<br> Empty Set!");
  }
  mysql_close($link);
}

if (isset($_GET['baddress']))
{
  $baddress = $_GET['baddress'];
  print("<br><br>baddress = ".$baddress."<br>");
  
  echo '<script> setAddress("'.$baddress.'"); </script>';
}
?> 
</font>
<div id="googleMap" style="width:500px;height:380px;"></div>

</body>
</html>
