<?php

/**
 * This class is used to handle quiz questions search
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class QuizQuestionSearch extends YocoachSearch
{

    /* Initialize Quiz Search
     *
     * @param int $langId
     * @param int $userId
     * @param int $userType
     */
    public function __construct(int $langId, int $userId, int $userType)
    {
        $this->table = Quiz::DB_TBL_QUIZ_QUESTIONS;
        $this->alias = 'quique';
        parent::__construct($langId, $userId, $userType);
        $this->joinTable(Quiz::DB_TBL, 'INNER JOIN', 'quiz_id = quique_quiz_id', 'quiz');
        $this->joinTable(Question::DB_TBL, 'INNER JOIN', 'ques_id = quique_ques_id', 'ques');
    }


    /**
     * Apply Primary Conditions
     *
     * @return void
     */
    public function applyPrimaryConditions(): void
    {
        $this->addCondition('ques_status', '=', AppConstant::ACTIVE);
        $this->addCondition('ques_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
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
    }

    /**
     * Fetch & Format quiztions
     *
     * @return array
     */
    public function fetchAndFormat(): array
    {
        $rows = FatApp::getDb()->fetchAll($this->getResultSet());
        if (count($rows) == 0) {
            return [];
        }
        $categoryIds = [];
        array_map(function ($val) use (&$categoryIds) {
            $categoryIds = array_merge($categoryIds, [$val['ques_cate_id'], $val['ques_subcate_id']]);
        }, $rows);
        $categoryIds = array_unique($categoryIds);
        $categories = Category::getNames($categoryIds, $this->langId);

        foreach ($rows as $key => $row) {
            $cateId = $row['ques_cate_id'];
            $subcateId = $row['ques_subcate_id'];

            $row['cate_name'] = isset($categories[$cateId]) ? $categories[$cateId] : '-';
            $row['subcate_name'] = isset($categories[$subcateId]) ? $categories[$subcateId] : '-';

            $rows[$key] = $row;
        }
        return $rows;
    }

    /**
     * Add Search Listing Fields
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
            'quique.quique_quiz_id' => 'quique_quiz_id',
            'quique.quique_ques_id' => 'quique_ques_id',
            'quique.quique_order' => 'quique_order',
            'quiz.quiz_id' => 'quiz_id',
            'ques.ques_id' => 'ques_id',
            'ques.ques_title' => 'ques_title',
            'ques.ques_detail' => 'ques_detail',
            'ques.ques_hint' => 'ques_hint',
            'ques.ques_marks' => 'ques_marks',
            'ques.ques_answer' => 'ques_answer',
            'ques.ques_type' => 'ques_type',
            'ques.ques_cate_id' => 'ques_cate_id',
            'ques.ques_subcate_id' => 'ques_subcate_id',
            'ques.ques_created' => 'ques_created',
            'ques.ques_status' => 'ques_status',
        ];
    }

    /**
     * Set order
     */
    public function setOrder()
    {
        $this->addOrder('quique_order', 'ASC');
    }

    /**
     * Join category table
     */
    public function joinCategory()
    {
        $this->joinTable(Category::DB_TBL, 'INNER JOIN', 'ques_cate_id = cate.cate_id', 'cate');
        $this->addCondition('cate.cate_status', '=', AppConstant::ACTIVE);
        $this->addCondition('cate.cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
    }
}