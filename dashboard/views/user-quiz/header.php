<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$stickyDemoHeader = MyUtility::isDemoUrl() ? 'sticky-demo-header' : '';
?>
<!doctype html>
<html lang="en" dir="<?php echo $siteLanguage['language_direction']; ?>" class="<?php echo $stickyDemoHeader; ?>">

<head>
    <!-- Basic Page Needs ======================== -->
    <meta charset="utf-8">
    <?php echo $this->writeMetaTags(); ?>
    <!-- MOBILE SPECIFIC METAS ===================== -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0,user-scalable=0" />
    <!-- FONTS ================================================== -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>" />
    <!-- FAVICON ================================================== -->
    <link rel="shortcut icon" href="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_FAVICON, 0, Afile::SIZE_ORIGINAL], CONF_WEBROOT_FRONTEND); ?>">
    <link rel="apple-touch-icon" href="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_APPLE_TOUCH_ICON, 0, Afile::SIZE_LARGE], CONF_WEBROOT_FRONTEND); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/apple-touch-icon-114x114.png">
    <!-- CSS/JS ================================================== -->
    <?php
    $jsVariables = CommonHelper::htmlEntitiesDecode($jsVariables);
    $sslUsed = (FatApp::getConfig('CONF_USE_SSL', FatUtility::VAR_BOOLEAN, false)) ? 1 : 0;
    $websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, FatUtility::VAR_STRING, '');
    $mainDashboardClass = (($controllerName == 'Teacher' || $controllerName == 'Learner') && $actionName == "index") ? "main-dashboard" : '';
    ?>
    <script type="text/javascript">
        var langLbl = <?php echo json_encode(CommonHelper::htmlEntitiesDecode($jsVariables)) ?>;
        var layoutDirection = '<?php echo MyUtility::getLayoutDirection(); ?>';
        var SslUsed = '<?php echo $sslUsed; ?>';
        var userTimeZone = '<?php echo MyUtility::getSiteTimezone(); ?>';
        var timeZoneOffset = '<?php echo MyDate::getOffset(MyUtility::getSiteTimezone()); ?>';
        var cookieConsent = <?php echo json_encode($cookieConsent); ?>;
        var userType = <?php echo FatUtility::int($siteUserType); ?>;
        const LEARNER = <?php echo User::LEARNER; ?>;
        const TEACHER = <?php echo User::TEACHER; ?>;
        const ALERT_CLOSE_TIME = '<?php echo FatApp::getConfig("CONF_AUTO_CLOSE_ALERT_TIME", FatUtility::VAR_INT, 0); ?>';
        const confWebRootUrl = '<?php echo CONF_WEBROOT_URL; ?>';
        const confFrontEndUrl = '<?php echo CONF_WEBROOT_FRONTEND; ?>';
        <?php if (isset($setMonthAndWeekNames) && $setMonthAndWeekNames) { ?>
            var monthNames = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::getAllMonthName(false, $siteLangId))); ?>;
            var weekDayNames = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::dayNames(false, $siteLangId))); ?>;
            var meridiems = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::meridiems(false, $siteLangId))); ?>;
        <?php } ?>
    </script>
    <?php
    echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE);
    if (isset($includeEditor) && $includeEditor) {
    ?>
        <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/innovaeditor.js"></script>
        <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/common/webfont.js"></script>
    <?php }
    if (FatApp::getConfig('CONF_ENABLE_PWA', FatUtility::VAR_BOOLEAN, false)) { ?>
        <link rel="manifest" href="<?php echo MyUtility::makeUrl('Pwa', '', [], CONF_WEBROOT_FRONTEND); ?>">
        <script>
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker.register("<?php echo CONF_WEBROOT_FRONTEND; ?>sw.js");
            }
        </script>
    <?php } ?>
    <?php echo Common::setThemeColorStyle(true); ?>
</head>
<?php $isPreviewOn = MyUtility::isDemoUrl() ? 'is-preview-on' : ''; ?>

