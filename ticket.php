<?php
session_start();

$query = "SELECT * FROM seat";

// Connect to MySQL
if ( !( $database = mysqli_connect( "db.peicloud.ca", "tauU", "tautau" ) ) )
    die( "<p>Could not connect to database</p></body></html>" );

if ( !mysqli_select_db( $database, "tauDB" ) )
    die( "<p>Could not open tauDB database</p></body></html>" );

// query flightseats database
if ( !( $result = mysqli_query( $database, $query ) ) ) 
{
    print( "<p>Could not execute query!</p>" );
    die( mysql_error() . "</body></html>" );
} // end if

$seats_array = array();

// fetch each record in result set
for ( $counter = 0; $row = mysqli_fetch_row( $result ); ++$counter )
{
    $seats_array[$row[0]-1] = $row[1]; 
} // end for

?>

<!DOCTYPE html>

<html>
   <head>
      <meta charset = "utf-8">
      <title>Tacid Flights -- Ticket</title>
      <style type = "text/css">
         p       { margin: 0px; }
         .error  { color: red }
         p.head  { font-weight: bold; margin-top: 10px; }

         .background_image   { position: absolute;
                 top: 0px;
                 left: 0px;
                 z-index: 1; }
      	 .ticketHeader    { position: absolute;
                 top: 25px;
                 left: 150px;
                 z-index: 3;
                 font-size: 15pt;
                 font-family: tahoma, geneva, sans-serif; }

      	 .name    { position: absolute;
                 top: 70px;
                 left: 150px;
                 z-index: 3;
                 font-size: 12pt;
                 font-family: tahoma, geneva, sans-serif; }
      	 .sectionText    { position: absolute;
                 top: 110px;
                 left: 150px;
                 z-index: 3;
                 font-size: 12pt;
                 font-family: tahoma, geneva, sans-serif; }  
      	 .seatNumText    { position: absolute;
                 top: 150px;
                 left: 150px;
                 z-index: 3;
                 font-size: 12pt;
                 font-family: tahoma, geneva, sans-serif; }  
      	 .priceText    { position: absolute;
                 top: 190px;
                 left: 150px;
                 z-index: 3;
                 font-size: 12pt;
                 font-family: tahoma, geneva, sans-serif; }  
          .button1 { position: absolute;
                 top: 350px;
                 left: 10px;
                 z-index: 3; }
          .button2 { position: absolute;
                 top: 50px;
                 left: 10px;
                 z-index: 3; }
      </style>
   </head>
   <body>

   		<?php
          $seat = -1;
   			if ($_POST["section"] === "A")
   			{
   				$price = $_SESSION["sectionA_price"];
          $upper = 5;
          $lower = 0;

   			}
   			else if ($_POST["section"] === "B")
   			{
   				$price = $_SESSION["sectionB_price"];
          $upper = 10;
          $lower = 5;
   			}
   			else if ($_POST["section"] === "C")
   			{
   				$price = $_SESSION["sectionC_price"];
          $upper = 15;
          $lower = 10;
   			}

        $done = false;
        for ( $i = $lower; !$done && $i < $upper; ++$i ) 
          {
              if($seats_array[$i] == 0)
              {
                $seat = $i + 1;

                $update = "UPDATE seat SET SeatAvailable=1 WHERE SeatNumber=$seat";
                if (!( $result = mysqli_query($database, $update) ) ) {
                    die("Update failed");
                  }

                $done = true;
                mysqli_close( $database );
              }
          }

        if ($seat < 0 || $seat > 15)
        {
          print("<p> Flight ticket not available.</p?>");
          print("<p class = 'button2'><a href = 'buy_ticket.php'>
                 <button type='button'> Book a flight!</button>
                  </a>
                </p>");
          die(); 
        }
      ?>

   		<p><img src = "ticket_blank.png" class = "background_image"
         alt = "A plane ticket" /></p>

      	<p class = "ticketHeader">Boarding Pass</p>
      

      	<p id = "name_id" class = "name">
      		<?php 
      			print(  "Name: " ); 
      			print(  $_POST["fname"] ); 
      			print(  " " ); 
      			print(  $_POST["lname"] ); 
      		?>
      	</p>
      	<p id = "section_id" class = "sectionText">
      		<?php 
      			print(  "Section: " ); 
      			print(  $_POST["section"] ); 
      		?>
      	</p>
      	<p id = "seat_id" class = "seatNumText">
      		<?php 
      			print(  "Seat: " ); 
      			print(  "$seat" ); 
      		?>
      	</p>
      	<p id = "price_id" class = "priceText">
      		<?php 
      			print(  "Price: $" ); 
      			print(  "$price" ); 
      		?>
      	</p>

        <p class = "button1"><a href = "buy_ticket.php">
         <button type="button"> Book a flight!</button>
          </a>
        </p>
   </body>
</html>