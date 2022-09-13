<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (count($quizzes) == 0) {
    $this->includeTemplate('_partial/no-record-found.php');
    return;
}
?>
<div class="table-scroll">
    <table class="table table--styled table--responsive table--aligned-middle">
        <tr class="title-row">
            <th><?php echo $titleLbl = Label::getLabel('LBL_TITLE'); ?></th>
            <th><?php echo $typeLbl = Label::getLabel('LBL_TYPE'); ?></th>
            <th><?php echo $durationLbl = Label::getLabel('LBL_DURATION'); ?></th>
            <th><?php echo $attemptsLbl = Label::getLabel('LBL_ATTEMPTS'); ?></th>
            <th><?php echo $passLbl = Label::getLabel('LBL_PASS_PERCENT'); ?></th>
            <th><?php echo $statusLbl = Label::getLabel('LBL_STATUS'); ?></th>
            <th><?php echo $activeLbl = Label::getLabel('LBL_ACTIVE'); ?></th>
            <th><?php echo $dateLbl = Label::getLabel('LBL_DATE'); ?></th>
            <th><?php echo $actionLbl = Label::getLabel('LBL_ACTIONS'); ?></th>
        </tr>
        <?php
        $naLabel = Label::getLabel('LBL_N/A');
        $statuses = Question::getStatuses();
        foreach ($quizzes as $quiz) {
        ?>
            <tr>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $titleLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $quiz['quiz_title']; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $typeLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $types[$quiz['quiz_type']]; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $durationLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo MyUtility::convertDuration($quiz['quiz_duration']); ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $attemptsLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $quiz['quiz_attempts']; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $passLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo MyUtility::formatPercent($quiz['quiz_passmark']); ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $statusLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $status[$quiz['quiz_status']]; ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $activeLbl; ?></div>
                        <div class="flex-cell__content">
                            <label class="switch switch--small">
                                <input class="switch__label" data-field-caption="" type="checkbox" name="quiz_active" value="<?php echo $quiz['quiz_active']; ?>" <?php echo ($quiz['quiz_active'] == AppConstant::ACTIVE) ? 'checked="checked"' : '' ?> onchange="updateStatus('<?php echo $quiz['quiz_id']; ?>', this)"> <i class="switch__handle bg-green"></i>
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $dateLbl; ?></div>
                        <div class="flex-cell__content">
                            <?php echo MyDate::formatDate($quiz['quiz_created']); ?>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $actionLbl; ?></div>
                        <div class="flex-cell__content">
                            <a href="javascript:void(0);" onclick="form('<?php echo $quiz['quiz_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                <svg class="icon icon--edit icon--small">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#edit'; ?>"></use>
                                </svg>
                                <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_EDIT'); ?></div>
                            </a>
                            <a href="javascript:void(0);" onclick="remove('<?php echo $quiz['quiz_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                <svg class="icon icon--issue icon--small">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#trash'; ?>"></use>
                                </svg>
                                <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_Remove'); ?></div>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php
$pagingArr = [
    'pageSize' => $post['pagesize'],
    'page' => $post['pageno'],
    'recordCount' => $recordCount,
    'pageCount' => ceil($recordCount / $post['pagesize'])
];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmPaging']);
?>