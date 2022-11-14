<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="row justify-content-between">
    <div class="col-xl-12">
        <div class="cms-container">
            <div class="editor-content editorContentJs">
                <iframe srcdoc="<?php echo $quiz['quilin_detail']; ?>" style="border:none;width: 100%;height: 100%;"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-directions border-top">
    <div class="row justify-content-between">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-auto">
            <div class="btn-actions">
                <a href="javascript:void(0);" class="btn btn--primary-bordered margin-right-1" onclick="loadLecture('<?php echo $lectureId ?>');">
                    <svg class="icon icon--arrow icon--xsmall margin-right-2">
                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#prev"></use>
                    </svg>
                    <?php echo Label::getLabel('LBL_PREV') ?>
                </a>
                <a href="javascript:void(0);" class="btn btn--primary-bordered margin-left-1 getNextJs btn--disabled">
                    <?php echo Label::getLabel('LBL_NEXT') ?>
                    <svg class="icon icon--arrow icon--xsmall margin-left-2">
                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#next"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var id = '<?php echo $quiz['quizat_id']; ?>';
        getQuiz(id);
    });
</script>