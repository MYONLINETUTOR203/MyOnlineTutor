<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (count($quizzes) == 0) {
    ?>
    <tr>
        <td colspan="3">
            <?php $this->includeTemplate('_partial/no-record-found.php'); ?>
        </td>
    </tr>    
    <?php return;
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
            <label class="checkbox">
                <input type="checkbox" name="quilin_quiz_id[]" value="<?php echo $quiz['quiz_id']; ?>">
                <i class="input-helper"></i>
            </label>
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