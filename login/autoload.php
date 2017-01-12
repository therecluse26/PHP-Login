<?php //Class Autoloader
spl_autoload_register(function ($className) {

    $className = strtolower($className);

    $path = __DIR__ . "/class/{$className}.php";

    if (file_exists($path)) {

        require_once($path);

    } else {

        $path = __DIR__."/login/class/{$className}.php";


        if (file_exists($path)) {

            require_once($path);

        } else {

            die("The file {$className}.php could not be found.");
        }
    }
});
