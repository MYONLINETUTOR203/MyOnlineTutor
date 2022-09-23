<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (count($quizzes) == 0) {
    $this->includeTemplate('_partial/no-record-found.php');
    return;
}
$titleLbl = Label::getLabel('LBL_TITLE');
$typeLbl = Label::getLabel('LBL_TYPE');
?>
<?php
$statuses = Question::getStatuses();
foreach ($quizzes as $quiz) {
?>
    <tr>
        <td>
            <input type="checkbox">
        </td>
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
    </tr>
<?php } ?>
 