<?php

if (isset($_SERVER['REMOTE_ADDR'])){
    die("This script must be executed from localhost".PHP_EOL);
}

if (count($argv) < 2 ){
	 die("Missing parameters : php console.php plugin action [ parameter1 parameter2 ... ]".PHP_EOL);
}

if (count($argv) < 3 ){
	
	if ( $argv[1] == 'help'){
		echo 'php console.php help => to list actions available .'.PHP_EOL;
        echo 'php console.php core generate_htaccess => regenerate htaccess .'.PHP_EOL;
        echo 'php console.php core generate_resources => regenerate resources .'.PHP_EOL;
		ShaContext::listAllPluginsCommandes();
        return;
	} else {
		die("Missing parameters : php console.php plugin action [ parameter1 parameter2 ... ]".PHP_EOL);
	}
	    
}

$plugin = $argv[1];
$action = $argv[2];

require_once __DIR__ . "/shaoline/init.php";

function updateWebFolderUserAndAccess(){
	if (stripos(PHP_OS, 'linux')){
		exec("chown -R www-data:www-data web/");
		exec("chmod -R 755 web/");
	}
}

$commandFound = ShaContext::checkCommandInPlugins($plugin, $action, $argv);
if ($commandFound){
	return;
}

switch ($action){
	
    case 'generate_htaccess':

		echo "Start generating htaccess.".PHP_EOL;
		ShaPage::rebuildHtaccess();
		updateWebFolderUserAndAccess();
		break;
 
    case 'generate_resources':
	
	    echo "Start generating resources.".PHP_EOL;
        ShaContext::updateWeb();
		updateWebFolderUserAndAccess();
        break;

    default  :
        die("Action unknown, please use 'php console.php help' to display actions available".PHP_EOL);

}

?>