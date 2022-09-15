<?php

class Quiz extends MyAppModel
{
    public const DB_TBL = 'tbl_quizzes';
    public const DB_TBL_PREFIX = 'quiz_';
    public const DB_TBL_QUIZ_QUESTIONS = 'tbl_quizzes_questions';

    public const TYPE_AUTO_GRADED = 1;
    public const TYPE_NON_GRADED = 2;

    public const STATUS_DRAFTED = 1;
    public const STATUS_COMPLETED = 2;

    private $userId;

    /**
     * Initialize Quiz
     *
     * @param int $id
     * @param int $userId
     */
    public function __construct(int $id = 0, int $userId = 0)
    {
        $this->userId = $userId;
        parent::__construct(static::DB_TBL, 'quiz_id', $id);
    }

    /**
     * Get Quiz Types
     *
     * @param int $key
     * @return string|array
     */
    public static function getTypes(int $key = null)
    {
        $arr = [
            static::TYPE_AUTO_GRADED => Label::getLabel('LBL_AUTO_GRADED'),
            static::TYPE_NON_GRADED => Label::getLabel('LBL_NON_GRADED')
        ];
        return AppConstant::returArrValue($arr, $key);
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
            static::STATUS_DRAFTED => Label::getLabel('LBL_DRAFTED'),
            static::STATUS_COMPLETED => Label::getLabel('LBL_COMPLETED')
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Get quiz by id
     *
     * @return array
     */
    public function getById()
    {
        $srch = new SearchBase(self::DB_TBL, 'quiz');
        $srch->addCondition('quiz_id', '=', $this->getMainTableRecordId());
        $srch->addCondition('quiz_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addMultipleFields(['quiz_id', 'quiz_type', 'quiz_user_id', 'quiz_title', 'quiz_detail']);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    /**
     * Delete
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $this->setFldValue('quiz_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Delete binded questions
     *
     * @param int $quesId
     * @return boolean
     */
    public function deleteQuestion(int $quesId): bool
    {
        $db = FatApp::getDb();
        $quizId = $this->getMainTableRecordId();

        /* validate data */
        $srch = new SearchBase(static::DB_TBL_QUIZ_QUESTIONS);
        $srch->addCondition('quique_quiz_id', '=', $quizId);
        $srch->addCondition('quique_ques_id', '=', $quesId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        if (!$db->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }

        /* validate quiz */
        if (!$this->validate()) {
            return false;
        }
        
        /* delete question */
        $where = ['smt' => 'quique_quiz_id = ? AND quique_ques_id = ?', 'vals' => [$quizId, $quesId]];
        if (!$db->deleteRecords(static::DB_TBL_QUIZ_QUESTIONS, $where)) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }

    /**
     * Setup quiz basic details
     *
     * @param array $data
     * @return bool
     */
    public function setup(array $data): bool
    {
        $quizId = $this->getMainTableRecordId();
        if ($quizId > 0) {
            if (!$this->validate()) {
                return false;
            }
        }
        $this->assignValues($data);
        $this->assignValues([
            'quiz_user_id' => $this->userId,
            'quiz_active' => AppConstant::ACTIVE,
            'quiz_status' => static::STATUS_DRAFTED,
            'quiz_updated' => date('Y-m-d H:i:s')
        ]);
        if (empty($quizId)) {
            $this->setFldValue('quiz_created', date('Y-m-d H:i:s'));
        }
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Update quiz status
     *
     * @param int $status
     * @return bool
     */
    public function updateStatus(int $status): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $status = ($status == AppConstant::ACTIVE) ? AppConstant::INACTIVE : AppConstant::ACTIVE;
        $this->setFldValue('quiz_active', $status);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Validate quiz for update & delete actions
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (!$quiz = $this->getById()) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        if ($this->userId != $quiz['quiz_user_id']) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        return true;
    }
}
