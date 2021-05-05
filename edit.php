<?php 
    session_start();
    require_once "../../pdo.php";
    $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM Profile Where profile_id=".$_GET["profile_id"]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row){
        echo("No rows came back from database.");
        $_SESSION['error'] = "Could not load profile";
    }

    if(isset($_POST['save'])){
        if($row['user_id'] != $_SESSION["user_id"]){
            $_SESSION['error'] = "Unauthorized attemp to delete record from database.";
        }
        else{

            //$stmt = $pdo->prepare('UPDATE Profile  SET (user_id, first_name, last_name, email, headline, summary) VALUES (:ui, :fn, :ln, :em, :hl, :sm) WHERE profile_id='.$_GET["profile_id"]);
            // $stmt = $pdo->prepare('UPDATE Profile SET (first_name, last_name) VALUES (:fn, :ln) WHERE profile_id='.$_GET["profile_id"]);
            //$stmt = $pdo->query('UPDATE Profile SET (first_name, last_name) VALUES ('.$_POST['first_name'].', '.$_POST['last_name'].') WHERE profile_id='.$_GET["profile_id"]);
            
            $stmt = $pdo->prepare('UPDATE Profile SET first_name=:fn, last_name=:ln, email=:em, headline=:hl, summary=:sm WHERE profile_id='.$_GET['profile_id']);


            $stmt->execute(array(
                    // ':ui' => htmlentities($_SESSION['user_id']),
                    ':fn' => htmlentities($_POST['first_name']),
                    ':ln' => htmlentities($_POST['last_name']),
                    ':em' => htmlentities($_POST['email']),
                    ':hl' => htmlentities($_POST['headline']),
                    ':sm' => htmlentities($_POST['summary'])
                ));
        }

        header('Location: index.php');
        return;
    }

    

    if(!isset($_SESSION['logged-in'])){
        echo('<a href=login.php>Please log in first</a>');
        die("Please login");
    }
    if(!isset($_SESSION['user_id'])){
        die('Parameter is missing. Inside user id missing');
        header("Location: index.php");
        return;
    }
    if(!isset($_GET['profile_id'])){
        die('Parameter is missing. Profile id is missing');
        header("Location: index.php");
        return;
    }

    if(isset($_POST['cancel'])){
        header("Location: index.php");
        return;
    }

    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muhaddatha Abdulghani</title>

    <link rel="stylesheet" href="starter-temlpate.css">
    
    <?php require_once "style/bootstrap.php"; ?>
</head>
<body>
    <div class="container">
        <h1>Editing Profile</h1>
        <form method="post">
            <?php
                // $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline, summary FROM Profile Where profile_id=".$_GET["profile_id"]);
                // $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <p>First name: 
            <input type="text" name="first_name" size="60" value="<?php echo($row['first_name']) ?>" />
            </p>
            <p>Last name: 
            <input type="text" name="last_name" size="60" value="<?php echo($row['last_name']) ?>" />
            </p>
            <p>Email: 
            <input type="text" name="email" size="30" value="<?php echo($row['email']) ?>" />
            </p>
            <p>Headline: 
            <input type="text" name="headline" size="80" value="<?php echo($row['headline']) ?>" />
            </p>
            <p>Summary: 
            <textarea type="text" name="summary" rows="8" cols="80"><?php echo($row['summary']) ?></textarea>
            </p>

            <input type="hidden" name="profile_id" value="<?php echo($row['profile_id'])?>" />
            <input type="submit" name="save" value="Save" />
            <input type="submit" name="cancel" value="Cancel" />
        </form>
    </div>
</body>
</html>