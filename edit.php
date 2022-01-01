<?php 
    session_start();
    require_once "../../pdo.php";
    include 'head.php';
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
                )
            );
            //$profile_id = $pdo->lastInsertId();
            // $profile_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
            $stmt->execute(array( ':pid' => $_GET['profile_id']));


            $rank = 1;
            for($i = 1; $i <= 9; $i++){
                if(!isset($_POST['year'.$i])) continue;
                if(!isset($_POST['desc'.$i])) continue;
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];
                if(strlen($year) != 0 || strlen($desc) != 0){
                    $rank++;
                    $stmt = $pdo->prepare('INSERT INTO Position ( profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)');
                    $stmt->execute(array(
                        ':pid' => htmlentities($_GET['profile_id']),
                        ':rank' => htmlentities($rank),
                        ':year' => htmlentities($year),
                        ':desc' => htmlentities($desc))
                    );
                }
                
            }
            
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
        <h1>Editing Profile</h1>
        <form method="post">
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
            <p>Position: <input type="submit" id="addPos" value="+">
            <div id="position_fields">
                <?php
                    $stmt = $pdo->query("SELECT year, description, position_id FROM Position Where profile_id=".$_GET["profile_id"]);
                    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $currentPos = 0;
                    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                        $currentPos++;
                        echo('<div id="position'.$currentPos.'"> 
                        <p>Year: <input type="text" name="year'.$currentPos.'" value="'.$row['year'].'" /> 
                        <input type="button" value="-" 
                        onclick="$(\'#position'.$currentPos.').remove();return false;"></p> 
                        <textarea name="desc'.$currentPos.'" rows="8" cols="80">'.$row['description'].'</textarea> 
                        </div>');
                    }
                ?>
            </div>
            </p>
            <input type="submit" name="save" value="Save">
            <input type="submit" name="cancel" value="Cancel">

            <script type="text/javascript">
                $(document).ready(function(){
                    currentPos = $('div[id^=position]').length;
                    console.log(`Current pos: ${currentPos}`);
                    window.console && console.log('Document ready called');
                    $('#addPos').click( function(event) {
                        event.preventDefault();
                        if(currentPos >= 9){
                            alert('Maximum of nine position entries exceeded');
                            return;
                        }

                        currentPos++;
                        window.console && console.log(`Adding position ${currentPos}`);
                        $('#position_fields').append(
                            '<div id="position'+currentPos+'"> \
                            <p>Year: <input type="text" name="year'+currentPos+'" value="" /> \
                            <input type="button" value="-" \
                            onclick="$(\'#position'+currentPos+'\').remove();return false;"></p> \
                            <textarea name="desc'+currentPos+'" rows="8" cols="80"></textarea> \
                            \</div>'
                        );

                    });
            });
                
            </script>

            
            
        </form>
    </div>
</body>
</html>