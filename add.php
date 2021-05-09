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
        if(!isset($_POST['first_name']) || strlen($_POST['first_name']) == 0 || !isset($_POST['last_name']) || strlen($_POST['last_name']) == 0 || !isset($_POST['email']) || strlen($_POST['email']) == 0|| !isset($_POST['headline']) || strlen($_POST['headline']) == 0 || !isset($_POST['summary']) || strlen($_POST['summary']) == 0){
            $_SESSION['error'] = 'All fields are required';
            unset($_POST['submit']);
            header('Location: add.php');
            return;
        }

        // validate position(s)
        for($i = 1; $i <= 9; $i++){
            if(!isset($_POST['year'.$i])) continue;
            if(!isset($_POST['desc'.$i])) continue;
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
            if(strlen($year) == 0 || strlen($desc) == 0){
                echo "Year and/or description are empty.";
                $_SESSION['error'] = 'All fields are required';
                header('Location: add.php');
                return;
            }

            if(!is_numeric($year)){
                echo "year (". $year.") is not numeric";
                $_SESSION['error'] = 'Position year must be numeric';
                header('Location: add.php');
                return;
            }
        }

        if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
            if(strpos($_POST['email'], '@') === FALSE){
                // $failure = "Email must have an at-sign (@)";
                $_SESSION['error'] = "Email must have an at-sign (@)";
                header("Location: add.php");
                return;
            }
    
            $stmt = $pdo->prepare('INSERT INTO PROFILE (user_id, first_name, last_name, email, headline, summary) VALUES (:ui, :fn, :ln, :em, :hl, :sm)');
            $stmt->execute(array(
                ':ui' => htmlentities($_SESSION['user_id']),
                ':fn' => htmlentities($_POST['first_name']),
                ':ln' => htmlentities($_POST['last_name']),
                ':em' => htmlentities($_POST['email']),
                ':hl' => htmlentities($_POST['headline']),
                ':sm' => htmlentities($_POST['summary'])
            ));

            $profile_id = $pdo->lastInsertId();

            for($i = 1; $i <= 9; $i++){
                if(!isset($_POST['year'.$i])) continue;
                if(!isset($_POST['desc'.$i])) continue;
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];
                if(strlen($year) != 0 || strlen($desc) != 0){
                    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
                    $stmt->execute(array(
                        ':pid' => $profile_id,
                        ':rank' => $rank,
                        ':year' => $year,
                        ':desc' => $desc)
                    );
                }
                $rank++;
            }
            

            
    

            $_SESSION['success'] = "Profile added";
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
    <!-- <link rel="stylesheet" href="starter-temlpate.css"> -->
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <?php require_once "style/bootstrap.php"; ?>

</head>
<body>

    
    <?php
    
        // if the user is logged in, show database table and errors
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
            <input type="text" name="first_name">
        </p>
        <p>Last Name: 
            <br>
            <input type="text" name="last_name">
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
        <p>Position: <input type="submit" id="addPos" value="+">
            <div id="position_fields">
            </div>
        </p>
        <p>
            <input type="submit" value="Add" name="submit">
            <input type="submit" value="Cancel" name="cancel">
        </p>
    </form>

        <script type="text/javascript">
            countPos = 0;
            console.log('Hello');
            $(document).ready(function(){
                window.console && console.log('Document ready called');
                $('#addPos').click( function(event) {
                    event.preventDefault();
                    if(countPos >= 9){
                        alert('Maximum of nine position entries exceeded');
                        return;
                    }

                    countPos++;
                    window.console && console.log(`Adding position ${countPos}`);
                    $('#position_fields').append(
                        '<div id="position'+countPos+'"> \
                        <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                        <input type="button" value="-" \
                        onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                        <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea> \
                        \</div>'
                    );

                });
            });
            </script>
    
</body>
</html>