<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'searchQuestions(this); return(false);');
$keyword = $frm->getField('keyword');
$keyword->addFieldTagAttribute('placeholder', Label::getLabel('LBL_KEYWORD'));
$cate = $frm->getField('ques_cate_id');
$cate->addFieldTagAttribute('onchange', 'getSubcategories(this.value, "#quesSubCateJs");');
$subcate = $frm->getField('ques_subcate_id');
$subcate->addFieldTagAttribute('id', 'quesSubCateJs');
$btnclear = $frm->getField('btn_clear');
$btnclear->addFieldTagAttribute('onclick', 'clearSearch();');
?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6><?php echo Label::getLabel('LBL_ATTACH_QUESTIONS'); ?></h6>
            </div>
            <div>


                <div class="buttons-group d-flex align-items-center">
                    <a href="javascript:void(0)" class="btn btn--secondary qsearch-toggle-js margin-1">
                        <svg class="icon icon--clock icon--small margin-right-2">
                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>/images/sprite.svg#search"></use>
                        </svg>
                        <?php echo Label::getLabel('LBL_SEARCH'); ?>
                    </a>

                    <a href="javascript:void(0);" onclick="attachQuestions();" class="btn btn--bordered color-secondary margin-1">
                        <svg class="icon icon--add icon--small margin-right-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M11 11V7h2v4h4v2h-4v4h-2v-4H7v-2h4zm1 11C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"></path>
                        </svg>
                        <?php echo Label::getLabel('LBL_ATTACH'); ?>
                    </a>
                </div>

            </div>
        </div>
        <div class="qsearch-target-js" style="display:none;">
            <div class="form-search margin-top-6">
                <?php echo $frm->getFormTag(); ?>
                <div class="form-search__field">
                    <div class="row">
                        <div class="col-lg-3 col-sm-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo $keyword->getCaption(); ?>
                                    </label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <?php echo $keyword->getHtml(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo $cate->getCaption(); ?>
                                    </label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <?php echo $cate->getHtml(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                        <?php echo $subcate->getCaption(); ?>
                                    </label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <?php echo $subcate->getHtml(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label"></label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('btn_submit'); ?>
                                        <?php echo $btnclear->getHtml(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                echo $frm->getFieldHtml('quiz_id');
                echo $frm->getFieldHtml('pagesize');
                echo $frm->getFieldHtml('pageno');
                ?>
                </form>
                <?php echo $frm->getExternalJs(); ?>
            </div>
        </div>
        <div class="note note--secondary">
            <svg class="icon icon--explanation">
                <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND ?>/images/sprite.svg#explanation "></use>
            </svg>
            <p>
                <b><?php echo Label::getLabel('LBL_NOTE:') ?></b>
                <?php echo Label::getLabel("LBL_DIDN'T_FIND_QUESTION?_CLICK_TO");?>
                <a href="javascript:void(0);" class="underline" onclick="questionForm(0, '<?php echo $quizType ?>');">
                    <?php echo Label::getLabel("LBL_ADD_NEW"); ?>
                </a>
            </p>
        </div>
    </div>
    <div class="facebox-panel__body padding-0">
        <div class="table-scroll">
            <?php
            echo $quesFrm->getFormTag();
            ?>
            <table class="table table--responsive table--bordered">
                <thead>
                    <tr class="title-row">
                        <th></th>
                        <th><?php echo $titleLbl = Label::getLabel('LBL_TITLE') ?></th>
                        <th><?php echo $typeLbl = Label::getLabel('LBL_TYPE') ?></th>
                        <th><?php echo $cateLbl = Label::getLabel('LBL_CATEGORY') ?></th>
                        <th><?php echo $subcateLbl = Label::getLabel('LBL_SUB_CATEGORY') ?></th>
                    </tr>
                </thead>
                <tbody id="listingJs"></tbody>
            </table>
            <?php echo $quesFrm->getFieldHtml('quiz_id'); ?>
            </form>
            <?php echo $quesFrm->getExternalJs(); ?>
            <div class="show-more-container loadMoreJs padding-6" style="display:none;">
                <div class="show-more d-flex justify-content-center">
                    <a href="javascript:void(0);" class="btn btn--primary-bordered" data-page="1" onclick="goToPage(this)"><?php echo Label::getLabel('LBL_SHOW_MORE'); ?></a>
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