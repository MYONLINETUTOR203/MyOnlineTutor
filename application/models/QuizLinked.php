<?php

class QuizLinked extends MyAppModel
{
    public const DB_TBL = 'tbl_quiz_linked';
    public const DB_TBL_PREFIX = 'quilin_';
    public const DB_TBL_QUIZ_LINKED_QUESTIONS = 'tbl_quiz_linked_questions';
    public const DB_TBL_USER_QUIZZES = 'tbl_users_quizzes';

    private $session = [];
    /**
     * Initialize Quiz
     *
     * @param int $id
     * @param int $userId
     * @param int $userType
     * @param int $langId
     */
    public function __construct(int $id = 0, int $userId = 0, int $userType = 0, int $langId = 0)
    {
        parent::__construct(static::DB_TBL, 'quilin_id', $id);
        $this->userId = $userId;
        $this->userType = $userType;
        $this->langId = $langId;
    }

    /**
     * Validate lesson, class & course
     *
     * @param int $recordId
     * @param int $recordType
     * @return bool
     */
    public function validateRecordId(int $recordId, int $recordType)
    {
        if ($recordType == AppConstant::LESSON) {
            $srch = new LessonSearch($this->langId, $this->userId, $this->userType);
            $srch->applyPrimaryConditions();
            $srch->addCondition('ordles_id', '=', $recordId);
            $srch->addMultipleFields([
                'ordles_id', 'learner.user_first_name AS learner_first_name', 
                'learner.user_last_name AS learner_last_name', 'teacher.user_first_name as teacher_first_name',
                'teacher.user_last_name as teacher_last_name', 'learner.user_lang_id', 'learner.user_email'
            ]);
            $srch->setPageSize(1);
            $srch->doNotCalculateRecords();
            if (!$data = FatApp::getDb()->fetch($srch->getResultSet())) {
                $this->error = Label::getLabel('LBL_INVALID_LESSON');
                return false;
            }
        }
        if ($recordType == AppConstant::GCLASS) {
            $srch = new ClassSearch($this->langId, $this->userId, $this->userType);
            $srch->applyPrimaryConditions();
            $srch->addCondition('grpcls_id', '=', $recordId);
            $srch->addMultipleFields([
                'ordcls_id', 'learner.user_first_name AS learner_first_name', 
                'learner.user_last_name AS learner_last_name', 'teacher.user_first_name as teacher_first_name',
                'teacher.user_last_name as teacher_last_name', 'learner.user_lang_id', 'learner.user_email'
            ]);
            $srch->setPageSize(1);
            if (!$data = FatApp::getDb()->fetch($srch->getResultSet())) {
                $this->error = Label::getLabel('LBL_INVALID_CLASS');
                return false;
            }
        }
        if ($recordType == AppConstant::COURSE) {
            return true;
        }
        $this->session['session'] = $data;
        return true;
    }

    /**
     * Attach quizzes with lessons, classes & courses
     *
     * @param int $recordId
     * @param int $recordType
     * @param array $quizzes
     * @return bool
     */
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
        $quizList = FatApp::getDb()->fetchAll($srch->getResultSet(), 'quiz_id');
        if (count($quizList) != count($quizzes)) {
            $this->error = Label::getLabel('LBL_SOME_QUIZZES_ARE_NOT_AVAILABLE._PLEASE_TRY_AGAIN');
            return false;
        }
        $this->session['quizzes'] = $quizList;

