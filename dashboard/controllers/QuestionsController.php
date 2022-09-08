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
    public function form($id = 0)
    {
        $type = 0;
        if (0 < $id) {
            $question = Question::getAttributesById($id);
            if (empty($question) || $question['ques_user_id'] != $this->siteUserId) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
            $type = $question['ques_type'];
            $quesObj = new Question($question['ques_id']);
            $question['options'] = array_column($quesObj->getOptions(), NULL, 'queopt_order');
        }
        $frm = $this->getForm($type); 
        if (isset($question)) {
            $frm->fill($question ?? []);
            $this->set('question', $question ?? []);
            $this->set('optionsFrm',$this->getOptionsForm($question['ques_type'], $question['ques_options_count']));
        }
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $type = FatApp::getPostedData('ques_type', FatUtility::VAR_INT, 0);
        $frm = $this->getForm($type);
        
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), ['ques_subcate_id', 'ques_cate_id'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        if ($type != Question::TYPE_MANUAL) {
            $post['queopt_title'] = FatApp::getPostedData('queopt_title');
            $post['answers'] = FatApp::getPostedData('ques_answer');
            if (empty($post['answers'])) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Please_Select_Correct_Answer(s)_From_The_Given_Options'));
            }
        }
        $post['ques_user_id'] = $this->siteUserId;
        $post['ques_status'] = AppConstant::ACTIVE;
        $quesId = $post['ques_id'];
        unset($post['ques_id']);
        $question = new Question($quesId);
        if (!$question->addUpdateQuestion($post)) {
            FatUtility::dieJsonError($question->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
    }

    public function optionForm()
    {   
        $type = FatApp::getPostedData('ques_type', FatUtility::VAR_INT, 0);
        $count = FatApp::getPostedData('ques_options_count', FatUtility::VAR_INT, 0);
        $this->set('frm', $this->getOptionsForm($type, $count));
        $this->set('type', $type);
        $this->set('count', $count);
        $this->_template->render(false, false);
    }


    private function getForm($type = 0)
    {
        $categoryList = Category::getCategoriesByParentId($this->siteLangId, 0, Category::TYPE_QUESTION, false);
        $frm = new Form('frmQuestion');
        $frm->addHiddenField('', 'ques_id', 0)->requirements()->setIntPositive();
        $typeFld = $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'ques_type', Question::getQuesTypes());
        $typeFld->requirements()->setRequired();
        $fld = $frm->addRequiredField(Label::getLabel('LBL_TITLE'), 'ques_title');
        $fld->requirements()->setLength(10, 100);
        $fld = $frm->addTextArea(Label::getLabel('LBL_DESCRIPTION'), 'ques_detail');
        $fld->requirements()->setLength(10, 1000);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'ques_cate_id', $categoryList);
        $fld->requirements()->setRequired();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_SUBCATEGORY'), 'ques_subcate_id', []);
        $fld = $frm->addIntegerField(Label::getLabel('LBL_MARKS'), 'ques_marks');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $fld = $frm->addTextBox(Label::getLabel('LBL_HINT'), 'ques_hint');
        $countFld = $frm->addIntegerField(Label::getLabel('LBL_OPTION_COUNT'), 'ques_options_count');
        $countFld->requirements()->setRequired();
        $frm->addButton(Label::getLabel('LBL_ADD_OPTION'), 'add_options', Label::getLabel('LBL_ADD_OPTION'));
        $frm->addButton('', 'submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }


    private function getOptionsForm(int $type, int $count)
    {
        $frm = new Form('frmOptions');
        $frm->addTextBox(Label::getLabel('LBL_OPTION_TITLE'), 'queopt_title[]')->requirements()->setRequired();
        if ($type == Question::TYPE_SINGLE) {
            $fld = $frm->addRadioButtons(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', [1 => Label::getLabel('LBL_IS_CORRECT?')]);
            $fld->requirements()->setRequired();
        } elseif ($type == Question::TYPE_MULTIPLE) {
            $fld = $frm->addCheckBox(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', 1);
        }
        return $frm;
    }

     private function validate($data)
    {
        /* validate categories */
        $categories = [$data['ques_cate_id'], $data['ques_subcate_id']];
        $srch = Category::getSearchObject();
        $srch->doNotCalculateRecords();
        $srch->addFld('cate_id');
        $srch->addCondition('cate_id', 'IN', $categories);
        $srch->addCondition('cate_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $categories = FatApp::getDb()->fetchAll($srch->getResultSet(), 'cate_id');
        echo "<pre>"; print_r(($data));exit;
        if (!array_key_exists($data['ques_cate_id'], $categories)) {
           
            $this->error = Label::getLabel('LBL_CATEGORY_NOT_AVAILABLE');
            return false;
        }
        // if ($data['ques_subcate_id'] > 0 && !array_key_exists($data['ques_subcate_id'], $categories)) {
        //     $this->error = Label::getLabel('LBL_SUBCATEGORY_NOT_AVAILABLE');
        //     return false;
        // }

        return true;
    }

}
