<?php


    namespace COASniffle;

    $LocalDirectory = __DIR__ . DIRECTORY_SEPARATOR;

    include_once($LocalDirectory . 'AutoConfig.php');

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    class COASniffle
    {

    }