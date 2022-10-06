<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="page-body">
    <div class="container container--narrow">
        <div class="message-display no-skin">
            <div class="message-display__media">
                <img src="<?php echo CONF_WEBROOT_DASHBOARD ?>images/700x400.svg" alt="">
            </div>
            <h3 class="margin-bottom-2">Congratulations <strong class="bold-700">Stephen</strong> </h3>
            <p class="margin-bottom-2">You have completed your quiz successfully. You can be proud of yourself!</p>
            <div class="inline-meta margin-top-5 margin-bottom-5">
                <span class="inline-meta__item">Score: 
                <strong>
                    <?php
                    $label = Label::getLabel('LBL_{attempted-ques}_OF_{total}');
                    echo str_replace(['{attempted-ques}', '{total}'], [0, $data['quilin_questions']], $label);
                    ?>
                </strong>
                </span>
                <span class="inline-meta__item">
                    <?php echo Label::getLabel('LBL_PROGRESS:') ;?> 
                    <strong><?php echo MyUtility::formatPercent($data['quizat_progress']); ?></strong>
                </span>
                <span class="inline-meta__item">Time Spent: <strong>05:36</strong></span>
            </div>
            <div class="d-sm-flex justify-content-center margin-top-4">
                <a href="#" class="btn btn--primary-bordered margin-1 btn--sm-block">
                    <svg class="icon icon--png icon--small margin-right-2">
                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#retake"></use>
                    </svg>
                    <?php echo Label::getLabel('LBL_RETAKE_QUIZ'); ?>
                </a>
                <a href="#" class="btn btn--primary margin-1 btn--sm-block">
                    <?php echo Label::getLabel('LBL_CHECK_ANSWERS'); ?>
                </a>
                <?php $controller = ($data['quilin_record_type'] == AppConstant::LESSON) ? 'Lessons' : 'Classes' ?>
                <a href="<?php echo MyUtility::makeUrl($controller) ?>" class="btn btn--primary-bordered margin-1 btn--sm-block">
                    <svg class="icon icon--png icon--small margin-right-2">
                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#arrow-back"></use>
                    </svg>
                    <?php echo Label::getLabel('LBL_GO_TO_QUIZZES'); ?>
                </a>
            </div>
        </div>
    </div>
</div>