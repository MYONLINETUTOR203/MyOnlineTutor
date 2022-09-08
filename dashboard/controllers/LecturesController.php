<?php

/**
 * This Controller is used for handling lectures
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class LecturesController extends DashboardController
{

    /**
     * Initialize lectures
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Search Lecture
     *
     * @param int $lectureId
     */
    public function search(int $lectureId)
    {
        /* get lectures list */
            $obj = new LectureSearch();
            $lecture = $obj->getById($lectureId);
            $this->set('lecture', $lecture);

        $this->_template->render(false, false);
    }

    /**
     * Render Lecture Forms
     *
     * @param int $sectionId
     */
    public function form(int $sectionId)
    {
        $sectionId = FatUtility::int($sectionId);
        if ($sectionId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* validate section id */
        if (!Section::getAttributesById($sectionId, 'section_id')) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $lectureId = FatApp::getPostedData('lecture_id', FatUtility::VAR_INT, 0);
        $courseId = FatApp::getPostedData('course_id', FatUtility::VAR_INT, 0);
        $data = [
            'lecture_section_id' => $sectionId,
            'lecture_course_id' => $courseId,
            'lecture_id' => $lectureId,
        ];

        if ($lectureId > 0) {
            $srch = new LectureSearch();
            $srch->applyPrimaryConditions();
            $srch->applySearchConditions([
                'lecture_id' => $lectureId,
                'section_id' => $sectionId
            ]);
            $srch->addSearchListingFields();
            $srch->setPageSize(1);
            $data = FatApp::getDb()->fetch($srch->getResultSet());
        }
        
        /* get form and fill */
        $frm = $this->getForm();
        $frm->fill($data);
        $this->set('frm', $frm);
        
        $lectureDivId = $lectureId;
        if ($lectureId < 1) {
            $lectureDivId = FatApp::getPostedData('lecture_order', FatUtility::VAR_INT, 0) . '1';
            $data['lecture_order'] = '';
        }
        $this->set('lectureDivId', $lectureDivId);
        $this->set('lecture', $data);
        $this->_template->render(false, false);
    }

    /**
     * Get Form
     *
     */
    private function getForm(): Form
    {
        $frm = new Form('frmLecture');
        $frm->addTextBox(Label::getLabel('LBl_TITLE'), 'lecture_title')->requirements()->setRequired();
        $frm->addCheckBox(Label::getLabel('LBl_FOR_PREVIEW'), 'lecture_is_trial', AppConstant::YES, [], false, AppConstant::NO);
        $frm->addHtmlEditor(Label::getLabel('LBl_DESCRIPTION'), 'lecture_details')->requirements()->setRequired();
        $fld = $frm->addHiddenField('', 'lecture_section_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'lecture_course_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'lecture_id')->requirements()->setInt();
        $frm->addButton('', 'btn_cancel', Label::getLabel('LBL_CANCEL'));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }

    /**
     * Setup Lectures data
     *
     * @return json
     */
    public function setup()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if (Course::getAttributesById($post['lecture_course_id'], 'course_user_id') != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }
        if (Section::getAttributesById($post['lecture_section_id'], 'section_course_id') != $post['lecture_course_id']) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_DATA_SENT'));
        }
        if ($post['lecture_id'] > 0) {
            if (Lecture::getAttributesById($post['lecture_id'], 'lecture_section_id') != $post['lecture_section_id']) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_DATA_SENT'));
            }
        }
        $lecture = new Lecture($post['lecture_id']);
        if (!$lecture->setup($post)) {
            FatUtility::dieJsonError($lecture->getError());
        }
        FatUtility::dieJsonSuccess([
            'sectionId' => $post['lecture_section_id'],
            'lectureId' => $lecture->getMainTableRecordId(),
            'msg' => Label::getLabel('LBL_SETUP_SUCCESSFUL')
        ]);
    }

    /**
     * function to delete lecture
     *
     * @param int $lectureId
     */
    public function delete($lectureId)
    {
        $lectureId = FatUtility::int($lectureId);
        if ($lectureId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $lecture = new Lecture($lectureId, $this->siteUserId);
        if (!$lecture->delete()) {
            FatUtility::dieJsonError($lecture->getError());
        }

        FatUtility::dieJsonSuccess(Label::getLabel('LBL_REMOVED_SUCCESSFULLY'));
    }

    /**
     * Render Lecture Media Form
     *
     * @param int $lectureId
     */
    public function mediaForm(int $lectureId)
    {
        $lectureId = FatUtility::int($lectureId);
        if ($lectureId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* validate lecture id */
        $obj = new LectureSearch();
        if (!$lecture = $obj->getById($lectureId, [
            'lecture_title', 'lecture_section_id', 'lecture_order', 'lecture_course_id'
        ])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $obj = new Lecture($lectureId);
        $data = $obj->getMedia(Lecture::TYPE_RESOURCE_EXTERNAL_URL);
        $data['lecsrc_lecture_id'] = $lectureId;
        $data['lecsrc_course_id'] = $lecture['lecture_course_id'];

        /* get form and fill */
        $frm = $this->getMediaForm();
        $frm->fill($data);

        $this->sets([
            'frm' => $frm,
            'lecture' => $lecture,
            'lectureId' => $lectureId,
        ]);

        $this->_template->render(false, false);
    }

    /**
     * Setup Lectures media
     *
     * @return json
     */
    public function setupMedia()
    {
        $frm = $this->getMediaForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if (Course::getAttributesById($post['lecsrc_course_id'], 'course_user_id') != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }
        if (Lecture::getAttributesById($post['lecsrc_lecture_id'], 'lecture_course_id') != $post['lecsrc_course_id']) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_DATA_SENT'));
        }
        if ($post['lecsrc_id'] > 0) {
            $srch = new SearchBase(Lecture::DB_TBL_LECTURE_RESOURCE);
            $srch->addCondition('lecsrc_id', '=', $post['lecsrc_id']);
            $srch->addFld('lecsrc_lecture_id');
            $srch->doNotCalculateRecords();
            $srch->setPageSize(1);
            $resource = FatApp::getDb()->fetch($srch->getResultSet());
            if (!$resource || $resource['lecsrc_lecture_id'] != $post['lecsrc_lecture_id']) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_DATA_SENT'));
            }
        }
        $lecture = new Lecture($post['lecsrc_lecture_id']);
        if (!$lecture->setupMedia($post)) {
            FatUtility::dieJsonError($lecture->getError());
        }
        FatUtility::dieJsonSuccess([
            'lectureId' => $post['lecsrc_lecture_id'],
            'msg' => Label::getLabel('LBL_SETUP_SUCCESSFUL')
        ]);
    }

    /**
     * Updating Lectures sort order
     *
     * @return json
     */
    public function updateOrder()
    {
        $ids = FatApp::getPostedData('order');
        $lecture = new Lecture();
        if (!$lecture->updateOrder($ids)) {
            FatUtility::dieJsonError($lecture->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_ORDER_SETUP_SUCCESSFUL'));
    }

    /**
     * Get Media Form
     *
     * @param int $type
     */
    private function getMediaForm(): Form
    {
        $frm = new Form('frmLectureMedia');
        $fld = $frm->addTextBox(Label::getLabel('LBl_YOUTUBE_URL'), 'lecsrc_link');
        $fld->requirements()->setRequired();
        $fld->requirements()->setRegularExpressionToValidate(AppConstant::INTRODUCTION_VIDEO_LINK_REGEX);
        $fld->requirements()->setCustomErrorMessage(Label::getLabel('MSG_PLEASE_ENTER_VALID_VIDEO_LINK'));
        $fld = $frm->addHiddenField('', 'lecsrc_lecture_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'lecsrc_course_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'lecsrc_id')->requirements()->setInt();
        $frm->addButton('', 'btn_cancel', Label::getLabel('LBL_CANCEL'));
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }
}
