<?php
$db = new mysqli('localhost', 'markazquba_gebed', 'SkE0QYGzgX', 'markazquba_gebed');

if ($db->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$tekst=$_POST['tekst'];
$password=$_POST['password'];

$sql = "UPDATE `options` SET `tekst` = '$tekst' WHERE `options`.`id` = 'tekst';";
if($password!="TvSchermQuba"){
    header("Location: https://markazquba.org/tv2/tekst.php?status=password"); /* Redirect browser */
    exit();
}
if (!$result = $db->query($sql)) {
    die('There was an error running the query [' . $db->error . ']');
}else{
    header("Location: https://markazquba.org/tv2/tekst.php?status=aangepast"); /* Redirect browser */
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>TV scherm Markaz Quba</title>

        <!-- Reset -->
        <link href="css/reset.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Style sheet -->
        <link href="css/style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    </head>
    <body>
    <body class="bg-light">

        <div class="container">
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-4" src="img/logo.png" alt="" >
                <h2>Tekstbalk vervangen</h2>
                <p class="lead">Hieronder kun je een nieuwe tekst invoeren voor de onderkant van de tv scherm.</p>
            </div>

            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-6 order-md-1">
                    <form action="tekst_1.php">
                        <div class="mb-3">
                            <label for="tekst">Tekst</label>
                            <input type="text" class="form-control" name="tekst" value="<?= $row['tekst'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password">Wachtwoord</label>
                            <input type="password" class="form-control" name="password">

                            <?php if (isset($_GET['status'])) {
                                if ($_GET['status'] == 'password'):
                                    ?>
                                    <div class="invalid-feedback">
                                        Wachtwoord klopt niet
                                    </div>
                             <?php endif; ?>
                            <?php } ?>
                        </div>
                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Aanpassen</button>
                    </form>
                </div>
                <div class="col-md-3">

                </div>
            </div>

            <footer class="my-5 pt-5 text-muted text-center text-small">
                <p class="mb-1">&copy; 2019 MarkazQuba</p>
            </footer>
        </div>
    </body>
</html>