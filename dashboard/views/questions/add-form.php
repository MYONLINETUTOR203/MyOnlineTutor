<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'questionFrm');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupQuestion(this, false); return(false);');
$titleFld = $frm->getField('ques_title');
$typeFld = $frm->getField('ques_type');
$detailFld = $frm->getField('ques_detail');
$catFld = $frm->getField('ques_cate_id');
$subCatFld = $frm->getField('ques_subcate_id');
$hintFld = $frm->getField('ques_hint');
$marksFld = $frm->getField('ques_marks');
$fld = $frm->getField('ques_id');
$fld->setFieldTagAttribute('id', 'ques_id');
$bannerFld = $frm->getField('ques_banner');
$submitButton = $frm->getField('submit');
$submitButton->addFieldTagAttribute('onClick', 'setupQuestion(this.form, true); return(false);');

?>
<div class="facebox-panel">
    <div class="facebox-panel__head">
        <h4><?php echo Label::getLabel('LBL_ADD_GROUP_CLASS'); ?></h4>
        <div class="tabs tabs--line border-bottom-0">
            <ul>
                <li class="is-active"><a href="javascript:void(0);"><?php echo Label::getLabel('LBL_GENERAL'); ?></a></li>
            </ul>
        </div>
    </div>
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
                            <?php echo $marksFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="options-container">
            <div class="row">
                <div class="col-md-10">
                    <div class="field-set">
                        <div class="caption-wraper">
                            <label class="field_label">
                                <?php echo Label::getLabel('LBL_QUESTION_OPTIONS'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <a href="javascript:addOptionRow()" class="color-secondary"> +<?php echo Label::getLabel('LBL_ADD_OPTIONS'); ?></a>
                        </label>
                    </div>
                </div>
            </div>

            <div class="more-container-js"></div>
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