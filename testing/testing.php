<?php
include dirname(__DIR__)."/vendor/autoload.php";

$blade = new \Adimancifi\Blade\Blade(realpath(__DIR__), "cache");
echo $blade->render('view', ['nama' => 'adiman']);