<?php 
    session_start();
    require_once "../../pdo.php";

    if(!isset($_GET['profile_id'])){
        die('Parameter is missing.');
    }
    
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
        
        <?php 
            
            //if the user is logged in, show database table and errors
            if(isset($_SESSION['logged-in'])){

                if ( isset($_SESSION['error']) ) {
                    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                    unset($_SESSION['error']);
                }
                if ( isset($_SESSION['success']) ) {
                    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
                    unset($_SESSION['success']);
                }

            
            }



            $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline, summary FROM Profile Where profile_id=".$_GET["profile_id"]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo('<h2>Profile details for:<em> '.$row['first_name'].' '.$row['last_name'].'</em></h2>');
            echo('<ul>');
            echo('<li>Profile ID: ' . $row['profile_id'] . '</li>');
            echo('<li>User ID: ' . $row['user_id'] . '</li>');
            echo('<li>First name: ' . $row['first_name'] . '</li>');
            echo('<li>Last name: ' . $row['last_name'] . '</li>');
            echo('<li>Headline: ' . $row['headline'] . '</li>');
            echo('<li>Summary: ' . $row['summary'] . '</li>');
            // echo('<table> <tr>'.'<td>'.$row['profile_id'].'</td> <td>'.$row['user_id'].'</td> <td>'.$row['first_name'].'</td> <td>'.$row['last_name'].'</td> <td>'.$row['headline'].'</td> <td>'.$row['summary'].'</tr> </table>');
            echo('</ul>');
            
        ?>

        <p><a href="index.php">Go back</a>

    </div>
    
</body>
</html>