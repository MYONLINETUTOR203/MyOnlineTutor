<?php

/**
 * Speak Language Controller is used for Speak Language handling
 *  
 * @package YoCoach
 * @author Fatbit Team
 */
class SpeakLanguageController extends AdminBaseController
{

    /**
     * Initialize Speak Language
     * 
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewSpeakLanguage();
    }

    /**
     * Render Search Form
     */
    public function index()
    {
        $this->sets([
            'frmSearch' => $this->getSearchForm(),
            'canEdit' => $this->objPrivilege->canEditSpeakLanguage(true),
        ]);
        $this->_template->render();
    }

    /**
     * Search & List Languages
     */
    public function search()
    {
        $data = FatApp::getPostedData();
        $searchForm = $this->getSearchForm();
        $post = $searchForm->getFormDataFromArray($data);
        $srch = new SearchBase(SpeakLanguage::DB_TBL, 'slang');
        $joinon = 'slanglang.slanglang_slang_id = slang.slang_id AND slanglang.slanglang_lang_id = ' . $this->siteLangId;
        $srch->joinTable(SpeakLanguage::DB_TBL_LANG, 'LEFT JOIN', $joinon, 'slanglang');
        if (!empty($post['keyword'])) {
            $cond = $srch->addCondition('slang_identifier', 'like', '%' . $post['keyword'] . '%');
            $cond->attachCondition('slanglang.slang_name', 'like', '%' . $post['keyword'] . '%');
        }
        $srch->addOrder('slang_order', 'ASC');
        $srch->addOrder('slang_active', 'DESC');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $this->sets([
            'arrListing' => FatApp::getDb()->fetchAll($srch->getResultSet()),
            'canEdit' => $this->objPrivilege->canEditSpeakLanguage(true)
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Render Speak Language Form
     * 
     * @param int $sLangId
     */
    public function form($sLangId = 0)
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $sLangId = FatUtility::int($sLangId);
        $frm = $this->getForm();
        $frm->getField('slang_id')->value = $sLangId;
        if ($sLangId > 0) {
            $data = SpeakLanguage::getAttributesById($sLangId);
            if (empty($data)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
            $frm->fill($data);
        }
        $this->sets(['languages' => Language::getAllNames(), 'sLangId' => $sLangId, 'frm' => $frm]);
        $this->_template->render(false, false);
    }

    /**
     * Setup Speak Lang
     */
    public function setup()
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $sLangId = $post['slang_id'];
        if ($sLangId > 0) {
            $data = SpeakLanguage::getAttributesById($sLangId, ['slang_id']);
            if (empty($data)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
        }
        unset($post['slang_id']);
        $speakLanguage = new SpeakLanguage($sLangId);
        $speakLanguage->assignValues($post);
        if (!$speakLanguage->save()) {
            FatUtility::dieJsonError($speakLanguage->getError());
        }
        if ($post['slang_active'] == AppConstant::NO) {
            $userSpeakLang = new UserSpeakLanguage();
            $userSpeakLang->removeSpeakLang([$sLangId]);
            $teacherStat = new TeacherStat(0);
            $teacherStat->setSpeakLangBulk();
        }
        $data = [
            'msg' => Label::getLabel('LBL_SETUP_SUCCESSFUL'),
            'sLangId' => $speakLanguage->getMainTableRecordId()
        ];
        FatUtility::dieJsonSuccess($data);
    }

    /**
     * Speak Lang Language Form
     * 
     * @param int $sLangId
     * @param int $langId
     */
    public function langForm($sLangId = 0, $langId = 0)
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $sLangId = FatUtility::int($sLangId);
        $langId = FatUtility::int($langId);
        $data = SpeakLanguage::getAttributesById($sLangId, ['slang_id']);
        if (empty($data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $langFrm = $this->getLangForm($langId);
        $languages = $langFrm->getField('slanglang_lang_id')->options;
        if (!array_key_exists($langId, $languages)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $langData = SpeakLanguage::getAttributesByLangId($langId, $sLangId);
        if (empty($langData)) {
            $langData = ['slanglang_lang_id' => $langId, 'slanglang_slang_id' => $sLangId];
        }
        $langFrm->fill($langData);
        $this->sets([
            'languages' => $languages,
            'lang_id' => $langId,
            'sLangId' => $sLangId,
            'langFrm' => $langFrm,
            'formLayout' => Language::getLayoutDirection($langId),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Setup Speak Lang Language Data
     */
    public function langSetup()
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $frm = $this->getLangForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        if (empty(SpeakLanguage::getAttributesById($post['slanglang_slang_id'], ['slang_id']))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $data = [
            'slanglang_lang_id' => $post['slanglang_lang_id'],
            'slanglang_slang_id' => $post['slanglang_slang_id'],
            'slang_name' => $post['slang_name']
        ];
        $speakLanguage = new SpeakLanguage($post['slanglang_slang_id']);
        if (!$speakLanguage->updateLangData($post['slanglang_lang_id'], $data)) {
            FatUtility::dieJsonError($speakLanguage->getError());
        }
        $data = [
            'msg', Label::getLabel('LBL_SETUP_SUCCESSFUL'),
            'sLangId' => $post['slanglang_slang_id']
        ];
        FatUtility::dieJsonSuccess($data);
    }

    /**
     * Change Status
     */
    public function changeStatus()
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $sLangId = FatApp::getPostedData('sLangId', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if (empty(SpeakLanguage::getAttributesById($sLangId, ['slang_id']))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $speakLanguage = new SpeakLanguage($sLangId);
        if (!$speakLanguage->changeStatus($status)) {
            FatUtility::dieJsonError($speakLanguage->getError());
        }
        if ($status == AppConstant::NO) {
            $userSpeakLang = new UserSpeakLanguage();
            $userSpeakLang->removeSpeakLang([$sLangId]);
            $teacherStat = new TeacherStat(0);
            $teacherStat->setSpeakLangBulk();
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_ACTION_PERFORMED_SUCCESSFULLY'));
    }

    /**
     * Delete Record
     */
    public function deleteRecord()
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $sLangId = FatApp::getPostedData('sLangId', FatUtility::VAR_INT, 0);
        if (empty(SpeakLanguage::getAttributesById($sLangId, 'slang_id'))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $speakLanguage = new SpeakLanguage($sLangId);
        if (!$speakLanguage->deleteRecord($sLangId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        (new UserSpeakLanguage())->removeSpeakLang([$sLangId]);
        (new TeacherStat(0))->setSpeakLangBulk();
        (new Afile(Afile::TYPE_SPOKEN_LANGUAGES))->removeFile($sLangId, true);
        (new Afile(Afile::TYPE_FLAG_SPOKEN_LANGUAGES))->removeFile($sLangId, true);
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_RECORD_DELETED_SUCCESSFULLY'));
    }

    /**
     * Get Speak Lang Form
     * 
     * @return Form
     */
    private function getForm(): Form
    {
        $frm = new Form('frmLessonPackage');
        $fld = $frm->addHiddenField('', 'slang_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $frm->addRequiredField(Label::getLabel('LBL_LANGUAGE_IDENTIFIER'), 'slang_identifier');
        $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'slang_active', AppConstant::getActiveArr(), '', [], '');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES'));
        return $frm;
    }

    /**
     * Get Speak Lang Language Form
     * 
     * @param int $langId
     * @return Form
     */
    private function getLangForm(int $langId = 0): Form
    {
        $frm = new Form('frmLessonPackageLang');
        $frm->addHiddenField('', 'slanglang_slang_id');
        $frm->addSelectBox('', 'slanglang_lang_id', Language::getAllNames(), '', [], '');
        $frm->addRequiredField(Label::getLabel('LBL_LANGUAGE_NAME', $langId), 'slang_name');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_CHANGES', $langId));
        return $frm;
    }

    /**
     * Media Form
     * 
     * @param int $sLangId
     */
    public function mediaForm($sLangId)
    {
        $sLangId = FatUtility::int($sLangId);
        if (empty(SpeakLanguage::getAttributesById($sLangId))) {
            FatUtility::dieJsonError(Label::getLabel('MSG_INVALID_REQUEST_OR_INACTIVE_RECORD'));
        }
        $flagImage = new Afile(Afile::TYPE_FLAG_SPOKEN_LANGUAGES);
        $langImage = new Afile(Afile::TYPE_SPOKEN_LANGUAGES);
        $this->sets([
            "sLangId" => $sLangId,
            "canEdit" => $this->objPrivilege->canEditSpeakLanguage($this->siteAdminId, true),
            "mediaFrm" => $this->getMediaForm($sLangId),
            "flagImage" => $flagImage->getFile($sLangId),
            "languages" => Language::getAllNames(),
            "spokenLangImage" => $langImage->getFile($sLangId),
            "flagImageExts" => implode(',', Afile::getAllowedExts(Afile::TYPE_FLAG_SPOKEN_LANGUAGES)),
            "spokenLangImageExts" => implode(', ', Afile::getAllowedExts(Afile::TYPE_SPOKEN_LANGUAGES)),
            "langImgDimensions" => $langImage->getImageSizes(Afile::SIZE_LARGE),
            "flagImageDimensions" => $flagImage->getImageSizes(Afile::SIZE_LARGE),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Get Media Form
     * 
     * @param int $slang_id
     * @return Form
     */
    private function getMediaForm($slang_id): Form
    {
        $frm = new Form('frmSpeakLanguageMedia');
        $frm->addHiddenField('', 'slang_id', $slang_id);
        $frm->addFileUpload('', 'slanguage_image_file');
        $frm->addFileUpload('', 'slanguage_flag_file');
        $frm->addButton(Label::getLabel('LBL_LANGUAGE_IMAGE'), 'slanguage_image', Label::getLabel('LBL_UPLOAD_FILE'), [
            'class' => 'slanguageFile-Js', 'id' => 'slanguage_image', 'data-slang_id' => $slang_id
        ]);
        $frm->addButton(Label::getLabel('LBL_LANGUAGE_FLAG_IMAGE'), 'slanguage_flag_image', Label::getLabel('LBL_UPLOAD_FILE'), [
            'class' => 'slanguageFlagFile-Js', 'id' => 'slanguage_flag_image', 'data-slang_id' => $slang_id
        ]);
        return $frm;
    }

    /**
     * Upload File
     * 
     * @param int $slanguageId
     */
    public function uploadFile($slanguageId = 0)
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $slanguageId = FatUtility::int($slanguageId);
        if (empty(SpeakLanguage::getAttributesById($slanguageId, 'slang_id'))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $type = FatApp::getPostedData('imageType', FatUtility::VAR_INT, Afile::TYPE_SPOKEN_LANGUAGES);
        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_PLEASE_SELECT_A_FILE'));
        }
        $file = new Afile($type);
        if (!$file->saveFile($_FILES['file'], $slanguageId, true)) {
            FatUtility::dieJsonError($file->getError());
        }
        $data = [
            'slanguageId' => $slanguageId,
            'msg' => $_FILES['file']['name'] . Label::getLabel('LBL_FILE_UPLOADED_SUCCESSFULLY')
        ];
        FatUtility::dieJsonSuccess($data);
    }

    /**
     * Remove File
     * 
     * @param int $slanguageId
     * @param int $fileType
     */
    public function removeFile($slanguageId, $fileType)
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $slanguageId = FatUtility::int($slanguageId);
        $fileType = FatUtility::int($fileType);
        if (1 > $fileType) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if (empty(SpeakLanguage::getAttributesById($slanguageId, 'slang_id'))) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $file = new Afile($fileType);
        if (!$file->removeFile($slanguageId, true)) {
            FatUtility::dieJsonError($file->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_DELETED_SUCCESSFULLY'));
    }

    /**
     * Update Order
     */
    public function updateOrder()
    {
        $this->objPrivilege->canEditSpeakLanguage();
        $post = FatApp::getPostedData();
        if (!empty($post)) {
            $spokeLangObj = new SpeakLanguage();
            if (!$spokeLangObj->updateOrder($post['spokenLangages'])) {
                FatUtility::dieJsonError($spokeLangObj->getError());
            }
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_ORDER_UPDATED_SUCCESSFULLY'));
        }
    }

    /**
     * Get Search Form
     * 
     * @return Form
     */
    private function getSearchForm(): Form
    {
        $frm = new Form('frmSpeakLanguageSearch');
        $frm->addTextBox(Label::getLabel('LBL_LANGUAGE_IDENTIFIER'), 'keyword', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_CLEAR'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
}
