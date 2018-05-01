<?php

header("Content-Type: text/xml");


//Init fwk and cms
require_once __DIR__ . "/../shaoline/init.php";

$languageId = ShaUtilsArray::getPOSTGET("l");
if (!ShaUtilsString::isRegexPositiveInteger($languageId)) {
	die(ShaContext::t("badParameterFormat")." : l");
}


echo ShaPage::createSitemapXML($languageId);

?>
