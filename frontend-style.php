<?php
require_once __DIR__ . "/scss.inc.php";
$scss = new scssc();
$scssIn = file_get_contents(__DIR__ . '/scss/frontend-rec-style.scss');
$cssOut = $scss->compile($scssIn);
file_put_contents(__DIR__ . '/css/frontend-rec-style.css', $cssOut);