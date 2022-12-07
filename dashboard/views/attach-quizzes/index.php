<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('onsubmit', 'searchQuizzes(this, 1); return(false);');
$frm->setFormTagAttribute('class', 'form form--small');
$keyword = $frm->getField('keyword');
$type = $frm->getField('quiz_type');
$recType = $frm->getField('record_type');
$status = $frm->getField('quiz_status');
$active = $frm->getField('quiz_active');
$btnClear = $frm->getField('btn_clear');
$btnClear->addFieldTagAttribute('onclick', 'clearQuizSearch(1)');
?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6><?php echo Label::getLabel('LBL_ATTACH_QUIZZES'); ?></h6>
            </div>
            <div>
                <div class="buttons-group d-flex align-items-center">
                    <a href="javascript:void(0)" class="btn btn--secondary qsearch-toggle-js margin-1">
                        <svg class="icon icon--clock icon--small margin-right-2">
                            <use xlink:href="/dashboard/images/sprite.svg#search"></use>
                        </svg>
                        <?php echo Label::getLabel('LBL_SEARCH'); ?>
                    </a>
                    <?php if ($recType->value != AppConstant::COURSE) { ?>
                        <a href="javascript:void(0);" onclick="attachQuizzes();" class="btn btn--bordered color-secondary margin-1">
                            <svg class="icon icon--add icon--small margin-right-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"></path>
                            </svg>
                            <?php echo Label::getLabel('LBL_ATTACH'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="qsearch-target-js" style="display:none;">
            <div class="form-search margin-top-6">

                <?php echo $frm->getFormTag(); ?>
                <div class="row">
                    <div class="col-lg-<?php echo ($recType->value != AppConstant::COURSE) ? '4' : '8' ?> col-sm-12">
                        <div class="field-set">
                            <div class="caption-wraper">
                                <label class="field_label">
                                    <?php echo $keyword->getCaption(); ?>
                                    <?php if ($keyword->requirement->isRequired()) { ?>
                                        <span class="spn_must_field">*</span>
                                    <?php } ?>
                                </label>
                            </div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php echo $keyword->getHtml(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($recType->value != AppConstant::COURSE) { ?>
                        <div class="col-lg-4 col-sm-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo $type->getCaption(); ?>
                                        <?php if ($type->requirement->isRequired()) { ?>
                                            <span class="spn_must_field">*</span>
                                        <?php } ?>
                                    </label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <?php echo $type->getHtml(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-lg-4 col-sm-4  form-buttons-group">
                        <div class="field-set">
                            <div class="caption-wraper"><label class="field_label"></label></div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php echo $frm->getFieldHtml('pageno'); ?>
                                    <?php echo $frm->getFieldHtml('pagesize'); ?>
                                    <?php echo $frm->getFieldHtml('record_id'); ?>
                                    <?php echo $frm->getFieldHtml('record_type'); ?>
                                    <?php echo $frm->getFieldHtml('btn_submit'); ?>
                                    <?php echo $frm->getFieldHtml('btn_clear'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <?php echo $frm->getExternalJS(); ?>

            </div>
        </div>
    </div>
    <div class="facebox-panel__body padding-0">
        <div class="table-scroll">
            <?php echo $quizFrm->getFormTag(); ?>
            <table class="table table--responsive table--aligned-middle table--bordered" id="quiz-listing">
                <thead>
                    <tr class="title-row">
                        <?php if ($recType->value != AppConstant::COURSE) { ?>
                            <th></th>
                        <?php } ?>
                        <th><?php echo Label::getLabel('LBL_TITLE'); ?></th>
                        <th><?php echo Label::getLabel('LBL_TYPE'); ?></th>
                        <?php if ($recType->value == AppConstant::COURSE) { ?>
                            <th><?php echo Label::getLabel('LBL_ACTION'); ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            echo $quizFrm->getFieldHtml('quilin_record_id');
            echo $quizFrm->getFieldHtml('quilin_user_id');
            echo $quizFrm->getFieldHtml('quilin_record_type');
            ?>
            </form>
            <?php echo $quizFrm->getExternalJS(); ?>
            <div class="show-more-container loadMoreJs padding-6" style="display:none;">
                <div class="show-more d-flex justify-content-center">
                    <a href="javascript:void(0);" class="btn btn--primary-bordered" data-page="1" onclick="goToQuizPage(this)"><?php echo Label::getLabel('LBL_SHOW_MORE'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".qsearch-toggle-js").click(function() {
        $(".qsearch-target-js").slideToggle();
    });
</script>