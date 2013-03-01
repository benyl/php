<html>

<!-- 
//////////////////////////////////////////////////////
// Yanbin Luo - Database assigment
// Nov,19,2012
//
// 1-b) When the user clicks on the mid of a movie, the system should 
// return a list of all branches (with bid, bname, and baddress) where  
// the movie is currently available, and the number of copies of the movie
// available at the branch. 
//
//////////////////////////////////////////////////////
-->

<head>
<title>Problem 1-b</title>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>

<script>
//Declare the variable that will store the geocode object
var geocoder;
var map;

var maddress = [];
function addAddress(input) {
  maddress.push(input + ", new york");
}

function codeAddress() {
  var i = 0;
  for ( item in maddress) {
        geocoder.geocode( {'address': maddress[i]}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var marker = new google.maps.Marker({
                map: map, 
				title: maddress[i],
                position: results[0].geometry.location
            });
			
            google.maps.event.addListener(marker, 'click', function() {});
			
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
      });
            i++;
        }
}

function initialize()
{
  //Set the geocoder variable equal to an instance of the google maps geocoder object as new google.maps.Geocoder()
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
  
  var mapProp = {
    center:latlng,
    zoom:10,
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

if (isset($_GET['mid']))
{
  $mid = $_GET['mid'];
  print("mid = ".$mid."<br>");
  
  $user="localhost";
  $password="";
  $database="test";

  $link = mysql_connect('localhost',$user,$password);
  
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  }

  @mysql_select_db($database) or die( "Unable to select database");
  
  $query="SELECT bid, bname, baddress, count(*) as num_copies
          FROM copy JOIN branch USING (bid)
          WHERE mid = ". $mid." AND copyid NOT IN
          (SELECT DISTINCT copyid FROM rented WHERE returndate IS NULL)
          GROUP BY bid, bname, baddress;";
		  
  print("query = ".$query."<br>");

  $results = mysql_query($query);
  $rows = mysql_num_rows($results);
  if($rows > 0) {
  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	
	print("<br>| ");
	print(str_replace('-','&nbsp;',str_pad("bid", 10, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("bname", 40, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("baddress", 40, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("num_copies", 10, "-", STR_PAD_BOTH))." | ");
	
  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	
    while($obj = mysql_fetch_object($results)){
	
	  $taddress = str_replace(' ','+',($obj->baddress.", new york"));
	  echo '<script> addAddress("'.$taddress.'"); </script>';
	  
	  print('<br>| '.str_replace($obj->bid, 
	         '<a href="http://localhost/dbForm3.php?bid='.$obj->bid.
			 '&baddress='.$taddress.'">'.$obj->bid.'</a>',
	         str_replace('-','&nbsp;',str_pad($obj->bid, 10, '-', STR_PAD_BOTH))).' | ');
	  print(str_replace('-','&nbsp;',str_pad($obj->bname, 40, "-", STR_PAD_BOTH))." | ");

	  print(str_replace($obj->baddress, 
	         '<a href="https://maps.google.com/maps?q='.$taddress.'">'.$obj->baddress.'</a>',
	         str_replace('-','&nbsp;',str_pad($obj->baddress, 40, '-', STR_PAD_BOTH))).' | ');

	  //print(str_replace('-','&nbsp;',str_pad($obj->baddress, 40, "-", STR_PAD_BOTH))." | ");
	  print(str_replace('-','&nbsp;',str_pad($obj->num_copies, 10, "-", STR_PAD_BOTH))." | ");
    }

  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
  }
  else {
	print("<br> Empty Set!");
  }
  mysql_close($link);
}

?>

<br><br> Map of branches: <br> </font>
<div id="googleMap" style="width:500px;height:380px;"></div>

</body>
</html>
