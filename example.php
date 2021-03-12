<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Class Example</title>
</head>

<body>
    <h1>CSRF Example</h1>
    <hr>

    <?php
    require_once('csrf.class.php');

    $csrf = new CSRF();
    $csrfInput = $csrf->createInput();

    if($_REQUEST){
        if($csrf->checkToken($_GET['_csrf'])){
            echo '<span style="color:green">Success</span><hr>';
        }else{
            echo '<span style="color:red">Failed</span><hr>';
        }
    }

    ?>

    <form action="" method="get">
        <?= $csrfInput ?>
        <input type="text" name="name">
        <br>
        <br>
        <input type="submit" value="send">
    </form>



</body>

</html>