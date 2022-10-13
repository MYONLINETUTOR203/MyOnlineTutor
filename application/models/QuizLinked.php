<?php

class QuizLinked extends MyAppModel
{
    public const DB_TBL = 'tbl_quiz_linked';
    public const DB_TBL_PREFIX = 'quilin_';
    public const DB_TBL_QUIZ_LINKED_QUESTIONS = 'tbl_quiz_linked_questions';

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
        $srch->addOrder('quiz_id', 'DESC');
        $quizList = FatApp::getDb()->fetchAll($srch->getResultSet(), 'quiz_id');
        if (count($quizList) != count($quizzes)) {
            $this->error = Label::getLabel('LBL_SOME_QUIZZES_ARE_NOT_AVAILABLE._PLEASE_TRY_AGAIN');
            return false;
        }

        $db = FatApp::getDb();
        $db->startTransaction();
        $quizzesData = $quizLinkedIds = [];
        foreach ($quizList as $quiz) {
            $quizLink = new TableRecord(static::DB_TBL);
            $data = [
                'quilin_quiz_id' => $quiz['quiz_id'],
                'quilin_type' => $quiz['quiz_type'],
                'quilin_title' => $quiz['quiz_title'],
                'quilin_detail' => $quiz['quiz_detail'],
                'quilin_user_id' => $this->userId,
                'quilin_record_id' => $recordId,
                'quilin_record_type' => $recordType,
                'quilin_duration' => $quiz['quiz_duration'],
                'quilin_attempts' => $quiz['quiz_attempts'],
                'quilin_marks' => $quiz['quiz_marks'],
                'quilin_passmark' => $quiz['quiz_passmark'],
                'quilin_failmsg' => $quiz['quiz_failmsg'],
                'quilin_passmsg' => $quiz['quiz_passmsg'],
                'quilin_validity' => date("Y-m-d H:i:s", strtotime('+' . $quiz['quiz_validity'] . ' hours')),
                'quilin_certificate' => $quiz['quiz_certificate'],
                'quilin_questions' => $quiz['quiz_questions'],
                'quilin_created' => date('Y-m-d H:i:s')
            ];
            $quizLink->assignValues($data);
            if (!$quizLink->addNew()) {
                $db->rollbackTransaction();
                $this->error = $quizLink->getError();
                return false;
            }
            $quizzesData[$quiz['quiz_id']] = $data + ['quilin_id' => $quizLink->getId()];
            $quizLinkedIds[] = $quizLink->getId();
        }
        /* setup user quizzes */
        if (!$this->setupUserQuizzes($recordId, $recordType, $quizLinkedIds)) {
            $db->rollbackTransaction();
            return false;
        }

        /* attach questions */
        if (!$this->setupQuestions($quizzesData)) {
            $db->rollbackTransaction();
            return false;
        }

        $this->sendQuizAttachedNotification($quizzesData);

        $db->commitTransaction();

