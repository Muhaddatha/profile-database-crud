<?php 
    session_start();
    require_once "../../pdo.php";

    if ( isset($_POST['cancel'] ) ) {
        // Redirect the browser to game.php
        header("Location: index.php");
        return;
    }

    if(isset($_POST['login'])){
        //check password and username
        $salt = 'XyZzy12*_';
        $username = $_POST['email'];
        $password = hash('md5', $salt.$_POST['pass']);

        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email =:em AND password =:ps');
        $result = $stmt->execute(array(
            ':em' => htmlentities($username),
            ':ps' => htmlentities($password)
        )); 

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //if a row came back, login was successful
        if($row !== false){
            //successful login
            $_SESSION['logged-in'] = "true";
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $_POST['email'];
            // wait(1);
            header("Location: index.php");
            return;
        }
        else{ //if no row, login failed
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }

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
    <script> src="scripts/script.js"</script> 
</head>
<body>
    <div class="container">

        <h1>Please Log In</h1>
        
        <?php 
            
            if ( isset($_SESSION['error']) ) {
                echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                unset($_SESSION['error']);
            }
            

            
        ?>

        <form method="POST">
            <label for="nam">Email</label>
            <input type="text" name="email" id="nam"><br/>
            <label for="id_1723">Password</label>
            <input type="text" name="pass" id="id_1723"><br/>
            <input type="submit" value="Log In" name="login" onClick="validateForm()">
            <input type="submit" name="cancel" value="Cancel">
            
        </form>

    </div>
    
</body>
</html>