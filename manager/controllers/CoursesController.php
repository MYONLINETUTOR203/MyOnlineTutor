<?php

/**
 * Courses Controller is used for course handling
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CoursesController extends AdminBaseController
{

    /**
     * Initialize Courses
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewCourses();
    }

    /**
     * Render Search Form
     */
    public function index()
    {
        $frm = $this->getSearchForm();
        $this->set('srchFrm', $frm);
        $this->_template->render();
    }

    /**
     * Search & List
     */
    public function search()
    {
        $frm = $this->getSearchForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        
        $srch = new CourseSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->applySearchConditions($post);
        $srch->applyPrimaryConditions();
        $srch->joinTable(
            Course::DB_TBL_APPROVAL_REQUEST,
            'INNER JOIN',
            'course.course_id = coapre.coapre_course_id',
            'coapre'
        );
        $srch->addSearchListingFields();
        $srch->addCondition('coapre.coapre_status', '=', Course::REQUEST_APPROVED);
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['page']);
        $orders = $srch->fetchAndFormat();
        $this->sets([
            'arrListing' => $orders,
            'page' => $post['page'],
            'post' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Render Course View
     *
     * @param int $courseId
     * return html
     */
    public function view(int $courseId)
    {
        $srch = new CourseSearch($this->siteLangId, 0, User::SUPPORT);

        $srch->addCondition('course.course_id', '=', $courseId);
        $srch->applyPrimaryConditions();

        $srch->joinTable(Category::DB_TBL, 'INNER JOIN', 'subcate.cate_id = course.course_subcate_id', 'subcate');
        $srch->joinTable(
            Category::DB_LANG_TBL,
            'INNER JOIN',
            'subcate.cate_id = subcatelang.catelang_cate_id AND subcatelang.catelang_lang_id = ' . $this->siteLangId,
            'subcatelang'
        );
        
        $srch->addSearchListingFields();
        $srch->addFld('subcatelang.cate_name AS subcate_name');
        $courses = $srch->fetchAndFormat();
        if (empty($courses)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = current($courses);
        $this->sets([
            'courseData' => $course,
            'canEdit' => $this->objPrivilege->canEditCourses(true),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Get Search Form
     *
     * @return \Form
     */
    private function getSearchForm(): Form
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(
            Label::getLabel('LBL_KEYWORD'),
            'keyword',
            '',
            ['placeholder' => Label::getLabel('LBL_SEARCH_BY_KEYWORD')]
        );
        $frm->addTextBox(
            Label::getLabel('LBL_LANGUAGE'),
            'course_clang',
            '',
            ['id' => 'course_clang_id', 'autocomplete' => 'off']
        );
        $categoryList = Category::getCategoriesByParentId($this->siteLangId);
        $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'course_cate_id', $categoryList);
        $frm->addHiddenField('', 'course_clang_id', '', ['id' => 'course_clang_id', 'autocomplete' => 'off']);
        $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'course_status', Course::getStatuses())
        ->requirements()
        ->setIntPositive();
        $frm->addDateField(Label::getLabel('LBL_DATE_FROM'), 'course_addedon_from', '', ['readonly' => 'readonly']);
        $frm->addDateField(Label::getLabel('LBL_DATE_TO'), 'course_addedon_till', '', ['readonly' => 'readonly']);
        $frm->addHiddenField('', 'pagesize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'))->requirements()->setIntPositive();
        $frm->addHiddenField('', 'page', 1)->requirements()->setIntPositive();
        $frm->addHiddenField('', 'order_id');
        $btnSubmit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search'));
        $btnSubmit->attachField($frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_Clear')));
        return $frm;
    }
}
