<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupSettings(this); return false;');
$durationFld = $frm->getField('quiz_duration');
$durationFld->htmlAfterField = "<small>" . Label::getLabel('LBL_LEAVE_EMPTY_IN_CASE_OF_NO_TIME_LIMIT') . "</small>";
$attemptsFld = $frm->getField('quiz_attempts');
$marksFld = $frm->getField('quiz_passmark');
$validityFld = $frm->getField('quiz_validity');
$validityFld->htmlAfterField = "<small>" . Label::getLabel('LBL_QUIZ_VALIDITY_INSTRUCTIONS') . "</small>";
$cartificateFld = $frm->getField('quiz_certificate');
$failFld = $frm->getField('quiz_failmsg');
$passFld = $frm->getField('quiz_passmsg');
$submitFld = $frm->getField('btn_submit');
?>
<?php echo $this->includeTemplate('quizzes/navigation.php', ['quizId' => $quizId, 'active' => 3]) ?>
<div class="tabs-data">
    <div class="box-panel__container">
        <?php echo $frm->getFormTag(); ?>
        <div class="row">
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $durationFld->getCaption(); ?>
                            <?php if ($durationFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo str_replace('type="text"', 'type="number"', $durationFld->getHtml()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $attemptsFld->getCaption(); ?>
                            <?php if ($attemptsFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo str_replace('type="text"', 'type="number"', $attemptsFld->getHtml()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $marksFld->getCaption(); ?>
                            <?php if ($marksFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo str_replace('type="text"', 'type="number"', $marksFld->getHtml()); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $validityFld->getCaption(); ?>
                            <?php if ($validityFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo str_replace('type="text"', 'type="number"', $validityFld->getHtml()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $cartificateFld->getCaption(); ?>
                            <?php if ($cartificateFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <ul class="list-inline">
                                <?php if ($offerCertificate == true) { ?>
                                    <?php $selected = ($cartificateFld->value > 0) ? $cartificateFld->value : AppConstant::NO;
                                    foreach ($cartificateFld->options as $val => $option) { ?>
                                        <li>
                                            <label>
                                                <span class="radio">
                                                    <input type="radio" <?php echo ($selected == $val) ? 'checked="checked"' : '' ?> data-fatreq='{"required":true}' name="quiz_certificate" value="<?php echo $val; ?>">
                                                    <i class="input-helper"></i>
                                                </span>
                                                <?php echo $option; ?>
                                            </label>
                                        </li>
                                    <?php } ?>
                                <?php } else {
                                    echo $cartificateFld->getHtml();
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $failFld->getCaption(); ?>
                            <?php if ($failFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $failFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $passFld->getCaption(); ?>
                            <?php if ($passFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $passFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">

                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $submitFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $frm->getFieldHtml('quiz_id'); ?>
        </form>
        <?php echo $frm->getExternalJs(); ?>
    </div>
</div>