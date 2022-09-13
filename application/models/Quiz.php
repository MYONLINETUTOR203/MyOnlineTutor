<?php

class Quiz extends MyAppModel
{
    public const DB_TBL = 'tbl_quizzes';
    public const DB_TBL_PREFIX = 'quiz_';

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
        $srch->addMultipleFields(['quiz_id', 'quiz_type', 'quiz_user_id']);
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
        if (!$quiz = $this->getById()) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        if ($this->userId != $quiz['quiz_user_id']) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        $this->setFldValue('quiz_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }
}
