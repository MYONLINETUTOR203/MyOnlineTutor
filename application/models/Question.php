<?php

class Question extends MyAppModel
{
    public const DB_TBL = 'tbl_questions';
    public const DB_TBL_PREFIX = 'ques_';
    public const DB_TBL_OPTIONS = 'tbl_question_options';

    public const TYPE_SINGLE = 1;
    public const TYPE_MULTIPLE = 2;
    public const TYPE_MANUAL = 3;

    private $userId;

    /**
     * Initialize Questions
     *
     * @param int $id
     * @param int $userId
     */
    public function __construct(int $id = 0, int $userId = 0)
    {
        $this->userId = $userId;
        parent::__construct(static::DB_TBL, 'ques_id', $id);
    }

    /**
     * Get Question Types
     *
     * @param int $key
     * @return string|array
     */
    public static function getTypes(int $key = null)
    {
        $arr = [
            static::TYPE_SINGLE => Label::getLabel('LBL_SINGLE_CHOICE'),
            static::TYPE_MULTIPLE => Label::getLabel('LBL_MULTIPLE_CHOICE'),
            static::TYPE_MANUAL => Label::getLabel('LBL_MANUAL'),
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Get Question Status List
     *
     * @param integer $key
     * @return string|array
     */
    public static function getStatuses(int $key = null)
    {
        $arr = [
            AppConstant::ACTIVE => Label::getLabel('LBL_ACTIVE'),
            AppConstant::INACTIVE => Label::getLabel('LBL_INACTIVE'),
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Get question by id
     *
     * @return array
     */
    public function getById()
    {
        $srch = new SearchBase(self::DB_TBL, 'ques');
        $srch->addCondition('ques_id', '=', $this->getMainTableRecordId());
        $srch->addCondition('ques_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    /**
     * get search base class object
     *
     * @return object
     */
    public static function getSearchObject()
    {
        $srch = new SearchBase(self::DB_TBL, 'ques');
        return $srch;
    }

    /**
     * Get Question Options
     *
     * @param array $optionsIds
     * @return array
     */
    public function getQuesOptions(array $optionsIds = [])
    {
        $srch = new SearchBase(self::DB_TBL_OPTIONS, 'queopt');
        $srch->addMultipleFields(['queopt_id', 'queopt_title']);
        $srch->addCondition('queopt_ques_id', '=', $this->mainTableRecordId);
        if (!empty($optionsIds)) {
            $srch->addCondition('queopt_id', 'IN', $optionsIds);
        }
        $srch->addOrder('queopt_order', 'ASC');
        return FatApp::getDb()->fetchAll($srch->getResultSet(), 'queopt_id');
    }

    /**
     * Get Question Options Details
     *
     * @return array
     */
    public function getOptions()
    {
        $srch = new SearchBase(self::DB_TBL_OPTIONS, 'queopt');
        $srch->addMultipleFields(['queopt_id', 'queopt_title', 'queopt_order', 'queopt_detail']);
        $srch->addCondition('queopt_ques_id', '=', $this->getMainTableRecordId());
        $srch->doNotCalculateRecords();
        $srch->addOrder('queopt_order', 'ASC');
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }

    /**
     * Delete
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$question = $this->getById()) {
            $this->error = Label::getLabel('LBL_QUESTION_NOT_FOUND');
            return false;
        }
        if ($this->userId != $question['ques_user_id']) {
            $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $this->setFldValue('ques_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        if (!$this->updateCount([$question['ques_cate_id'], $question['ques_subcate_id']])) {
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        return true;
    }

    /**
     * Setup Questions
     *
     * @param array $data
     * @return bool
     */
    public function setup(array $data)
    {
        $categories = [];
        if ($this->mainTableRecordId > 0) {
            if (!$question = $this->getById()) {
                $this->error = Label::getLabel('LBL_QUESTION_NOT_FOUND');
                return false;
            }
            if ($this->userId != $question['ques_user_id']) {
                $this->error = Label::getLabel('LBL_UNAUTHORIZED_ACCESS');
                return false;
            }
            $categories = [$question['ques_cate_id'], $question['ques_subcate_id']];
        }
        if (!$this->validate($data)) {
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $this->setFldValue('ques_user_id', $this->userId);
        $this->setFldValue('ques_status', AppConstant::ACTIVE);
        if ($data['ques_type'] == Question::TYPE_MANUAL) {
            $data['ques_options_count'] = 0;
        } else {
            if ($data['ques_options_count'] != count($data['queopt_title'])) {
                $this->error = Label::getLabel('LBL_OPTION_COUNT_&_N0._OF_OPTIONS_SUBMITTED_DOES_NOT_MATCH');
                return false;
            }
        }
        $this->assignValues($data);
        if ($this->mainTableRecordId < 1) {
            $this->setFldValue('ques_created', date('Y-m-d H:i:s'));
        }
        $this->setFldValue('ques_updated', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        if (!$this->setupOptions($data, $this->getMainTableRecordId())) {
            $db->rollbackTransaction();
            return false;
        }
        $categories = array_merge($categories, [$data['ques_cate_id'], $data['ques_subcate_id']]);
        if (!$this->updateCount($categories)) {
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        return true;
    }

    /**
     * Setup question options
     *
     * @param array $data
     * @param int   $quesId
     * @return bool
     */
    private function setupOptions(array $data, int $quesId): bool
    {
        $db = FatApp::getDb();

        /* delete old questions */
        if (
            !$db->deleteRecords(
                static::DB_TBL_OPTIONS,
                ['smt' => 'queopt_ques_id = ?', 'vals' => [$quesId]]
            )
        ) {
            $this->error = $db->getError();
            return false;
        }
        $ques_answers = [];
        if ($data['ques_type'] != Question::TYPE_MANUAL) {
            $i = 1;
            foreach ($data['queopt_title'] as $key => $value) {
                $queopt = new TableRecord(Question::DB_TBL_OPTIONS);
                $queopt->assignValues([
                    'queopt_ques_id' => $quesId,
                    'queopt_title'   => $value,
                    'queopt_order'   => $i,
                ]);
                if (!$queopt->addNew()) {
                    $this->error = $this->getError();
                    return false;
                }
                if (in_array($key, $data['answers'])) {
                    $ques_answers[] = $queopt->getId();
                }
                $i++;
            }
            $this->setFldValue('ques_id', $quesId);
            $this->assignValues(['ques_answer' => json_encode($ques_answers)]);
            if (!$this->save()) {
                $this->error = $this->getError();
                return false;
            }
        }
        return true;
    }

    /**
     * Update Questions Count In Categories
     *
     * @param array $cateIds
     * @return bool
     */
    private function updateCount(array $cateIds)
    {
        if (count($cateIds) < 1) {
            $this->error = Label::getLabel('LBL_INVALID_DATA_SENT_FOR_QUESTION_COUNT_UPDATE');
            return false;
        }
        $cateIds = array_filter($cateIds);
        $db = FatApp::getDb();
        if (
            !$db->query(
                "UPDATE " . Category::DB_TBL . "
                LEFT JOIN(
                    SELECT ques.ques_cate_id AS catId,
                        COUNT(*) AS totalRecord
                    FROM
                        " . self::DB_TBL . " AS ques
                    WHERE 
                        ques.ques_cate_id IN (" . implode(',', $cateIds) . ") AND
                        ques.ques_deleted IS NULL
                    GROUP BY
                        ques.ques_cate_id 
                ) mainCat
                ON
                    mainCat.catId = " . Category::DB_TBL . ".cate_id
                LEFT JOIN(
                    SELECT
                        ques1.ques_subcate_id AS catId,
                        COUNT(*) AS totalRecord
                    FROM
                        " . self::DB_TBL . " AS ques1
                    WHERE ques1.ques_subcate_id IN (" . implode(',', $cateIds) . ") AND
                        ques1.ques_deleted IS NULL
                    GROUP BY
                        ques1.ques_subcate_id
                ) catChild
                ON
                    catChild.catId = cate_id
                SET cate_records = (IFNULL(mainCat.totalRecord, 0) + IFNULL(catChild.totalRecord, 0)) 
                WHERE cate_id IN (" . implode(',', $cateIds) . ")"
            )
        ) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }

    /**
     * Validate Categories
     *
     * @param array $data
     * @return bool
     */
    private function validate(array $data)
    {
        $categories = [$data['ques_cate_id'], $data['ques_subcate_id']];
        $srch = Category::getSearchObject();
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields(['cate_id', 'cate_parent']);
        $srch->addCondition('cate_id', 'IN', $categories);
        $srch->addCondition('cate_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $categories = FatApp::getDb()->fetchAll($srch->getResultSet(), 'cate_id');
        if (!array_key_exists($data['ques_cate_id'], $categories)) {
            $this->error = Label::getLabel('LBL_CATEGORY_NOT_AVAILABLE');
            return false;
        }
        if ($data['ques_subcate_id'] > 0) {
            if (!array_key_exists($data['ques_subcate_id'], $categories)) {
                $this->error = Label::getLabel('LBL_SUBCATEGORY_NOT_AVAILABLE');
                return false;
            }
            if ($categories[$data['ques_subcate_id']]['cate_parent'] != $data['ques_cate_id']) {
                $this->error = Label::getLabel('LBL_INVALID_SUBCATEGORY');
                return false;
            }
        }
        return true;
    }
}
