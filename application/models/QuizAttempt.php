<?php

class QuizAttempt extends MyAppModel
{
    public const DB_TBL = 'tbl_quiz_attempts';
    public const DB_TBL_PREFIX = 'quizat_';
    public const DB_TBL_QUESTIONS = 'tbl_quiz_attempts_questions';

    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

    private $quiz;

    /**
     * Initialize User Quiz
     *
     * @param int $id
     * @param int $userId
     * @param int $userType
     * @param int $langId
     */
    public function __construct(int $id = 0, int $userId = 0, int $userType = 0, int $langId = 0)
    {
        parent::__construct(static::DB_TBL, 'quizat_id', $id);
        $this->userId = $userId;
        $this->userType = $userType;
        $this->langId = $langId;
    }

    /**
     * Get Quiz Status
     *
     * @param int $key
     * @return string|array
     */
    public static function getStatuses(int $key = null)
    {
        $arr = [
            static::STATUS_PENDING => Label::getLabel('LBL_PENDING'),
            static::STATUS_IN_PROGRESS => Label::getLabel('LBL_IN_PROGRESS'),
            static::STATUS_COMPLETED => Label::getLabel('LBL_COMPLETED')
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Start Quiz
     *
     * @return bool
     */
    public function start()
    {
        /* validate */
        if (!$this->validate(QuizAttempt::STATUS_PENDING)) {
            return false;
        }

        $db = FatApp::getDb();
        $db->startTransaction();

        $this->assignValues([
            'quizat_status' => static::STATUS_IN_PROGRESS,
            'quizat_created' => date('Y-m-d H:i:s'),
        ]);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }

        if (!$this->setQuestion()) {
            $db->rollbackTransaction();
            $this->error = $this->getError();
            return false;
        }

        $db->commitTransaction();
        return true;
    }

    public function setup($data, $next = AppConstant::NO)
    {
        if (!$this->validate(QuizAttempt::STATUS_IN_PROGRESS)) {
            return false;
        }

        $question = QuizLinked::getQuestionById($data['ques_id']);
        if (empty($question) || $data['ques_id'] != $this->quiz['quizat_qulinqu_id']) {
            $this->error = Label::getLabel('LBL_QUESTION_NOT_FOUND');
            return false;
        }
        if ($question['qulinqu_type'] != $data['ques_type']) {
            $this->error = Label::getLabel('LBL_INVALID_QUESTION_TYPE');
            return false;
        }
        
        $db = FatApp::getDb();
        $db->startTransaction();

        $quesAttempt = new TableRecord(static::DB_TBL_QUESTIONS);
        $answer = $data['ques_answer'];
        if ($data['ques_type'] == Question::TYPE_MANUAL) {
            $answer = [$data['ques_answer']];
        }
        $assignValues = [
            'quatqu_quizat_id' => $data['ques_attempt_id'],
            'quatqu_id' => $data['quatqu_id'],
            'quatqu_qulinqu_id' => $data['ques_id'],
            'quatqu_answer' => json_encode($answer),
        ];
        $quesAttempt->assignValues($assignValues);
        if (!$quesAttempt->addNew([], $assignValues)) {
            $this->error = $quesAttempt->getError();
            return false;
        }

        if ($question['qulinqu_type'] != Question::TYPE_MANUAL) {
            $assignValues['quatqu_id'] = empty($data['quatqu_id']) ? $quesAttempt->getId() : $data['quatqu_id'];
            if (!$this->setupQuesScore($assignValues)) {
                return false;
            }
        }

        /* calculations */
        if (!$this->setupQuizProgress()) {
            return false;
        }

        if ($next == AppConstant::YES) {
            if (!$this->setQuestion(AppConstant::YES)) {
                $db->rollbackTransaction();
                return false;
            }
        }
        $db->commitTransaction();
        return true;
    }

    public function setupQuesScore($data)
    {
        /* get answers */
        $ques = QuizLinked::getQuestionById($data['quatqu_qulinqu_id']);
        if (!$ques) {
            $this->error = Label::getLabel('LBL_AN_ERROR_OCCURRED');
            return false;
        }
        $correctAnswers = json_decode($ques['qulinqu_answer'], true);
        $submittedAnswers = json_decode($data['quatqu_answer'], true);
        $answers = array_intersect($correctAnswers, $submittedAnswers);

        $marksPerAnswer = $ques['qulinqu_marks'] / count($correctAnswers);
        $answeredScore = $marksPerAnswer * count($answers);

        $quesAttempt = new TableRecord(static::DB_TBL_QUESTIONS);
        $assignValues = [
            'quatqu_id' => $data['quatqu_id'],
            'quatqu_scored' => $answeredScore
        ];
        $quesAttempt->assignValues($assignValues);
        if (!$quesAttempt->addNew([], $assignValues)) {
            $this->error = $quesAttempt->getError();
            return false;
        }
        return true;
    }

    public function setupQuizProgress()
    {
        $progress = $score = 0;
        $srch = new SearchBase(QuizAttempt::DB_TBL_QUESTIONS);
        $srch->addCondition('quatqu_quizat_id', '=', $this->getMainTableRecordId());
        $srch->doNotCalculateRecords();
        $srch->addFld('COUNT(quatqu_quizat_id) as attempted_questions');
        $srch->addFld('SUM(quatqu_scored) as total_score');
        if ($quesCount = FatApp::getDb()->fetch($srch->getResultSet())) {
            $progress = ($quesCount['attempted_questions'] * 100) / $this->quiz['quilin_questions'];
            $score = $quesCount['total_score'];
        }

        $this->assignValues([
            'quizat_progress' => $progress,
            'quizat_scored' => $score,
        ]);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    public function markComplete()
    {
        if (!$this->validate(QuizAttempt::STATUS_IN_PROGRESS)) {
            return false;
        }
        $this->assignValues([
            'quizat_qulinqu_id' => 0,
            'quizat_status' => QuizAttempt::STATUS_COMPLETED,
            'quizat_updated' => date('Y-m-d H:i:s')
        ]);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Get data by id
     *
     * @return array
     */
    public function getById()
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->joinTable(QuizLinked::DB_TBL, 'INNER JOIN', 'quizat_quilin_id = quilin_id');
        $srch->addCondition('quizat_id', '=', $this->getMainTableRecordId());
        $srch->addMultipleFields([
            'quilin_title', 'quilin_detail', 'quilin_type', 'quilin_questions', 'quilin_duration', 'quilin_record_type',
            'quilin_attempts', 'quilin_marks', 'quilin_passmark', 'quilin_validity', 'quilin_certificate',
            'quilin_user_id', 'quizat_status', 'quizat_id', 'quizat_user_id', 'quizat_qulinqu_id', 'quizat_progress',
            'quilin_id', 'quizat_quilin_id'
        ]);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    public function setQuestion($next = AppConstant::YES)
    {
        if (!$this->validate(QuizAttempt::STATUS_IN_PROGRESS)) {
            return false;
        }

        /* get next question id */
        $srch = new SearchBase(QuizLinked::DB_TBL_QUIZ_LINKED_QUESTIONS);
        $srch->addCondition('qulinqu_quilin_id', '=', $this->quiz['quizat_quilin_id']);
        if ($next == AppConstant::NO) {
            $srch->addCondition('qulinqu_id', '<', $this->quiz['quizat_qulinqu_id']);
            $srch->addOrder('qulinqu_id', 'DESC');
        } else {
            $srch->addCondition('qulinqu_id', '>', $this->quiz['quizat_qulinqu_id']);
            $srch->addOrder('qulinqu_id', 'ASC');
        }
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('qulinqu_id as quizat_qulinqu_id');
        $data = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($data)) {
            $data = ['quizat_qulinqu_id' => 0];
        }

        /* setup question id */
        $this->assignValues($data);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }

        return true;
    }

    private function validate(int $status)
    {
        $quiz = $this->getById();
        if (empty($quiz)) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        /* validate logged in user */
        if ($quiz['quizat_user_id'] != $this->userId) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        if ($quiz['quizat_status'] != $status) {
            if ($status == QuizAttempt::STATUS_IN_PROGRESS) {
                $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS_TO_UNATTENDED_OR_COMPLETED_QUIZ');
            } elseif ($status == QuizAttempt::STATUS_PENDING) {
                $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS_TO_IN_PROGRESS_OR_COMPLETED_QUIZ');
            }
            return false;
        }
        $this->quiz = $quiz;
        return true;
    }
}
