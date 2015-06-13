<html>
    <body>
        <?php  
            include_once('directory.php');
        ?>
    
        <div class='queryUI'>
            <form action="S1.php" method="GET">Search for other actors/movies<br>
            Search: <input type='text' name='keyword'><input type='submit' value='Search'>
            </form><hr>

            <h3>Movie Information</h3>
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
                /*
                $title = $_GET["movieTitle"];
                if(empty($title)){
                    $title = "Matrix, The";
                }
                $movieQuery = "select * from Movie where title = \"".$title."\"";
                */
                $mid = $_GET["movieID"];
                if(empty($mid)){
                    $mid = 2632;
                }
                $movieQuery = "select * from Movie where id = ".$mid;
                $movieInfo = mysql_fetch_row(mysql_query($movieQuery, $db_connection));
                $title = $movieInfo[1];
                $year = $movieInfo[2];
                $rating = $movieInfo[3];
                $company = $movieInfo[4];

                //director
                $directorQuery = "select did from MovieDirector where mid = ".$mid;
                $directorRow = mysql_fetch_row(mysql_query($directorQuery, $db_connection));
                $directorID = $directorRow[0];
                if(empty($directorID))
                    $director = "Unknown director";
                else{
                    $directorNameQ = "select CONCAT(first, ' ', last) from Director where id = ".$directorID;
                    $directorNameRow = mysql_fetch_row(mysql_query($directorNameQ, $db_connection));
                    $director = $directorNameRow[0];
                }
                
                //genre
                $genreQuery = "select genre from MovieGenre where mid = ".$mid;
                $genreResults = mysql_query($genreQuery, $db_connection);
                $firstIterationFlag = true;
                while($genreRow = mysql_fetch_row($genreResults)){
                    if($firstIterationFlag){
                        $genre = $genreRow[0];
                        $firstIterationFlag = false;
                    }
                    else
                        $genre = $genre.", ".$genreRow[0];    
                }
                

                //echo $year."<br>".$rating."<br>".$company."<br>";
                if($rating == "")
                    $rating = "Movie has no rating yet.";
                if($company == "")
                    $company = "Company is not known.";
                
            ?>

            <b>Title</b>: <?php echo $title; ?><br>
            <b>Director</b>: <?php echo $director; ?><br>
            <b>Company</b>: <?php echo $company; ?><br>
            <b>Year</b>: <?php echo $year; ?><br>
            <b>Genre</b>: <?php echo $genre; ?><br>
            <b>Rating</b>: <?php echo $rating; ?><br><br><hr><br>
            
            Actors in this movie:<br><br>
            <?php
                $actorQuery = "select aid, role from MovieActor where mid = ".$mid;
                $result = mysql_query($actorQuery, $db_connection);
                while($actor = mysql_fetch_row($result)){
                    $aid = $actor[0];
                    $role = $actor[1];

                    $actorsMovieQ = "select first, last from Actor where id = ".$aid;
                    $actorRow = mysql_fetch_row(mysql_query($actorsMovieQ, $db_connection));
                    $actorFirstName = $actorRow[0];
                    $actorLastName = $actorRow[1];
                    $actorName = $actorFirstName." ".$actorLastName;
                    echo "<a href=\"B1.php?
                    first=".$actorFirstName."&last=".$actorLastName."\">"
                    .$actorName."</a> acted as ".$role."<br>";
                }
                mysql_close($db_connection);
            ?>
        </div>
    </body>
<html>
