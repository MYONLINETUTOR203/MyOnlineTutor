<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$mediaFrm->setFormTagAttribute('class', 'web_form form_horizontal');
$mediaFrm->developerTags['colClassPrefix'] = 'col-md-';
$mediaFrm->developerTags['fld_default_col'] = 12;
$fld1 = $mediaFrm->getField('slanguage_image');
$fld1->addFieldTagAttribute('class', 'btn btn--primary btn--sm');
$imageFile = $mediaFrm->getField('slanguage_image_file');
$imageFile->addFieldTagAttribute('class', 'hide slanguage_image_file');
$imageFile->addFieldTagAttribute('onChange', 'uploadImage(this, ' . $sLangId . ', ' . Afile::TYPE_SPOKEN_LANGUAGES . ')');
$imageFlagFile = $mediaFrm->getField('slanguage_flag_file');
$imageFlagFile->addFieldTagAttribute('class', 'hide slanguage_flag_file');
$imageFlagFile->addFieldTagAttribute('onChange', 'uploadImage(this, ' . $sLangId . ', ' . Afile::TYPE_FLAG_SPOKEN_LANGUAGES . ')');
$extensionLabel = Label::getLabel('LBL_ALLOWED_FILE_EXTS_{extension}');
$demensionLabel = Label::getLabel('LBL_PREFERRED_DIMENSIONS_ARE_WIDTH_{width}_&_HEIGHT_{height}');
$preferredDimensionsStr = '<span class="uploadimage--info" >' . str_replace(['{width}', '{height}'], ['350px', '263px'], $demensionLabel) . '</span>';
$preferredDimensionsStr .= '<span class="uploadimage--info" >' . str_replace('{extension}', $spokenLangImageExts, $extensionLabel) . '</span>';
$htmlAfterField = $preferredDimensionsStr;
$htmlAfterField .= '<div id="image-listing"><ul class="grids--onethird" id="sortable">';
if (!empty($spokenLangImage)) {
    $htmlAfterField .= '<li class="spoken-lang-js"><div class="logoWrap"><div class="logothumb">';
    $htmlAfterField .= '<img class="spoken-lang-img-js" src="' . MyUtility::makeUrl('image', 'show', [Afile::TYPE_SPOKEN_LANGUAGES, $spokenLangImage['file_record_id'], Afile::SIZE_SMALL], CONF_WEBROOT_FRONT_URL) . '?' . time() . '" title="' . $spokenLangImage['file_name'] . '" alt="' . $spokenLangImage['file_name'] . '"> ';
    if ($canEdit) {
        $htmlAfterField .= '<a class="deleteLink white" href="javascript:void(0);" onclick="removeFile(' . $sLangId . ', ' . Afile::TYPE_SPOKEN_LANGUAGES . ');" class="delete">
		<i class="ion-close-round"></i>
				</a>';
    }
    $htmlAfterField .= '</div><small class=""><strong>' . Label::getLabel('LBL_LANGUAGE') . ':</strong>' . Label::getLabel('LBL_ALL') . '</small></div></li>';
}
$htmlAfterField .= '</ul></div>';
$fld1->htmlAfterField = $htmlAfterField;
$fld1 = $mediaFrm->getField('slanguage_flag_image');
if ($fld1) {
    $fld1->addFieldTagAttribute('class', 'btn btn--primary btn--sm');
    $preferredDimensionsStr = '<span class="uploadimage--info" >' . str_replace(['{width}', '{height}'], ['150px', '150px'], $demensionLabel) . '</span>';
    $preferredDimensionsStr .= '<span class="uploadimage--info" >' . str_replace('{extension}', $flagImageExts, $extensionLabel) . '</span>';
    $htmlAfterField = $preferredDimensionsStr;
    $htmlAfterField .= '<div id="flag-image-listing"><ul class="grids--onethird">';
    if (!empty($flagImage)) {
        $htmlAfterField .= '<li class="spoken-lang-flag-js"><div class="logoWrap"><div class="logothumb">';
        $htmlAfterField .= '<img class="spoken-lang-flag-img-js" src="' . MyUtility::makeUrl('image', 'show', [Afile::TYPE_FLAG_SPOKEN_LANGUAGES, $flagImage['file_record_id'], Afile::SIZE_SMALL], CONF_WEBROOT_FRONT_URL) . '?' . time() . '" title="' . $flagImage['file_name'] . '" alt="' . $flagImage['file_name'] . '"> ';
        if ($canEdit) {
            $htmlAfterField .= '<a class="deleteLink white" href="javascript:void(0);" onclick="removeFile(' . $sLangId . ',' . Afile::TYPE_FLAG_SPOKEN_LANGUAGES . ');" class="delete">
			<i class="ion-close-round"></i>
					</a>';
        }
        $htmlAfterField .= '</div><small class=""><strong>' . Label::getLabel('LBL_LANGUAGE') . ':</strong>' . Label::getLabel('LBL_ALL') . '</small></div></li>';
    }
    $htmlAfterField .= '</ul></div>';
    $fld1->htmlAfterField = $htmlAfterField;
}
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Label::getLabel('LBL_LANGUAGE_IMAGE'); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="row">
            <div class="col-sm-12">
                <div class="tabs_nav_container responsive flat">
                    <ul class="tabs_nav">
                        <li><a href="javascript:void(0);" onclick="form(<?php echo $sLangId; ?>);"><?php echo Label::getLabel('LBL_GENERAL'); ?></a></li>
                        <?php foreach ($languages as $langId => $langName) { ?>
                            <li><a href="javascript:void(0);" onclick="langForm(<?php echo $sLangId ?>, <?php echo $langId; ?>);"><?php echo $langName; ?></a></li>
                        <?php } ?>
                        <li><a class="active" href="javascript:void(0)" onclick="mediaForm(<?php echo $sLangId ?>);"><?php echo Label::getLabel('LBL_MEDIA'); ?></a></li>
                    </ul>
                    <div class="tabs_panel_wrap">
                        <div class="tabs_panel">
                            <?php echo $mediaFrm->getFormHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>