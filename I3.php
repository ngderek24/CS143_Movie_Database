<html>
    <body>
    <?php  
        include_once('directory.php');
    ?>

    <div class='queryUI'>
        <h2>Movie Database</h2>
        <p>Add a new actor to a movie!</p>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">
        Movie: <select name="movie">
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

                $mquery = "select title from Movie order by title";
                $result = mysql_query($mquery, $db_connection);

                while($mrow = mysql_fetch_row($result)){
                    $title = $mrow[0];
                    echo "<option value=\"$title\">$title</option>";
                }
            ?>
        </select><br>

        Actor: <select name="actor">
            <?php
                $aquery = "select CONCAT(first, ' ', last) from Actor order by first";
                $rs = mysql_query($aquery, $db_connection);

                while($arow = mysql_fetch_row($rs)){
                    $name = $arow[0];
                    echo "<option value=\"$name\">$name</option>";
                }
            ?>
        </select><br>

        Role: <input type="text" name="role"><br><br>
        <input type="submit" name="add" value="Add to Database">
        </form>

        <?php
            $role = $_GET["role"];
            if(!empty($role)) {
                $title = $_GET["movie"];
                $midQuery = "select id from Movie where title = \"".$title."\"";

                $actor = $_GET["actor"];
                $spaceIndex = strpos($actor, " ");
                $first = substr($actor, 0, $spaceIndex);
                $last = substr($actor, $spaceIndex + 1, strlen($actor) - $spaceIndex - 1);
                //echo $first."<br>";
                $aidQuery = "select id from Actor where first = \"".$first."\" and last = \"".$last."\"";
                //echo $aidQuery."<br>";

                $midrow = mysql_fetch_row(mysql_query($midQuery, $db_connection));
                $mid = $midrow[0];
                //echo $mid."<br>";

                $aidrow = mysql_fetch_row(mysql_query($aidQuery, $db_connection));
                $aid = $aidrow[0];
                //echo $aid;

                $insert = "insert into MovieActor values(".$mid.", ".$aid.", \"".$role."\")";
                //echo $insert;
                mysql_query($insert, $db_connection);
                echo "Successfully added a role";
            }
            else
                echo "Movie, Actor, or Role cannot be empty.";
            mysql_close($db_connection);
        ?>
    </div>
    </body>
<html>
