<?php

class QuizLinked extends MyAppModel
{
    public const DB_TBL = 'tbl_quiz_linked';
    public const DB_TBL_PREFIX = 'quilin_';

    /**
     * Initialize Quiz
     *
     * @param int $id
     * @param int $userId
     * @param int $userType
     * @param int $langId
     */
    public function __construct(int $id =0, int $userId = 0, int $userType = 0, int $langId = 0)
    {
        parent::__construct(static::DB_TBL, 'quilin_id', $id);
        $this->userId = $userId;
        $this->userType = $userType;
        $this->langId = $langId;
    }

    public function validateRecordId(int $recordId, int $recordType)
    {
        if ($recordType == AppConstant::LESSON) {
            $srch = new LessonSearch($this->langId, $this->userId, $this->userType);
            $srch->applyPrimaryConditions();
            $srch->addCondition('ordles_id', '=', $recordId);
            $srch->addFld('ordles_id');
            $srch->setPageSize(1);
            $srch->doNotCalculateRecords();
            if (!FatApp::getDb()->fetch($srch->getResultSet())) {
                $this->error = Label::getLabel('LBL_INVALID_LESSON');
                return false;
            }
        }
        if ($recordType == AppConstant::GCLASS) {
            $srch = new ClassSearch($this->langId, $this->userId, $this->userType);
            $srch->applyPrimaryConditions();
            $srch->addCondition('grpcls_id', '=', $recordId);
            $srch->addSearchListingFields();
            $srch->setPageSize(1);
            if (!FatApp::getDb()->fetch($srch->getResultSet())) {
                $this->error = Label::getLabel('LBL_INVALID_CLASS');
                return false;
            }
        }
        if ($recordType == AppConstant::COURSE) {
            return true;
        }
        return true;
    }

    public function setup(int $recordId, int $recordType, array $quizzes)
    {
        if ($recordId < 1 || !array_key_exists($recordType, AppConstant::getSessionTypes()) || count($quizzes) < 1) {
            $this->error = Label::getLabel('LBL_INVALID_DATA_SENT');
            return false;
        }

        if (!$this->validateRecordId($recordId, $recordType)) {
            return false;
        }

        $srch = new SearchBase(QuizLinked::DB_TBL);
        $srch->addCondition('quilin_record_id', '=', $recordId);
        $srch->addCondition('quilin_record_type', '=', $recordType);
        $srch->addCondition('quilin_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addCondition('quilin_quiz_id', 'IN', $quizzes);
        $srch->addFld('quilin_quiz_id');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        if (FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_SOME_QUIZ(S)_ARE_ALREADY_ATTACHED._PLEASE_TRY_REFRESHING_THE_LIST');
            return false;
        }

        /* validate quizzes list */
        $srch = new QuizSearch(0, $this->userId, $this->userType);
        $srch->addSearchListingFields();
        $srch->applyPrimaryConditions();
        $srch->addCondition('quiz.quiz_active', '=', AppConstant::ACTIVE);
        $srch->addCondition('quiz.quiz_status', '=', Quiz::STATUS_PUBLISHED);
        $srch->addCondition('quiz.quiz_id', 'IN', $quizzes);
        $quizList = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (count($quizList) != count($quizzes)) {
            $this->error = Label::getLabel('LBL_SOME_QUIZZES_ARE_NOT_AVAILABLE._PLEASE_TRY_AGAIN');
            return false;
        }

        $db = FatApp::getDb();
        foreach ($quizList as $quiz) {
            $quizLink = new TableRecord(static::DB_TBL);
            $quizLink->assignValues([
                'quilin_quiz_id' => $quiz['quiz_id'],
                'quilin_type' => $quiz['quiz_type'],
                'quilin_title' => $quiz['quiz_title'],
                'quilin_detail' => $quiz['quiz_detail'],
                'quilin_user_id' => $this->userId,
                'quilin_record_id' => $recordId,
                'quilin_record_type' => $recordType,
                'quilin_duration' => $quiz['quiz_duration'],
                'quilin_attempts' => $quiz['quiz_attempts'],
                'quilin_passmark' => $quiz['quiz_passmark'],
                'quilin_failmsg' => $quiz['quiz_failmsg'],
                'quilin_passmsg' => $quiz['quiz_passmsg'],
                'quilin_validity' => $quiz['quiz_validity'],
                'quilin_certificate' => $quiz['quiz_certificate'],
                'quilin_questions' => $quiz['quiz_questions'], 
                'quilin_created' => date('Y-m-d H:i:s')
            ]);
            if (!$quizLink->addNew()) {
                $db->rollbackTransaction();
                $this->error = $quizLink->getError();
                return false;
            }
        }
        $db->commitTransaction();

        return true;
    }
}
