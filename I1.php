<html>
<body>
    <?php  
        include_once('directory.php');
    ?>

    <div class='queryUI'>
        <h2>Movie Database</h2>
        <p>Add a new actor/director to our database!</p>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET">
        First Name: <input type="text" name="firstName"><br>
        Last Name: <input type="text" name="lastName"><br>
        Sex: <input type="radio" name="sex" value="Male">Male<input type="radio" name="sex" value="Female">Female<br>
        Date of Birth (yyyy-mm-dd): <input type="text" name="dob"><br>
        Date of Death (if applicable): <input type="text" name="dod"><br>
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

            //select database, and process form
            mysql_select_db("CS143", $db_connection);
            $firstName = $_GET["firstName"];
            $lastName = $_GET["lastName"];
            $sex = $_GET["sex"];
            $dob = $_GET["dob"];
            $dod = $_GET["dod"];

            //add actor to database
            if(!empty($firstName) && !empty($lastName) && !empty($dob)){
                //check duplicate
                $duplicateQuery = "select * from Actor where last = \"".$lastName."\" 
                                    and first = \"".$firstName."\" 
                                    and dob = \"".$dob."\"";

                $isDuplicateResult = mysql_query($duplicateQuery, $db_connection);
                $isDuplicate = mysql_num_rows($isDuplicateResult);
                if(!$isDuplicate){
                    //id
                    $updateID = "update MaxPersonID set id = id + 1";
                    mysql_query($updateID, $db_connection);
                    $idQuery = "select id from MaxPersonID";
                    $idrow = mysql_fetch_row(mysql_query($idQuery, $db_connection));
                    $id = $idrow[0];
                    
                    //sex
                    if(empty($sex))
                        $sex = "null";
                    else
                        $sex = "\"".$sex."\"";


                    //TODO: FIX THE DOD. If actor already exists, don't insert
                    //dod
                    $birthregex = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
                    if(empty($dod)){
                        $dod = "null";
                    }
                    else{
                        if(!preg_match($birthregex, $dod)){
                            echo "Date of death is not in the right format.";
                            exit(1);
                        }
                        $dod = "\"".$dod."\"";
                    }

                    //dob syntax check
                    if(!preg_match($birthregex, $dob)){
                        echo "Date of birth is not in the right format.";
                        exit(1);
                    }

                    
                    $insert = "insert into Actor values(".$id.", \"".$lastName."\", \"".$firstName."\", ".$sex.", \"".$dob."\", ".$dod.")";
                    $result = mysql_query($insert, $db_connection);
                    if($result)
                        echo "Successfully inserted a new actor.";
                    else    
                        echo "Insert failed!";
                } 
                else
                    echo "Successfully inserted a new actor.";
            }
            else
                echo "Actor's first name, last name, or date of birth cannot be null";

            mysql_close($db_connection);
        ?>
    </div>
</body>
<html>
