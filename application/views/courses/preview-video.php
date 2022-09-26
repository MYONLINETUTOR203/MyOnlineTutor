<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <h5><?php echo $course['course_title'] ?></h5>
    </div>
    <div class="facebox-panel__body padding-0">
        <?php
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') == false) { ?>
            <div class="preview-video ratio--16by9">
                <div class="align-center heading-5 -alifgn-auto mx-6 mt-20 mb-20">
                    <p><?php echo Label::getLabel('LBL_BROWSER_VIDEO_NOT_SUPPORTED_INFO'); ?></p>
                    <a target="_blank" href="<?php echo MyUtility::makeUrl('Image', 'download', [Afile::TYPE_COURSE_PREVIEW_VIDEO, $courseId], CONF_WEBROOT_FRONT_URL); ?>">
                        <svg class="icon icon--edit icon--large">
                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>/images/sprite.svg#download-icon"></use>
                        </svg>
                        <p><?php echo Label::getLabel('LBL_DOWNLOAD'); ?></p>
                    </a>
                </div>
            </div>
        <?php } else { ?>
            <div class="preview-video ratio ratio--16by9">
                <iframe src="<?php echo MyUtility::makeUrl('Image', 'showVideo', [Afile::TYPE_COURSE_PREVIEW_VIDEO, $courseId], CONF_WEBROOT_FRONT_URL) . '?t=' . time(); ?>" allowfullscreen="" width="100%" height="100%" frameborder="0"></iframe>
            </div>
        <?php } ?>
    </div>
</div>