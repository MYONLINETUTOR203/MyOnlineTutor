<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$username = ucwords($user['user_first_name'] . ' ' . $user['user_last_name'])
?>
<div class="page-body">
    <div class="container container--narrow">
        <div class="message-display no-skin">
            <?php if ($data['quizat_evaluation'] == QuizAttempt::EVALUATION_PASSED) { ?>
                <div class="message-display__media">
                    <img src="<?php echo CONF_WEBROOT_DASHBOARD ?>images/700x400.svg" alt="">
                </div>
                <h3 class="margin-bottom-2">
                    <?php
                    $label = Label::getLabel('LBL_QUIZ_PASS_MSG_HEADING');
                    echo str_replace('{username}', '<strong class="bold-700">' . $username . '</strong>', $label);
                    ?>
                </h3>
                <p class="margin-bottom-2">
                    <?php echo $data['quilin_passmsg']; ?>
                </p>
            <?php } else { ?>
                <div class="message-display__media">
                    <img src="<?php echo CONF_WEBROOT_DASHBOARD ?>images/quiz-fail.svg" alt="">
                </div>
                <h3 class="margin-bottom-2">
                    <?php
                    $label = Label::getLabel('LBL_QUIZ_FAIL_MSG_HEADING');
                    echo str_replace('{username}', '<strong class="bold-700">' . $username . '</strong>', $label);
                    ?>
                </h3>
                <p class="margin-bottom-2">
                    <?php echo $data['quilin_failmsg']; ?>
                </p>
            <?php } ?>
            <div class="inline-meta margin-top-5 margin-bottom-5">
                <span class="inline-meta__item">Score:
                    <strong>
                        <?php
                        $label = Label::getLabel('LBL_{score}_OF_{total}');
                        echo str_replace(['{score}', '{total}'], [$data['quizat_marks'], $data['quilin_marks']], $label);
                        ?>
                    </strong>
                </span>
                <span class="inline-meta__item">
                    <?php echo Label::getLabel('LBL_ACHIEVED_PERCENT:'); ?>
                    <strong><?php echo MyUtility::formatPercent($data['quizat_scored']); ?></strong>
                </span>
                <?php if ($data['quilin_duration'] > 0) { ?>
                    <span class="inline-meta__item">
                        <?php echo Label::getLabel('LBL_TIME_SPENT:'); ?>
                        <strong>
                            <?php
                            $diff = date_diff(date_create($data['quizat_updated']), date_create($data['quizat_started']));
                            echo $diff->format('%h:%i:%s');
                            ?>
                        </strong>
                    </span>
                <?php } ?>
            </div>
            <div class="d-sm-flex justify-content-center margin-top-4">
                <?php if ($canRetake == true) { ?>
                    <a href="javascript:void(0);" class="btn btn--primary-bordered margin-1 btn--sm-block" onclick="retakeQuiz('<?php echo $data['quizat_id'] ?>');">
                        <svg class="icon icon--png icon--small margin-right-2">
                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#retake"></use>
                        </svg>
                        <?php echo Label::getLabel('LBL_RETAKE_QUIZ'); ?>
                    </a>
                <?php } ?>
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
                <?php if ($canDownloadCertificate == true) { ?>
                    <a href="<?php echo MyUtility::makeUrl('UserQuiz', 'downloadCertificate', [$data['quizat_id']], CONF_WEBROOT_DASHBOARD); ?>" class="btn btn--primary margin-1 btn--sm-block">
                        <svg class="icon icon--png icon--small margin-right-2">
                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD; ?>images/sprite.svg#download-icon"></use>
                        </svg>
                        <?php echo Label::getLabel('LBL_DOWNLOAD_CERTIFICATE'); ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>