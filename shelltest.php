<?php

if (substr(PHP_OS, 0, 3) != 'WIN'){


    echo shell_exec('which curl');

    echo shell_exec('which php');

} else {

    echo shell_exec('where curl');

    echo shell_exec('where php');
}

/*
