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

    public function setupQuestion()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $type = FatApp::getPostedData('type', FatUtility::VAR_INT, 0);
        $answers=[];
        if ($type != Question::TYPE_MANUAL) {
            $post['options'] = FatApp::getPostedData('queopt_title');
            $post['option_answers'] = FatApp::getPostedData('ques_answer');
            $answers = FatApp::getPostedData('answers');
            $description = FatApp::getPostedData('queopt_description');
            if (empty($post['options'])) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Please_Enter_Question_Option_Values'));
            }
            if (empty($post['option_answers'])) {
                FatUtility::dieJsonError(Label::getLabel('LBL_Please_Select_Correct_Answer(s)_From_The_Given_Options'));
            }
            if (!is_array($answers)) {
                $answers = [$answers];
            }
        }
        $post['correct_answers'] = $answers;
        $post['ques_user_id'] = $this->siteUserId;
        $post['ques_status'] = AppConstant::ACTIVE;

        $question = new Question($post['ques_id']);

        if(!$question->addUpdateQuestion($post)){
            FatUtility::dieJsonError($question->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
        
    }

    public function addOption()
    {
        $type = FatApp::getPostedData('type', FatUtility::VAR_INT, 0);
        $frm = $this->getForm($type);
        $this->set('frm', $frm);
        $this->set('type', $type);
        $this->_template->render(false, false);
    }


    private function getForm($type = 0)
    {
        $categoryList = Category::getCategoriesByParentId($this->siteLangId, 0, Category::TYPE_QUESTION, false);
        $frm = new Form('frmQuestion');
        $frm->addHiddenField('', 'ques_id')->requirements()->setIntPositive();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'ques_type', Question::getQuesTypes());
        $fld->requirements()->setRequired(true);
        $fld = $frm->addRequiredField(Label::getLabel('LBL_TITLE'), 'ques_title');
        $fld->requirements()->setLength(10, 100);
        $fld = $frm->addTextArea(Label::getLabel('LBL_DESCRIPTION'), 'ques_detail');
        $fld->requirements()->setLength(10, 1000);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'ques_cate_id', $categoryList);
        $fld->requirements()->setRequired(true);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_SUBCATEGORY'), 'ques_subcate_id', []);
        $fld = $frm->addTextBox(Label::getLabel('LBL_HINT'), 'ques_hint');
        $fld = $frm->addIntegerField(Label::getLabel('LBL_MARKS'), 'ques_marks');
        $fld->requirements()->setRequired(true);
        $frm->addTextBox(Label::getLabel('LBL_OPTION_TITLE'), 'queopt_title[]')->requirements()->setRequired(true);
        $frm->addTextArea(Label::getLabel('LBL_OPTION_DESCRIPTION'), 'queopt_detail[]')->requirements()->setLength(10, 100);

        if ($type == Question::TYPE_SINGLE) {
            $fld = $frm->addRadioButtons('', 'ques_answer[]', [1 => Label::getLabel('LBL_IS_CORRECT?')]);
        }
        if ($type == Question::TYPE_MULTIPLE) {
            $fld = $frm->addCheckBox(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', 1);
        }


        $frm->addButton('', 'submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }


    private function getOptionsForm($type = Question::TYPE_SINGLE, $no = 0)
    {
        $frm = new Form('frmOptions');
        $frm->addTextBox(Label::getLabel('LBL_OPTION_TITLE'), 'option_title[]')->requirements()->setRequired(true);
        if ($type == Question::TYPE_SINGLE) {
            $fld = $frm->addRadioButtons('', 'answer[]', [1 => Label::getLabel('LBL_IS_CORRECT?')]);
        }
        if ($type == Question::TYPE_MULTIPLE) {
            $fld = $frm->addCheckBox(Label::getLabel('LBL_IS_CORRECT?'), 'answer[]', 1);
        }
        $frm->addTextArea(Label::getLabel('LBL_OPTION_DESCRIPTION'), 'option_description[]')->requirements()->setLength(10, 100);
        return $frm;
    }
}
