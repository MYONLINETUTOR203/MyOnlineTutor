<?php

class Quiz extends MyAppModel
{
    public const DB_TBL = 'tbl_quizzes';
    public const DB_TBL_PREFIX = 'quiz_';
    public const DB_TBL_QUIZ_QUESTIONS = 'tbl_quizzes_questions';

    public const TYPE_AUTO_GRADED = 1;
    public const TYPE_NON_GRADED = 2;

    public const STATUS_DRAFTED = 1;
    public const STATUS_PUBLISHED = 2;

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
            static::STATUS_PUBLISHED => Label::getLabel('LBL_PUBLISHED')
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Get quiz by id
     *
     * @return array
     */
    public function getById(): array
    {
        $srch = new SearchBase(self::DB_TBL, 'quiz');
        $srch->addCondition('quiz_id', '=', $this->getMainTableRecordId());
        $srch->addCondition('quiz_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addMultipleFields([
            'quiz_id', 'quiz_type', 'quiz_user_id', 'quiz_title', 'quiz_detail',
            'quiz_attempts', 'quiz_passmark', 'quiz_validity', 'quiz_failmsg', 'quiz_passmsg'
        ]);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $data = FatApp::getDb()->fetch($srch->getResultSet());
        if (!$data) {
            return [];
        }
        return $data;
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
        
        $db->startTransaction();
        /* delete question */
        $where = ['smt' => 'quique_quiz_id = ? AND quique_ques_id = ?', 'vals' => [$quizId, $quesId]];
        if (!$db->deleteRecords(static::DB_TBL_QUIZ_QUESTIONS, $where)) {
            $this->error = $db->getError();
            return false;
        }

        if (!$this->updateCount()) {
            $db->rollbackTransaction();
            return false;
        }

