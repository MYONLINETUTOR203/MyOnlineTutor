<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (count($courses) == 0) {
    $this->includeTemplate('_partial/no-record-found.php');
    return;
}
$requestStatuses = Course::getRefundStatuses();
?>
<div class="course-group">
    <!-- [ COURSE CARD ========= -->
    <?php foreach ($courses as $course) { ?>
        <div class="card-course">
            <div class="card-course__colum card-course__colum--first">
                <div class="ratio ratio--16by9">
                    <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_COURSE_IMAGE, $course['course_id'], 'MEDIUM', $siteLangId], CONF_WEBROOT_FRONT_URL); ?>" alt="">
                </div>
            </div>
            <div class="card-course__colum card-course__colum--second">
                <div class="card-course__head">
                    <small class="card-course__subtitle uppercase color-gray-900">
                        <?php echo $course['cate_name'] ?>
                        <?php echo !empty($course['subcate_name']) ? ' / ' . $course['subcate_name'] : ''; ?>
                    </small>
                    <span class="card-course__title">
                        <?php echo $course['course_title'] ?>
                    </span>
                </div>
                <div class="card-course__body">
                    <div class="course-stats">
                        <span class="course-stats__item">
                            <strong>
                                <?php
                                if ($siteUserType == User::LEARNER) {
                                    echo CourseUtility::formatMoney($course['course_price']);
                                } else {
                                    echo CourseUtility::formatMoney($course['course_price'], $course['course_currency_id']);
                                }
                                ?>
                            </strong>
                        </span>
                        <span class="course-stats__item">
                            <?php echo Label::getLabel('LBL_LECTURES') ?>
                            <strong><?php echo $course['course_lectures'] ?></strong>
                        </span>
                        <?php if ($course['course_type'] > 0) { ?>
                            <span class="course-stats__item">
                                <strong><?php echo $courseTypes[$course['course_type']] ?></strong>
                            </span>
                        <?php } ?>
                        <span class="course-stats__item">
                            <?php echo Label::getLabel('LBL_STUDENTS') ?>
                            <strong><?php echo $course['course_students'] ?></strong>
                        </span>
                        <div class="course-stats__item">
                            <div class="ratings">
                                <svg class="icon icon--rating">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#rating"></use>
                                </svg>
                                <span class="value">
                                    <?php echo $course['course_ratings']; ?>
                                </span>
                                <span class="count">
                                    (<?php echo $course['course_reviews']; ?>)
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php if ($siteUserType == User::TEACHER) { ?>
                        <span class="card-landscape__status badge color-primary badge--curve badge--small margin-left-0">
                            <?php echo $courseStatuses[$course['course_status']]; ?>
                        </span>
                    <?php } else { ?>
                        <span class="card-landscape__status badge color-secondary badge--curve badge--small margin-left-0">
                            <?php echo $orderStatuses[$course['crspro_status']]; ?>
                        </span>
                    <?php } ?>
                    <?php if ($siteUserType == User::TEACHER) { ?>
                        <span class="card-landscape__status badge color-red badge--curve badge--small margin-left-0">
                            <?php echo AppConstant::getActiveArr($course['course_active']); ?>
                        </span>
                    <?php } ?>
                    <?php if ($siteUserType == User::LEARNER) { ?>
                        <?php if (isset($course['corere_status'])) { ?>
                            <span class="card-landscape__status badge color-red badge--curve badge--small margin-left-0">
                                <?php echo $requestStatuses[$course['corere_status']]; ?>
                            </span>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="card-course__colum card-course__colum--third">
                <div class="actions-group">
                    <?php if ($siteUserType == User::TEACHER) { ?>
                        <a href="<?php echo MyUtility::makeUrl('CoursePreview', 'index', [$course['course_id']]); ?>" title="<?php echo Label::getLabel('LBL_PREVIEW'); ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                            <svg class="icon icon--enter icon--18">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#view-icon"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_PREVIEW'); ?>
                            </div>
                        </a>
                    <?php } elseif ($course['can_view_course']) { ?>
                        <a href="<?php echo MyUtility::makeUrl('Tutorials', 'start', [$course['ordcrs_id']]); ?>" title="<?php echo Label::getLabel('LBL_VIEW'); ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                            <svg class="icon icon--enter icon--18">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#view-icon"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_VIEW'); ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if ($course['can_edit_course']) { ?>
                        <a href="<?php echo MyUtility::makeUrl('Courses', 'form', [$course['course_id']]); ?>" title="<?php echo Label::getLabel('LBL_EDIT'); ?>" class="btn btn--equal btn--shadow btn--bordered is-hover margin-1">
                            <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#edit"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_EDIT'); ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if ($course['can_delete_course']) { ?>
                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_DELETE'); ?>" onclick="remove('<?php echo $course['course_id']; ?>')" class="btn btn--equal btn--shadow btn--bordered is-hover margin-1">
                            <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#delete-icon"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_DELETE'); ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if ($course['can_cancel_course']) { ?>
                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_CANCEL'); ?>" onclick="cancelForm('<?php echo $course['ordcrs_id']; ?>')" class="btn btn--equal btn--shadow btn--bordered is-hover margin-1">
                            <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#cancel"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_CANCEL'); ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if ($course['can_rate_course']) { ?>
                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_RATE'); ?>" onclick="feedbackForm('<?php echo $course['ordcrs_id']; ?>')" class="btn btn--equal btn--shadow btn--bordered is-hover margin-1">
                            <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#review-star"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_RATE'); ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if ($course['can_retake_course']) { ?>
                        <a href="javascript:void(0);" title="<?php echo Label::getLabel('LBL_RETAKE'); ?>" onclick="retake('<?php echo $course['crspro_id']; ?>')" class="btn btn--equal btn--shadow btn--bordered is-hover margin-1">
                            <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#retake"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_RETAKE'); ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if ($course['can_download_certificate']) { ?>
                        <a href="<?php echo MyUtility::makeUrl('Certificates', 'index', [$course['crspro_id']], CONF_WEBROOT_DASHBOARD); ?>" target="_blank" title="<?php echo Label::getLabel('LBL_DONWLOAD_CERTIFICATE'); ?>" class="btn btn--equal btn--shadow btn--bordered is-hover margin-1">
                            <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#download-icon"></use>
                            </svg>
                            <div class="tooltip tooltip--top bg-black">
                                <?php echo Label::getLabel('LBL_DONWLOAD_CERTIFICATE'); ?>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- ] ========= -->
</div>
<?php
$pagingArr = [
    'page' => $post['page'],
    'pageSize' => $post['pagesize'],
    'pageCount' => ceil($recordCount / $post['pagesize']),
    'recordCount' => $recordCount,
    'callBackJsFunc' => 'goToSearchPage'
];

$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmPaging']);
