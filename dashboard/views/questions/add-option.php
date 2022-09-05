<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="row">


    <div class="col-md-6">
        <div class="field-set">
            <div class="caption-wraper">
                <label class="field_label">
                    <?php echo Label::getLabel('LBL_OPTION_TITLE') ?>
                    <span class="spn_must_field">*</span>
                </label>
            </div>
            <div class="field-wraper">
                <div class="field_cover">
                    <input type="text" name="queopt_title[]">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="field-set">
            
            <div class="field-wraper">
                <div class="field_cover">
                    <input type="checkbox" name="queopt_title[]">
                </div>
            </div>
            <div class="field-wraper">
                <div class="field_cover">
                    <button type="button">Remove</button>
                </div>
            </div>

        </div>
    </div>
</div>