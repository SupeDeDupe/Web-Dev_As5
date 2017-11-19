<?php
// Start the session
session_start();

$query = "SELECT * FROM seat";

// Connect to MySQL
if ( !( $database = mysqli_connect( "localhost", "root", "" ) ) )
    die( "<p>Could not connect to database</p></body></html>" );

if ( !mysqli_select_db( $database, "flightseats" ) )
    die( "<p>Could not open flightseats database</p></body></html>" );

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

mysqli_close( $database );

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

      <?php
         $numAvailableSeats = 0;
         for ( $i = 0; $i < count( $seats_array ); ++$i ) 
         {
            if($seats_array[$i] == 0)
               $numAvailableSeats++;
         }

         if($numAvailableSeats == 0)
            $_SESSION["fullyBooked"] = true;

      // if the seats are all booked, tell the user
         if ($_SESSION["fullyBooked"])
         {
            print( "The information for next flight will be available soon." );
            die(); 
         }

         print( "<h3>Book a flight!</h3>" );

         foreach ( $_SESSION["prices"] as $element => $value )
         {

            print("<table border = '1'><caption><strong>Section ");

            if($value === "Section A")
            {
               $startSeatIndex = 0;
               print("A");
               $price = $_SESSION["sectionA_price"];
            }
            else if($value === "Section B")
            {
               $startSeatIndex = 5;
               print("B");
               $price = $_SESSION["sectionB_price"];
            }
            else if($value === "Section C")
            {
               $startSeatIndex = 10;
               print("C");
               $price = $_SESSION["sectionC_price"];
            }

            print("</strong></caption>");

            print("<thead>
                        <tr> <!-- <tr> inserts a table row -->
                           <th>Seat</th> <!-- insert a heading cell -->
                           <th>Price</th>
                           <th>Availability</th>
                        </tr>
                     </thead>

                     <tbody>");

            for ( $i = 0; $i < 5; ++$i ) 
            {
               $seatIndex = $startSeatIndex + $i;
               $seatNumber = $seatIndex + 1;
               print( "<tr>
                        <td>$seatNumber</td> 
                        <td>$price</td>");


               print("<td>");

               if ($seats_array[$seatIndex] == 0)
                  print( "Available" );
               else
                  print( "Unavailable" );

               print("</td></tr>");
            }

            print("</tbody>
               </table><p></p>");
         }
      ?>

      <p>Enter your information and select a section to buy a seat from.</p>
   
      <form method = "post" action = "ticket.php" >
         <h2>Flyer Information</h2>

         <div><label>First name:</label> 
            <input type = "text" name = "fname" required></div>
         <div><label>Last name:</label>
            <input type = "text" name = "lname" required></div>
         </div>

         <h2>Section Selection</h2>
         <p>Which section would you like to book a seat from?</p>

         <?php
            print("<select name = 'section' required>");
            
            if($seats_array[4] == 0)
               print("<option>A</option>");
            if($seats_array[9] == 0)
               print("<option>B</option>");
            if($seats_array[14] == 0)
               print("<option>C</option>");
            print("</select>");
         ?>

         <!-- create a submit button -->
         <p><input type = "submit" name = "submit" value = "Buy Ticket"></p>
      </form>
   </body>
</html>