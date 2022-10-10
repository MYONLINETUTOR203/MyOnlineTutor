<?php

use GuzzleHttp\Psr7\Query;

/**
 * This Controller is used for user quiz solving
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class UserQuizController extends DashboardController
{
    /**
     * Initialize
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        if ($this->siteUserType != User::LEARNER) {
            FatUtility::exitWithErrorCode(404);
        }
    }

    /**
     * Render Instructions Form
     */
    public function index(int $id)
    {
        $quiz = new QuizAttempt($id);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_user_id'] != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }

        if ($data['quizat_status'] == QuizAttempt::STATUS_IN_PROGRESS) {
            FatApp::redirectUser(MyUtility::generateUrl('UserQuiz', 'questions', [$id]));
        } elseif ($data['quizat_status'] == QuizAttempt::STATUS_COMPLETED) {
            FatApp::redirectUser(MyUtility::generateUrl('UserQuiz', 'completed', [$id]));
        }

        $this->set('data', $data);
        $this->_template->render();
    }

    /**
     * Start Quiz
     */
    public function start()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $attempt = new QuizAttempt($id, $this->siteUserId, $this->siteUserType);
        if (!$attempt->start()) {
            FatUtility::dieJsonError($attempt->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_QUIZ_STARTED_SUCCESSFULLY'));
    }

    /**
     * Quiz questions forms
     *
     * @param int $id
     */
    public function questions(int $id)
    {
        $quiz = new QuizAttempt($id, $this->siteUserId);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_user_id'] != $this->siteUserId) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_status'] != QuizAttempt::STATUS_IN_PROGRESS) {
            FatUtility::exitWithErrorCode(404);
        }

        $endtime = $data['quilin_duration'] + strtotime($data['quizat_started']);
        if (strtotime(date('Y-m-d H:i:s')) > $endtime) {
            if (!$quiz->markComplete(date('Y-m-d H:i:s', $endtime))) {
                FatUtility::dieJsonError($quiz->getError());
            }
            Message::addErrorMessage(Label::getLabel('LBL_QUIZ_DURATION_IS_OVER'));
            FatApp::redirectUser(MyUtility::makeUrl('UserQuiz', 'completed', [$id]));
        }

        $this->set('data', $data);
        $this->set('attemptId', $id);

        $this->_template->addJs('js/app.timer.js');
        $this->_template->render();
    }

    /**
     * Quiz questions forms
     *
     * @param int $id
     */
    public function view()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* get quiz details */
        $quiz = new QuizAttempt($id, $this->siteUserId);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
        }
        
        /* validate logged in user */
        if ($data['quizat_user_id'] != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }

        /* get current question & options data */
        $question = QuizLinked::getQuestionById($data['quizat_qulinqu_id']);
        if (empty($question)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUESTION_NOT_FOUND'));
        }

        /* get question attempt data */
        $srch = new SearchBase(QuizLinked::DB_TBL_QUIZ_LINKED_QUESTIONS);
        $srch->joinTable(QuizAttempt::DB_TBL_QUESTIONS, 'LEFT JOIN', 'quatqu_qulinqu_id = qulinqu_id AND quatqu_quizat_id = ' . $id);
        $srch->addCondition('qulinqu_quilin_id', '=', $data['quilin_id']);
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields(['quatqu_id', 'quatqu_answer', 'qulinqu_id', 'qulinqu_order']);
        $srch->addOrder('qulinqu_order', 'ASC');
        $attemptedQues = FatApp::getDb()->fetchAll($srch->getResultSet(), 'qulinqu_id');

        $answer = [];
        $attemtQuesId = '';
        $currentQuesId = $data['quizat_qulinqu_id'];
        $attemtQuesId = $attemptedQues[$currentQuesId]['quatqu_id'];
        if (!empty($attemptedQues[$currentQuesId]['quatqu_answer'])) {
            $answer = json_decode($attemptedQues[$currentQuesId]['quatqu_answer'], 0);
        }
        if ($question['qulinqu_type'] == Question::TYPE_MANUAL) {
            $answer = $answer[0] ?? '';
        }

        /* get question form */
        $frm = $this->getForm($question['qulinqu_type']);
        $frm->fill([
            'ques_id' => $data['quizat_qulinqu_id'], 'ques_attempt_id' => $id, 'quatqu_id' => $attemtQuesId,
            'ques_type' => $question['qulinqu_type'], 'ques_answer' => $answer
        ]);

        $this->sets([
            'frm' => $frm,
            'data' => $data,
            'attemptedQues' => $attemptedQues,
            'question' => $question,
            'options' => json_decode($question['qulinqu_options'], true),
            'expired' => 0
        ]);

        /* Set quiz stats data */
        $quesInfoLabel = Label::getLabel('LBL_QUESTION_{current-question}_OF_{total-questions}');
        $quesInfoLabel = str_replace(
            ['{current-question}', '{total-questions}'],
            [
                '<strong>' . $question['qulinqu_order'] . '</strong>',
                '<strong>' . $data['quilin_questions'] . '</strong>'
            ],
            $quesInfoLabel
        );
        FatUtility::dieJsonSuccess([
            'html' => $this->_template->render(false, false, 'user-quiz/view.php', true),
            'questionsInfo' => $quesInfoLabel,
            'totalMarks' => $data['quilin_marks'],
            'progressPercent' => MyUtility::formatPercent($data['quizat_progress']),
            'progress' => $data['quizat_progress'],
        ]);
    }

    public function setQuestion()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $next = FatApp::getPostedData('next', FatUtility::VAR_INT, AppConstant::YES);
        $quesId = FatApp::getPostedData('ques_id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* get quiz details */
        $quiz = new QuizAttempt($id, $this->siteUserId);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
        }

        /* validate logged in user */
        if ($data['quizat_user_id'] != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }
        
        if ($quesId > 0) {
            $quiz->assignValues(['quizat_qulinqu_id' => $quesId]);
            if (!$quiz->save()) {
                FatUtility::dieJsonError($quiz->getError());
            }
        } else {
            if (!$quiz->setQuestion($next)) {
                FatUtility::dieJsonError($quiz->getError());
            }
        }
        FatUtility::dieJsonSuccess('');
    }

    public function saveAndNext($next = AppConstant::YES)
    {
        $post = FatApp::getPostedData();
        $frm = $this->getForm($post['ques_type']);
        if (!$post = $frm->getFormDataFromArray($post, ['ques_answer'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $quiz = new QuizAttempt($post['ques_attempt_id'], $this->siteUserId);
        if (!$quiz->setup($post, $next)) {
            FatUtility::dieJsonError($quiz->getError());
        }
        $msg = ($next == AppConstant::NO) ? Label::getLabel('LBL_QUIZ_SAVED_SUCCESSFULLY') : '';
        FatUtility::dieJsonSuccess([
            'id' => $post['ques_attempt_id'],
            'msg' => $msg
        ]);
    }

    public function saveAndFinish()
    {
        $attemptId = FatApp::getPostedData('ques_attempt_id');
        $answer = FatApp::getPostedData('ques_answer', FatUtility::VAR_STRING, '');
        $quiz = new QuizAttempt($attemptId, $this->siteUserId);
        if (!empty($answer)) {
            if (!$quiz->setup(FatApp::getPostedData(), AppConstant::NO)) {
                FatUtility::dieJsonError($quiz->getError());
            }
        }
        if (!$quiz->markComplete()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess([
            'msg' => Label::getLabel('LBL_QUIZ_COMPLETED_SUCCESSFULLY'),
            'id' => $attemptId
        ]);
    }

    /**
     * Quiz completion page
     */
    public function completed(int $id)
    {
        $quiz = new QuizAttempt($id, $this->siteUserId);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_user_id'] != $this->siteUserId) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_status'] != QuizAttempt::STATUS_COMPLETED) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_active'] == AppConstant::NO) {
            FatUtility::exitWithErrorCode(404);
        }
        /* get user name */
        $this->sets([
            'user' => User::getAttributesById($this->siteUserId, ['user_first_name', 'user_last_name']),
            'data' => $data,
            'attemptId' => $id,
            'attempts' => $quiz->getAttemptCount($data['quizat_quilin_id'])
        ]);
        $this->_template->render();
    }

    public function retake()
    {
        $id = FatApp::getPostedData('id');
        $quiz = new QuizAttempt($id, $this->siteUserId);
        if (!$quiz->retake()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        Message::addMessage(Label::getLabel('LBL_QUIZ_PROGRESS_RESET_SUCCESSFULLY'));
        FatUtility::dieJsonSuccess([
            'id' => $quiz->getMainTableRecordId()
        ]);
    }

    private function getForm(int $type)
    {
        $frm = new Form('frmQuiz');
        if ($type == Question::TYPE_SINGLE) {
            $fld = $frm->addRadioButtons('', 'ques_answer', []);
            $fld->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PLEASE_SELECT_ANSWER'));
        } elseif ($type == Question::TYPE_MULTIPLE) {
            $fld = $frm->addCheckBoxes('', 'ques_answer', []);
            $fld->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PLEASE_SELECT_ANSWER'));
        } elseif ($type == Question::TYPE_MANUAL) {
            $fld = $frm->addTextArea(Label::getLabel('LBL_ANSWER'), 'ques_answer');
        } else {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_QUESTION_TYPE'));
        }
        $fld->requirements()->setRequired();

        $fld = $frm->addHiddenField('', 'ques_type');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();

        $fld = $frm->addHiddenField('', 'ques_attempt_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();

        $fld = $frm->addHiddenField('', 'ques_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();

        $fld = $frm->addHiddenField('', 'quatqu_id');
        $fld->requirements()->setInt();

        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_&_NEXT'));
        $frm->addButton('', 'btn_skip', Label::getLabel('LBL_SKIP'));
        return $frm;
    }
}
