<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$types = Quiz::getTypes();
?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6><?php echo Label::getLabel('LBL_ATTACHED_QUIZZES'); ?></h6>
            </div>
        </div>
    </div>
    <div class="facebox-panel__body">
        <div class="table-scroll">
            <table class="table table--styled table--responsive table--aligned-middle" id="">
                <thead>
                    <tr class="title-row">
                        <th><?php echo Label::getLabel('LBL_TITLE'); ?></th>
                        <th><?php echo Label::getLabel('LBL_TYPE'); ?></th>
                        <th><?php echo Label::getLabel('LBL_ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($quizzes) > 0) { ?>
                        <?php foreach ($quizzes as $quiz) {
                            $url = MyUtility::makeFullUrl('UserQuiz', 'index', [$quiz['users']['quizat_id']]);
                            if ($quiz['users']['quizat_status'] == QuizAttempt::STATUS_COMPLETED) {
                                $url = MyUtility::makeFullUrl('UserQuiz', 'completed', [$quiz['users']['quizat_id']]);
                            } elseif ($quiz['users']['quizat_status'] == QuizAttempt::STATUS_IN_PROGRESS) {
                                $url = MyUtility::makeFullUrl('UserQuiz', 'questions', [$quiz['users']['quizat_id']]);
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo $quiz['quilin_title'] ?>
                                </td>
                                <td><?php echo $types[$quiz['quilin_type']] ?></td>
                                <td>
                                    <a target="_blank" href="<?php echo $url; ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                        <svg class="icon icon--cancel icon--small">
                                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#view"></use>
                                        </svg>
                                        <div class="tooltip tooltip--top bg-black">
                                            <?php echo Label::getLabel('LBL_VIEW'); ?>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3"><?php $this->includeTemplate('_partial/no-record-found.php'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>