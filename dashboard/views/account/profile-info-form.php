<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$profileFrm->setFormTagAttribute('id', 'profileInfoFrm');
$profileFrm->setFormTagAttribute('class', 'form form--horizontal');
$profileFrm->setFormTagAttribute('onsubmit', 'setupProfileInfo(this, false); return(false);');
$userIdFld = $profileFrm->getField('user_id');
$userIdFld->addFieldTagAttribute('id', 'user_id');
if ($profileFrm->getField('user_username')) {
    $userUsername = $profileFrm->getField('user_username');
    $userUsername->addFieldTagAttribute('onchange', 'formatUrl(this);');
    $userUsername->developerTags['col'] = 12;
    $userUsername->htmlAfterField = '<small class=" user_url_string margin-bottom-0">' . MyUtility::makeFullUrl('teachers', 'view', [], CONF_WEBROOT_FRONT_URL) . '/<b>' . $userUsername->value . '</b></small>';
}
if ($profileFrm->getField('user_book_before')) {
    $profileFrm->getField('user_book_before')->htmlAfterField = "<br><small>" . Label::getLabel("htmlAfterField_booking_before_text") . ".</small>";
}
$profileFrm->developerTags['colClassPrefix'] = 'col-md-';
$profileFrm->developerTags['fld_default_col'] = 6;
$firstNameField = $profileFrm->getField('user_first_name');
$firstNameField->addFieldTagAttribute('placeholder', $firstNameField->getCaption());
$lastNameField = $profileFrm->getField('user_last_name');
$lastNameField->addFieldTagAttribute('placeholder', $lastNameField->getCaption());
$genderField = $profileFrm->getField('user_gender');
$phoneField = $profileFrm->getField('user_phone_number');
$countryField = $profileFrm->getField('user_country_id');
$timeZoneField = $profileFrm->getField('user_timezone');
$bookingBeforeField = $profileFrm->getField('user_book_before');
$freeTrialField = $profileFrm->getField('user_trial_enabled');
$siteLangField = $profileFrm->getField('user_lang_id');
$nextButton = $profileFrm->getField('btn_next');
$nextButton->addFieldTagAttribute('onClick', 'setupProfileInfo(this.form, true); return(false);');
$phoneCode = $profileFrm->getField('user_phone_code');
$phoneCode->addFieldTagAttribute('id', 'user_phone_code');
$userGender = $profileFrm->getField('user_gender');
$userGender->setOptionListTagAttribute('class', 'list-inline list-inline--onehalf');
if ($userRow['user_is_teacher'] == AppConstant::YES) {
    $timeZoneField->htmlAfterField = "<br><small class='color-secondary'>" . Label::getLabel("htmlAfterField_TIMEZONE_TEXT") . ".</small>";
}
if (MyUtility::getLayoutDirection() == 'rtl') {
    $phoneField->addFieldTagAttribute('style', 'direction: ltr;text-align:right;');
}
?>
<div class="content-panel__head">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h5><?php echo Label::getLabel('LBL_Manage_Profile'); ?></h5>
        </div>
    </div>
