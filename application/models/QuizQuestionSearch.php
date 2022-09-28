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
            'quiz.quiz_id' => 'quiz_id',
            'ques.ques_id' => 'ques_id',
            'ques.ques_title' => 'ques_title',
            'ques.ques_type' => 'ques_type',
            'ques.ques_cate_id' => 'ques_cate_id',
            'ques.ques_subcate_id' => 'ques_subcate_id',
            'ques.ques_created' => 'ques_created',
            'ques.ques_status' => 'ques_status'
        ];
    }

    /**
     * Set order
     */
    public function setOrder()
    {
        $this->addOrder('quique_order', 'ASC');
    }

    public function joinCategory()
    {
        $this->joinTable(Category::DB_TBL, 'INNER JOIN', 'ques_cate_id = cate.cate_id', 'cate');
        $this->addCondition('cate.cate_status', '=', AppConstant::ACTIVE);
        $this->addCondition('cate.cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
    }

    /**
     * Need to create a new Search Model
     * class QuizQuestionSearch extends YocoachSearch
     *
     */

    /**
     * Get questions for quiz
     *
     * @param array $quizIds
     * @param int   $type
     * @return array
     */
    public function getQuestions(array $quizIds, int $type = 0): array
    {
        if (empty($quizIds)) {
            return [];
        }
        $srch = new SearchBase(Quiz::DB_TBL_QUIZ_QUESTIONS);
        $srch->joinTable(Quiz::DB_TBL, 'INNER JOIN', 'quiz_id = quique_quiz_id');
        $srch->joinTable(Question::DB_TBL, 'INNER JOIN', 'ques_id = quique_ques_id');
        

        $srch->addCondition('quique_quiz_id', 'IN', $quizIds);
        if ($this->userType == User::TEACHER) {
            $srch->addCondition('quiz_user_id', '=', $this->userId);
        }
        $srch->addCondition('ques_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('ques_deleted', 'IS', 'mysql_func_NULL', 'AND', true);

        

        if ($type > 0) {
            if ($type == Quiz::TYPE_AUTO_GRADED) {
                $srch->addCondition('ques_type', '!=', Question::TYPE_MANUAL);
            } else {
                $srch->addCondition('ques_type', '=', Question::TYPE_MANUAL);
            }
        }
        $srch->addMultipleFields([
            'quique_quiz_id', 'quique_ques_id', 'quiz_id', 'ques_id', 'ques_title', 'ques_type',
            'ques_cate_id', 'ques_subcate_id', 'ques_created', 'ques_status'
        ]);
        $srch->doNotCalculateRecords();
        $srch->addOrder('quique_order', 'ASC');
        $questions = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (count($questions) < 1) {
            return [];
        }

        /* get categories list */
        $categoryIds = [];
        array_map(function ($val) use (&$categoryIds) {
            $categoryIds = array_merge($categoryIds, [$val['ques_cate_id'], $val['ques_subcate_id']]);
        }, $questions);
        $categoryIds = array_unique($categoryIds);
        $categories = Category::getNames($categoryIds, $this->langId);

        foreach ($questions as $key => $question) {
            $cateId = $question['ques_cate_id'];
            $subcateId = $question['ques_subcate_id'];

            $question['cate_name'] = isset($categories[$cateId]) ? $categories[$cateId] : '-';
            $question['subcate_name'] = isset($categories[$subcateId]) ? $categories[$subcateId] : '-';

            $questions[$key] = $question;
        }
        return $questions;
    }

    /**
     * Get Search Form
     *
     * @return Form
     */
    public static function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Label::getLabel('LBL_TITLE'), 'keyword', '');
        $frm->addTextBox(Label::getLabel('LBL_TEACHER'), 'teacher', '', ['autocomplete' => 'off']);
        $frm->addHiddenField('', 'teacher_id');
        $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'quiz_type', Quiz::getTypes());
        $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'quiz_status', Quiz::getStatuses());
        $frm->addSelectBox(Label::getLabel('LBL_ACTIVE'), 'quiz_active', AppConstant::getActiveArr());
        $frm->addHiddenField(Label::getLabel('LBL_PAGESIZE'), 'pagesize', AppConstant::PAGESIZE)
            ->requirements()->setInt();
        $frm->addHiddenField(Label::getLabel('LBL_PAGENO'), 'pageno', 1)->requirements()->setInt();
        $frm->addHiddenField('', 'record_id');
        $frm->addHiddenField('', 'record_type');
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $frm->addButton('', 'btn_clear', Label::getLabel('LBL_CLEAR'));
        return $frm;
    }

    public static function getQuizForm()
    {
        $frm = new Form('frmQuizLink');
        $quesFld = $frm->addCheckBoxes('', 'quilin_quiz_id', []);
        $quesFld->requirements()->setRequired();
        $quesFld->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PLEASE_SELECT_QUIZ(S)'));
        $fld = $frm->addHiddenField('', 'quilin_record_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'quilin_record_type');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'quilin_user_id');
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        return $frm;
    }
}
