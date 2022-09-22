<?php

/**
 * Questions Controller
 *
 * @package YoCoach
 * @author Fatbit Team
 */

class QuestionsController extends AdminBaseController
{

    /**
     * Initialize Categories
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewQuestions();
    }

    /**
     * Render Search Form
     */
    public function index()
    {
        $frm = $this->getSearchForm();
        $frm->fill(FatApp::getQueryStringData());
        $this->sets([
            "frmSearch" => $frm,
            "canEdit" => $this->objPrivilege->canEditQuestions(true),
            "params" => FatApp::getQueryStringData(),
        ]);
        $this->_template->render();
    }

    /**
     * Search & List Questions
     */
    public function search()
    {
        $form = $this->getSearchForm();
        if (!$post = $form->getFormDataFromArray(FatApp::getPostedData(), ['ques_subcate_id'])) {
            FatUtility::dieJsonError(current($form->getValidationErrors()));
        }
        $srch = new QuestionSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->applySearchConditions($post);
        $srch->applyPrimaryConditions();
        $srch->addMultipleFields(
            [
                'CONCAT(teacher.user_first_name, " ", teacher.user_last_name) as full_name',
                'ques.ques_id',
                'ques.ques_cate_id',
                'ques.ques_subcate_id',
                'ques.ques_title',
                'ques.ques_status',
                'ques.ques_created'
            ]
        );
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['page']);
        $srch->addOrder('ques_status', 'DESC');
        $srch->addOrder('ques_id');
        $data = $srch->fetchAndFormat(); 
        $categoryIds = array_keys($data);
        $this->sets([
            'arrListing' => $data,
            'postedData' => $post,
            'canEdit' => $this->objPrivilege->canEditCategories(true),
            'page' => $post['page'],
            'post' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Render Question View
     *
     * @param int $quesId
     * return html
     */
    public function view($quesId)
    {   
        $srch = new QuestionSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->addCondition('ques_id', '=', $quesId);
        $srch->applyPrimaryConditions();
        $srch->joinTable(Category::DB_LANG_TBL, 'LEFT OUTER JOIN', 'ques.ques_cate_id = catg_l.catelang_cate_id', 'catg_l');
        $srch->addSearchListingFields();
        $data = $srch->fetchAndFormat();
        $questionData = current($data);
        $question = new Question($quesId);
        $options = $question->getQuesOptions();
        $answerIds = json_decode($questionData['ques_answer'], true);
        $this->sets([
            'questionData' => $questionData,
            'options' => $options,
            'answers' => $answerIds,
            'canEdit' => $this->objPrivilege->canEditQuestions(true),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Auto Complete JSON
     */
    public function teacherAutoCompleteJson()
    {
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        if (empty($keyword)) {
            FatUtility::dieJsonSuccess(['data' => []]);
        }
        $srch = new SearchBase(User::DB_TBL, 'teacher');
        $srch->addMultiplefields(['user_id', "CONCAT(teacher.user_first_name, ' ',teacher.user_last_name) as full_name", 'user_email']);
        if (!empty($keyword)) {
            $cond = $srch->addCondition('user_email', 'LIKE', '%'.$keyword.'%');
            $fullname = 'mysql_func_CONCAT(teacher.user_first_name, " ", teacher.user_last_name)';
            $cond->attachCondition($fullname, 'LIKE', '%' . $keyword . '%', 'OR', true);
        }
        $srch->addOrder('full_name', 'ASC');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(20);
        $data = FatApp::getDb()->fetchAll($srch->getResultSet(), 'user_id');
        FatUtility::dieJsonSuccess(['data' => $data]);
    }

    /**
     * Fetch sub categories for selected category
     *
     * @param int $catgId
     * @param int $subCatgId
     * @return html
     */
    public function getSubcategories(int $catgId, int $subCatgId = 0)
    {
        $catgId = FatUtility::int($catgId);
        $subcategories = [];
        if ($catgId > 0) {
            $subcategories = Category::getCategoriesByParentId($this->siteLangId, $catgId, Category::TYPE_QUESTION);
        }
        $this->set('subCatgId', $subCatgId);
        $this->set('subcategories', $subcategories);
        $this->_template->render(false, false);
    }

    /**
     * Get User Search Form
     * 
     * @return Form
     */
    private function getSearchForm(): Form
    {
        $categoryList = Category::getCategoriesByParentId($this->siteLangId, 0, Category::TYPE_QUESTION, false);

        $frm = new Form('frmQuesSearch');
        $frm->addTextBox(Label::getLabel('LBL_TITLE'), 'keyword', '', ['id' => 'keyword', 'autocomplete' => 'off']);
        $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'ques_cate_id', $categoryList);
        $frm->addSelectBox(Label::getLabel('LBL_SUBCATEGORY'), 'ques_subcate_id', []);
        $frm->addTextBox(Label::getLabel('LBL_TEACHER'), 'quesTeacher', '', ['id' => 'quesTeacher', 'autocomplete' => 'off']);
        $frm->addHiddenField('', 'pagesize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'))->requirements()->setIntPositive();
        $frm->addHiddenField('', 'page', 1)->requirements()->setIntPositive();
        $frm->addHiddenField('', 'teacher_id', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_CLEAR'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

}


