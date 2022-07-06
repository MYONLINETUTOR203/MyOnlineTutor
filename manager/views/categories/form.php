<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');



$fld = $frm->getField('catelang_lang_id');
$fld->setFieldTagAttribute('onchange', 'categoryForm("'.$categoryId.'", this.value)');

?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_CATEGORY_SETUP'); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container responsive flat">
            <div class="tabs_panel_wrap">
                <div class="tabs_panel">
                    <?php echo $frm->getFormHtml(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
