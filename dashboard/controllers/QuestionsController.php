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
        if ($this->siteUserType != User::TEACHER) {
            FatUtility::exitWithErrorCode(404);
        }
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
                'ques.ques_type',
                'ques.ques_created',
            ]
        );
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['pageno']);
        $srch->addOrder('ques_status', 'DESC');
        $srch->addOrder('ques_id', 'DESC');
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
     * Remove Question
     */
    public function remove()
    {
        $quesId = FatApp::getPostedData('quesId', FatUtility::VAR_INT, 0);
        $question = new Question($quesId, $this->siteUserId);
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
     * @param int $id
     */
    public function form(int $id = 0)
    {
        $question = $options = $answers = [];
        $type = 0;
        if (0 < $id) {
            $quesObj = new Question($id);
            $question = $quesObj->getById();
            if (empty($question) || $question['ques_user_id'] != $this->siteUserId) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
            $options = $quesObj->getOptions();
            $type = $question['ques_type'];
            $answers = json_decode($question['ques_answer']);
        }
        $frm = $this->getForm();
        $frm->fill($question);
        $this->sets([
            'optionsFrm' => $this->getOptionsForm($type),
            'frm' => $frm,
            'options' => $options,
            'answers' => $answers
        ]);
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
            if (!$optionFrm->getFormDataFromArray(FatApp::getPostedData())) {
                FatUtility::dieJsonError(current($optionFrm->getValidationErrors()));
            }
            $post['answers'] = FatApp::getPostedData('ques_answer');
            $post['queopt_title'] = FatApp::getPostedData('queopt_title');
        }
        
        $question = new Question($post['ques_id'], $this->siteUserId);
        unset($post['ques_id']);
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
        $type = FatApp::getPostedData('type', FatUtility::VAR_INT, 0);
        $quesId = FatApp::getPostedData('quesId', FatUtility::VAR_INT, 0);
        $count = FatApp::getPostedData('count', FatUtility::VAR_INT, 0);
        if ($count < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_PLEASE_ENTER_OPTIONS_COUNT'));
        }

        $options = $answers = [];
        if ($quesId > 0) {
            $question = new Question($quesId, $this->siteUserId);
            if (!$data = $question->getById()) {
                FatUtility::dieJsonError(Label::getLabel('LBL_QUESTION_NOT_FOUND'));
            }
            if ($this->siteUserId != $data['ques_user_id']) {
                FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
            }
            if ($count == $data['ques_options_count']) {
                $options = $question->getQuesOptions();
                $answers = json_decode($data['ques_answer'], true);
            }
        }
        $this->sets([
            'frm' => $this->getOptionsForm($type),
            'type' => $type,
            'count' => $count,
            'options' => $options,
            'answers' => $answers
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Update question status
     *
     * @return json
     */
    public function updateStatus()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $status = ($status == AppConstant::ACTIVE) ? AppConstant::INACTIVE : AppConstant::ACTIVE;
        $question = new Question($id);
        if (!$data = $question->getById()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUESTION_NOT_FOUND'));
        }
        if ($this->siteUserId != $data['ques_user_id']) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }
        $question->setFldValue('ques_status', $status);
        if (!$question->save()) {
            FatUtility::dieJsonError($question->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_STATUS_UPDATED_SUCCESSFULLY'));
    }

    /**
     * Get Questions Form
     */
    private function getForm()
    {
        $categoryList = Category::getCategoriesByParentId($this->siteLangId, 0, Category::TYPE_QUESTION, false);
        $frm = new Form('frmQuestion');
        $frm->addHiddenField('', 'ques_id')->requirements()->setInt();
        $typeFld = $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'ques_type', Question::getTypes());
        $typeFld->requirements()->setRequired();
        $fld = $frm->addRequiredField(Label::getLabel('LBL_TITLE'), 'ques_title');
        $fld->requirements()->setLength(10, 100);
        $fld = $frm->addTextArea(Label::getLabel('LBL_DESCRIPTION'), 'ques_detail');
        $fld->requirements()->setLength(10, 1000);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'ques_cate_id', $categoryList);
        $fld->requirements()->setRequired();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_SUBCATEGORY'), 'ques_subcate_id', []);
        $fld->requirements()->setInt();
        $fld = $frm->addIntegerField(Label::getLabel('LBL_MARKS'), 'ques_marks');
        $fld->requirements()->setRequired();
        $fld->requirements()->setIntPositive();
        $fld = $frm->addTextBox(Label::getLabel('LBL_HINT'), 'ques_hint');
        $fld->requirements()->setLength(10, 255);

        $countFld = $frm->addIntegerField(Label::getLabel('LBL_OPTION_COUNT'), 'ques_options_count');
        $countFld->requirements()->setRequired();
        $countFld->requirements()->setIntPositive();

        $reqCountFld = new FormFieldRequirement('ques_options_count', Label::getLabel('LBL_OPTION_COUNT'));
        $reqCountFld->setRequired(true);
        $notReqCountFld = new FormFieldRequirement('ques_options_count', Label::getLabel('LBL_OPTION_COUNT'));
        $notReqCountFld->setRequired(false);

        $typeFld->requirements()->addOnChangerequirementUpdate(
            Question::TYPE_MANUAL,
            'ne',
            'ques_options_count',
            $reqCountFld
        );
        $typeFld->requirements()->addOnChangerequirementUpdate(
            Question::TYPE_MANUAL,
            'eq',
            'ques_options_count',
            $notReqCountFld
        );

        $frm->addButton(Label::getLabel('LBL_ADD_OPTION'), 'add_options', Label::getLabel('LBL_ADD_OPTION'));
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }

    /**
     * Get Question Options Form
     *
     * @param int $type
     */
    private function getOptionsForm(int $type = 0)
    {
        $frm = new Form('frmOptions');
        $fld = $frm->addTextBox(Label::getLabel('LBL_OPTION_TITLE'), 'queopt_title[]');
        $fld->requirements()->setRequired();
        $fld->requirements()->setLength(1, 255);

        if ($type == Question::TYPE_SINGLE) {
            $options = [1 => Label::getLabel('LBL_IS_CORRECT?')];
            $fld = $frm->addRadioButtons(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', $options);
            $fld->requirements()->setRequired();
            $fld->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PLEASE_MARK_ANSWERS.'));
        } elseif ($type == Question::TYPE_MULTIPLE) {
            $fld = $frm->addCheckBox(Label::getLabel('LBL_IS_CORRECT?'), 'ques_answer[]', 1);
            $fld->requirements()->setRequired();
            $fld->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PLEASE_MARK_ANSWERS'));
        }
        return $frm;
    }
}
