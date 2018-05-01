<?php

//Init fwk and cms
require_once __DIR__ . "/../shaoline/init.php";

//Get instruction
$command = ShaUtilsArray::getPOSTGET('command');

/**
 * Check if session ok
 */
if (ShaContext::getUser() == null) {
    return ShaContext::t("session lost");
}

/**
 * Refresh GC managed object in database
 */
if ($command == "checkGc") {
    $parameters = ShaUtilsArray::analysePostParameters(
        array(
            'managedItemList' => array('type' => ShaUtilsString::PATTERN_ALL)
        )
    );
    ShaGarbageCollector::refreshList($parameters['managedItemList']);
    return;
}


/**
 * Generic cms doAction function
 */
if ($command == "doAction") {
    $parameters = ShaUtilsArray::analysePostParameters(
        array(
            'config' => array('type' => ShaUtilsString::PATTERN_POSITIVEINTEGER)
        )
    );

    try {
    	$operation = ShaOperation::load($parameters['config']);
    	return $operation->run();
    } catch (Exception $e){
    	$response = ShaResponse::inlinePopinResponse($e->getMessage(), ShaContext::t("error"), "red");
    	echo $response->render();
    	return;
    }
}

/**
 * Submit classical formulaire
 */
if ($command == "submitForm") {

    $parameters = ShaUtilsArray::analysePostParameters(
        array(
            'config' => array('type' => ShaUtilsString::PATTERN_ALL),
            'values' => array('type' => ShaUtilsString::PATTERN_ALL),
        )
    );
    
    echo ShaForm::submitForm($parameters['config'], $parameters['values']);
    return;
}


/**
 * Admin command
 */
if (ShaContext::getUser()->isAuthentified() && ShaContext::getUser()->getValue("user_admin") == 1) {

	/**
	 * Get reporting 
	 */
	if ($command == "getConstantReporting") {
		echo ShaController::getProgress();
		return;
	}

}




?>