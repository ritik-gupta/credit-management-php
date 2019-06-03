<?php
    require_once('db_connect.php'); //connect to database

    $name = $_GET['name'];
    $query = "select * from users where name='" . $name . "'";
    $result = mysqli_query($link,$query);
    $row = mysqli_fetch_array($result);
    
    $query = "select name from users where name<>'" . $row['name'] . "'";
    $result = mysqli_query($link,$query);

    if(isset($_POST['transfer'])) {
        if($_POST['credits_tr'] > $row['credit']) {
            echo "Credits transferred cannot be more than " . $row['credit'] . "<br>";
        }

        else {
            $query = "update users set credit=credit-" . $_POST['credits_tr'] . " where name='" . $row['name'] . "'";
            mysqli_query($link,$query);

            $query = "update users set credit=credit+" . $_POST['credits_tr'] . " where name='" . $_POST['to_user'] . "'";
            mysqli_query($link,$query);

            $query = "insert into transfers values('" . $row['name'] . "','" . $_POST['to_user'] . "'," . $_POST['credits_tr'] . ")";
            mysqli_query($link,$query);

            header("Location: index.php");
        }
    }
?>

<html>
	<head>
        <title>Transfer Credits</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:900'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
        <link rel="stylesheet" href="css/transfer.css">
    </head>

    <body>
        <a href="index.php">&lt; Back</a>
        <br><br>
        <h1 id="hello">Hello <u><?php echo $row['name'] ?></u>,</h1>
        <br>
        <h1 id="credits">Your credits are: <?php echo $row['credit'] ?></h1>
        <br><br>
        <br><br>        
    <div id="forms">
        <form action="#" method="post">
        
            <fieldset>
            <br>
                <legend><u><strong>Transfer details</strong></u></legend>
                Credits: <input type="number" name="credits_tr" min =0 value=1 required>
                <br><br>
                Transfer to: <select name="to_user" required>
                    <option selected>Select user</option>
                    <option value =""></option>

                <?php
                        while($tname = mysqli_fetch_array($result)) {
                            echo "<option value='" . $tname['name'] . "'>" . $tname['name'] . "</option>";
                        }
                ?>

                </select>
                <br>
            </fieldset>
            <br>
            <input type="submit" name="transfer" value="Transfer">
        </form>
        </div>
    </body>
</html>
