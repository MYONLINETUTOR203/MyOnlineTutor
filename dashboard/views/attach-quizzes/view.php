<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$types = Quiz::getTypes();
$status = QuizAttempt::getStatuses();
?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6><?php echo Label::getLabel('LBL_ATTACHED_QUIZZES'); ?></h6>
            </div>
        </div>
    </div>
    <div class="facebox-panel__body padding-0">
        <div class="table-scroll">
            <table class="table table--responsive table--bordered table--styled " id="">
                <thead>
                    <tr class="title-row">
                        <th><?php echo Label::getLabel('LBL_TITLE'); ?></th>
                        <th><?php echo Label::getLabel('LBL_TYPE'); ?></th>
                        <?php if ($recordType == AppConstant::LESSON) { ?>
                            <th><?php echo Label::getLabel('LBL_LEARNER'); ?></th>
                        <?php } ?>
                        <th><?php echo Label::getLabel('LBL_ACTION'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($quizzes) > 0) { ?>
                        <?php foreach ($quizzes as $quiz) { ?>
                            <tr class="quizRowJs quizRow<?php echo $quiz['quilin_id'] ?>">
                                <td width="60%">
                                    <div class="d-inline-flex">
                                        <?php if ($recordType == AppConstant::GCLASS) { ?>
                                            <span class="arrow-icon margin-left-0" onclick="view('<?php echo $quiz['quilin_id'] ?>');"></span>
                                        <?php } ?>
                                        <span><?php echo $quiz['quilin_title'] ?></span>

                                    </div>
                                </td>
                                <td><?php echo $types[$quiz['quilin_type']] ?></td>
                                <?php if ($recordType == AppConstant::LESSON) { ?>
                                    <td>
                                        <?php echo ucwords($quiz['users']['user_first_name'] . ' ' . $quiz['users']['user_last_name']); ?>
                                    </td>
                                <?php } ?>
                                <td>
                                    <?php if ($recordType == AppConstant::LESSON) { ?>
                                        <?php if ($quiz['users']['quizat_status'] == QuizAttempt::STATUS_COMPLETED) { ?>
                                            <a target="_blank" href="<?php echo MyUtility::makeFullUrl('UserQuiz', 'index', [$quiz['quilin_id']]); ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                                <svg class="icon icon--cancel icon--small">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#view"></use>
                                                </svg>
                                                <div class="tooltip tooltip--top bg-black">
                                                    <?php echo Label::getLabel('LBL_VIEW'); ?>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                    <a href="javascript:void(0);" data-record-id="<?php echo $recordId; ?>" data-record-type="<?php echo $recordType; ?>" onclick="removeQuiz('<?php echo $quiz['quilin_id']; ?>', this);" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                        <svg class="icon icon--cancel icon--small">
                                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#cancel"></use>
                                        </svg>
                                        <div class="tooltip tooltip--top bg-black">
                                            <?php echo Label::getLabel('LBL_REMOVE'); ?>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            <?php if ($recordType == AppConstant::GCLASS) { ?>
                                <tr style="display:none;" class="userListJs userListJs<?php echo $quiz['quilin_id'] ?>">
                                    <td colspan="3">
                                        <table class="table table--styled table--responsive table--aligned-middle" id="">
                                            <thead>
                                                <tr class="title-row">
                                                    <th><?php echo Label::getLabel('LBL_LEARNER'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_STATUS'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_ACTION'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (count($quiz['users']) > 0) { ?>
                                                    <?php foreach ($quiz['users'] as $user) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo ucwords($user['user_first_name'] . ' ' . $user['user_last_name']); ?>
                                                            </td>
                                                            <td><?php echo $status[$user['quizat_status']] ?></td>
                                                            <td>
                                                                <?php if ($user['quizat_status'] == QuizAttempt::STATUS_COMPLETED) { ?>
                                                                    <a target="_blank" href="<?php echo MyUtility::makeFullUrl('QuizReview', 'index', [$user['quizat_id']]); ?>" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                                                        <svg class="icon icon--cancel icon--small">
                                                                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#view"></use>
                                                                        </svg>
                                                                        <div class="tooltip tooltip--top bg-black">
                                                                            <?php echo Label::getLabel('LBL_VIEW'); ?>
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <tr class="noRecordJS" style="display:none;">
                        <td colspan="4"><?php $this->includeTemplate('_partial/no-record-found.php'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>