<?php

/**
 * This Controller is used for handling course sections
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class SectionsController extends DashboardController
{

    /**
     * Initialize sections
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Render Search Form
     *
     */
    public function index()
    {
        FatUtility::dieWithError(Label::getLabel('LBL_INVALID_REQUEST'));
    }

    /**
     * Search Sections
     *
     * @param int $courseId
     * @param int $langId
     */
    public function search(int $courseId, int $langId)
    {
        $sectionId = FatApp::getPostedData('section_id', FatUtility::VAR_INT, 0);

        $srch = new SectionSearch($langId, $this->siteUserId, User::TEACHER);
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        if ($sectionId > 0) {
            $srch->addCondition('section.section_id', '=', $sectionId);
        }
        $srch->addCondition('section.section_course_id', '=', $courseId);
        
        $srch->addOrder('section.section_order', 'ASC');
        $srch->doNotLimitRecords();
        $sections = $srch->fetchAndFormat();
        $this->set('sectionsList', $sections);

        $this->_template->render(false, false);
    }

    /**
     * Render Section Forms
     *
     * @param int $courseId
     * @param int $langId
     */
    public function form(int $courseId, int $langId)
    {
        $courseId = FatUtility::int($courseId);
        $langId = FatUtility::int($langId);
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* validate course id */
        if (!Course::getAttributesById($courseId, 'course_id')) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $sectionId = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);

        $section = [];
        $formData = [
            'section_course_id' => $courseId,
            'seclang_lang_id' => $langId,
            'section_id' => $sectionId,
        ];
        if ($sectionId > 0) {
            $srch = new SectionSearch($langId, $this->siteUserId, User::TEACHER);
            $srch->applyPrimaryConditions();
            $srch->applySearchConditions([
                'seclang_section_id' => $sectionId,
                'seclang_lang_id' => $langId,
                'course_id' => $courseId
            ]);
            $srch->addSearchListingFields();
            $srch->setPageSize(1);
            if ($section = FatApp::getDb()->fetch($srch->getResultSet())) {
                $formData = $formData + $section;
            }
        }
        
        /* get form and fill */
        $frm = $this->getForm();
        $frm->fill($formData);
        $this->set('frm', $frm);
        
        $this->set('order', FatApp::getPostedData('section_order', FatUtility::VAR_INT, 1));
        $this->set('sectionId', $sectionId);
        $this->_template->render(false, false);
    }

    

    /**
     * Get Form
     *
     */
    private function getForm(): Form
    {
        $frm = new Form('frmSection');

        $frm->addTextBox(Label::getLabel('LBl_TITLE'), 'section_title')->requirements()->setRequired();
        $frm->addTextArea(Label::getLabel('LBl_DESCRIPTION'), 'section_details')->requirements()->setRequired();

        $frm->addHiddenField('', 'section_id')->requirements()->setInt();
        $frm->addHiddenField('', 'section_course_id')->requirements()->setInt();
        $frm->addHiddenField('', 'seclang_lang_id')->requirements()->setInt();
        $frm->addHiddenField('', 'seclang_id')->requirements()->setInt();
        
        $frm->addButton('', 'btn_cancel', Label::getLabel('LBL_CANCEL'));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }

    /**
     * Setup sections data
     *
     * @return json
     */
    public function setup()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        
        $section = new Section($post['section_id']);
        if (!$section->setup($post)) {
            FatUtility::dieJsonError($section->getError());
        }
        
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_SETUP_SUCCESSFUL'));
    }

    /**
     * Updating Sections sort order
     *
     * @return json
     */
    public function updateOrder()
    {
        $ids = FatApp::getPostedData('order');
        $section = new Section();
        if (!$section->updateOrder($ids)) {
            FatUtility::dieJsonError($section->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_ORDER_SETUP_SUCCESSFUL'));
    }

    /**
     * function to delete section
     *
     * @param int $sectionId
     */
    public function delete($sectionId)
    {
        $sectionId = FatUtility::int($sectionId);
        if ($sectionId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $section = new Section($sectionId);
        if (!$section->delete()) {
            FatUtility::dieJsonError($section->getError());
        }

        FatUtility::dieJsonSuccess(Label::getLabel('LBL_REMOVED_SUCCESSFULLY'));
    }
}
