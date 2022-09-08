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
        $srch = new QuestionSearch($this->siteLangId, $this->siteUserId, User::SUPPORT);
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
        if (0 < $id) {
            $question = Question::getAttributesById($id);
            if (empty($question) || $question['ques_user_id'] != $this->siteUserId) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
            $type = $question['ques_type'];
            $quesObj = new Question($question['ques_id']);
            $question['options'] = array_column($quesObj->getOptions(), NULL, 'queopt_order');
        }
        $frm = $this->getForm(); 
        if (isset($question)) {
            $frm->fill($question ?? []);
            $this->set('question', $question ?? []);
            $this->set('optionsFrm',$this->getOptionsForm($question['ques_type']));
        }
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    /**
     * Setup Questions
     */
    public function setup()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), ['ques_subcate_id', 'ques_cate_id'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }  
        if ($post['ques_type'] != Question::TYPE_MANUAL) {
            $optionFrm = $this->getOptionsForm($post['ques_type']);
            if (!$optionData = $optionFrm->getFormDataFromArray(FatApp::getPostedData())) {
                FatUtility::dieJsonError(current($optionFrm->getValidationErrors()));
            }  
            $post['answers'] = FatApp::getPostedData('ques_answer');
            $post['queopt_title'] = FatApp::getPostedData('queopt_title');
        }
        $quesId = $post['ques_id'];
        $question = new Question($quesId, $this->siteUserId);
        if (!$question->setup($post)) {
            FatUtility::dieJsonError($question->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
    }

    /**
     * Render Option Fields
     */
    public function optionForm()
    {   
        $type = FatApp::getPostedData('ques_type', FatUtility::VAR_INT, 0);
        $count = FatApp::getPostedData('ques_options_count', FatUtility::VAR_INT, 0);
        $this->set('frm', $this->getOptionsForm($type));
        $this->set('type', $type);
        $this->set('count', $count);
        $this->_template->render(false, false);
    }

    /**
     * Get Questions Form
     *
     * @return Form
     */
    private function getForm()
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
        $countFld->requirements()->setIntPositive();
        $frm->addButton(Label::getLabel('LBL_ADD_OPTION'), 'add_options', Label::getLabel('LBL_ADD_OPTION'));
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }

    /**
     * Get Question Options Form
     *
     * @return Form
     */
    private function getOptionsForm(int $type)
    {
        $frm = new Form('frmOptions');
        $frm->addTextBox(Label::getLabel('LBL_OPTION_TITLE'), 'queopt_title[]')->requirements()->setRequired();
        if ($type == Question::TYPE_SINGLE) {
            $fld = $frm->addRadioButtons(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', [1 => Label::getLabel('LBL_IS_CORRECT?')]);
            $fld->requirements()->setRequired();
        } elseif ($type == Question::TYPE_MULTIPLE) {
            $fld = $frm->addCheckBox(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', 1);
            $fld->requirements()->setRequired();
        }
        return $frm;
    }

}
