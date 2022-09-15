<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'searchQuestions(this); return(false);');
$keyword = $frm->getField('keyword');
$keyword->addFieldTagAttribute('placeholder', Label::getLabel('LBL_KEYWORD'));
$cate = $frm->getField('ques_cate_id');
$subcate = $frm->getField('ques_subcate_id');
?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6><?php echo Label::getLabel('LBL_ATTACH_QUESTIONS'); ?></h6>
            </div>
            <div>
                <a href="javascript:void(0);" onclick="uploadResource('frmLectureForm');" class="btn btn--bordered color-secondary">
                    <svg class="icon">
                        <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#plus-more"></use>
                    </svg>
                    <?php echo Label::getLabel('LBL_ATTACH'); ?>
                </a>
            </div>
        </div>
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
    <div class="facebox-panel__body padding-0">
        <?php
        /* $resrcFrm->setFormTagAttribute('id', 'frmLectureForm');
        echo $resrcFrm->getFormTag();
        echo $resrcFrm->getFieldHtml('lecsrc_type');
        echo $resrcFrm->getFieldHtml('lecsrc_lecture_id');
        echo $resrcFrm->getFieldHtml('lecsrc_course_id');
        ?>
        <div class="table-scroll">
            <table class="table table--styled table--responsive" id="listingJs">
                <tr class="title-row">
                    <th></th>
                    <th><?php echo $titleLabel = Label::getLabel('LBL_FILENAME'); ?></th>
                    <th><?php echo $typeLabel = Label::getLabel('LBL_TYPE'); ?></th>
                    <th><?php echo $dateLabel = Label::getLabel('LBL_DATE'); ?></th>
                </tr>
            </table>
            <div class="show-more-container rvwLoadMoreJs padding-6" style="display:none;">
                <div class="show-more d-flex justify-content-center">
                    <a href="javascript:void(0);" class="btn btn--primary-bordered" data-page="1" onclick="resourcePaging(this)"><?php echo Label::getLabel('LBL_SHOW_MORE'); ?></a>
                </div>
            </div>
        </div>
        </form>
        <?php echo $resrcFrm->getExternalJs();  */ ?>
    </div>
</div>