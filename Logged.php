<?php
    spl_autoload_register(function ($classRequirement) {
        require_once $classRequirement . ".php";
    });

    $default_security = DefaultSecuritySetting::GetObject();

    echo "<div style=\"text-align: center\">Wellcome back, " . $_SESSION["name"] . "!" . "<//div>";
    echo "<br>";
?>