        return true;
    }

    /**
     * Get Attached Quiz
     *
     * @param array $recordIds
     * @param int   $type
     * @return array
     */
    public static function getQuizzes(array $recordIds, int $type): array
    {
        if (count($recordIds) == 0) {
            return [];
        }
        $srch = new SearchBase(static::DB_TBL, 'quilin');
        $srch->addCondition('quilin.quilin_record_id', 'IN', array_unique($recordIds));
        $srch->addCondition('quilin.quilin_record_type', '=', $type);
        $srch->addCondition('quilin.quilin_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields(['quilin_record_id', 'COUNT(*) as quiz_count']);
        $srch->addGroupBy('quilin_record_id');
        return FatApp::getDb()->fetchAll($srch->getResultSet(), 'quilin_record_id');
    }

    /**
     * Get Attached Quiz
     *
     * @param int $recordIds
     * @param int $type
     * @return array
     */
    public function getAttachedQuizzes(int $recordId, int $type): array
    {
        if ($recordId < 1) {
            return [];
        }

        $srch = new SearchBase(static::DB_TBL, 'quilin');
        $srch->addCondition('quilin.quilin_record_id', '=', $recordId);
        $srch->addCondition('quilin.quilin_record_type', '=', $type);
        $srch->addCondition('quilin.quilin_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addMultipleFields([
            'quilin_record_id', 'quilin_id', 'quilin_title', 'quilin_type', 'quilin_validity'
        ]);
        $srch->doNotCalculateRecords();
        $srch->addOrder('quilin_id', 'DESC');
        $attachedQuizzes = FatApp::getDb()->fetchAll($srch->getResultSet(), 'quilin_id');
        if (empty($attachedQuizzes)) {
            return [];
        }
        
        $quizLinkedIds = array_column($attachedQuizzes, 'quilin_id');
        $srch = new SearchBase(QuizAttempt::DB_TBL);
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'user_id = quizat_user_id');
        $srch->addCondition('quizat_quilin_id', 'IN', $quizLinkedIds);
        $srch->addCondition('quizat_active', '=', AppConstant::YES);
        if ($this->userType == User::LEARNER) {
            $srch->addCondition('quizat_user_id', '=', $this->userId);
        }
        $srch->addMultipleFields([
            'user_first_name', 'user_last_name', 'quizat_status', 'quizat_quilin_id', 'quizat_id'
        ]);
        $srch->addOrder('quizat_id');
        $usersList = FatApp::getDb()->fetchAll($srch->getResultSet());
        foreach ($usersList as $user) {
            if ($type == AppConstant::LESSON || $this->userType == User::LEARNER) {
                $attachedQuizzes[$user['quizat_quilin_id']]['users'] = $user;
            } else {
                $attachedQuizzes[$user['quizat_quilin_id']]['users'][] = $user;
            }
        }
        return $attachedQuizzes;
    }

    /**
     * Remove attached quiz
     *
     * @return bool
     */
    public function delete()
    {
        $id = $this->getMainTableRecordId();
        $data = QuizLinked::getAttributesById($id, [
            'quilin_deleted', 'quilin_user_id', 'quilin_record_id', 'quilin_record_type', 'quilin_title', 'quilin_id'
        ]);
        if (!$data || !empty($data['quilin_deleted'])) {
            $this->error = Label::getLabel('LBL_QUIZ_NOT_FOUND');
            return false;
        }
        if ($data['quilin_user_id'] != $this->userId) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        /* check if quiz already attended by any user */
        $srch = new SearchBase(QuizAttempt::DB_TBL);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('quizat_id');
        $srch->addCondition('quizat_quilin_id', '=', $id);
        $srch->addCondition('quizat_status', '!=', QuizAttempt::STATUS_PENDING);
        if (FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_ATTENDED_QUIZZES_CANNOT_BE_DELETED');
            return false;
        }

        $db = FatApp::getDb();
        $db->startTransaction();
        $this->setFldValue('quilin_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }

        /* delet user quiz */
        if (!$db->deleteRecords(QuizAttempt::DB_TBL, ['smt' => 'quizat_quilin_id = ?', 'vals' => [$id]])) {
            $db->rollbackTransaction();
            $this->error = $db->getError();
            return false;
        }

        $this->sendQuizRemovedNotification($data);
        $db->commitTransaction();
        return true;
    }

    /**
     * Get Linked Quiz Details
     *
     * @return array
     */
    public function getById(): array
    {
        $srch = new SearchBase(static::DB_TBL);
        $srch->addCondition('quilin_id', '=', $this->getMainTableRecordId());
        $srch->setPageSize(1);
        $srch->doNotCalculateRecords();
        $quiz = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($quiz)) {
            return [];
        }
        return $quiz;
    }

    /**
     * Get question by id
     *
     * @param int $id
     * @return array
     */
    public static function getQuestionById(int $id)
    {
        $srch = new SearchBase(static::DB_TBL_QUIZ_LINKED_QUESTIONS);
        $srch->addCondition('qulinqu_id', '=', $id);
        $srch->setPageSize(1);
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields([
            'qulinqu_id', 'qulinqu_type', 'qulinqu_ques_id', 'qulinqu_title', 'qulinqu_detail', 'qulinqu_hint',
            'qulinqu_marks', 'qulinqu_options', 'qulinqu_order', 'qulinqu_answer'
        ]);
        $question = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($question)) {
            return [];
        }
        return $question;
    }

    /**
     * Bind quizzes for users
     *
     * @param int $recordId
     * @param int $type
     * @return bool
     */
    public function bindUserQuiz(int $recordId, int $type)
    {
        /* get linked quizzes list */
        $srch = new SearchBase(static::DB_TBL, 'quilin');
        $srch->addCondition('quilin.quilin_record_id', '=', $recordId);
        $srch->addCondition('quilin.quilin_record_type', '=', $type);
        $srch->addCondition('quilin.quilin_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addMultipleFields([
            'quilin_id',
        ]);
        $srch->doNotCalculateRecords();
        $quizzes = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (empty($quizzes)) {
            return true;
        }
        foreach ($quizzes as $quiz) {
            $attempt = new QuizAttempt(0, $this->userId);
            if (!$attempt->setupUserQuiz($quiz['quilin_id'])) {
                $this->error = $attempt->getError();
                return false;
            }
        }
        return true;
    }

    /**
     * Send quiz removal notification
     *
     * @param array $data
     */
    private function sendQuizRemovedNotification(array $data)
    {
        $sessionTypes = AppConstant::getSessionTypes();
        $srch = new SearchBase(QuizAttempt::DB_TBL);
        $srch->addCondition('quizat_quilin_id', '=', $data['quilin_id']);
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = quizat_user_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = quizat_user_id', 'learner');
        $srch->addMultipleFields([
            'learner.user_first_name AS learner_first_name', 'learner.user_last_name AS learner_last_name',
            'learner.user_lang_id', 'learner.user_email', 'learner.user_id',
            'teacher.user_first_name as teacher_first_name', 'teacher.user_last_name as teacher_last_name'
        ]);
        $srch->addGroupBy('learner.user_id');
        $users = FatApp::getDb()->fetchAll($srch->getResultSet());

        foreach ($users as $user) {
            $mail = new FatMailer($user['user_lang_id'], 'quiz_removed_email');
            $vars = [
                '{quiz_title}' => ucfirst($data['quilin_title']),
                '{learner_full_name}' => ucwords($user['learner_first_name'] . ' ' . $user['learner_last_name']),
                '{teacher_full_name}' => ucwords($user['teacher_first_name'] . ' ' . $user['teacher_last_name']),
                '{session_type}' => strtolower($sessionTypes[$data['quilin_record_type']]),
            ];
            $mail->setVariables($vars);
            $mail->sendMail([$user['user_email']]);

            $notifi = new Notification($user['user_id'], Notification::TYPE_QUIZ_REMOVED);
            $notifi->sendNotification(['{session}' => strtolower($sessionTypes[$data['quilin_record_type']])]);
        }
    }

    /**
     * Send Quiz Attachment Notification
     *
     * @param array $data
     */
    private function sendQuizAttachedNotification(array $data)
    {
        $record = current($data);
        $sessionType = AppConstant::getSessionTypes($record['quilin_record_type']);

        $linkedIds = array_column($data, 'quilin_id');
        $srch = new SearchBase(QuizAttempt::DB_TBL);
        $srch->addCondition('quizat_quilin_id', 'IN', $linkedIds);
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = quizat_user_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = quizat_user_id', 'learner');
        $srch->addMultipleFields([
            'learner.user_first_name AS learner_first_name', 'learner.user_last_name AS learner_last_name',
            'learner.user_lang_id', 'learner.user_email', 'learner.user_id',
            'teacher.user_first_name as teacher_first_name', 'teacher.user_last_name as teacher_last_name'
        ]);
        $srch->addGroupBy('learner.user_id');
        $users = FatApp::getDb()->fetchAll($srch->getResultSet());

        $quiztypes = Quiz::getTypes();
        $html = '<table style="border:1px solid #ddd; border-collapse:collapse; width:100%" cellspacing="0" cellpadding="0" border="0"><thead><tr><td style="padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;">{title}</td><td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd; font-weight:bold;">{type}</td><td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd; font-weight:bold;">{validity}</td><td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd; font-weight:bold;">{action}</td></tr></thead><tbody>';
        foreach ($data as $quiz) {
            $html .= '<tr>' .
            '<td style="padding:10px;font-size:13px;border:1px solid #ddd; color:#333;">' . $quiz['quilin_title'] . '</td>' .
            '<td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">' . $quiztypes[$quiz['quilin_type']] . '</td>' .
            '<td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">' . MyDate::formatDate($quiz['quilin_validity']) . '</td>' .
            '<td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;"><a style="color: {primary-color};" target="_blank" href="' . MyUtility::makeFullUrl('UserQuiz', 'index', [$quiz['quilin_id']], CONF_WEBROOT_DASHBOARD) . '">{view}</a></td>' .
            '</tr>';
        }
        $html .= '</tbody></table>';

        foreach ($users as $user) {
            $list = str_replace(
                ['{title}', '{type}', '{validity}', '{action}', '{view}'],
                [
                    Label::getLabel('LBL_TITLE', $user['user_lang_id']),
                    Label::getLabel('LBL_TYPE', $user['user_lang_id']),
                    Label::getLabel('LBL_VALIDITY', $user['user_lang_id']),
                    Label::getLabel('LBL_ACTION', $user['user_lang_id']),
                    Label::getLabel('LBL_VIEW', $user['user_lang_id']),
                ],
                $html
            );
            $mail = new FatMailer($user['user_lang_id'], 'quiz_attached_email');
            $vars = [
                '{learner_full_name}' => ucwords($user['learner_first_name'] . ' ' . $user['learner_last_name']),
                '{teacher_full_name}' => ucwords($user['teacher_first_name'] . ' ' . $user['teacher_last_name']),
                '{session_type}' => strtolower($sessionType),
                '{quizzes_list}' => $list,
            ];
            $mail->setVariables($vars);
            $mail->sendMail([$user['user_email']]);

            $notifi = new Notification($user['user_id'], Notification::TYPE_QUIZ_ATTACHED);
            $notifi->sendNotification(['{session}' => strtolower($sessionType)]);
        }
    }

    /**
     * Setup users quizzes
     *
     * @param int   $recordId
     * @param int   $recordType
     * @param array $data
     * @return bool
     */
    private function setupUserQuizzes(int $recordId, int $recordType, array $data)
    {
        if (empty($data)) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }

        if ($recordType == AppConstant::LESSON) {
            $srch = new LessonSearch($this->langId, $this->userId, $this->userType);
            $srch->applyPrimaryConditions();
            $srch->addCondition('ordles_id', '=', $recordId);
            $srch->addFld('learner.user_id');
            $users = FatApp::getDb()->fetchAll($srch->getResultSet());
        } elseif ($recordType == AppConstant::GCLASS) {
            $srch = new ClassSearch($this->langId, $this->userId, $this->userType);
            $srch->applyPrimaryConditions();
            $srch->addCondition('grpcls_id', '=', $recordId);
            $srch->addFld('learner.user_id');
            $srch->removGroupBy('grpcls.grpcls_id');
            $users = FatApp::getDb()->fetchAll($srch->getResultSet());
        } elseif ($recordType == AppConstant::COURSE) {
            $users = [];
        }

        foreach ($users as $user) {
            foreach ($data as $id) {
                $attempt = new QuizAttempt(0, $user['user_id']);
                if (!$attempt->setupUserQuiz($id)) {
                    $this->error = $this->getError();
                    return false;
                }
            }
        }
        return true;
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
        $quesOptions = [];
        if (count($options) > 0) {
            foreach ($options as $option) {
                $quesOptions[$option['queopt_ques_id']][] = $option;
            }
        }

        $i = 1;
        foreach ($questions as $question) {
            $opts = $quesOptions[$question['ques_id']] ?? [];
            if ($question['ques_type'] != Question::TYPE_MANUAL && count($opts) < 1) {
                $this->error = Label::getLabel('LBL_QUESTION_OPTIONS_ARE_NOT_AVAILABLE');
                return false;
            }

            $linkQues = new TableRecord(static::DB_TBL_QUIZ_LINKED_QUESTIONS);
            $linkQues->assignValues([
                'qulinqu_type' => $question['ques_type'],
                'qulinqu_quilin_id' => $data[$question['quiz_id']]['quilin_id'],
                'qulinqu_ques_id' => $question['ques_id'],
                'qulinqu_title' => $question['ques_title'],
                'qulinqu_detail' => $question['ques_detail'],
                'qulinqu_hint' => $question['ques_hint'],
                'qulinqu_marks' => $question['ques_marks'],
                'qulinqu_answer' => $question['ques_answer'],
                'qulinqu_options' => json_encode($opts),
                'qulinqu_order' => $i
            ]);
            if (!$linkQues->addNew()) {
                $this->error = $linkQues->getError();
                return false;
            }
            $i++;
        }
        
        return true;
    }
}
