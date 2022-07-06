<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('id', 'frmCourses');
$crsFld = $frm->getField('course_image');
$crsFld->setFieldTagAttribute('onchange', 'setupMedia()');
$videoFld = $frm->getField('course_preview_video');
$videoFld->setFieldTagAttribute('onchange', 'setupMedia()');
$fld = $frm->getField('btn_next');
$fld->setFieldTagAttribute('class', 'btn btn--primary');
$fld->setFieldTagAttribute('onclick', 'intendedLearnersForm()');

echo $frm->getFormTag(); ?>
<div class="page-layout">
    <div class="page-layout__small">
        <?php echo $this->includeTemplate('courses/sidebar.php', ['frm' => $frm, 'active' => 1, 'courseId' => $courseId]) ?>
    </div>
    <div class="page-layout__large">
        <div class="box-panel">
            <div class="box-panel__head">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4><?php echo Label::getLabel('LBL_MANAGE_BASIC_DETAILS'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="box-panel__body">
                <nav class="tabs tabs--line padding-left-8 padding-right-8">
                    <ul>
                        <li>
                            <a href="javascript:void(0)" onclick="generalForm();">
                                <?php echo Label::getLabel('LBL_General'); ?>
                            </a>
                        </li>
                        <li class="is-active">
                            <a href="javascript:void(0)">
                                <?php echo Label::getLabel('LBL_PHOTOS_&_VIDEOS'); ?>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="tabs-data">
                    <div class="box-panel__container">
                        <div class="media-uploader">
                            <div class="media-uploader__asset">
                                <div class="media-placeholder ratio ratio--16by9">
                                    <!-- [ UPLOADED MEDIA ========= -->
                                    <?php if ($image) { ?>
                                        <div class="media-placeholder__preview">
                                            <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_COURSE_IMAGE, $courseId, 'LARGE', $langId], CONF_WEBROOT_FRONT_URL) . '?=' . time(); ?> alt="">
                                            <a href=" javascript:void(0)" class="close" onclick="removeMedia('<?php echo Afile::TYPE_COURSE_IMAGE ?>');"></a>
                                        </div>
                                    <?php } else { ?>
                                        <svg class="media-placeholder__default">
                                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#placeholder-image"></use>
                                        </svg>
                                    <?php } ?>
                                    <!-- ] -->
                                </div>
                            </div>
                            <div class="media-uploader__content">
                                <h6 class="margin-bottom-4"><?php echo $crsFld->getCaption(); ?>
                                    <span class="spn_must_field">*</span>
                                </h6>
                                <p class="style-italic margin-bottom-8">
                                    <?php
                                    $lbl = Label::getLabel('LBL_COURSE_IMAGE_INFO');
                                    echo str_replace(
                                        ['{extensions}', '{dimensions}'],
                                        [implode(', ', $extensions), implode('x', $dimensions)],
                                        $lbl
                                    );
                                    ?>
                                </p>
                                <button type="button" class="btn btn--primary-bordered btn--fileupload cursor-pointer">
                                    <?php echo $crsFld->getHtml(); ?>
                                    <svg class="icon icon--back margin-right-3">
                                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#photo-icon"></use>
                                    </svg>
                                    <?php echo Label::getLabel('LBL_UPLOAD_FILE'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="media-uploader">
                            <div class="media-uploader__asset">
                                <div class="media-placeholder ratio ratio--16by9">
                                    <?php if ($previewVideo) { ?>
                                        <div class="media-placeholder__preview">
                                            <video width="387" height="218" controls>
                                                <source src="<?php echo MyUtility::makeUrl('Image', 'showVideo', [Afile::TYPE_COURSE_PREVIEW_VIDEO, $courseId, $langId], CONF_WEBROOT_FRONT_URL) . '?t=' . time(); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <a href=" javascript:void(0)" class="close" onclick="removeMedia('<?php echo Afile::TYPE_COURSE_PREVIEW_VIDEO ?>');"></a>
                                        </div>
                                    <?php } else { ?>
                                        <!-- [ DEFAULT ICON ========= -->
                                        <svg class="media-placeholder__default">
                                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#placeholder-video"></use>
                                        </svg>
                                        <!-- ] -->
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="media-uploader__content">
                                <h6 class="margin-bottom-4">
                                    <?php echo $videoFld->getCaption(); ?>
                                    <span class="spn_must_field">*</span>
                                </h6>
                                <p class="style-italic margin-bottom-8">
                                    <?php
                                    echo Label::getLabel('LBL_COURSE_PREVIEW_VIDEO_INFO');
                                    echo Label::getLabel('LBL_LEARN_HOW_TO_MAKE_YOURS_AWESOME!');
                                    ?>
                                </p>
                                <button type="button" class="btn btn--primary-bordered btn--fileupload cursor-pointer">
                                    <?php echo $videoFld->getHtml(); ?>
                                    <svg class="icon icon--back margin-right-3">
                                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#video-icon"></use>
                                    </svg>
                                    <?php echo Label::getLabel('LBL_UPLOAD_FILE'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $frm->getFieldHtml('course_id'); ?>
<?php echo $frm->getFieldHtml('crslang_lang_id'); ?>
</form>
<?php echo $frm->getExternalJS(); ?>