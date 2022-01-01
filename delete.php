<?php 
    session_start();
    require_once "../../pdo.php";
    include 'head.php';
    $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline, summary FROM Profile Where profile_id=".$_GET["profile_id"]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row){
        echo("No rows came back from database.");
        $_SESSION['error'] = "Could not load profile";
    }

    if(isset($_POST['delete'])){
        if($row['user_id'] != $_SESSION["user_id"]){
            $_SESSION['error'] = "Unauthorized attemp to delete record from database.";
        }
        else{
            $stmt = $pdo->query("DELETE FROM Profile Where profile_id=".$_GET["profile_id"]);
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

<body>
    <div class="container">
        <h1>Deleting Profile</h1>
        <form method="post">
            <?php
                // $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline, summary FROM Profile Where profile_id=".$_GET["profile_id"]);
                // $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <p>First name: <?php echo($row['first_name'])?></p>
            
            <p>Last name: <?php echo($row['last_name'])?></p>

            <input type="hidden" name="profile_id" value="<?php echo($row['profile_id'])?>" />
            <input type="submit" name="delete" value="Delete" />
            <input type="submit" name="cancel" value="Cancel" />
        </form>
    </div>
</body>
</html>