<body>
    <?php
    if (MyUtility::isDemoUrl()) {
        include(CONF_INSTALLATION_PATH . 'restore/view/header-bar.php');
    }
    if (isset($_SESSION['preview_theme'])) {
        $this->includeTemplate('header/preview.php', array(), false);
    }
    ?>
    <page class="page">
        <!-- [ HEADER ========= -->
        <?php if (!$courseQuiz) { ?>
        <header class="header nav-down">
            <div class="header-primary d-sm-flex justify-content-sm-between align-items-sm-center">
                <div class="header-primary__right order-sm-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- [ USER ACCOUNT ========= -->
                        <div class="account">
                            <a href="#accout-target" class="avtar avtar--small account__trigger trigger-js" data-title="<?php echo CommonHelper::getFirstChar($siteUser['user_first_name']); ?>">
                                <img src="<?php echo MyUtility::makeUrl('Image', 'show', array(Afile::TYPE_USER_PROFILE_IMAGE, $siteUserId, Afile::SIZE_SMALL), CONF_WEBROOT_FRONTEND) . '?' . time() ?>" alt="">
                            </a>
                            <div id="accout-target" class="account__target">
                                <nav class="menu-vertical">
                                    <ul>
                                        <?php if ($siteUserType == User::TEACHER) { ?>
                                            <li class="menu__item <?php echo ("Teacher" == $controllerName) ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Teacher', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Dashboard'); ?></a></li>
                                            <li class="menu__item <?php echo ("Students" == $controllerName) ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Students', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_My_Students'); ?></a></li>
                                            <li class="menu__item <?php echo ("Lessons" == $controllerName) ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Lessons', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Lessons'); ?></a></li>
                                        <?php
                                        }
                                        if ($siteUserType == User::LEARNER) {
                                        ?>
                                            <li class="menu__item <?php echo ("Learner" == $controllerName) ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Learner', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Dashboard'); ?></a></li>
                                            <li class="menu__item <?php echo ("Teachers" == $controllerName) ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Teachers', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_My_Teachers'); ?></a></li>
                                            <li class="menu__item <?php echo ("Lessons" == $controllerName) ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Lessons', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Lessons'); ?></a></li>
                                        <?php }
                                        ?>
                                        <li class="menu__item <?php echo ("Account" == $controllerName && "profileInfo" == $action) ? 'is-active' : ''; ?>">
                                            <a href="<?php echo MyUtility::makeUrl('Account', 'ProfileInfo', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Settings'); ?></a>
                                        </li>
                                        <li class="menu__item border-top margin-top-3">
                                            <a href="<?php echo MyUtility::makeUrl('Account', 'logout', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Logout'); ?></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- ] -->
                    </div>
                </div>
                <div class="header-primary__left order-sm-1">
                    <div class="d-sm-flex justify-content-sm-between align-items-sm-center">
                        <figure class="header-logo">
                            <a href="<?php echo MyUtility::makeUrl('', '', [], CONF_WEBROOT_FRONT_URL); ?>">
                                <?php if (MyUtility::isDemoUrl()) { ?>
                                    <img src="<?php echo CONF_WEBROOT_FRONTEND . 'images/yocoach-logo.svg'; ?>" alt="" />
                                <?php } else { ?>
                                    <img src="<?php echo FatCache::getCachedUrl(MyUtility::makeFullUrl('Image', 'show', array(Afile::TYPE_FRONT_LOGO, 0, Afile::SIZE_LARGE), CONF_WEBROOT_FRONT_URL), CONF_DEF_CACHE_TIME, '.jpg'); ?>" alt="<?php echo $websiteName; ?>">
                                <?php } ?>
                            </a>
                        </figure>
                        <h1 class="page-title">
                            <?php echo $data['quilin_title'] ?>
                        </h1>
                    </div>
                </div>

            </div>
        </header>
        <?php } ?>
        <!-- ] -->