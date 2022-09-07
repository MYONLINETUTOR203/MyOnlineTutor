<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$titleFld = $frm->getField("queopt_title[]");
$answerFld = $frm->getField('ques_answer[]');
// echo "<pre>"; print_r($frm);exit;
?>
<div class="sortableLearningJs">
    <?php foreach (range(1, $count) as $i) { ?>
        <div class="row ">
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
                        <input data-field-caption="<?php echo $titleFld->getCaption(); ?>" placeholder="<?php echo $titleFld->getCaption(); ?>" data-fatreq="{&quot;required&quot;:true}" type="text" name="queopt_title[<?php echo $i; ?>]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="field-set">
                    <div class="field-wraper">
                    <label>
                        <input data-field-caption="<?php echo $answerFld->getCaption(); ?>" data-fatreq="{&quot;required&quot;:false}" type="radio" name="ques_answer[]" value="<?php echo $i; ?>">
                        <?php echo $answerFld->getCaption(); ?>
                    </label>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
<script type="text/javascript">
    $(function() {
        $(".sortableLearningJs").sortable({
            handle: ".sortHandlerJs",
        });
    });
</script>