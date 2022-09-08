<?php

class Question extends MyAppModel
{
    public const DB_TBL = 'tbl_questions';
    public const DB_TBL_PREFIX = 'ques_';
    public const DB_TBL_OPTIONS = 'tbl_question_options';

    public const TYPE_SINGLE = 1;
    public const TYPE_MULTIPLE = 2;
    public const TYPE_MANUAL = 3;

    /**
     * Initialize Questions
     *
     * @param int $id
     */
    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, 'ques_id', $id);
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
     * @param int $key
     * @return array
     */
    public function getQuesOptions($optionsIds = [])
    {
        $srch = new SearchBase(self::DB_TBL_OPTIONS, 'queopt');
        $srch->addMultipleFields(['queopt_id', 'queopt_title']);
        $srch->addCondition('queopt_ques_id', '=', $this->mainTableRecordId);
        if(!empty($optionsIds)){
            $srch->addCondition('queopt_id', 'IN', $optionsIds);
        }
        $srch->addOrder('queopt_order', 'ASC');
        return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
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
        $srch->addCondition('queopt_ques_id', '=', $this->mainTableRecordId);
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
        if (!$question = self::getAttributesById($this->mainTableRecordId)) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }

        $this->setFldValue('ques_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    
    public function addUpdateQuestion($data) 
    {
        if (!$this->validate($data)) {
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $this->assignValues($data);
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        $quesId = $this->getMainTableRecordId();
        $ques_answers = [];
        FatApp::getDb()->deleteRecords(static::DB_TBL_OPTIONS,
        ['smt' => 'queopt_ques_id = ?', 'vals' => [$quesId]]);
        if ($data['ques_type'] != Question::TYPE_MANUAL) {
            $i = 1;
            foreach ($data['queopt_title'] as $key => $value) {
                $opt_data = [
                    'queopt_ques_id' => $quesId,
                    'queopt_title'   => $value,
                    'queopt_order'   => $i,
                    'queopt_detail' =>  $data['queopt_detail'][$key] ?? ''
                ];
                $queopt = new TableRecord(Question::DB_TBL_OPTIONS);
                $queopt->assignValues($opt_data);
                if (!$queopt->addNew()) {
                    $db->rollbackTransaction();
                    return false;
                }
                if(in_array($key, $data['answers'])){
                    $ques_answers[] = $queopt->getId();
                }
                $i++;
            }
            $this->assignValues(['ques_answer' => json_encode($ques_answers)]);
            if (!$this->save()) {
                $db->rollbackTransaction();
                return false;
            }
        }
        $db->commitTransaction();
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
        $srch->addFld('cate_id');
        $srch->addCondition('cate_id', 'IN', $categories);
        $srch->addCondition('cate_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $categories = FatApp::getDb()->fetchAll($srch->getResultSet(), 'cate_id');
        if (!array_key_exists($data['ques_cate_id'], $categories)) {
            $this->error = Label::getLabel('LBL_CATEGORY_NOT_AVAILABLE');
            return false;
        }
        if ($data['ques_subcate_id'] > 0 && !array_key_exists($data['ques_subcate_id'], $categories)) {
            $this->error = Label::getLabel('LBL_SUBCATEGORY_NOT_AVAILABLE');
            return false;
        }
        return true;
    }



    /**
     * Get Question Types
     * 
     * @param int $key
     * @return string|array
     */
    public static function getQuesTypes(int $key = null)
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


}