        /* check completion status */
        $quizStatus = $this->getCompletedStatus();
        if ($quizStatus['is_complete'] == AppConstant::YES) {
            $this->setFldValue('quiz_status', static::STATUS_PUBLISHED);
        } else {
            $this->setFldValue('quiz_status', static::STATUS_DRAFTED);
        }
        $this->setFldValue('quiz_updated', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $db->rollbackTransaction();
            $this->error = $this->getError();
            return false;
        }
        $db->commitTransaction();
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
            $type = Quiz::getAttributesById($quizId, 'quiz_type');
            if ($type != $data['quiz_type']) {
                $this->error = Label::getLabel('LBL_QUIZ_TYPE_CANNOT_BE_MODIFIED');
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
     * Binding questions with quiz
     *
     * @param array $questions
     * @return bool
     */
    public function bindQuestions(array $questions): bool
    {
        /* validate data */
        if (!$this->validate()) {
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();

        /* validate question ids */
        $srch = new SearchBase(Question::DB_TBL);
        $srch->addCondition('ques_id', 'IN', $questions);
        $srch->addCondition('ques_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('ques_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addCondition('ques_user_id', '=', $this->userId);
        $srch->doNotCalculateRecords();
        $srch->addFld('COUNT(ques_id) as count');
        $count = $db->fetch($srch->getResultSet())['count'];
        if ($count != count($questions)) {
            $this->error = Label::getLabel('LBL_INVALID_DATA_SENT');
            return false;
        }
        
        /* bind questions */
        $data = ['quique_quiz_id' => $this->getMainTableRecordId()];
        foreach ($questions as $quesId) {
            $data['quique_ques_id'] = $quesId;
            if (!$db->insertFromArray(static::DB_TBL_QUIZ_QUESTIONS, $data, false, [], $data)) {
                $db->rollbackTransaction();
                return false;
            }
        }
        
        if (!$this->updateCount()) {
            $db->rollbackTransaction();
            return false;
        }

        /* check completion status */
        $quizStatus = $this->getCompletedStatus();
        if ($quizStatus['is_complete'] == AppConstant::YES) {
            $this->setFldValue('quiz_status', static::STATUS_PUBLISHED);
        } else {
            $this->setFldValue('quiz_status', static::STATUS_DRAFTED);
        }
        $this->setFldValue('quiz_updated', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $db->rollbackTransaction();
            $this->error = $this->getError();
            return false;
        }

        $db->commitTransaction();
        return true;
    }

    /**
     * Setup Settings
     *
     * @param array $data
     * @param int   $langId
     * @return bool
     */
    public function setupSettings(array $data, int $langId): bool
    {
        $srch = CertificateTemplate::getSearchObject($langId);
        $srch->addCondition('certpl_code', '=', 'evaluation_certificate');
        $srch->addCondition('certpl_status', '=', AppConstant::ACTIVE);
        if (!FatApp::getDb()->fetch($srch->getResultSet()) && $data['quiz_certificate'] == AppConstant::YES) {
            $this->error = Label::getLabel('LBL_OFFER_CERTIFICATE_OPTION_HAS_BEEN_DISABLED_BY_ADMIN');
            return false;
        }
        $data['quiz_duration'] = $data['quiz_duration'] * 60;
        $data['quiz_validity'] = $data['quiz_validity'] * 3600;
        $this->assignValues($data);

        /* check completion status */
        $quizStatus = $this->getCompletedStatus();
        if ($quizStatus['is_complete'] == AppConstant::YES) {
            $this->setFldValue('quiz_status', static::STATUS_PUBLISHED);
        } else {
            $this->setFldValue('quiz_status', static::STATUS_DRAFTED);
        }
        $this->setFldValue('quiz_updated', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Update display order
     *
     * @param array $order
     * @return bool
     */
    public function updateOrder(array $order): bool
    {
        $order = FatApp::getPostedData('order');
        if (empty($order)) {
            $this->error = Label::getLabel('LBL_INVALID_DATA_SENT');
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        foreach ($order as $i => $id) {
            if (FatUtility::int($id) < 1) {
                continue;
            }
            $data = explode('_', $id);
            if (
                !$db->updateFromArray(
                    Quiz::DB_TBL_QUIZ_QUESTIONS,
                    ['quique_order' => $i],
                    ['smt' => 'quique_quiz_id = ? AND quique_ques_id = ?', 'vals' => [$data[0], $data[1]]]
                )
            ) {
                $db->rollbackTransaction();
                $this->error = Label::getLabel('LBL_AN_ERROR_OCCURRED_WHILE_UPDATING_ORDER');
                return false;
            }
        }
        $db->commitTransaction();
        return true;
    }

    /**
     * Get quiz completed status
     *
     * @return array
     */
    public function getCompletedStatus()
    {
        if (!$data = $this->getById()) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        if ($this->userId != $data['quiz_user_id']) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        $criteria = ['general' => 0, 'settings' => 0, 'questions' => 0, 'is_complete' => AppConstant::NO];

        /* get basic data */
        if (!empty($data['quiz_type'])) {
            $criteria['general'] = 1;
        }

        /* get questions count */
        $srch = new QuizQuestionSearch(0, $this->userId, User::TEACHER);
        $srch->addCondition('quique_quiz_id', '=', $this->getMainTableRecordId());
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->setPageSize(1);
        $questions = $srch->fetchAndFormat();
        if (count($questions) > 0) {
            $criteria['questions'] = 1;
        }

        /* check settings data */
        if (
            !empty($data['quiz_attempts']) && !empty($data['quiz_passmark']) && !empty($data['quiz_validity']) &&
            !empty($data['quiz_failmsg']) && !empty($data['quiz_passmsg'])
        ) {
            $criteria['settings'] = 1;
        }

        if ($criteria['general'] == 1 && $criteria['questions'] == 1 && $criteria['settings'] == 1) {
            $criteria['is_complete'] = AppConstant::YES;
        }
        return $criteria;
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

    /**
     * Count & update no of questions in a quiz
     *
     * @return bool
     */
    private function updateCount(): bool
    {
        $srch = new SearchBase(static::DB_TBL_QUIZ_QUESTIONS, 'quique');
        $srch->joinTable(static::DB_TBL, 'INNER JOIN', 'quiz_id = quique_quiz_id', 'quiz');
        $srch->doNotCalculateRecords();
        $srch->addCondition('quiz.quiz_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addCondition('quiz.quiz_active', '=', AppConstant::ACTIVE);
        $srch->addCondition('quiz_user_id', '=', $this->userId);
        $srch->addCondition('quique_quiz_id', '=', $this->getMainTableRecordId());
        $srch->addFld('COUNT(quique_ques_id) as quiz_questions');
        $data = FatApp::getDb()->fetch($srch->getResultSet());
        $this->assignValues($data);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }
}
