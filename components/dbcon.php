<?php

    require __DIR__ . '/../vendor/autoload.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Contract\Auth;

    $jsonFilePath = __DIR__ . '/../components/SDK'; //SDK'in dosya yolunu buraya koyacaksınız

    if (!file_exists($jsonFilePath)) {
        die("Service account JSON file does not exist: " . $jsonFilePath);
    }

    $factory = (new Factory)
        ->withServiceAccount($jsonFilePath)
        ->withDatabaseUri('https://'); //Buraya REaltime Database URL nizi yapıştıracaksınız

    $database = $factory->createDatabase();
    $auth = $factory->createAuth();
?>
