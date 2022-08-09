<?php

/**
 * Courses Requests Controller to manage approval requests
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CourseRequestsController extends AdminBaseController
{

    /**
     * Initialize Course Requests
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewCourseRequests();
    }

    /**
     * Render Search Form
     */
    public function index()
    {
        $this->set("canEdit", $this->objPrivilege->canEditCourseRequests(true));
        $this->set("frmSearch", $this->getSearchForm($this->siteLangId));

        $this->_template->render();
    }

    /**
     * Get Search Form
     *
     * @return Form
     */
    private function getSearchForm(): Form
    {
        $frm = new Form('requestSearch');
        $frm->addTextBox(Label::getLabel('LBL_KEYWORD'), 'keyword', '');
        $frm->addHiddenField('', 'page', 1);
        $frm->addHiddenField('', 'pagesize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'));
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_CLEAR'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    /**
     * Search & Listing
     */
    public function search()
    {
        $form = $this->getSearchForm();
        if (!$post = $form->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($form->getValidationErrors()));
        }

        $srch = new CourseRequestSearch($this->siteLangId, $this->siteAdminId, User::SUPPORT);
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'course.course_user_id = u.user_id', 'u');
        $srch->addSearchListingFields();
        $srch->addOrder('coapre_id', 'DESC');
        $srch->applySearchConditions($post);
        $srch->setPageNumber($post['page']);
        $srch->setPageSize($post['pagesize']);
        $data = $srch->fetchAndFormat();

        $this->sets([
            'arrListing' => $data,
            'requestStatus' => Course::getRequestStatuses(),
            'page' => $post['page'],
            'postedData' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
            'canEdit' => $this->objPrivilege->canEditCourseRequests(true),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * View Request Detail
     *
     * @param int $requestId
     * @return html
     */
    public function view(int $requestId)
    {
        $requestId = FatUtility::int($requestId);
        if ($requestId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $srch = new CourseRequestSearch($this->siteLangId, $this->siteAdminId, User::SUPPORT);
        $srch->addSearchListingFields();
        $srch->joinUser();
        $srch->applySearchConditions(['coapre_id' => $requestId]);
        $data = FatApp::getDb()->fetch($srch->getResultSet());
        $this->sets([
            'requestData' => $data
        ]);

        $this->_template->render(false, false);
    }

    /**
     * Change status form
     *
     * @param int $requestId
     * @return form
     */
    public function form(int $requestId)
    {
        $requestId = FatUtility::int($requestId);
        if ($requestId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $frm = $this->getForm();
        $frm->fill(['coapre_id' => $requestId]);
        $this->set('frm', $frm);

        $this->_template->render(false, false);
    }

    /**
     * Get Search Form
     *
     * @return Form
     */
    private function getForm(): Form
    {
        $frm = new Form('frmStatus');
        $frm->addHiddenField('', 'coapre_id', 0)->requirements()->setInt();
        $statusList = Course::getRequestStatuses();
        unset($statusList[Course::REQUEST_PENDING]);
        $status = $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'coapre_status', $statusList, '');
        $status->requirements()->setRequired();

        $fld = $frm->addTextArea(Label::getLabel('LBL_COMMENT'), 'coapre_remark', '');
        $fld->requirements()->setRequired();
        $requiredFld = new FormFieldRequirement('coapre_remark', Label::getLabel('LBL_COMMENT'));
        $requiredFld->setRequired(true);

        $notRequiredFld = new FormFieldRequirement('coapre_remark', Label::getLabel('LBL_COMMENT'));
        $notRequiredFld->setRequired(false);

        $status->requirements()
        ->addOnChangerequirementUpdate(Course::REQUEST_APPROVED, 'eq', 'coapre_remark', $notRequiredFld);
        $status->requirements()
        ->addOnChangerequirementUpdate(Course::REQUEST_DECLINED, 'eq', 'coapre_remark', $requiredFld);

        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_UPDATE'));
        return $frm;
    }

    /**
     * Update status
     *
     * @return bool
     */
    public function updateStatus()
    {
        $form = $this->getForm();
        if (!$post = $form->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($form->getValidationErrors()));
        }
        $srch = new CourseRequestSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->addSearchListingFields();
        $srch->applySearchConditions(['coapre_id' => $post['coapre_id']]);
        $srch->joinUser();
        $srch->addFld('user_lang_id');
        if (!$requestData = FatApp::getDb()->fetch($srch->getResultSet())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $requestData = array_merge($requestData, $post);
        $course = new Course($requestData['coapre_course_id'], 0, 0, $requestData['user_lang_id']);
        if (!$course->updateRequestStatus($requestData)) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_STATUS_UPDATED_SUCCESSFULLY'));
    }
}
