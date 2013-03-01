<html>

<!-- 
//////////////////////////////////////////////////////
// Yanbin Luo - Database assigment
// Nov,19,2012
//
// 1-a) A user should be able to input a (single) keyword into the browser, 
// and the system should return the mid, title, and genre of all movies
// whose title or genre contains the keyword.
//
//////////////////////////////////////////////////////
-->

<head>
<title>Problem 1-a</title>
</head>
<body>
<font face="Courier">

Please input a keyword for searching: <br>

<FORM NAME ="form1" METHOD ="GET" ACTION = "dbForm1.php">

<INPUT TYPE = "TEXT" Name ="keyword">
<INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Search">

</FORM>

<?php

if (isset($_GET['Submit1']))
{

  $keyword = $_GET['keyword'];
  print("keyword = ".$keyword."<br>");
  
  $user="localhost";
  $password="";
  $database="test";

  $link = mysql_connect('localhost',$user,$password);
  
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  }

  @mysql_select_db($database) or die( "Unable to select database");
  
  $query="SELECT mid, title, genre FROM movie WHERE title LIKE \"%".$keyword.
         "%\" OR genre LIKE \"%".$keyword."%\";";
  print("query = ".$query."<br>");

  $results = mysql_query($query);
  $rows = mysql_num_rows($results);
  if($rows > 0) {

  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 20, "-", STR_PAD_BOTH)." + ");
  
	print("<br>| ");
	print(str_replace('-','&nbsp;',str_pad("mid", 10, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("title", 40, "-", STR_PAD_BOTH))." | ");
	print(str_replace('-','&nbsp;',str_pad("genre", 20, "-", STR_PAD_BOTH))." | ");
	
  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 20, "-", STR_PAD_BOTH)." + ");
	
    while($obj = mysql_fetch_object($results)){
	  print('<br>| '.str_replace($obj->mid, 
	         '<a href="http://localhost/dbForm2.php?mid='.$obj->mid.'">'.$obj->mid.'</a>',
	         str_replace('-','&nbsp;',str_pad($obj->mid, 10, '-', STR_PAD_BOTH))).' | ');
	  print(str_replace('-','&nbsp;',str_pad($obj->title, 40, '-', STR_PAD_BOTH)).' | ');
	  print(str_replace('-','&nbsp;',str_pad($obj->genre, 20, '-', STR_PAD_BOTH)).' | ');
    }
	
  	print("<br>+ ");
	print(str_pad("-", 10, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 40, "-", STR_PAD_BOTH)." + ");
	print(str_pad("-", 20, "-", STR_PAD_BOTH)." + ");
  }
  else {
	print("<br> Empty Set!");
  }
  mysql_close($link);
}

?>

</font>
</body>
</html>
