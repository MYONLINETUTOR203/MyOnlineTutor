<?php if (isset($includeEditor) && $includeEditor) { ?>
    <script   src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/innovaeditor.js"></script>
    <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/common/webfont.js" ></script>	
<?php } ?>
<script>
    var ALERT_CLOSE_TIME = <?php echo FatApp::getConfig("CONF_AUTO_CLOSE_ALERT_TIME"); ?>;
</script>
</head>
<?php
$autoRestartOn = FatApp::getConfig('conf_auto_restore_on', FatUtility::VAR_INT, 1);
$isPreviewOn = ($autoRestartOn == AppConstant::YES) ? 'is-preview-on' : '';
?>
<body class="<?php echo $bodyClass . ' ' . $isPreviewOn; ?>">
    <?php
    if (FatApp::getConfig('conf_auto_restore_on', FatUtility::VAR_INT, 1) && MyUtility::isDemoUrl()) {
        include(CONF_INSTALLATION_PATH . 'restore/view/header-bar.php');
    }
    ?>
    <div class="page-container"></div>
