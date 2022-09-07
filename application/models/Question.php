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
     * @param int $key
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
        $db = FatApp::getDb();
        $db->startTransaction();
      
        $this->assignValues($data);
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        $quesId = $this->getMainTableRecordId();
        $ques_answers = [];
        if ($data['ques_type'] != Question::TYPE_MANUAL) {
            foreach ($data['queopt_title'] as $key => $value) {
                $opt_data = [
                    'queopt_ques_id' => $quesId,
                    'queopt_title'   => $value,
                    'queopt_order'   => $key,
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
     * Get Question Types
     * 
     * @param int $key
     * @return string|array
     */
    public static function getQuesTypes(int $key = null)
    {
        $arr = [
            static::TYPE_SINGLE => Label::getLabel('LBL_SINGLE'),
            static::TYPE_MULTIPLE => Label::getLabel('LBL_MULTIPLE'),
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