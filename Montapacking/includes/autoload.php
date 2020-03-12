<?php
    spl_autoload_register(function ($className) {

        $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
        $className = str_replace("_", "/", $className);

        $file = $_SERVER['DOCUMENT_ROOT'] . '/Montapacking/lib/' . $className . '.php';
        //print $file."<br>";
        if (file_exists($file)) {
            include_once ($file);
        }

    });
