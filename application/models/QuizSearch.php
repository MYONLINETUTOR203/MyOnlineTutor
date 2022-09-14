<?php

/**
 * This class is used to handle quiz Search 
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class QuizSearch extends YocoachSearch
{

    /* Initialize Quiz Search
     * 
     * @param int $langId
     * @param int $userId
     * @param int $userType
     */
    public function __construct(int $langId, int $userId, int $userType)
    {
        $this->table = 'tbl_quizzes';
        $this->alias = 'quiz';
        parent::__construct($langId, $userId, $userType);
        $this->joinTable(User::DB_TBL, 'LEFT JOIN', 'quiz.quiz_user_id = teacher.user_id', 'teacher');
    }

    
    /**
     * Apply Primary Conditions
     *
     * @return void
     */
    public function applyPrimaryConditions(): void
    {
        $this->addCondition('quiz.quiz_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        if ($this->userType == User::TEACHER) {
            $this->addCondition('quiz_user_id', '=', $this->userId);
        }
    }

    /**
     * Apply Search Conditions
     * 
     * @param array $post
     * @return void
     */
    public function applySearchConditions(array $post): void
    {
        if (!empty($post['keyword'])) {
            $this->addCondition('quiz_title', 'LIKE', '%' . $post['keyword'] . '%');
        }
        if (isset($post['quiz_type']) && $post['quiz_type'] > 0) {
            $this->addCondition('quiz.quiz_type', '=', $post['quiz_type']);
        }
        if (isset($post['quiz_status']) && $post['quiz_status'] > 0) {
            $this->addCondition('quiz.quiz_status', '=', $post['quiz_status']);
        }
        if (isset($post['quiz_active']) && $post['quiz_active'] != '') {
            $this->addCondition('quiz.quiz_active', '=', $post['quiz_active']);
        }
    }

    /**
     * Fetch & Format quiztions
     * 
     * @return array
     */
    public function fetchAndFormat(): array
    {
        $rows = FatApp::getDb()->fetchAll($this->getResultSet(), 'quiz_id');
        if (count($rows) == 0) {
            return [];
        }
        return $rows;
    }

    /**
     * Add Search Listing Fields
     *
     * @return void
     */
    public function addSearchListingFields(): void
    {
        $fields = static::getListingFields();
        foreach ($fields as $field => $alias) {
            $this->addFld($field . ' AS ' . $alias);
        }
    }

    /**
     * Get Listing FFields
     *
     * @return array
     */
    public static function getListingFields(): array
    {
        return [
            'quiz.quiz_id' => 'quiz_id',
            'quiz.quiz_type' => 'quiz_type',
            'quiz.quiz_title' => 'quiz_title',
            'quiz.quiz_detail' => 'quiz_detail',
            'quiz.quiz_user_id' => 'quiz_user_id',
            'quiz.quiz_duration' => 'quiz_duration',
            'quiz.quiz_user_id' => 'quiz_user_id',
            'quiz.quiz_status' => 'quiz_status',
            'quiz.quiz_created' => 'quiz_created',
            'quiz.quiz_hint' => 'quiz_hint',
            'quiz.quiz_status' => 'quiz_status',
            'quiz.quiz_marks' => 'quiz_marks',
            'quiz.quiz_created' => 'quiz_created',
            'teacher.user_first_name' => 'teacher_first_name',
            'teacher.user_last_name' => 'teacher_last_name',
            'teacher.user_username' => 'teacher_username',
        ];
    }

    public function getQuestions($quizId)
    {
        if ($quizId < 1) {
            return [];
        }
        $srch = new SearchBase(Quiz::DB_TBL_QUIZ_QUESTIONS);
        $srch->joinTable(Quiz::DB_TBL, 'INNER JOIN', 'quiz_id = quique_quiz_id');
        $srch->joinTable(Question::DB_TBL, 'INNER JOIN', 'ques_id = quique_ques_id');
        $srch->addCondition('quique_quiz_id', '=', $quizId);
        $srch->addCondition('quiz_user_id', '=', $this->userId);
        $srch->addMultipleFields([
            'quiz_id', 'ques_id', 'ques_title', 'ques_type'
        ]);
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }
}