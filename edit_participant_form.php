<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update participant scores</title>
</head>
<body>
    <form action="edit_participant.php" method="POST">
        Particpant Firstname<br>
        <input type="text" name="firstname" disabled value="<?php //echo the value of this field here for example echo $firstname?>"> <br>
        Particpant Surname <br>
        <input type="text" name="surname" disabled value="<?php //echo the value of this field here for example echo $surname?>"> <br>
        Kills<br>
        <input type="text" name="kills" value="<?php //echo the value of this field here for example echo $kills?>"> <br>
        Deaths<br>
        <input type="text" name="deaths" value="<?php //echo the value of this field here for example echo $deaths?>"> <br>
        
        <input type="hidden" name ="id" value="<?php //echo the value of this field here for example echo $id?>">

        <input type="submit" value="Update this player">
            
        
    </form>
    
</body>
</html>