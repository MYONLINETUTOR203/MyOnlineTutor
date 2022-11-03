<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('id', 'frmCourses');
$crsFld = $frm->getField('course_image');
$crsFld->setFieldTagAttribute('onchange', 'setupMedia()');
$videoFld = $frm->getField('course_preview_video');
$videoFld->setFieldTagAttribute('onchange', 'setupMedia()');
$fld = $frm->getField('btn_next');
$fld->setFieldTagAttribute('class', 'btn btn--primary -no-border');
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
                                            <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_COURSE_IMAGE, $courseId, 'LARGE'], CONF_WEBROOT_FRONT_URL) . '?=' . time(); ?> alt="">
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
                                        ['{extensions}', '{dimensions}', '{filesize}'],
                                        [implode(', ', $extensions), implode('x', $dimensions), $filesize],
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
                                <?php if ($previewVideo) { ?>
                                    <?php
                                    $userAgent = $_SERVER['HTTP_USER_AGENT'];
                                    if (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') == false) { ?>
                                        <div class="browser-support align-center padding-10">
                                            <svg width="42" height="42" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 350.07 352.12">
                                                <g>
                                                    <g>
                                                        <path fill="#002933" d="M323.4,116.23H23.98c0,1.4,0,2.71,0,4.02,0,56.87-.02,113.74,.01,170.62,0,17.88,10.5,31.82,26.74,35.6,2.53,.59,5.18,.7,7.79,.95,7.37,.7,12.73,6.3,12.36,12.94-.39,6.95-6.29,12.16-13.85,11.74-23.62-1.31-40.65-12.83-51.25-33.91C1.73,310.13,.06,301.42,.05,292.44,.02,214.95-.06,137.45,.08,59.95,.14,30.49,20.17,6.54,48.87,1.04c3.77-.72,7.69-.94,11.53-.94C136.63,.05,212.85,.19,289.07,0c34.65-.09,57.63,28.13,58.06,56.54,.55,36.86,.19,73.74,.22,110.62,0,9.25,.04,18.5-.01,27.75-.05,8.08-4.86,13.45-11.92,13.44-7.01,0-11.98-5.47-12-13.47-.05-24.87-.02-49.75-.02-74.62,0-1.23,0-2.45,0-4.02Zm-.25-24.27c0-12.55,.72-24.83-.17-36.99-1.3-17.67-15.8-30.85-33.18-30.87-76.69-.1-153.38-.1-230.07,0-18.01,.02-33.46,13.94-35.28,31.87-.78,7.67-.37,15.47-.46,23.21-.05,4.21,0,8.42,0,12.78H323.15Z" />
                                                        <path fill="#c9252d" d="M231.54,348.06c-26.7,0-53.39,.09-80.09-.03-19.1-.09-33.77-11.78-37.38-29.78-2.13-10.62,.49-20.27,6.8-29.03,17.21-23.91,34.37-47.87,51.55-71.8,9.32-12.98,18.64-25.96,27.95-38.95,16.48-22.98,46.67-23.05,63.1-.09,26.29,36.76,52.33,73.7,78.86,110.28,16.93,23.34,4.11,51.9-19.26,57.95-4.04,1.05-8.37,1.37-12.57,1.39-26.32,.12-52.64,.06-78.96,.06Zm-.03-24c25.93,0,51.86,0,77.8,0,1.62,0,3.25,.05,4.86-.15,9.74-1.2,14.7-10.43,9.41-17.87-26.79-37.75-53.65-75.45-80.64-113.06-1.38-1.92-4-3.43-6.34-4.08-8.1-2.25-13.16-.21-17.98,6.57-25.89,36.4-51.8,72.79-77.66,109.21-5.84,8.23-2.86,16.47,6.82,18.94,1.9,.48,3.96,.43,5.94,.43,25.93,.03,51.86,.02,77.8,.02Z" />
                                                        <path fill="#002933" d="M194.01,48.06c8.41,.03,14.66,6.1,14.57,14.17-.09,8.26-6.31,13.98-15.18,13.95-8.41-.03-14.65-6.1-14.57-14.18,.09-8.24,6.32-13.97,15.17-13.94Z" />
                                                        <path fill="#002933" d="M280.06,76.18c-8.56-.02-14.56-5.85-14.54-14.12,.02-8.39,6.08-13.73,15.01-14.09,9.75-.39,14.61,8.01,14.57,14.19-.06,8.35-6.15,14.04-15.04,14.02Z" />
                                                        <path fill="#002933" d="M238.27,48.06c8.73,0,14.65,5.59,14.72,13.9,.08,8.47-5.91,14.23-14.78,14.23-8.69,0-14.65-5.64-14.72-13.91-.08-8.45,5.92-14.22,14.78-14.22Z" />
                                                        <path fill="#c9252d" d="M243.67,245.43c0,3.87,.08,7.74-.02,11.61-.18,7.38-6.35,13.21-13.92,13.29-7.65,.08-14.01-5.91-14.11-13.47-.1-7.61-.11-15.23,0-22.84,.11-7.43,6.24-13.16,13.9-13.22,7.63-.06,13.88,5.65,14.11,13.02,.12,3.87,.02,7.74,.02,11.61Z" />
                                                        <path fill="#c9252d" d="M229.46,308.11c-7.67-.09-14.01-6.64-13.81-14.26,.21-7.78,6.54-13.79,14.37-13.63,7.59,.15,13.7,6.43,13.64,14-.07,7.68-6.51,13.99-14.19,13.9Z" />
                                                    </g>
                                                </g>
                                            </svg>

                                            
                                            <p class="margin-bottom-3 margin-top-3 color-danger"><?php echo Label::getLabel('LBL_BROWSER_VIDEO_NOT_SUPPORTED_INFO'); ?></p>

                                            <a class="btn btn--primary btn--wide btn--small" target="_blank" href="<?php echo MyUtility::makeUrl('Image', 'download', [Afile::TYPE_COURSE_PREVIEW_VIDEO, $courseId], CONF_WEBROOT_FRONT_URL); ?>">
                                                <?php echo Label::getLabel('LBL_DOWNLOAD'); ?>
                                            </a>
                                        </div>
                                    <?php } else { ?>
                                        <div class="media-placeholder ratio ratio--16by9">
                                            <div class="media-placeholder__preview">
                                                <video width="100%" height="100%" controls>
                                                    <source src="<?php echo MyUtility::makeUrl('Image', 'showVideo', [Afile::TYPE_COURSE_PREVIEW_VIDEO, $courseId], CONF_WEBROOT_FRONT_URL) . '?t=' . time(); ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <a href=" javascript:void(0)" class="close" onclick="removeMedia('<?php echo Afile::TYPE_COURSE_PREVIEW_VIDEO ?>');"></a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="media-placeholder ratio ratio--16by9">
                                        <!-- [ DEFAULT ICON ========= -->
                                        <svg class="media-placeholder__default">
                                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#placeholder-video"></use>
                                        </svg>
                                        <!-- ] -->
                                    </div>
                                <?php } ?>
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
                                    ?><br>
                                    <?php
                                    $label = Label::getLabel('LBL_COURSE_PREVIEW_VIDEO_GUIDELINES');
                                    echo str_replace(
                                        ['{extensions}', '{filesize}'],
                                        [implode(', ', $videoFormats), $filesize],
                                        $label
                                    );
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
</form>
<?php echo $frm->getExternalJS(); ?>