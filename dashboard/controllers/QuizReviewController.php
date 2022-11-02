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
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            Message::addErrorMessage($quiz->getError());
            $controller = ($this->siteUserType == User::LEARNER) ? 'Learner' : 'Teacher';
            FatApp::redirectUser(MyUtility::makeUrl($controller));
        }
        $data = $quiz->get();
        if ($this->siteUserType == User::TEACHER) {
            $this->set('user', User::getAttributesById($data['quizat_user_id'], ['user_first_name', 'user_last_name']));
        }
        $this->set('data', $quiz->get());

        $attempt = new QuizAttempt(0, $data['quizat_user_id']);
        $this->set('attempts', $attempt->getAttemptCount($data['quizat_quilin_id']));
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
            Message::addErrorMessage($quiz->getError());
            $controller = ($this->siteUserType == User::LEARNER) ? 'Learner' : 'Teacher';
            FatApp::redirectUser(MyUtility::makeUrl($controller));
        }
        $data = $quiz->get();
        if ($this->siteUserType == User::TEACHER) {
            $this->set('user', User::getAttributesById($data['quizat_user_id'], ['user_first_name', 'user_last_name']));
        }
        $this->set('data', $quiz->get());
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
        $linked = new QuizLinked();
        $attemptedQues = $linked->getQuesWithAttemptedAnswers($id, $data['quilin_id']);

        $answer = [];
        $currentQuesId = $data['quizat_qulinqu_id'];
        if (!empty($attemptedQues[$currentQuesId]['quatqu_answer'])) {
            $answer = $attemptedQues[$currentQuesId]['quatqu_answer'];
        }
        if ($question['qulinqu_type'] == Question::TYPE_MANUAL) {
            $answer = $answer[0] ?? '';
        }

        $this->sets([
            'data' => $data,
            'attemptedQues' => $attemptedQues,
            'answers' => $answer,
            'question' => $question,
            'quesAnswers' => json_decode($question['qulinqu_answer'], true),
            'options' => json_decode($question['qulinqu_options'], true),
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
            'totalMarks' => $data['quilin_marks'],
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

    public function finish()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        $quiz = new QuizReview($id, $this->siteUserId, $this->siteUserType);
        if (!$quiz->validate()) {
            FatUtility::dieJsonError($quiz->getError());
        }

        $quiz->setFldValue('quizat_qulinqu_id', 0);
        if (!$quiz->save()) {
            FatUtility::dieJsonError($quiz->getError());
        }
        FatUtility::dieJsonSuccess('');
    }
}
