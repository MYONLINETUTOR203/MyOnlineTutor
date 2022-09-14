<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

?>
<div class="container container--fixed">
    <div class="page__head">
        <a href="<?php echo MyUtility::makeUrl('Courses') ?>" class="page-back">
            <svg class="icon icon--back margin-right-3">
                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#arrow-back"></use>
            </svg>
            <?php echo Label::getLabel('LBL_BACK_TO_QUIZZES'); ?>
        </a>
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-8">
                <h1 id="mainHeadingJs">
                    <?php echo Label::getLabel('LBL_MANAGE_QUIZZES'); ?>
                </h1>
                <p class="margin-0">&nbsp;</p>
            </div>
            <div class="col-sm-auto"></div>
        </div>
    </div>
    <div class="page__body">
        <div class="page-layout">
            <div class="box-panel">
                <div class="box-panel__head">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4><?php echo Label::getLabel('LBL_ADD_QUIZ'); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="box-panel__body" id="pageContentJs">

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            form("<?php echo $quizId ?>");
        });
    </script>