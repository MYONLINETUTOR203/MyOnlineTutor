<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <h5><?php echo $course['course_title'] ?></h5>
    </div>
    <div class="facebox-panel__body padding-0">
        <div class="preview-video ratio ratio--16by9">
            <iframe src="<?php echo MyUtility::makeUrl('Image', 'showVideo', [Afile::TYPE_COURSE_PREVIEW_VIDEO, $courseId, $siteLangId], CONF_WEBROOT_FRONT_URL) . '?t=' . time(); ?>" allowfullscreen="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>