<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_QUESTIONS'); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="row">
            <div class="col-sm-12">
                <div class=" sectionbody space">
                    <div class="border-box border-box--space">
                        <table class="table table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%"><?php echo Label::getLabel('LBL_TITLE') ?></th>
                                    <th><?php echo Label::getLabel('LBL_TYPE') ?></th>
                                    <th><?php echo Label::getLabel('LBL_CATEGORY') ?></th>
                                    <th><?php echo Label::getLabel('LBL_SUBCATEGORY') ?></th>
                                    <th><?php echo Label::getLabel('LBL_ADDED_ON') ?></th>
                                    <th><?php echo Label::getLabel('LBL_STATUS') ?></th>
                                </tr>
                                <?php if (count($questions) > 0) { ?>
                                    <?php foreach ($questions as $question) { ?>
                                        <tr>
                                            <td><?php echo $question['ques_title'] ?></td>
                                            <td><?php echo Question::getTypes($question['ques_type']) ?></td>
                                            <td><?php echo !empty($question['cate_name']) ? $question['cate_name'] : '-' ?></td>
                                            <td><?php echo !empty($question['subcate_name']) ? $question['subcate_name'] : '-' ?></td>
                                            <td><?php echo MyDate::formatDate($question['ques_created']) ?></td>
                                            <td><?php echo Question::getStatuses($question['ques_status']) ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6"><?php echo Label::getLabel('LBL_NO_QUESTION_FOUND') ?></td>
                                    </tr>
                                <?php } ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>