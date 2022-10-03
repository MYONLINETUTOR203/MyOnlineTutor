<?php

class QuizAttempt extends MyAppModel
{
    public const DB_TBL = 'tbl_quiz_attempts';
    public const DB_TBL_PREFIX = 'quizat_';

    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

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
        /* validate id */
        if (!$quiz = $this->getById()) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        if ($quiz['quizat_user_id'] != $this->userId) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }

        $db = FatApp::getDb();
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

    private function setQuestion()
    {
        $quiz = QuizAttempt::getAttributesById($this->getMainTableRecordId(), [
            'quizat_qulinqu_id', 'quizat_quilin_id'
        ]);
        if (empty($quiz)) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }

        /* get next question id */
        $srch = new SearchBase(QuizLinked::DB_TBL_QUIZ_LINKED_QUESTIONS);
        $srch->addCondition('qulinqu_quilin_id', '=', $quiz['quizat_quilin_id']);
        $srch->addCondition('qulinqu_id', '>', $quiz['quizat_qulinqu_id']);
        $srch->addOrder('qulinqu_id', 'ASC');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('qulinqu_id as quizat_qulinqu_id');
        $data = FatApp::getDb()->fetch($srch->getResultSet());

        /* setup question id */
        $this->assignValues($data);
        if (!$this->save()) {
            $this->error = Label::getLabel('LBL_AN_ERROR_OCCURRED._PLEASE_TRY_AGAIN');
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
            'quilin_title', 'quilin_detail', 'quilin_type', 'quilin_questions', 'quilin_duration',
            'quilin_attempts', 'quilin_passmark', 'quilin_validity', 'quilin_certificate', 'quilin_user_id',
            'quizat_status', 'quizat_id', 'quizat_user_id'
        ]);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }
}
