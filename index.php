<?php
// Start the session
session_start();

// put the prices in session variables
$_SESSION["sectionA_price"] = rand(100,200);
$_SESSION["sectionB_price"] = rand(100,300);
$_SESSION["sectionC_price"] = rand(100,300);

$prices_array = array($_SESSION["sectionA_price"] => "Section A", 
                     $_SESSION["sectionB_price"] => "Section B", 
                     $_SESSION["sectionC_price"] => "Section C");

$_SESSION["prices"] = $prices_array;
ksort($_SESSION["prices"]);

$_SESSION["fullyBooked"] = false;

?>

<!DOCTYPE html>

<html>
   <head>
      <meta charset = "utf-8">
      <title>Tacid Flights</title>
      <style type = "text/css">
         label  { width: 5em; float: left; }
      </style>
   </head>
   <body>
      <h1>Tacid Flights</h1>

      <a href = "buy_ticket.php">
         <button type="button"> Book a flight!</button>
      </a>

      <p>This site was tested using Google Chrome</p>
   </body>
</html>