<?php

/**
 * This Controller is to manage cancellation requests
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CourseRefundRequestsController extends AdminBaseController
{

    /**
     * Initialize Requests
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewCourseRefundRequests();
    }

    /**
     * Render Search Form
     */
    public function index()
    {
        $this->set("canEdit", $this->objPrivilege->canEditCourseRefundRequests(true));
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

        $srch = new CourseRefundRequestSearch($this->siteLangId, $this->siteAdminId, User::SUPPORT);
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'course.course_user_id = u.user_id', 'u');
        $srch->addSearchListingFields();
        $srch->applySearchConditions($post);
        $srch->setPageNumber($post['page']);
        $srch->setPageSize($post['pagesize']);
        $srch->addOrder('corere_id', 'DESC');
        $data = $srch->fetchAndFormat();

        $this->sets([
            'arrListing' => $data,
            'requestStatus' => Course::getRefundStatuses(),
            'page' => $post['page'],
            'postedData' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
            'canEdit' => $this->objPrivilege->canEditCourseRefundRequests(true),
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

        $srch = new CourseRefundRequestSearch($this->siteLangId, $this->siteAdminId, User::SUPPORT);
        $srch->addSearchListingFields();
        $srch->joinUser();
        $srch->applySearchConditions(['corere_id' => $requestId]);
        if (!$data = FatApp::getDb()->fetch($srch->getResultSet())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

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
        $srch = new SearchBase(Course::DB_TBL_REFUND_REQUEST, 'corere');
        $srch->joinTable(CourseProgress::DB_TBL, 'LEFT JOIN', 'crspro.crspro_ordcrs_id = corere.corere_ordcrs_id', 'crspro');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('IFNULL(crspro_progress, 0) as crspro_progress');
        if (!$data = FatApp::getDb()->fetch($srch->getResultSet())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $frm = $this->getForm();
        $frm->fill(['corere_id' => $requestId]);
        $this->set('frm', $frm);
        $this->set('data', $data);
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
        $frm->addHiddenField('', 'corere_id', 0)->requirements()->setInt();
        $statusList = Course::getRefundStatuses();
        unset($statusList[Course::REFUND_PENDING]);
        $status = $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'corere_status', $statusList, '');
        $status->requirements()->setRequired();

        $frm->addTextArea(Label::getLabel('LBL_COMMENT'), 'corere_remark', '');
        $requiredFld = new FormFieldRequirement('corere_remark', Label::getLabel('LBL_COMMENT'));
        $requiredFld->setRequired(true);

        $notRequiredFld = new FormFieldRequirement('corere_remark', Label::getLabel('LBL_COMMENT'));
        $notRequiredFld->setRequired(false);

        $status->requirements()
        ->addOnChangerequirementUpdate(Course::REFUND_APPROVED, 'eq', 'corere_remark', $notRequiredFld);
        $status->requirements()
        ->addOnChangerequirementUpdate(Course::REFUND_DECLINED, 'eq', 'corere_remark', $requiredFld);

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
        $course = new Course(0, 0, 0, $this->siteLangId);
        if (!$course->updateRefundRequestStatus($post)) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_STATUS_UPDATED_SUCCESSFULLY'));
    }
}
