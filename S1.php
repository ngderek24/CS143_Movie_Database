<html>
<body>
	<?php  
        include_once('directory.php');
    ?>
    
    <div class='queryUI'>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">Search for actors/movies<br>
		Search: <input type='text' name='keyword'><input type='submit' value='Search'>
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

			$keywordList = $_GET["keyword"];
			if(!empty($keywordList)){
				echo "Search results for \"".$keywordList."\":";
				//parse keyword into a set of words, and form query strings
				$firstKeywordFlag = true;
				while($keywordList != ""){
					$spacePos = strpos($keywordList, " ");
					if($spacePos){
						$keyword = substr($keywordList, 0, $spacePos);
						$keywordList = substr($keywordList, $spacePos + 1, strlen($keywordList) - $spacePos - 1);					
					}
					else{
						$keyword = $keywordList;
						$keywordList = "";
					}

					//build query strings by appending
					if($firstKeywordFlag){
						$actorQuery = "select first, last, dob from Actor 
						where (first like '%".$keyword."%' or last like '%".$keyword."%')";
						$movieQuery = "select title, year from Movie where (title like '%".$keyword."%')";
						$firstKeywordFlag = false;
					}
					else{
						$actorQuery = $actorQuery." and (first like '%".$keyword."%' or last like '%".$keyword."%')";
						$movieQuery = $movieQuery." and (title like '%".$keyword."%')";
					}
				}

				echo "<hr>";

				
				//run actorQuery
				$actorResult = mysql_query($actorQuery, $db_connection);
				while($searchActorRow = mysql_fetch_row($actorResult)){
					$searchActorFirstName = $searchActorRow[0];
					$searchActorLastName = $searchActorRow[1];
					$searchActorDOB = $searchActorRow[2];
					$searchActorName = $searchActorFirstName." ".$searchActorLastName;

					echo "Actor: <a href=\"B1.php?
					first=".$searchActorFirstName."&last=".$searchActorLastName."\">".
					$searchActorName." (".$searchActorDOB.")</a><br>";
				}
				echo "<hr>";

				//run movieQuery
				$movieResult = mysql_query($movieQuery, $db_connection);
				while($searchMovieRow = mysql_fetch_row($movieResult)){
					$searchMovieTitle = $searchMovieRow[0];
					$searchMovieYear = $searchMovieRow[1];
					$findMovieMid = "select id from Movie where title = \"".$searchMovieTitle."\"";
                    $midRow = mysql_fetch_row(mysql_query($findMovieMid, $db_connection));
                    $movieID = $midRow[0];

                    echo "Movie: <a href=\"B2.php?
                    movieID=".$movieID."\">".$searchMovieTitle." (".$searchMovieYear.")</a><br>";
				}
			}		
		mysql_close($db_connection);
		?>
	</div>
</body>
</html>
