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
        $srch->addOrder('ques_id', 'DESC');
        $data = $srch->fetchAndFormat();
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
        $srch->joinTable(
            Category::DB_LANG_TBL,
            'LEFT OUTER JOIN',
            'ques.ques_cate_id = catg_l.catelang_cate_id',
            'catg_l'
        );
        $srch->addSearchListingFields();
        $data = $srch->fetchAndFormat();
        $questionData = current($data);
        $question = new Question($quesId);
        $options = $question->getOptions();
        $answerIds = json_decode($questionData['ques_answer'], true);
        $this->sets([
            'questionData' => $questionData,
            'options' => $options,
            'answers' => $answerIds,
        ]);
        $this->_template->render(false, false);
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
        $frm->addTextBox(Label::getLabel('LBL_TEACHER'), 'teacher', '', ['id' => 'teacher', 'autocomplete' => 'off']);
        $fld = $frm->addHiddenField('', 'pagesize', FatApp::getConfig('CONF_ADMIN_PAGESIZE'));
        $fld->requirements()->setIntPositive();
        $frm->addHiddenField('', 'page', 1)->requirements()->setIntPositive();
        $frm->addHiddenField('', 'teacher_id', '');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $fld_cancel = $frm->addButton("", "btn_clear", Label::getLabel('LBL_CLEAR'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
}