</div>
<div class="content-panel__body">
    <div class="form" id="langForm">
        <div class="form__body padding-0">
            <nav class="tabs tabs--line padding-left-6 padding-right-6">
                <ul class="tab-ul-js">
                    <li class="is-active"><a href="javascript:void(0)" onclick="profileInfoForm();"><?php echo Label::getLabel('LBL_General'); ?></a></li>
                    <li><a href="javascript:void(0)" onclick="profileImageForm();" class="profile-imag-li"><?php echo Label::getLabel('LBL_Photos_&_Videos'); ?></a></li>

                    <?php
                    if ($siteUserType == User::TEACHER) {
                        foreach ($languages as $langId => $language) {
                            ?>
                            <li class="profile-lang-tab"><a href="javascript:void(0);" class="profile-lang-li" onclick="getLangProfileInfoForm(<?php echo $langId; ?>);"><?php echo $language['language_name']; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
            <div class="tabs-data">
                <div id="profileInfoFrmBlock">
                    <div class="action-bar border-top-0 <?php echo (!$isGoogleAuthSet) ? 'selection-disabled' : ''; ?>">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="action-bar__media margin-right-5">
                                        <div class="g-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31">
                                                <path fill="#fbbb00" d="M6.87,148.63l-1.079,4.028-3.944.083a15.527,15.527,0,0,1-.114-14.474h0l3.511.644,1.538,3.49a9.25,9.25,0,0,0,.087,6.228Z" transform="translate(0 -129.896)" />
                                                <path fill="#518ef8" d="M276.516,208.176a15.494,15.494,0,0,1-5.525,14.983h0l-4.423-.226-.626-3.907a9.238,9.238,0,0,0,3.975-4.717h-8.288v-6.132h14.888Z" transform="translate(-245.787 -195.572)" />
                                                <path fill="#28b446" d="M53.865,318.262h0a15.5,15.5,0,0,1-23.356-4.742l5.023-4.112a9.219,9.219,0,0,0,13.284,4.72Z" transform="translate(-28.662 -290.675)" />
                                                <path fill="#f14336" d="M52.285,3.568,47.263,7.679a9.217,9.217,0,0,0-13.589,4.826L28.625,8.372h0a15.5,15.5,0,0,1,23.661-4.8Z" transform="translate(-26.891)" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="action-bar__content">
                                        <p class="margin-bottom-0"><?php echo Label::getLabel('LBL_TO_SYNC_WITH_GOOGLE_CALENDAR'); ?></p>
                                        <?php if (!$isGoogleAuthSet) { ?>
                                            <p class="margin-bottom-0 color-secondary"><?php echo Label::getLabel('LBL_GOOGLE_CALENDAR_NOT_ACTIVE_YET'); ?></p>
                                        <?php } else { ?>
                                            <p class="margin-bottom-0 color-secondary"><?php echo (empty($accessToken)) ? Label::getLabel('LBL_YOUR_GOOGLE_CALENDAR_NOT_SYNC') : Label::getLabel('LBL_YOUR_GOOGLE_CALENDAR_ALREADY_SYNCED'); ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <a onclick="googleCalendarAuthorize();" href="javascript:void(0);" class="btn social-button social-button--google">
                                    <span class="social-button__media"><img src="<?php echo CONF_WEBROOT_URL . 'images/google.svg'; ?>" alt="<?php echo Label::getLabel('LBL_CONNECT_GOOGLE_CALENDAR'); ?>"></span>
                                    <span class="social-button__label"><?php echo Label::getLabel('LBL_CONNECT_GOOGLE_CALENDAR'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="padding-6">
                        <div class="max-width-80">
                            <?php
                            echo $profileFrm->getFormTag();
                            if ($profileFrm->getField('user_id')) {
                                echo $profileFrm->getFieldHtml('user_id');
                            }
                            ?>
                            <?php if (isset($userUsername)) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label">
                                                    <?php echo $userUsername->getCaption(); ?>
                                                    <?php if ($userUsername->requirement->isRequired()) { ?>
                                                        <span class="spn_must_field">*</span>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <?php echo $userUsername->getHTML(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"><?php echo Label::getLabel('LBL_Name'); ?>
                                                <?php if ($firstNameField->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <div class="custom-cols custom-cols--onehal">
                                                    <ul>
                                                        <li><?php echo $firstNameField->getHTML('user_first_name'); ?></li>
                                                        <li><?php echo $lastNameField->getHTML('user_last_name'); ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"><?php echo $genderField->getCaption(); ?>
                                                <?php if ($genderField->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <div class="custom-cols custom-cols--onehal">
                                                    <ul class="list-inline list-inline--onehalf">
                                                        <?php foreach ($genderField->options as $id => $name) { ?>
                                                            <li class="<?php echo ($genderField->value == $id) ? 'is-active' : ''; ?>"><label><span class="radio"><input type="radio" name="<?php echo $genderField->getName(); ?>" value="<?php echo $id; ?>" <?php echo ($genderField->value == $id) ? 'checked' : ''; ?>><i class="input-helper"></i></span><?php echo $name; ?></label></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"> <?php echo $countryField->getCaption(); ?>
                                                <?php if ($countryField->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover custom-select-search">
                                                <?php echo $countryField->getHTML(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"><?php echo Label::getLabel('LBL_PHONE'); ?>
                                                <?php if ($phoneField->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <div class="custom-cols custom-cols--onehal">
                                                    <ul>
                                                        <li class="custom-select-search"><?php echo $phoneCode->getHTML(); ?></li>
                                                        <li><?php echo $phoneField->getHTML(); ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"> <?php echo $timeZoneField->getCaption(); ?>
                                                <?php if ($timeZoneField->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $timeZoneField->getHTML(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($bookingBeforeField) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label"> <?php echo $bookingBeforeField->getCaption(); ?>
                                                    <?php if ($bookingBeforeField->requirement->isRequired()) { ?>
                                                        <span class="spn_must_field">*</span>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <?php echo $bookingBeforeField->getHTML(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label"> <?php echo $siteLangField->getCaption(); ?>
                                                <?php if ($siteLangField->requirement->isRequired()) { ?>
                                                    <span class="spn_must_field">*</span>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $siteLangField->getHTML(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($freeTrialField) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label"> <?php echo $freeTrialField->getCaption(); ?>
                                                    <?php if ($freeTrialField->requirement->isRequired()) { ?>
                                                        <span class="spn_must_field">*</span>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <label class="switch-group d-flex align-items-center justify-content-between">
                                                        <span class="switch-group__label free-trial-status-js"><?php echo ($freeTrialField->checked) ? Label::getLabel('LBL_Active') : Label::getLabel('LBL_In-active'); ?></span>
                                                        <span class="switch switch--small">
                                                            <input class="switch__label" type="<?php echo $freeTrialField->fldType; ?>" name="<?php echo $freeTrialField->getName(); ?>" value="<?php echo $freeTrialField->value; ?>" <?php echo ($freeTrialField->checked) ? 'checked' : ''; ?>>
                                                                <i class="switch__handle bg-green"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row submit-row">
                                <div class="col-sm-auto">
                                    <div class="field-set">
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $profileFrm->getFieldHtml('btn_submit'); ?>
                                                <?php echo $profileFrm->getFieldHtml('btn_next'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <?php echo $profileFrm->getExternalJS(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var statusActive = '<?php echo Label::getLabel('LBL_Active'); ?>';
    var statusInActive = '<?php echo Label::getLabel('LBL_In-active'); ?>';


    $(document).ready(function () {
        $("[name='user_timezone'], [name='user_country_id'], [name='user_phone_code']").select2();
        $("[name='user_country_id']").on('change', function () {
            $("[name='user_phone_code']").val($("[name='user_country_id']").val());
            $("[name='user_phone_code']").css({width: '100%'});
            $("[name='user_phone_code']").next().remove();
            $("[name='user_phone_code']").select2();
        });
        $('input[name="user_username"]').on('keypress', function (e) {
            if (e.which == 32) {
                return false;
            }
        });
        $('input[name="user_username"]').on('change', function (e) {
            var user_name = $(this).val();
            user_name = user_name.replace(/ /g, "");
            $(this).val(user_name);
            $('.user_username_span').html(user_name);
        });
        $('input[name="user_username"]').on('keyup', function () {
            var user_name = $(this).val();
            $('.user_username_span').html(user_name);
        });
        $('input[name="user_trial_enabled"]').on('change', function () {
            let status = ($(this).is(':checked')) ? statusActive : statusInActive;
            $('.free-trial-status-js').text(status);
        });
    });
</script>