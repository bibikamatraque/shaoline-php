<?php

//Requirements (config and Cms)
require_once __DIR__ . "/../shaoline/init.php";

//Manage visit counter
$lastVisit = strtotime(ShaParameter::get('LAST_VISIT_DATETIME'));
$day = date("d", $lastVisit);
if ($day <> date('d')){
    ShaParameter::set('QTY_DAILY_VISITS', 0);
} else {
    ShaParameter::set('QTY_DAILY_VISITS', ( (int)(ShaParameter::get('QTY_DAILY_VISITS')) + 1) );
}
ShaParameter::set('LAST_VISIT_DATETIME', date('Y-m-d H:i:s'));

//Draw page
ShaPage::draw();


