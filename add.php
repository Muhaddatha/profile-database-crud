<?php 
    session_start();
    require_once "../../pdo.php";

    if(!isset($_SESSION['logged-in'])){
        echo('<a href=login.php>Please log in first</a>');
        die("Please login");
    }
    if(!isset($_SESSION['user_id'])){
        die('Parameter is missing.');
    }

    
    if(isset($_POST['submit'])){
        if(!isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['email']) || !isset($_POST['headline']) || !isset($_POST['summary'])){
            $_SESSION['error'] = 'All fields are required';
            unset($_POST['submit']);
            header('Location: add.php');
            return;
        }

        if(isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
            if(strpos($_POST['email'], '@') === FALSE){
                // $failure = "Email must have an at-sign (@)";
                $_SESSION['error'] = "Email must have an at-sign (@)";
                header("Location: add.php");
                return;
            }
    
            $stmt = $pdo->prepare('INSERT INTO PROFILE (user_id, first_name, last_name, email, headline, summary) VALUES (:ui, :fn, :ln, :em, :hl, :sm)');
                $stmt->execute(array(
                    ':ui' => htmlentities($_SESSION['user_id']),
                    ':fn' => htmlentities($_POST['firstName']),
                    ':ln' => htmlentities($_POST['lastName']),
                    ':em' => htmlentities($_POST['email']),
                    ':hl' => htmlentities($_POST['headline']),
                    ':sm' => htmlentities($_POST['summary'])
                ));
    
            $_SESSION['success'] = "Record inserted";
            header("Location: index.php");
            return;
        }
    }
    
    if(isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muhaddatha Abdulghani</title>
    <link rel="stylesheet" href="starter-temlpate.css">
    
    <?php require_once "style/bootstrap.php"; ?>

</head>
<body>

    
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
    
    ?>



    <form method="post">
        <p>First Name: 
            <br>
            <input type="text" name="firstName">
        </p>
        <p>Last Name: 
            <br>
            <input type="text" name="lastName">
        </p>
        <p>Email: 
            <br>
            <input type="text" name="email">
        </p>
        <p>Headline: 
            <br>
            <input type="text" name="headline">
        </p>
        <p>Summary: 
        <br>
            <textarea name="summary" rows="8" cols="80" spellcheck="false"></textarea>
        </p>
        <p>
            <input type="submit" value="Add" name="submit">
            <input type="submit" value="Cancel" name="cancel">
        </p>
    </form>

    
</body>
</html>