<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_QUIZ_DETAIL'); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container responsive flat">
            <div class="tabs_panel_wrap">
                <div class="sectionhead">
                    <h4><?php echo Label::getLabel('LBL_BASIC_DETAILS') ?></h4>
                </div>
                <div class="tabs_panel">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_TITLE'); ?>
                                    </label>
                                    : <strong><?php echo $quiz['quiz_title']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_DESCRIPTION'); ?>
                                    </label>
                                    : <strong><?php echo CommonHelper::renderHtml($quiz['quiz_detail']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_TYPE'); ?>
                                    </label>
                                    : <strong><?php echo Quiz::getTypes($quiz['quiz_type']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_TEACHER_NAME'); ?>
                                    </label>
                                    : <strong><?php echo ucwords($quiz['teacher_first_name'] . ' ' . $quiz['teacher_last_name']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_ACTIVE'); ?>
                                    </label>
                                    : <strong><?php echo AppConstant::getYesNoArr($quiz['quiz_active']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_STATUS'); ?>
                                    </label>
                                    : <strong><?php echo Quiz::getStatuses($quiz['quiz_status']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_ADDED_ON'); ?>
                                    </label>
                                    : <strong><?php echo MyDate::formatDate($quiz['quiz_created']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sectionhead">
                        <h4><?php echo Label::getLabel('LBL_SETTINGS') ?></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_DURATION'); ?>
                                    </label>
                                    : <strong><?php echo ($quiz['quiz_duration']) ? MyUtility::convertDuration($quiz['quiz_duration']) : Label::getLabel('LBL_NA'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_NO_OF_ATTEMPTS_ALLOWED'); ?>
                                    </label>
                                    : <strong><?php echo ($quiz['quiz_attempts']) ? $quiz['quiz_attempts'] : Label::getLabel('LBL_NA'); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_PASS_MARKS'); ?>
                                    </label>
                                    : <strong><?php echo ($quiz['quiz_passmark']) ? MyUtility::formatPercent($quiz['quiz_passmark']) : Label::getLabel('LBL_NA'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_VALIDITY'); ?>
                                    </label>
                                    : <strong>
                                        <?php
                                        if (!empty($quiz['quiz_validity'])) {
                                            $label = Label::getLabel('LBL_{validity}_HOUR(S)');
                                            echo str_replace('{validity}', $quiz['quiz_validity'], $label);
                                        } else {
                                            echo Label::getLabel('LBL_NA');
                                        }
                                        ?>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_CERTIFICATE'); ?>
                                    </label>
                                    : <strong><?php echo AppConstant::getYesNoArr($quiz['quiz_certificate']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_NO_OF_QUESTIONS'); ?>
                                    </label>
                                    : <strong><?php echo $quiz['quiz_questions']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_PASS_MESSAGE'); ?>
                                    </label>
                                    : <strong><?php echo ($quiz['quiz_passmsg']) ? CommonHelper::renderHtml($quiz['quiz_passmsg']) : Label::getLabel('LBL_NA');  ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_FAIL_MESSAGE'); ?>
                                    </label>
                                    : <strong><?php echo ($quiz['quiz_failmsg']) ? CommonHelper::renderHtml($quiz['quiz_failmsg'])  : Label::getLabel('LBL_NA'); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>