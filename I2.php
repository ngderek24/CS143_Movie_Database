<html>
    <body>
        <?php  
            include_once('directory.php');
        ?>
        
        <div class='queryUI'>
            <h2>Movie Database</h2>
            <p>Add a new movie to our database!</p>

            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">
                Title: <input type="text" name="title"><br>
                Company: <input type="text" name="company"><br>
                Year: <input type="text" name="year"><br>
                MPAA Rating: 
                <select name="rating">
                <option value="G">G</option>
                <option value="PG">PG</option>
                <option value="PG-13">PG-13</option>
                <option value="R">R</option>
                <option value="NC-17">NC-17</option>
                </select><br>

                Genre: 
                <input type="checkbox" name="genre[]" value="Action">Action
                <input type="checkbox" name="genre[]" value="Adult">Adult
                <input type="checkbox" name="genre[]" value="Adventure">Adventure
                <input type="checkbox" name="genre[]" value="Animation">Animation
                <input type="checkbox" name="genre[]" value="Comedy">Comedy
                <input type="checkbox" name="genre[]" value="Crime">Crime
                <input type="checkbox" name="genre[]" value="Documentary">Documentary
                <input type="checkbox" name="genre[]" value="Drama">Drama
                <input type="checkbox" name="genre[]" value="Family">Family
                <input type="checkbox" name="genre[]" value="Fantasy">Fantasy
                <input type="checkbox" name="genre[]" value="Horror">Horror
                <input type="checkbox" name="genre[]" value="Musical">Musical
                <input type="checkbox" name="genre[]" value="Mystery">Mystery
                <input type="checkbox" name="genre[]" value="Romance">Romance
                <input type="checkbox" name="genre[]" value="Sci-Fi">Sci-Fi
                <input type="checkbox" name="genre[]" value="Short">Short
                <input type="checkbox" name="genre[]" value="Thriller">Thriller
                <input type="checkbox" name="genre[]" value="War">War
                <input type="checkbox" name="genre[]" value="Western">Western<br><br>
                <input type="submit" name="add" value="Add to Database">
            </form>

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

                $title = $_GET["title"];
                $year = $_GET["year"];
                $company = $_GET["company"];
                $rating = $_GET["rating"];

                //print $title."<br>".$year."<br>".$company."<br>".$rating."<br>";

                if(!empty($title) && isset($_GET['genre'])){
                    //TODO: if movie already exists, don't insert
                    $updateID = "update MaxMovieID set id = id + 1";
                    mysql_query($updateID, $db_connection);
                    $idQuery = "select id from MaxMovieID";
                    $idrow = mysql_fetch_row(mysql_query($idQuery, $db_connection));
                    $id = $idrow[0];
                    
                    //year
                    if(empty($year))
                        $year = "null";
                    else{
                        $yearregex = '/^[0-9]{4}$/';
                        if(!preg_match($yearregex, $year)){
                            echo "Year is invalid.";
                            exit(1);
                        }
                    }

                    //company
                    if(empty($company))
                        $company = "null";
                    else
                        $company = "\"".$company."\"";

                    $movieInsert = "insert into Movie values(".$id.", \"".$title."\", ".$year.", \"".$rating."\", ".$company.")";
                    //echo $movieInsert;
                    $movieInsertResult = mysql_query($movieInsert, $db_connection);
                    //echo mysql_error($movieInsertResult);
                    if($movieInsertResult)
                        echo "Successfully inserted a new movie.";
                    else    
                        echo "Insert failed!";

                    
                    //genre
                    if(isset($_GET['genre'])){
                        if(is_array($_GET['genre'])){
                            foreach($_GET['genre'] as $genre){
                                $genreInsert = "insert into MovieGenre values(".$id.", \"".$genre."\")";
                                $genreInsertResult = mysql_query($genreInsert, $db_connection);                                
                            }
                        }                    
                    }                                
                }
                else
                    echo "The movie must have a title and genre.";

                mysql_close($db_connection);
            ?>
        </div>
    </body>
<html>
