<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');

?>
<?php echo $this->includeTemplate('quizzes/navigation.php', ['quizId' => $quizId, 'active' => 2]) ?>
<div class="tabs-data">
    <div class="box-panel__container">
        <div class="card-controls-view card-box">
            <div class="page">
                <div class="page__body">
                    <div class="table-scroll">
                        <table class="table table--styled table--responsive">
                            <tbody>
                                <tr class="title-row">
                                    <th><?php echo $titleLbl = Label::getLabel('LBL_TITLE') ?></th>
                                    <th><?php echo $typeLbl = Label::getLabel('LBL_TYPE') ?></th>
                                    <th><?php echo $actionLbl = Label::getLabel('LBL_ACTION') ?></th>
                                </tr>
                                <?php if (count($questions) > 0) { ?>
                                    <?php foreach ($questions as $question) { ?>
                                        <tr>
                                            <td>
                                                <div class="flex-cell">
                                                    <div class="flex-cell__label">
                                                        <?php echo $titleLbl; ?>: </div>
                                                    <div class="flex-cell__content">
                                                        <div class="file-attachment">
                                                            <div class="d-flex">
                                                                <div class="file-attachment__media d-none d-sm-flex">
                                                                    <svg class="attached-media">
                                                                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>/images/sprite.svg#jpg-attachment"></use>
                                                                    </svg>
                                                                </div>
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
                                                        <?php echo $actionLbl; ?>: </div>
                                                    <div class="flex-cell__content">
                                                        <div class="actions-group">
                                                            <a href="javascript:void(0);" onclick="removeLectureResrc('317', '276');" class="btn btn--bordered btn--shadow btn--equal margin-1 is-hover">
                                                                <svg class="icon icon--issue icon--small">
                                                                    <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>/images/sprite.svg#delete-icon"></use>
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
                </div>
            </div>
        </div>
    </div>
</div>