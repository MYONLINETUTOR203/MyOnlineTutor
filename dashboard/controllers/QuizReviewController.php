<?php

use GuzzleHttp\Psr7\Query;

/**
 * This Controller is used for user quiz solving
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class QuizReviewController extends DashboardController
{
    /**
     * Initialize
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Render Instructions Form
     *
     * @param int $id
     */
    public function index(int $id)
    {
        if ($id < 1) {
            FatUtility::dieWithError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieWithError($quiz->getError());
        }
        $data = $quiz->get();
        if ($this->siteUserType == User::TEACHER) {
            $this->set('user', User::getAttributesById($data['quizat_user_id'], ['user_first_name', 'user_last_name']));
        }
        $this->set('data', $quiz->get());

        $attempt = new QuizAttempt(0, $data['quizat_user_id']);
        $this->set('attempts', $attempt->getAttemptCount($data['quizat_quilin_id']));
        $this->set('courseQuiz', ($data['quilin_record_type'] === AppConstant::COURSE));
        $this->_template->render();
    }

    /**
     * Start Quiz Review
     */
    public function start()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->start()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess('');
    }

    /**
     * Quiz questions forms
     *
     * @param int $id
     */
    public function questions(int $id)
    {
        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieWithError($quiz->getError());
        }
        $data = $quiz->get();
        if ($this->siteUserType == User::TEACHER) {
            $this->set('user', User::getAttributesById($data['quizat_user_id'], ['user_first_name', 'user_last_name']));
        }
        $this->set('data', $quiz->get());
        $this->set('courseQuiz', ($data['quilin_record_type'] === AppConstant::COURSE));
        $this->_template->render();
    }

    /**
     * Quiz questions forms
     *
     * @return json
     */
    public function view()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        $data = $quiz->get();

        /* get current question & options data */
        $question = QuizLinked::getQuestionById($data['quizat_qulinqu_id']);
        if (empty($question)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUESTION_NOT_FOUND'));
        }

        /* get question attempt data */
        $attemptedQues = [];
        $linked = new QuizLinked();
        $attemptedQues = $linked->getQuesWithAttemptedAnswers($id, $data['quilin_id']);

        $answer = [];
        $currentQues = $attemptedQues[$data['quizat_qulinqu_id']];
        if (!empty($currentQues['quatqu_answer'])) {
            $answer = $currentQues['quatqu_answer'];
        }

        if ($question['qulinqu_type'] == Question::TYPE_MANUAL) {
            $answer = $answer[0] ?? '';

            /* evaluation form for Manual quiz */
            $frm = $this->getForm($question['qulinqu_marks']);
            $frm->fill([
                'quatqu_id' => ($currentQues['quatqu_id'] ?? ''),
                'quizat_id' => $id,
                'quatqu_scored' => floatval($currentQues['quatqu_scored']) ?? ''
            ]);
            $this->set('frm', $frm);
        }

        $this->sets([
            'data' => $data,
            'attemptedQues' => $attemptedQues,
            'currentQues' => $currentQues,
            'answers' => $answer,
            'question' => $question,
            'quesAnswers' => json_decode($question['qulinqu_answer'], true),
            'options' => json_decode($question['qulinqu_options'], true)
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
            'html' => $this->_template->render(false, false, 'quiz-review/view.php', true),
            'questionsInfo' => $quesInfoLabel,
            'totalMarks' => $data['quilin_marks']
        ]);
    }

    /**
     * Set question next, previous or by id
     *
     * @return json
     */
    public function setQuestion()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $next = FatApp::getPostedData('next', FatUtility::VAR_INT, AppConstant::YES);
        $quesId = FatApp::getPostedData('ques_id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
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

    /**
     * Finish Review
     *
     * @return json
     */
    public function finish()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $submit = FatApp::getPostedData('submit', FatUtility::VAR_INT, AppConstant::NO);
        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        if (!$quiz->setupEvaluation($submit)) {
            FatUtility::dieJsonError($quiz->getError());
        }
        $msg = Label::getLabel('LBL_REVIEW_FINISHED_SUCCESSFULLY');
        if ($submit == AppConstant::YES) {
            $msg = Label::getLabel('LBL_EVALUATION_SUBMITTED_SUCCESSFULLY');
        }
        FatUtility::dieJsonSuccess($msg);
    }

    /**
     * Setup Question Evaluation
     *
     * @return bool
     */
    public function setup()
    {
        $post = FatApp::getPostedData();
        /* validate question id */
        $srch = new SearchBase(QuizAttempt::DB_TBL_QUESTIONS);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('quatqu_qulinqu_id');
        $srch->addCondition('quatqu_id', '=', $post['quatqu_id']);
        $srch->addCondition('quatqu_quizat_id', '=', $post['quizat_id']);
        if (!$data = FatApp::getDb()->fetch($srch->getResultSet())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_DATA_SENT'));
        }

        $marks = QuizLinked::getQuestionById($data['quatqu_qulinqu_id'])['qulinqu_marks'];
        $frm = $this->getForm($marks);
        $post = $frm->getFormDataFromArray($post);

        $quiz = new QuizReview($post['quizat_id'], $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
        }

        $quizData = $quiz->get();
        if ($quizData['quizat_evaluation'] != QuizAttempt::EVALUATION_PENDING) {
            FatUtility::dieJsonError(Label::getLabel('LBL_EVALUATION_IS_ALREADY_SUBMITTED'));
        }

        if (!$quiz->setup($post)) {
            FatUtility::dieJsonError($quiz->getError());
        }

        FatUtility::dieJsonSuccess(Label::getLabel('LBL_SCORE_SETUP_SUCCESSFUL'));
    }

    /**
     * Load question evaluation form
     *
     * @param float $marks
     */
    private function getForm(float $marks = 0)
    {
        $frm = new Form('frmEvaluation');
        $fld = $frm->addFloatField(Label::getLabel('LBL_SCORE'), 'quatqu_scored');
        $fld->requirements()->setRequired();
        if ($marks > 0) {
            $fld->requirements()->setRange(0, $marks);
        }
        $frm->addHiddenField('', 'quatqu_id');
        $frm->addHiddenField('', 'quizat_id');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SUBMIT'));
        return $frm;
    }
}