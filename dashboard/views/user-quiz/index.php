<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="page-body">
    <div class="container container--narrow">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="box-view box-view--space">
                    <hgroup class="margin-bottom-4">
                        <h4 class="margin-bottom-2">
                            <?php echo Label::getLabel('LBL_QUIZ_SOLVING_INSTRUCTIONS_HEADING'); ?>
                        </h4>
                    </hgroup>
                    <div class="check-list margin-bottom-10">
                        <?php echo CommonHelper::renderHtml($data['quilin_detail']); ?>
                        <div class="-gap-10"></div>
                        <ul class="quiz-detail">
                            <li>
                                <b><?php echo Label::getLabel('LBL_QUIZ_TYPE'); ?> : </b>
                                <?php echo Quiz::getTypes($data['quilin_type']) ?>
                            </li>
                            <li>
                                <b><?php echo Label::getLabel('LBL_TOTAL_NO._OF_QUESTIONS') ?> : </b>
                                <?php echo $data['quilin_questions'] ?>
                            </li>
                            <li>
                                <b><?php echo Label::getLabel('LBL_DURATION'); ?> : </b>
                                <?php echo MyUtility::convertDuration($data['quilin_duration']) ?>
                            </li>
                            <li>
                                <b><?php echo Label::getLabel('LBL_ALLOWED_ATTEMPTS'); ?> : </b>
                                <?php echo $data['quilin_attempts'] ?>
                            </li>
                            <li>
                                <b><?php echo Label::getLabel('LBL_PASS_PERCENT'); ?> : </b>
                                <?php echo MyUtility::formatPercent($data['quilin_passmark']) ?>
                            </li>
                            <li>
                                <b><?php echo Label::getLabel('LBL_VALID_TILL'); ?> : </b>
                                <?php echo MyDate::formatDate($data['quilin_validity'], 'Y-m-d H:i') ?>
                            </li>
                            <?php if ($data['quilin_certificate'] == AppConstant::YES) { ?>
                                <li>
                                    <b><?php echo Label::getLabel('LBL_CERTIFICATE_AVAILABLE'); ?> : </b>
                                    <?php echo AppConstant::getYesNoArr($data['quilin_certificate']) ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <a href="javascript:void(0);" onclick="start('<?php echo $data['quizat_id'] ?>')" class="btn btn--primary btn--wide">
                        <?php echo Label::getLabel('LBL_START_NOW'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>