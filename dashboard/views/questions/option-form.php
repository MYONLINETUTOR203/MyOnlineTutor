<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$titleFld = $frm->getField("queopt_title[]");
$answerFld = $frm->getField('ques_answer[]');
if ($type == Question::TYPE_SINGLE) {
    $quesType = 'radio';
} elseif ($type == Question::TYPE_MULTIPLE) {
    $quesType = 'checkbox';
}
?>
<div class="sortableLearningJs">
    <?php if (count($options) > 0) { ?>
        <?php foreach ($options as $key => $option) { ?>
            <div class="row optionsRowJs">
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn--equal btn--sort btn--transparent color-gray-1000 cursor-move sortHandlerJs">
                        <svg class="svg-icon" viewBox="0 0 16 12.632">
                            <path d="M7.579 9.263v1.684H0V9.263zm1.684-4.211v1.684H0V5.053zM7.579.842v1.684H0V.842zM13.474 12.632l-2.527-3.789H16z"></path>
                            <path d="M12.632 2.105h1.684v7.579h-1.684z"></path>
                            <path d="M13.473 0L16 3.789h-5.053z"></path>
                        </svg>
                    </a>
                </div>
                <div class="col-md-7">
                    <div class="field-set">
                        <div class="field-wraper">
                            <div class="field_cover">
                                <input data-field-caption="<?php echo $titleFld->getCaption(); ?>" placeholder="<?php echo $titleFld->getCaption(); ?>" data-fatreq="{&quot;required&quot;:true}" type="text" name="queopt_title[<?php echo $option['queopt_id']; ?>]" value="<?php echo $option['queopt_title']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="field-set">
                        <div class="field-wraper">
                            <label>
                                <input data-field-caption="<?php echo $answerFld->getCaption(); ?>" data-fatreq="{&quot;required&quot;:false}" type="<?php echo $quesType; ?>" name="ques_answer[]" value="<?php echo $option['queopt_id']; ?>" <?php echo (in_array($option['queopt_id'], $answers)) ? 'checked="checked"' : ''; ?>>
                                <?php echo $answerFld->getCaption(); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <?php
        $i = 1;
        while ($count > 0) { ?>
            <div class="row optionsRowJs">
                <div class="col-md-1">
                    <a href="javascript:void(0)" class="btn btn--equal btn--sort btn--transparent color-gray-1000 cursor-move sortHandlerJs">
                        <svg class="svg-icon" viewBox="0 0 16 12.632">
                            <path d="M7.579 9.263v1.684H0V9.263zm1.684-4.211v1.684H0V5.053zM7.579.842v1.684H0V.842zM13.474 12.632l-2.527-3.789H16z"></path>
                            <path d="M12.632 2.105h1.684v7.579h-1.684z"></path>
                            <path d="M13.473 0L16 3.789h-5.053z"></path>
                        </svg>
                    </a>
                </div>
                <div class="col-md-7">
                    <div class="field-set">
                        <div class="field-wraper">
                            <div class="field_cover">
                                <input data-field-caption="<?php echo $titleFld->getCaption(); ?>" placeholder="<?php echo $titleFld->getCaption(); ?>" data-fatreq="{&quot;required&quot;:true}" type="text" name="queopt_title[<?php echo $i; ?>]" value="<?php echo $titleFld->value; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="field-set">
                        <div class="field-wraper">
                            <label>
                                <input data-field-caption="<?php echo $answerFld->getCaption(); ?>" data-fatreq="{&quot;required&quot;:false}" type="<?php echo $quesType; ?>" name="ques_answer[]" value="<?php echo $i; ?>" <?php echo ($i == 1 && $type == Question::TYPE_SINGLE) ? 'checked="checked"' : ''; ?>>
                                <?php echo $answerFld->getCaption(); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
    <?php
            $i++;
            $count--;
        }
    }
    ?>
</div>
<script type="text/javascript">
    $(function() {
        $(".sortableLearningJs").sortable({
            handle: ".sortHandlerJs",
        });
    });
</script>