        $db = FatApp::getDb();
        $db->startTransaction();
        $quizLinkedIds = [];
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
                'quilin_validity' => date("Y-m-d H:i:s", strtotime('+' . $quiz['quiz_validity'] . ' hours')),
                'quilin_certificate' => $quiz['quiz_certificate'],
                'quilin_questions' => $quiz['quiz_questions'], 
                'quilin_created' => date('Y-m-d H:i:s')
            ]);
            if (!$quizLink->addNew()) {
                $db->rollbackTransaction();
                $this->error = $quizLink->getError();
                return false;
            }
            $quizLinkedIds[$quiz['quiz_id']] = $quizLink->getId();
        }
        /* attach questions */
        if (!$this->setupQuestions($quizLinkedIds)) {
            $db->rollbackTransaction();
            return false;
        }
        $this->sendQuizAttachedNotification();
        $db->commitTransaction();

        return true;
    }

    /**
     * Send Cancel Class Notification
     * 
     * @param array $ordClses
     * @param array $class
     */
    private function sendQuizAttachedNotification()
    {
        $user = $this->session['session'];
        $quizzes = $this->session['quizzes'];


        // $noti = new Notification($value['user_id'], Notification::TYPE_CLASS_CANCELLED);
        // $noti->sendNotification(['{link}' => $url, '{class_name}' => $class['grpcls_title']], User::LEARNER);
        $mail = new FatMailer($value['learner_lang_id'], 'quiz_attached_email');
        $vars = [
            '{class_name}' => $class['grpcls_title'],
            '{teacher_comment}' => $class['comment'],
            '{learner_name}' => $value['learner_first_name'] . ' ' . $value['learner_last_name'],
            '{teacher_name}' => $value['teacher_first_name'] . ' ' . $value['teacher_last_name'],
            '{class_url}' => $url,
        ];
        $mail->setVariables($vars);
        $mail->sendMail([$value['learner_email']]);
    }

    /**
     * Setup Questions data
     *
     * @param array $data
     * @return bool
     */
    private function setupQuestions(array $data)
    {
        $srch = new QuizQuestionSearch($this->langId, $this->userId, $this->userType);
        $srch->addCondition('quique_quiz_id', 'IN', array_keys($data));
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->setOrder();
        $srch->joinCategory();
        $questions = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (count($questions) < 1) {
            $this->error = Label::getLabel('LBL_QUESTIONS_NOT_FOUND');
            return false;
        }
        $questionIds = array_column($questions, 'ques_id');

        $srch = new SearchBase(Question::DB_TBL_OPTIONS, 'queopt');
        $srch->addMultipleFields(['queopt_id', 'queopt_title', 'queopt_ques_id']);
        $srch->addCondition('queopt_ques_id', 'IN', $questionIds);
        $srch->doNotCalculateRecords();
        $srch->addOrder('queopt_order', 'ASC');
        $options = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (count($options) < 1) {
            $this->error = Label::getLabel('LBL_QUESTION_OPTIONS_ARE_NOT_AVAILABLE');
            return false;
        }
        $quesOptions = [];
        foreach ($options as $option) {
            $quesOptions[$option['queopt_ques_id']][] = $option;
        }

        foreach ($questions as $question) {
            $linkQues = new TableRecord(static::DB_TBL_QUIZ_LINKED_QUESTIONS);
            $linkQues->assignValues([
                'qulinqu_type' => $question['ques_type'],
                'qulinqu_quilin_id' => $data[$question['quiz_id']],
                'qulinqu_ques_id' => $question['ques_id'],
                'qulinqu_title' => $question['ques_title'],
                'qulinqu_detail' => $question['ques_detail'],
                'qulinqu_hint' => $question['ques_hint'],
                'qulinqu_marks' => $question['ques_marks'],
                'qulinqu_answer' => $question['ques_answer'],
                'qulinqu_options' => json_encode($quesOptions[$question['ques_id']]),
            ]);
            if (!$linkQues->addNew()) {
                $this->error = $linkQues->getError();
                return false;
            }
        }
        return true;
    }


    /**
     * Get Attached Quiz
     * 
     * @param array $recordIds
     * @param int   $type
     * @param bool  $single
     * @return array
     */
    public static function getQuizzes(array $recordIds, int $type, bool $single = false): array
    {
        if (count($recordIds) == 0) {
            return [];
        }
        $srch = new SearchBase(static::DB_TBL, 'quilin');
        $srch->addCondition('quilin.quilin_record_id', 'IN', array_unique($recordIds));
        $srch->addCondition('quilin.quilin_record_type', '=', $type);
        $srch->addCondition('quilin.quilin_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->doNotCalculateRecords();
        if ($single == true) {
            $srch->addMultipleFields([
                'quilin_record_id', 'quilin_id', 'quilin_title', 'quilin_type'
            ]);
            $key =  'quilin_id';
        } else {
            $key = 'quilin_record_id';
            $srch->addMultipleFields(['quilin_record_id', 'COUNT(*) as quiz_count']);
            $srch->addGroupBy('quilin_record_id');
        }
        // pr($srch->getQuery());
        return FatApp::getDb()->fetchAll($srch->getResultSet(), $key);
    }

    /**
     * Remove attached quiz
     *
     * @return bool
     */
    public function delete()
    {
        $id = $this->getMainTableRecordId();
        $data = QuizLinked::getAttributesById($id, ['quilin_deleted', 'quilin_user_id']);
        if (!$data || !empty($data['quilin_deleted'])) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        if ($data['quilin_user_id'] != $this->userId) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        /* check if quiz already attended by any user */
        $srch = new SearchBase(static::DB_TBL_USER_QUIZZES);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('userquiz_id');
        $srch->addCondition('userquiz_quilin_id', '=', $id);
        if (FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_ATTENDED_QUIZZES_CANNOT_BE_DELETED');
            return false;
        }

        $this->setFldValue('quilin_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }
}
