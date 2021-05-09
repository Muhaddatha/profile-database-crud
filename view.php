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
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
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



            $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM Profile Where profile_id=".$_GET["profile_id"]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo('<h2>Profile details for:<em> '.$row['first_name'].' '.$row['last_name'].'</em></h2>');
            // echo('<p>Profile ID: ' . $row['profile_id'].'</p>');
            // echo('<p>User ID: ' . $row['user_id'].'</p>');
            echo('<p>First name: ' . $row['first_name'].'</p>');
            echo('<p>Last name: ' . $row['last_name'].'</p>');
            echo('<p>Email: ' . $row['email'].'</p>');
            echo('<p>Headline: ' . $row['headline'].'</p>');
            echo('<p>Summary: ' . $row['summary'].'</p>');
            // echo('<table> <tr>'.'<td>'.$row['profile_id'].'</td> <td>'.$row['user_id'].'</td> <td>'.$row['first_name'].'</td> <td>'.$row['last_name'].'</td> <td>'.$row['headline'].'</td> <td>'.$row['summary'].'</tr> </table>');
            

            $stmt = $pdo->query("SELECT year, description FROM Position Where profile_id=".$_GET["profile_id"]);
            $printPosition = true;
            while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                if($printPosition){
                    echo('<p>Position</p>');
                    echo('<ul>');
                }
                echo('<li>'.$row['year'].': '.$row['description'].'</li>');
            }
            echo('</ul>');
            
        ?>

        <p><a href="index.php">Go back</a>

    </div>
    
</body>
</html>