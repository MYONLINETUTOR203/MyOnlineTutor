<?php

class CourseRequestSearch extends YocoachSearch
{

    /**
     * Initialize Course Requests Search
     *
     * @param int $langId
     * @param int $userId
     * @param int $userType
     */
    public function __construct(int $langId, int $userId, int $userType)
    {
        $this->table = Course::DB_TBL_APPROVAL_REQUEST;
        $this->alias = 'coapre';

        parent::__construct($langId, $userId, $userType);

        $this->joinTable(Course::DB_TBL, 'INNER JOIN', 'coapre.coapre_course_id = course.course_id', 'course');
        $this->joinTable(Course::DB_TBL_LANG, 'INNER JOIN', 'crsdetail.course_id = course.course_id', 'crsdetail');
    }

    /**
     * Apply Search Conditions
     *
     * @param array $post
     * @return void
     */
    public function applySearchConditions(array $post): void
    {
        if (isset($post['coapre_id'])) {
            $this->addCondition('coapre_id', '=', $post['coapre_id']);
        }
        if (!empty($post['keyword'])) {
            $cnd = $this->addCondition('crsdetail.course_title', 'LIKE', '%' . $post['keyword'] . '%');
            $cnd->attachCondition('crsdetail.course_subtitle', 'LIKE', '%' . $post['keyword'] . '%', 'OR');
        }
        if (isset($post['coapre_id'])) {
            $this->addCondition('coapre_id', '=', $post['coapre_id']);
        }
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
            'coapre.coapre_id' => 'coapre_id',
            'coapre.coapre_status' => 'coapre_status',
            'coapre.coapre_remark' => 'coapre_remark',
            'coapre.coapre_created' => 'coapre_created',
            'coapre.coapre_course_id ' => 'coapre_course_id',
            'crsdetail.course_title' => 'course_title',
            'crsdetail.course_subtitle' => 'course_subtitle',
            'course.course_price' => 'course_price',
            'course.course_currency_id' => 'course_currency_id',
            'course.course_duration' => 'course_duration',
            'course.course_status' => 'course_status',
            'crsdetail.course_details' => 'course_details',
            'u.user_id' => 'user_id',
            'u.user_first_name' => 'user_first_name',
            'u.user_last_name' => 'user_last_name',
            'u.user_email' => 'user_email',
            'u.user_gender ' => 'user_gender',
        ];
    }

    /**
     * Fetch And Format
     *
     * @return array
     */
    public function fetchAndFormat(): array
    {
        $rows = FatApp::getDb()->fetchAll($this->getResultSet(), 'coapre_id');
        if (count($rows) == 0) {
            return [];
        }
        
        return $rows;
    }

    /**
     * Apply Primary Conditions
     *
     * @return void
     */
    public function applyPrimaryConditions(): void
    {
    }

    /**
     * Join user table
     *
     * @return void
     */
    public function joinUser()
    {
        $this->joinTable(
            User::DB_TBL,
            'INNER JOIN',
            'course.course_user_id = u.user_id',
            'u'
        );
    }
}
