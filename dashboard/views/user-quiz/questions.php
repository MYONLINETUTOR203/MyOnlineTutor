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
                    </div>
                    <a href="javascript:void(0);" onclick="start('')" class="btn btn--primary btn--wide">
                        <?php echo Label::getLabel('LBL_START_NOW'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>