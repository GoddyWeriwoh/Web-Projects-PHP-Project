<?php
if (empty($_POST)) {
    header("Location: register.html");
    exit;
}

if (empty($_POST['email']) || empty($_POST['password'])) {
    header("Location: register.html");
    exit;
}

$email = $_POST['email'];

$password = $_POST['password'];

$errors = isValid($email, $password);

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo nl2br($error . "\n");
    }
    exit(1);
}

else{
    $host = 'http://localhost:8081';
    $dbname = 'test';
    $user = 'root';
    $pass = 'admin';

    try{
        $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling

        $table = "user";
        # STH means "Statement Handle"
        $sql = "CREATE TABLE $table (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `email` varchar(255) NOT NULL DEFAULT '',
              `password` varchar(255) NOT NULL DEFAULT '',
              `created_at` datetime NOT NULL,
              `updated_at` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $DBH->exec($sql);

        $DBH = null;
    }
   catch (PDOException $e){
        echo $e -> getMessage();
   }
}

//header("Location: register.html");
exit;

function isValid(? string $email, ? string $password): array
{
    $errors = [];

    if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = sprintf('The email %s is not valid', $email);
    }

    if (strlen($password) < 6) {
        $errors[] = sprintf('The password %s is not valid', $password);
    }

    return $errors;
}
