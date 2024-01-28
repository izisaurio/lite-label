<?php

require '../vendor/autoload.php';

use LiteLabel\Language;

$lang = new Language('lang.php', 'en');

echo $lang['first'];

echo PHP_EOL;

echo $lang->get('es:name', ['Izisaurio']);