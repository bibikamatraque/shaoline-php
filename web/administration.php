<?php

//Requirments (config and Cms)
require_once __DIR__ . "/../shaoline/init.php";

//Test if user got permission to display admin desktop
if (ShaContext::getUser() == null) {
	die(ShaContext::t("sessionExpired"));
}

//Open log form if user not logged
if (ShaContext::getUser()->isAuthentified() && !ShaContext::getUser()->gotPermission("ACCESS_DESKTOP")) {
	die(ShaContext::t("notAllowedToAccessThisPage"));
}

ShaGarbageCollector::deleteSessionItem();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>

    <meta name="robots" content="noindex, nofollow">
	<link rel="icon" type="image/png" href="favicon.png" />
    <title><?php echo ShaContext::t("Admin page"); ?></title>

    <?php

//Write basic head requirment
echo ShaContext::writeBasicHeadInfo();
echo ShaContext::insertAdminCss();
echo ShaContext::insertJs();

?>

</head>


<body style="background-color: <?php echo ShaParameter::get('DESKTOP_COLOR'); ?>; -moz-box-sizing: none;">

	<?php

	//Adding Cms needed HTML DIV
    echo ShaContext::writeAdminHTMLDiv();

    ?>

	<!-- Main admin content -->
	<div id='cms_admin_body'>

		<?php

			if (!ShaContext::getUser()->isAuthentified()) {
                echo ShaUser::drawPopinFormulaireLogin();
			} else {
				//Draw desktop icons
				echo ShaContext::getUser()->drawDesktopShortcuts();
        ?>

		<!-- Task bar -->
		<div id="cms_admin_taskbar">

			<!-- Start button -->
			<div id="cms_admin_start_button" onclick="cms_changeStartMenuState()">
				<div class="cms_admin_start_button_left"></div>
				<div class="cms_admin_start_button_right">
					<a><?php echo ShaContext::t("startMenuLabel"); ?></a>
				</div>
			</div>

		</div>

		<!-- Start menu content -->
		<div id="cms_admin_taskbar_support" style="display: none">
			<div id='cms_admin_taskbar_panel'>
				<?php
				echo ShaContext::getUser()->drawMenuShortcuts();
				?>
			</div>
		</div>

		<?php
            }
            ?>
	</div>

	<script type='text/javascript'>
        Shaoline.getConstantReporting();
        <?php echo ShaContext::declareRsaPublicKey(); ?>
    </script>

</body>
</html>
