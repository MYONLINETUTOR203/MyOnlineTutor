<?php

/**
 * This Controller is used for creating quizzes
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class QuizzesController extends DashboardController
{
    /**
     * Initialize Quizzes
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
     * Render Quizzes Search Form
     */
    public function index()
    {
        $frm = $this->getSearchForm();
        $this->set('frm', $frm);
        $this->_template->render();
    }

    /**
     * Search & List Quizzes
     */
    public function search()
    {
        $frm = $this->getSearchForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        $srch = new QuizSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
        $srch->applyPrimaryConditions();
        $srch->applySearchConditions($post);
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['pageno']);
        $srch->addOrder('quiz_active', 'DESC');
        $srch->addOrder('quiz_id', 'DESC');

        $this->sets([
            'quizzes' => $srch->fetchAndFormat(),
            'types' => Quiz::getTypes(),
            'status' => Quiz::getStatuses(),
            'post' => $post,
            'recordCount' => $srch->recordCount()
        ]);
        
        $this->_template->render(false, false);
    }

    /**
     * Render add new question form
     *
     * @return html
     */
    public function form($id = 0)
    {
        $quiz = new Quiz($id, $this->siteUserId);
        if ($id > 0 && !$quiz->validate()) {
            Message::addErrorMessage($quiz->getError());
            FatApp::redirectUser(MyUtility::generateUrl('Quizzes'));
        }
        $this->sets([
            "quizId" => $id,
            "includeEditor" => true
        ]);
        $this->_template->render();
    }

    /**
     * Render add new question form
     *
     * @return html
     */
    public function basic()
    {
        $id = FatApp::getPostedData('id');
        $data = [];
        if ($id > 0) {
            $quiz = new Quiz($id);
            if (!$data = $quiz->getById()) {
                FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
            }
            if ($data['quiz_user_id'] != $this->siteUserId) {
                FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
            }
        }
        $frm = $this->getForm();
        $frm->fill($data);
        $this->sets([
            'frm' => $frm,
            'quizId' => $id
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Setup Quizzes
     */
    public function setup()
    {
        $frm = $this->getForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }  
        $quiz = new Quiz($post['quiz_id'], $this->siteUserId);
        if (!$quiz->setup($post)) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess([
            'quizId' => $quiz->getMainTableRecordId(),
            'msg' => Label::getLabel('MSG_SETUP_SUCCESSFUL')
        ]);
    }

    /**
     * Questions listing
     *
     * @return void
     */
    public function questions()
    {
        $id = FatApp::getPostedData('id');
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $quiz = new Quiz($id, $this->siteUserId);
        if (!$data = $quiz->getById()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
        }
        if ($this->siteUserId != $data['quiz_user_id']) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }
        $quiz = new QuizSearch(0, $this->siteUserId, 0);
        $questions = $quiz->getQuestions($id, $data['quiz_type']);
        $this->sets([
            'questions' => $questions,
            'quizId' => $id,
            'types' => Question::getQuesTypes(),
        ]);
        $this->_template->render(false, false);
    }
    public function questionForm()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $quiz = new Quiz($id, $this->siteUserId);
        /* validate data */
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
        }

        $frm = $this->getQuestSearchForm();
        $frm->fill(['quiz_id' => $id]);
        $this->sets([
            'frm' => $frm
        ]);
        $this->_template->render(false, false);
    }

    private function getQuestSearchForm()
    {
        $frm = QuestionSearch::getSearchForm($this->siteLangId);
        $frm->addHiddenField('', 'quiz_id');
        return $frm;
    }

    public function searchQuestions()
    {
        $id = FatApp::getPostedData('quiz_id', FatUtility::VAR_INT, 0);
        $quiz = new Quiz($id, $this->siteUserId);
        /* validate data */
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        $type = Quiz::getAttributesById($id, 'quiz_type');
        /* get questions list */
        $srch = new QuestionSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
        $srch->applyPrimaryConditions();
        if ($type == Quiz::TYPE_AUTO_GRADED) {
            $srch->addCondition('ques_type', '!=', Question::TYPE_MANUAL);
        } else {
            $srch->addCondition('ques_type', '=', Question::TYPE_MANUAL);
        }
        $srch->addMultipleFields([
            'ques_id', 'ques_cate_id', 'ques_subcate_id', 'ques_type', 'ques_title'
        ]);
        $this->sets([
            'questions' => $srch->fetchAndFormat()
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Delete Questions
     *
     * @return void
     */
    public function deleteQuestion()
    {
        $quizId = FatApp::getPostedData('quizId', FatUtility::VAR_INT, 0);
        $quesId = FatApp::getPostedData('quesId', FatUtility::VAR_INT, 0);
        if ($quizId < 1 || $quesId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $quiz = new Quiz($quizId, $this->siteUserId);
        if (!$quiz->deleteQuestion($quesId)) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_DELETED_SUCCESSFULLY!'));
    }

    /**
     * Settings
     *
     * @return void
     */
    public function settings()
    {
        $id = FatApp::getPostedData('id');
        $this->sets([
            // 'frm' => $frm,
            'quizId' => $id
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Update quiz status
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
        $quiz = new Quiz($id, $this->siteUserId);
        if (!$quiz->updateStatus($status)) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_STATUS_UPDATED_SUCCESSFULLY'));
    }

    public function delete()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $quiz = new Quiz($id, $this->siteUserId);
        if (!$quiz->delete()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_DELETED_SUCCESSFULLY!'));
    }

    /**
     * Get Quizzes Form
     *
     * @return Form
     */
    private function getForm()
    {
        $frm = new Form('frmQuiz');
        $frm->addTextBox(Label::getLabel('LBL_TITLE'), 'quiz_title', '')->requirements()->setRequired();
        $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'quiz_type', Quiz::getTypes())->requirements()->setRequired();
        $frm->addHtmlEditor(Label::getLabel('LBL_INSTRUCTIONS'), 'quiz_detail', '')->requirements()->setRequired();
        +$frm->addHiddenField('', 'quiz_id')->requirements()->setInt();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }

    /**
     * Get Search Form
     *
     * @return Form
     */
    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Label::getLabel('LBL_TITLE'), 'keyword', '', ['autocomplete' => 'off']);
        $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'quiz_type', Quiz::getTypes());
        $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'quiz_status', Quiz::getStatuses());
        $frm->addSelectBox(Label::getLabel('LBL_ACTIVE'), 'quiz_active', AppConstant::getActiveArr());
        $frm->addHiddenField(Label::getLabel('LBL_PAGESIZE'), 'pagesize', AppConstant::PAGESIZE)
        ->requirements()->setInt();
        $frm->addHiddenField(Label::getLabel('LBL_PAGENO'), 'pageno', 1)->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $frm->addButton('', 'btn_clear', Label::getLabel('LBL_CLEAR'));
        return $frm;
    }
}
