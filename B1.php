<html>
    <body>
        <?php  
            include_once('directory.php');
        ?>
    
        <div class='queryUI'>
            <form action="S1.php" method="GET">Search for other actors/movies<br>
            Search: <input type='text' name='keyword'><input type='submit' value='Search'>
            </form><hr>

            <h3>Actor Information</h3>
            <?php
                //make connection
                $db_connection = mysql_connect("localhost", "cs143", "");
                if(!$db_connection) {
                    $errmsg = mysql_error($db_connection);
                    print "Connection failed: $errmsg<br>";
                    exit(1);
                }

                //select database, and process query
                mysql_select_db("CS143", $db_connection);            

                $first = $_GET["first"];
                $last = $_GET["last"];
                if(empty($first) && empty($last)){
                    $first = "Leonardo";
                    $last = "DiCaprio";
                }
                $actorQuery = "select * from Actor where first = \"".$first."\" and last = \"".$last."\"";
                $actorInfo = mysql_fetch_row(mysql_query($actorQuery, $db_connection));
                $aid = $actorInfo[0];
                $sex = $actorInfo[3];
                $dob = $actorInfo[4];
                $dod = $actorInfo[5];
                $actorName = $first." ".$last;
                if($dod == "")
                    $dod = "Actor is still alive.";
            ?>

            <b>Name</b>: <?php echo $actorName; ?><br>
            <b>Sex</b>: <?php echo $sex; ?><br>
            <b>Date of Birth</b>: <?php echo $dob; ?><br>
            <b>Date of Death</b>: <?php echo $dod; ?><br><br><hr><br>
            
            Movies <?php echo $first." ".$last; ?> was in: <br><br>
            <?php
                $movieQuery = "select * from MovieActor where aid = ".$aid;
                $result = mysql_query($movieQuery, $db_connection);
                if(empty($result)){
                    echo "Actor makes no appearance in any movie.<br>";
                }
                else{
                    while($movie = mysql_fetch_row($result)){
                        $mid = $movie[0];
                        $role = $movie[2];

                        $actorsMovieQ = "select title from Movie where id = ".$mid;
                        $titleRow = mysql_fetch_row(mysql_query($actorsMovieQ, $db_connection));
                        $movieTitle = $titleRow[0];
                        //$editedMovieTitle = str_replace(" ", "+", $movieTitle);

                        echo "Acted as ".$role." in 
                        <a href=\"B2.php?
                        movieID=".$mid."\">".$movieTitle."</a><br>";
                    }
                }
                mysql_close($db_connection);
            ?>
        </div>
    </body>
<html>
