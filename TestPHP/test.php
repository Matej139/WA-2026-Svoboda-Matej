<?php
$name = "";
$message = "";
$age = 0;
$message2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["my_name"];
    if ($name == "Tom") {
        $message = "Ahoj Tome";
    } else {
        $message = "Neznám tě";
    }

    $age = $_POST["my_age"];
    if ($age < 18) {
        $message2 = "Jsi nezletilý";
    } else {
        $message2 = "Jsi zletilý";
    }

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Test formuláře</h1>
    <p>Vyplňte formulář a odešlete ho</p>
    <form method="post">
        <input type="text" name="my_name" placeholder="Zadejte své jméno">
        <button type="submit">Odeslat</button>
        <input type="number" name="my_age" placeholder="Zadejte svůj věk">
    </form>
    <p>
    <?php
        echo "Výstup:"; echo $message;
        echo "<br>";
        echo $message2;
    ?>
    </p>
    
</body>
</html>