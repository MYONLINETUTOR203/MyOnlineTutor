<?php

/**
 * Questions Controller is used for handling Question Bank
 *  
 * @package YoCoach
 * @author Fatbit Team
 */
class QuestionsController extends DashboardController
{
    /**
     * Initialize Questions
     * 
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Render Questions Search Form
     */
    public function index()
    {
        $frm = QuestionSearch::getSearchForm($this->siteLangId);
        $this->set('frm', $frm);
        $this->_template->render();
    }

    /**
     * Search & List Questions
     */
    public function search()
    { 
        $form = QuestionSearch::getSearchForm($this->siteLangId);
       
        if (!$post = $form->getFormDataFromArray(FatApp::getPostedData(), ['ques_subcate_id'])) {
            
            FatUtility::dieJsonError(current($form->getValidationErrors()));
        }
      
        $post['teacher_id'] = $this->siteUserId;
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
            ]
        );
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['pageno']);
        $srch->addOrder('ques_status', 'DESC');
        $srch->addOrder('ques_id');
        $data = $srch->fetchAndFormat(); 
        $categoryIds = array_keys($data);
        $this->sets([
            'questions' => $data,
            'postedData' => $post,
            'page' => $post['pageno'],
            'post' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Remove Flashcard
     */
    public function remove()
    {
        $quesId = FatApp::getPostedData('quesId', FatUtility::VAR_INT, 0);
        $userId = Question::getAttributesById($quesId, 'ques_user_id');
        if ($userId != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $question = new Question($quesId);
        if (!$question->delete()) {
            FatUtility::dieJsonError($question->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_DELETED_SUCCESSFULLY!'));
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
     * Render add new question form
     *
     * @return html
     */
    public function addForm()
    {
        $frm = $this->getForm();
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }


    public function addOption()
    {
         $this->_template->render(false, false);
    }


    private function getForm()
    {
        $categoryList = Category::getCategoriesByParentId($this->siteLangId, 0, Category::TYPE_QUESTION, false);
        $form = new Form('frmQuestion');
        $form->addHiddenField('', 'ques_id')->requirements()->setIntPositive();
        $fld = $form->addSelectBox(Label::getLabel('LBL_TYPE'), 'ques_type', Question::getQuesTypes());
        $fld->requirements()->setRequired(true);
        $fld = $form->addRequiredField(Label::getLabel('LBL_TITLE'), 'ques_title');
        $fld->requirements()->setLength(10, 100);
        $fld = $form->addTextArea(Label::getLabel('LBL_DESCRIPTION'), 'ques_detail');
        $fld->requirements()->setRequired(true);
        $fld->requirements()->setLength(10, 1000);
        $fld = $form->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'ques_cate_id', $categoryList);
        $fld->requirements()->setRequired(true);
        $fld = $form->addSelectBox(Label::getLabel('LBL_SUBCATEGORY'), 'ques_subcate_id', []);
        $fld = $form->addTextBox(Label::getLabel('LBL_HINT'), 'ques_hint');
        $fld = $form->addIntegerField(Label::getLabel('LBL_MARKS'), 'ques_marks');
        $fld->requirements()->setRequired(true);
        //$fld->requirements()->setIntPositive();
        // $form->addRequiredField(Label::getLabel('LBL_CLASS_TITLE'), 'title[]')->requirements()->setLength(10, 100);
        // $starttime = $form->addRequiredField(Label::getLabel('LBL_START_TIME'), 'starttime[]', '', ['class' => 'datetime', 'autocomplete' => 'off', 'readonly' => 'readonly']);
        // $starttime->requirements()->setRegularExpressionToValidate(AppConstant::DATE_TIME_REGEX);
        $form->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE'));
        return $form;
    }



    
  
}