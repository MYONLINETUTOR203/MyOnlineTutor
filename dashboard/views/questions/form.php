<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'questionFrm');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setup(this, false); return(false);');
$titleFld = $frm->getField('ques_title');
$typeFld = $frm->getField('ques_type');
$typeFld->setFieldTagAttribute('id', 'ques_type');
$typeFld->setFieldTagAttribute('onchange', 'showOptions(this.value)');
$detailFld = $frm->getField('ques_detail');
$catFld = $frm->getField('ques_cate_id');
$subCatFld = $frm->getField('ques_subcate_id');
$subCatFld->setFieldTagAttribute('id', 'subCateAddQues');
$catFld->setFieldTagAttribute('onchange', 'getSubcategories(this.value, "#subCateAddQues")');
$hintFld = $frm->getField('ques_hint');
$marksFld = $frm->getField('ques_marks');
$fld = $frm->getField('ques_id');
$fld->setFieldTagAttribute('id', 'ques_id');
$optionCount = $frm->getField('ques_options_count');
$addOptionsFld = $frm->getField('add_options');
$addOptionsFld->setFieldTagAttribute('onclick', 'addOptions()');
$submitButton = $frm->getField('submit');
$submitButton->addFieldTagAttribute('onClick', 'setup(this.form); return(false);');
?>
<div class="facebox-panel">
    <h4><?php echo Label::getLabel('LBL_ADD_QUESTION'); ?></h4>
    <div class="facebox-panel__body">
        <?php echo $frm->getFormTag(); ?>
        <?php echo $frm->getFieldHTML('ques_id'); ?>
        <div class="row">
            <div class="col-md-8">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $titleFld->getCaption(); ?>
                            <?php if ($titleFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $titleFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $typeFld->getCaption(); ?>
                            <?php if ($typeFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
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
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $detailFld->getCaption(); ?>
                            <?php if ($detailFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $detailFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $catFld->getCaption(); ?>
                            <?php if ($catFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $catFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $subCatFld->getCaption(); ?>
                            <?php if ($subCatFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $subCatFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $hintFld->getCaption(); ?>
                            <?php if ($hintFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $hintFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row options-container" style="<?php echo isset($question['ques_type']) && ($question['type'] != Question::TYPE_SINGLE || $question['ques_type'] != Question::TYPE_MULTIPLE) ? '': 'display: none;'?>">
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $optionCount->getCaption(); ?>
                            <?php if ($optionCount->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $optionCount->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">&nbsp;</label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                        <?php echo $addOptionsFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="more-container-js">
            <?php
                if(isset($question['options']) && count($question['options']) > 0 && isset($optionsFrm)) {
                    $this->includeTemplate('questions/option-form.php', array('question'=> $question, 'frm' => $optionsFrm), false);
                }
             ?>
        </div>
       
        
        <div class="row form-action-sticky">
            <div class="col-sm-12">
                <div class="field-set margin-bottom-0">
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $submitButton->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <?php echo $frm->getExternalJS(); ?>
    </div>
</div>

<script>
    var TYPE_SINGLE = <?php echo Question::TYPE_SINGLE; ?>;
    var TYPE_MULTIPLE = <?php echo Question::TYPE_MULTIPLE; ?>;
    var TYPE_MANUAL = <?php echo Question::TYPE_MANUAL; ?>;

    $(document).ready(function(){
        getSubcategories('<?php echo $question['ques_cate_id'] ?? 0; ?>', '#subCateAddQues', '<?php echo $question['ques_subcate_id'] ?? 0; ?>');
    });
</script>