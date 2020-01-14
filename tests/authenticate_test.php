<?php

    use COASniffle\Abstracts\ApplicationType;
    use COASniffle\COASniffle;
    use COASniffle\Exceptions\ApplicationAlreadyDefinedException;

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src';
    require($SourceDirectory . DIRECTORY_SEPARATOR . 'COASniffle' . DIRECTORY_SEPARATOR . 'COASniffle.php');

    $ApplicationConfiguration = array(
        "PublicID" => "APP4e89d34d6756306f5b90684922458a6a3db0ee38a06147e08f4692ddda4c9094920bcd5d",
        "SecretKey" => "0f2135ff26f0ee4c19ce1fd0ecd6ad70cf50ab6160f089186f0d9cf9a7348ef84c09536f",
        "Type" => ApplicationType::Redirect
    );

    $COASniffle = new COASniffle();

    try
    {
        $COASniffle->defineApplication(
            $ApplicationConfiguration["PublicID"],
            $ApplicationConfiguration["SecretKey"],
            $ApplicationConfiguration["Type"]
        );
    }
    catch (ApplicationAlreadyDefinedException $e)
    {
        print("ERROR: The application was already defined before");
        exit(0);
    }

    $COASniffle->getCOA()->requestAuthentication();
