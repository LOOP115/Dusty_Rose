<?php

if (isset($_GET["css_file"])){

    $file = $_GET["css_file"];

    if ($file[0] === "/"){
        die("Hacker detected");
    }

    $blacklist = array("/etc", "/usr", "/opt", "/dev", "/var", "/tmp", "auth", "search", "retrieve", "flag", "push", "pull", "../");

    foreach ($blacklist as $item){
        if (strpos($file, $item) !== false){
            die("Hacker detected");
        }
    }

    include($file);
    header("Content-Type: text/css");
}