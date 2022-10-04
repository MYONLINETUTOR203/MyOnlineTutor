<?php

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
            FatApp::redirectUser(MyUtility::generateUrl('UserQuiz', 'complete', [$id]));
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
        $quiz = new QuizAttempt($id);
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

        $this->set('data', $data);
        $this->set('attemptId', $id);
        $this->_template->render();
    }

    /**
     * Quiz questions forms
     *
     * @param int $id
     */
    public function getQuestion()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* get quiz details */
        $quiz = new QuizAttempt($id);
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

        $this->sets([
            'data' => $data,
            'question' => $question,
            'options' => json_decode($question['qulinqu_options'], true)
        ]);

        /* get question form */
        $frm = $this->getForm($question['qulinqu_type']);
        $frm->fill([
            'ques_id' => $data['quizat_qulinqu_id'], 'ques_attempt_id' => $id, 'ques_type' => $question['qulinqu_type']
        ]);
        $this->set('frm', $frm);

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
            'html' => $this->_template->render(false, false, 'user-quiz/get-question.php', true),
            'questionsInfo' => $quesInfoLabel,
            'totalMarks' => $data['quilin_marks'],
            'progressPercent' => MyUtility::formatPercent($data['quizat_progress']),
            'progress' => $data['quizat_progress'],
        ]);
    }

    public function setup()
    {
        $post = FatApp::getPostedData();
        $frm = $this->getForm($post['ques_type']);
        if (!$post = $frm->getFormDataFromArray($post, ['ques_answer'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $quiz = new QuizAttempt($post['ques_attempt_id'], $this->siteUserId);
        if (!$quiz->setup($post)) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess(['id' => $post['ques_attempt_id']]);
    }

    public function markComplete()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $quiz = new QuizAttempt($id);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
        }
        if ($data['quizat_user_id'] != $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }
        if ($data['quizat_status'] != QuizAttempt::STATUS_IN_PROGRESS) {
            FatUtility::dieJsonError(Label::getLabel('LBL_CANNOT_MARK_COMPLETE_TO_A_PENDING_OR_COMPLETED_QUIZ'));
        }
        if (!$quiz->markComplete()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_QUIZ_COMPLETED_SUCCESSFULLY'));
    }

    /**
     * Quiz completion page
     */
    public function completed(int $id)
    {
        $quiz = new QuizAttempt($id);
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

        $this->set('data', $data);
        $this->set('attemptId', $id);
        $this->_template->render();
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

        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_&_NEXT'));
        $frm->addButton('', 'btn_skip', Label::getLabel('LBL_SKIP'));
        return $frm;
    }
}
