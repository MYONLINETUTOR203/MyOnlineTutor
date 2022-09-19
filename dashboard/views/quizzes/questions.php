<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$count = count($questions);
?>
<?php echo $this->includeTemplate('quizzes/navigation.php', ['quizId' => $quizId, 'active' => 2, 'isComplete' => ($count > 0) ? true : false]) ?>
<div class="tabs-data">
    <div class="box-panel__container">

        <div class="page">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <b><?php echo ($count > 0) ? Label::getLabel('LBL_TOTAL_QUESTIONS:') . ' ' . $count : ''; ?></b>
                </div>
                <div>
                    <a href="javascript:void(0);" onclick="addQuestions('<?php echo $quizId ?>')" class="btn color-secondary btn--bordered">

                        <svg class="icon icon--uploader icon--small margin-right-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm2-1.645V14h-2v-1.5a1 1 0 0 1 1-1 1.5 1.5 0 1 0-1.471-1.794l-1.962-.393A3.501 3.501 0 1 1 13 13.355z" />
                        </svg>
                        <?php echo Label::getLabel('LBL_ADD_QUESTIONS'); ?>
                    </a>
                </div>
            </div>
            <div class="page__body">
                <div class="table-scroll">
                    <table class="table table--styled table--responsive">
                        <tbody>
                            <tr class="title-row">
                                <th><?php echo $titleLbl = Label::getLabel('LBL_TITLE') ?></th>
                                <th><?php echo $typeLbl = Label::getLabel('LBL_TYPE') ?></th>
                                <th><?php echo $cateLbl = Label::getLabel('LBL_CATEGORY') ?></th>
                                <th><?php echo $subcateLbl = Label::getLabel('LBL_SUB_CATEGORY') ?></th>
                                <th><?php echo $actionLbl = Label::getLabel('LBL_ACTION') ?></th>
                            </tr>
                            <?php if (count($questions) > 0) { ?>
                                <?php foreach ($questions as $question) { ?>
                                    <tr>
                                        <td>
                                            <div class="flex-cell">
                                                <div class="flex-cell__label">
                                                    <?php echo $titleLbl; ?>:
                                                </div>
                                                <div class="flex-cell__content">
                                                    <div class="file-attachment">
                                                        <div class="d-flex">
                                                            <div class="file-attachment__content">
                                                                <p class="margin-bottom-1 bold-600 color-black">
                                                                    <?php echo $question['ques_title']; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-cell">
                                                <div class="flex-cell__label">
                                                    <?php echo $typeLbl; ?>: </div>
                                                <div class="flex-cell__content">
                                                    <div style="max-width: 250px;">
                                                        <?php echo $types[$question['ques_type']]; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-cell">
                                                <div class="flex-cell__label">
                                                    <?php echo $cateLbl; ?>: </div>
                                                <div class="flex-cell__content">
                                                    <div style="max-width: 250px;">
                                                        <?php echo $question['cate_name']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-cell">
                                                <div class="flex-cell__label">
                                                    <?php echo $subcateLbl; ?>: </div>
                                                <div class="flex-cell__content">
                                                    <div style="max-width: 250px;">
                                                        <?php echo $question['subcate_name']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-cell">
                                                <div class="flex-cell__label">
                                                    <?php echo $actionLbl; ?>: </div>
                                                <div class="flex-cell__content">
                                                    <div class="actions-group">
                                                        <a href="javascript:void(0);" onclick="remove('<?php echo $question['quique_quiz_id'] ?>', '<?php echo $question['quique_ques_id'] ?>');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                                            <svg class="icon icon--issue icon--small">
                                                                <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>/images/sprite.svg#trash"></use>
                                                            </svg>
                                                            <div class="tooltip tooltip--top bg-black">
                                                                <?php echo Label::getLabel('LBL_DELETE'); ?>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="field-set margin-bottom-0">
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <a class="btn btn--primary" href=" javascript:void(0);" onclick="settingsForm('<?php echo $count ?>', '<?php echo $quizId ?>');">
                                        <?php echo Label::getLabel('LBL_SAVE_&_NEXT') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>