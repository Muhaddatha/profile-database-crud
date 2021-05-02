<?php 
    session_start();
    require_once "../../pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muhaddatha Abdulghani</title>
    <?php require_once "style/bootstrap.php"?>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome to the Resume Registry</h2>
        <br>

        <?php 
            
            $usersTableEmpty = true;

            echo('<table border="1">'."\n");
            $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline FROM Profile");
            while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {

                $usersTableEmpty = false;
                echo "<tr><td>";
                echo(htmlentities($row['profile_id']));
                echo("</td><td>");
                echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>');
                echo("</td><td>");
                echo(htmlentities($row['headline']));
                echo("</td>");
                if(isset($_SESSION['logged-in']) && !$usersTableEmpty){
                    echo('<td>');
                    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
                    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
                    echo("</td></tr>\n");
                }

            }

            


            //if the user is logged in, show database table and errors
            if(isset($_SESSION['logged-in'])){

                echo("<p><a href='add.php'>Add New Entry</a><p>");
                echo("<p><a href='logout.php'>Logout</a></p>");

                if ( isset($_SESSION['error']) ) {
                    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                    unset($_SESSION['error']);
                }
                if ( isset($_SESSION['success']) ) {
                    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
                    unset($_SESSION['success']);
                }

                

                if($usersTableEmpty){
                    echo("<p> No rows found </p>");
                }
            
            }
            else{
                echo("<p><a href='login.php'>Please log in</a></p>");
            }
            

        ?>
    </div>
    
</body>
</html>
