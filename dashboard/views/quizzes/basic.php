<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'questionFrm');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setup(); return false;');
$title = $frm->getField('quiz_title');
$type = $frm->getField('quiz_type');
$type->addFieldTagAttribute('id', 'quizTypeJs');
$typeId = $frm->getField('quiz_type_id');
if ($quizId > 0) {
    $typeId->addFieldTagAttribute('disabled', 'disabled');
} else {
    $typeId->addFieldTagAttribute('onchange', 'setType(this.value)');
}
$detail = $frm->getField('quiz_detail');
$submit = $frm->getField('submit');
?>
<?php echo $this->includeTemplate('quizzes/navigation.php', ['quizId' => $quizId, 'active' => 1]) ?>
<div class="tabs-data">
    <div class="box-panel__container">
        <?php echo $frm->getFormTag(); ?>
        <?php echo $frm->getFieldHTML('quiz_id'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $title->getCaption(); ?>
                            <?php if ($title->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $title->getHtml(); ?>
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
                            <?php echo $typeId->getCaption(); ?>
                            <?php if ($typeId->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $typeId->getHtml(); ?>
                            <?php echo $type->getHtml(); ?>
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
                            <?php echo $detail->getCaption(); ?>
                            <?php if ($detail->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $detail->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="field-set margin-bottom-0">
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $submit->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <?php echo $frm->getExternalJS(); ?>
    </div>
</div>
