<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$frm->setFormTagAttribute('onsubmit', 'notesSearch(this); return(false);');
$keywordFld = $frm->getField('keyword');
$keywordFld->addFieldTagAttribute('placeholder', Label::getLabel('LBL_SEARCH_BY_KEYWORD'));
$keywordFld->addFieldTagAttribute('id', 'notesKeywordJs');
$btnReset = $frm->getField('btn_reset');
$btnReset->addFieldTagAttribute('onclick', 'clearNotesSearch()');
?>
<div class="col-lg-8">
    <div class="notes-container">
        <div class="notes-container__head notesHeadJs">
            <div class="search-view">
                <div class="search-view__large">
                    <div class="form-search">
                        <?php echo $frm->getFormTag(); ?>
                        <div class="form-search__field">
                            <?php echo $keywordFld->getHtml(); ?>
                        </div>
                        <div class="form-search__action form-search__action--submit">
                            <?php echo $frm->getFieldHtml('btn_submit'); ?>
                            <span class="btn btn--equal btn--transparent color-black">
                                <svg class="icon icon--search icon--small">
                                    <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#search"></use>
                                </svg>
                            </span>
                        </div>
                        <div class="form-search__action form-search__action--reset" style="display:none;">
                            <?php echo $btnReset->getHtml(); ?>
                            <span class="form-reset"></span>
                        </div>
                        <?php
                        echo $frm->getFieldHtml('course_id');
                        echo $frm->getFieldHtml('pagesize');
                        echo $frm->getFieldHtml('page');
                        ?>
                        </form>
                    </div>
                </div>
                <div class="search-view__small">
                    <a href="javascript:void(0);" onclick="notesForm(0);" class="btn btn--secondary">
                        <svg class="icon">
                            <use xlink:href="<?php echo CONF_WEBROOT_DASHBOARD ?>images/sprite.svg#plus-more"></use>
                        </svg>
                        <?php echo Label::getLabel('LBL_ADD_NEW_NOTE'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="notes-container__body">
            <div class="notes-listing notesListingJs"></div>
        </div>
    </div>
</div>