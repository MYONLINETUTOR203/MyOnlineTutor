<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('id', 'frmCourses');
$frm->setFormTagAttribute('onsubmit', 'setupSettings(this); return false;');
$certFld = $frm->getField('course_certificate');
$typeFld = $frm->getField('course_certificate_type');
$typeFld->addFieldTagAttribute('onchange', 'showQuizSection(this.value);');
if ($certFld->value == AppConstant::YES) {
    $typeFld->requirements()->setRequired();
}
$tagFld = $frm->getField('course_tags');
$tagFld->addFieldTagAttribute('id', "tagsinput");
$tagFld->setFieldTagAttribute('placeholder', Label::getLabel('LBL_INSERT_YOUR_COURSE_TAGS'));
($frm->getField('btn_approval'))->setFieldTagAttribute('onclick', 'submitForReview();');
?>
<?php echo $frm->getFormTag(); ?>
<div class="page-layout">
    <div class="page-layout__small">
        <?php echo $this->includeTemplate('courses/sidebar.php', ['frm' => $frm, 'active' => 5, 'courseId' => $courseId]) ?>
    </div>
    <div class="page-layout__large">
        <div class="box-panel">
            <div class="box-panel__head border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4><?php echo Label::getLabel('LBL_MANAGE_COURSE_SETTINGS'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="box-panel__body">
                <div class="box-panel__container">
                    <p><?php echo Label::getLabel('LBL_SETTINGS_FORM_INFO'); ?></p>
                    <div class="margin-top-14">
                        <div class="form">
                            <?php if ($offerCetificate == true) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label">
                                                    <?php echo $certFld->getCaption(); ?>
                                                    <span class="spn_must_field">*</span>
                                                </label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <ul class="list-inline">
                                                        <?php
                                                        $selected = ($certFld->value > 0) ? $certFld->value : AppConstant::NO;
                                                        foreach ($certFld->options as $val => $option) { ?>
                                                            <li>
                                                                <label>
                                                                    <span class="radio">
                                                                        <input type="radio" onchange="getCertificates();" <?php echo ($selected == $val) ? 'checked="checked"' : '' ?> data-fatreq='{"required":true}' name="course_certificate" value="<?php echo $val; ?>">
                                                                        <i class="input-helper"></i>
                                                                    </span>
                                                                    <?php echo $option; ?>
                                                                </label>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                echo $certFld->getHtml();
                            }
                            ?>
                            <div class="row certTypeJs" style="display:<?php echo ($certFld->value == AppConstant::YES) ? 'block' : 'none'; ?>">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label">
                                                <?php echo $typeFld->getCaption(); ?>
                                                <span class="spn_must_field">*</span>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $typeFld->getHtml(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row quizSectionJs" style="display:<?php echo ($typeFld->value == Certificate::TYPE_COURSE_EVALUTAION) ? 'block' : 'none'; ?>">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label">
                                                <div class="attachedQuizJs" style="display:<?php echo (!empty($quiz)) ? 'block' : 'none'; ?>;">
                                                    <span class="quizTitleJs"><?php echo $quiz['quilin_title'] ?? '' ?></span>
                                                    <a href="javascript:void(0);" class="margin-1 is-hover" onclick="removeAttachedQuiz();">
                                                        <svg class="icon icon--issue icon--small">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#close'; ?>"></use>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="attachQuizLinkJs" style="display:<?php echo !empty($quiz) ? 'none' : 'block' ?>;">
                                                    <a href="javascript:void(0);" onclick="quizListing('<?php echo $courseId; ?>', '<?php echo AppConstant::COURSE; ?>')">
                                                        <svg class="icon icon--issue icon--small">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#attach'; ?>"></use>
                                                        </svg>
                                                        <?php echo Label::getLabel('LBL_ATTACH_QUIZ'); ?>
                                                        <?php echo $frm->getFieldHtml('course_quiz_id'); ?>
                                                    </a>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label">
                                                <?php echo $tagFld->getCaption(); ?>
                                                <span class="spn_must_field">*</span>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $tagFld->getHtml(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<script>
    $(document).ready(function() {
        $('input[name="course_tags"]').tagit({
            caseSensitive: false,
            allowDuplicates: false,
            allowSpaces: true,
        });
        $('.ui-autocomplete-input').attr('name', 'tags');
        $('form input[name="course_tags"]').on('keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>