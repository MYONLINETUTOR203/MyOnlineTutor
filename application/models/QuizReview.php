<?php

class QuizReview extends MyAppModel
{
    public const DB_TBL = 'tbl_quiz_attempts';
    public const DB_TBL_PREFIX = 'quizat_';

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
     * Start Quiz
     *
     * @return bool
     */
    public function start()
    {
        $this->setFldValue('quizat_qulinqu_id', 0);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        if (!$this->validate()) {
            $this->error = $this->getError();
        }
        if (!$this->setQuestion()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Get next & previous question
     *
     * @param int $next
     * @return bool
     */
    public function setQuestion(int $next = AppConstant::YES)
    {
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
            'quilin_title', 'quilin_detail', 'quizat_status', 'quizat_user_id', 'quilin_user_id', 'quizat_marks',
            'quilin_marks', 'quizat_scored', 'quilin_duration', 'quizat_started', 'quizat_updated',
            'quizat_evaluation', 'quizat_id', 'quizat_quilin_id', 'quizat_qulinqu_id', 'quilin_id', 'quilin_questions',
            'quizat_progress', 'quilin_record_type', 'quizat_active', 'quilin_attempts'
        ]);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    public function validate()
    {
        $data = $this->getById();
        if (empty($data)) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }

        if (
            ($this->userType == User::LEARNER && $data['quizat_user_id'] != $this->userId) ||
            ($this->userType == User::TEACHER && $data['quilin_user_id'] != $this->userId)
        ) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }

        if ($data['quizat_active'] == AppConstant::INACTIVE) {
            $this->error = Label::getLabel('LBL_LINK_HAS_EXPIRED_AS_THE_USER_HAS_REATTEMPTED_THE_QUIZ');
            return false;
        }

        if ($data['quizat_status'] != QuizAttempt::STATUS_COMPLETED) {
            $this->error = Label::getLabel('LBL_ACCESS_TO_PENDING_OR_IN_PROGRESS_QUIZZES_IS_NOT_ALLOWED');
            return false;
        }

        $this->quiz = $data;
        return true;
    }

    public function get()
    {
        return $this->quiz;
    }
}
