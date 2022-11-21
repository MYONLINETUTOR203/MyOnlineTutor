<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSearch->setFormTagAttribute('onsubmit', 'searchQuestions(this); return(false);');
$frmSearch->setFormTagAttribute('class', 'web_form');
$frmSearch->developerTags['colClassPrefix'] = 'col-md-';
$frmSearch->developerTags['fld_default_col'] = 3;
$submitBtn = $frmSearch->getField('btn_submit');
$submitBtn->developerTags['col'] = 6;
$fld = $frmSearch->getField('btn_clear');
$fld->addFieldTagAttribute('onclick', 'clearSearch()');
$catefld = $frmSearch->getField('ques_cate_id');
$catefld->addFieldTagAttribute('onchange', 'getSubcategories(this.value)');

$subcatefld = $frmSearch->getField('ques_subcate_id');
$subcatefld->addFieldTagAttribute('id', 'subCategories')
?>
<div class='page'>
    <div class='fixed_container'>
        <div class="row">
            <div class="space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first">
                            <span class="page__icon">
                                <i class="ion-android-star"></i></span>
                            <h5><?php echo Label::getLabel('LBL_MANAGE_QUESTIONS'); ?> </h5>
                            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                    </div>
                </div>
                <section class="section searchform_filter">
                    <div class="sectionhead">
                        <h4> <?php echo Label::getLabel('LBL_SEARCH'); ?></h4>
                    </div>
                    <div class="sectionbody space togglewrap" style="display:none;">
                        <?php echo $frmSearch->getFormHtml(); ?>
                    </div>
                </section>
                <section class="section">
                    <div class="sectionbody">
                        <div class="tablewrap">
                            <div id="questionListing">
                                <?php echo Label::getLabel('LBL_PROCESSING'); ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var catId = "<?php echo !empty($catefld->value) ? $catefld->value : 0; ?>";
        if (catId > 0) {
            $('.section.searchform_filter .sectionhead').click();
        }
    });
</script>