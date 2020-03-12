<?php
require_once __DIR__ . "/scss.inc.php";
$scss = new scssc();
$scssIn = file_get_contents(__DIR__ . '/scss/rec-products-sass.scss');
$cssOut = $scss->compile($scssIn);
file_put_contents(__DIR__ . '/css/rec-products-style.css', $cssOut);