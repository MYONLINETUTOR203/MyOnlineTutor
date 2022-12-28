<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$yesNoArr = AppConstant::getYesNoArr();
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_QUESTIONS_DETAIL'); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container responsive flat">
            <div class="tabs_panel_wrap">
                <div class="sectionhead">
                    <h4><?php echo Label::getLabel('LBL_BASIC_DETAILS') ?></h4>
                </div>
                <div class="tabs_panel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_TITLE'); ?>
                                    </label>
                                    : <strong><?php echo CommonHelper::renderHtml($questionData['ques_title']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_DESCRIPTION'); ?>
                                    </label>
                                    : <strong><?php echo ($questionData['ques_detail']) ? CommonHelper::renderHtml($questionData['ques_detail']) : Label::getLabel('LBL_NA'); ?></strong>
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
                                    : <strong><?php echo Question::getTypes($questionData['ques_type']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_TEACHER_NAME'); ?>
                                    </label>
                                    : <strong><?php echo $questionData['teacher_first_name'] . ' ' . $questionData['teacher_last_name']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_CATEGORY'); ?>
                                    </label>
                                    : <strong><?php echo CommonHelper::renderHtml($questionData['ques_cate_name']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_SUB_CATEGORY'); ?>
                                    </label>
                                    : <strong><?php echo empty($questionData['ques_subcate_name']) ? Label::getLabel('LBL_NA') : CommonHelper::renderHtml($questionData['ques_subcate_name']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_STATUS'); ?>
                                    </label>
                                    : <strong><?php echo Question::getStatuses($questionData['ques_status']); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_MARKS'); ?>
                                    </label>
                                    : <strong><?php echo $questionData['ques_marks']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_HINT'); ?>
                                    </label>
                                    : <strong><?php echo ($questionData['ques_hint']) ? CommonHelper::renderHtml($questionData['ques_hint']) : Label::getLabel('LBL_NA'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo Label::getLabel('LBL_ADDED_ON'); ?>
                                    </label>
                                    : <strong><?php echo MyDate::formatDate($questionData['ques_created']); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($file)) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                        <label class="field_label">
                                            <?php echo Label::getLabel('LBL_AUDIO'); ?>
                                        </label>
                                        <div class="source">
                                            <audio src="<?php echo $file; ?>" controls playsinline noplaybackrate controlsList="nodownload" volume=1 autostart="0" style="width:100%"></audio>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if (in_array($questionData['ques_type'], [Question::TYPE_MULTIPLE, Question::TYPE_SINGLE])) { ?>
                    <div class="sectionhead">
                        <h4><?php echo Label::getLabel('LBL_OPTIONS') ?></h4>
                    </div>
                    <div class="tabs_panel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                        <ul class="list-vertical">
                                            <?php foreach ($options as $option) : ?>
                                                <li><?php echo $option['queopt_title']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sectionhead">
                        <h4><?php echo Label::getLabel('LBL_ANSWERS') ?></h4>
                    </div>
                    <div class="tabs_panel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                        <?php if (isset($answers) && count($answers) > 0) :  ?>
                                            <ul class="list-vertical">
                                                <?php foreach ($answers as $answerId) : ?>
                                                    <li><?php echo $options[$answerId]['queopt_title']; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>