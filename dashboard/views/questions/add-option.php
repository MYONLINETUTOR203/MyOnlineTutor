<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php

$optionFld = $frm->getField("queopt_title[]");
$optionDesc = $frm->getField("queopt_detail[]");
if ($type == Question::TYPE_MULTIPLE) {
    $answerFld = $frm->getField("ques_answer[]");
} else {
    $answerFld = $frm->getField('ques_answer[]');
}

$answerFld->addFieldTagAttribute('class', 'optAnswer');

?>
<div class="row typeFieldsJs">

    <div class="col-md-12">
        <div class="row">

            <div class="col-md-8">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $optionFld->getCaption(); ?>
                            <?php if ($optionFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $optionFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $answerFld->getCaption(); ?>
                                <!-- <span class="spn_must_field">*</span> -->
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                        <?php echo $answerFld->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="field-set">
                    <div class="field-wraper">
                        <div class="field_cover mt-6">
                            <a href="javascript:void(0)" class="btn btn--equal btn--sort btn--transparent color-gray-1000 cursor-move sortHandlerJs">
                                <svg class="svg-icon" viewBox="0 0 16 12.632">
                                        <path d="M7.579 9.263v1.684H0V9.263zm1.684-4.211v1.684H0V5.053zM7.579.842v1.684H0V.842zM13.474 12.632l-2.527-3.789H16z"></path>
                                        <path d="M12.632 2.105h1.684v7.579h-1.684z"></path>
                                        <path d="M13.473 0L16 3.789h-5.053z"></path>
                                    </svg>
                            </a>
                            <a href="javascript:void(0);" onclick="removeOptionRow(this);" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                <svg class="icon icon--issue icon--small">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.svg#trash'; ?>"></use>
                                </svg>
                                <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Remove'); ?></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php echo $optionDesc->getCaption(); ?>
                            <?php if ($optionDesc->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php echo $optionDesc->getHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
    

        </div>

    </div>

  
   
    

    
</div>
<script type="text/javascript">
    $(function() {
        $(".sortableLearningJs").sortable({
            handle: ".sortHandlerJs",
        });
    });
</script>