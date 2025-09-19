<?php

//ensure users are logged in to access this page

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search results</title>
    
</head>
<body>
<a href=".">Back to index</a>
    <?php
        
            
            include 'dbconnect.php';
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //checking which form has been posted. If $_POST['participant'] exists and is equal to 1 then the user must be searching for individual participants. Otherwise they're searching for a team 
            if ($_POST['participant'] == "1") {

                //TODO search for a particpant here and display the results
            }
            else{

                //TODO search for a team here and display the team members as well as their individual and combined stats. 
            }
            
               
            }
        catch(PDOException $e)
            {
                echo $e->getMessage(); //If we are not successful in connecting or running the query we will see an error
            }
        ?>


</body>
</html>