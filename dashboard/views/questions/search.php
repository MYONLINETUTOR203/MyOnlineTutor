<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if (count($questions) == 0) {
    $this->includeTemplate('_partial/no-record-found.php');
    return;
}
?>
<div class="table-scroll">
    <table class="table table--styled table--responsive table--aligned-middle">
        <tr class="title-row">
            <th><?php echo $startLabel = Label::getLabel('LBL_TITLE'); ?></th>
            <th><?php echo $endLabel = Label::getLabel('LBL_CATEGORY'); ?></th>
            <th><?php echo $languageLabel = Label::getLabel('LBL_SUBCATEGORY'); ?></th>
            <th><?php echo $statusLabel = Label::getLabel('LBL_STATUS'); ?></th>
            <th><?php echo $actionLabel = Label::getLabel('LBL_ACTIONS'); ?></th>
        </tr>
        <?php
        $naLabel = Label::getLabel('LBL_N/A');
        $statuses = Question::getStatuses();
        foreach ($questions as $question) {
            ?>
            <tr>
                <td>
                <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $startLabel; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $question['ques_title']; ?>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $startLabel; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $question['ques_cate_name']; ?>
                    </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $endLabel; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $question['ques_subcate_name']; ?>
                        </div>
                </td>
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $languageLabel; ?></div>
                        <div class="flex-cell__content">
                            <?php echo $statuses[$question['ques_status']]; ?>
                        </div>

                        <!-- <div class="flex-cell__content">
                        <label id="<?php echo $row['ques_id']; ?>" class="statustab status_<?php echo $row['ques_id'] . ' ' . $active ?>" onclick="<?php echo $statusAct; ?>">
                            <span data-off="<?php echo $activeLabel ?>" data-on="<?php echo $inactiveLabel ?>" class="switch-labels"></span>
                            <span class="switch-handles <?php echo $statusClass ?>"></span>
                        </label>    
                        </div> -->
                    </div>
                </td>
               
                <td>
                    <div class="flex-cell">
                        <div class="flex-cell__label"><?php echo $actionLabel; ?></div>
                        <div class="flex-cell__content">
                            <a href="javascript:void(0);" onclick="form('<?php echo $question['ques_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                <svg class="icon icon--edit icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#edit'; ?>"></use>
                                </svg>
                                <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_EDIT'); ?></div>
                            </a>   
                            <a href="javascript:void(0);" onclick="remove('<?php echo $question['ques_id']; ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                <svg class="icon icon--issue icon--small">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL.'images/sprite.svg#trash'; ?>"></use>
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
echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmSearchPaging']);
?>