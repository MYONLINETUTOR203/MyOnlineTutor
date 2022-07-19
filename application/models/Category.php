<?php

class Category extends MyAppModel
{
    const DB_TBL = 'tbl_categories';
    const DB_TBL_PREFIX = 'cate_';
    const DB_LANG_TBL = 'tbl_categories_lang';
    const DB_LANG_TBL_PREFIX = 'catelang_';

    const TYPE_COURSE = 1;
    const TYPE_QUESTION = 2;

    /**
     * Initialize Categories
     *
     * @param int $id
     */
    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, 'cate_id', $id);
    }

    /**
     * Get Categories Types
     *
     * @param int $key
     * @return string|array
     */
    public static function getCategoriesTypes(int $key = null)
    {
        $arr = [
            static::TYPE_COURSE => Label::getLabel('LBL_COURSE'),
            static::TYPE_QUESTION => Label::getLabel('LBL_QUESTION')
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Add/Edit Category
     *
     * @param array $data
     * @return bool
     */
    public function addUpdateData($data): bool
    {
        $db = FatApp::getDb();

        $db->startTransaction();
        $catgIds = [];
        /* add selected parent category id */
        if (!empty($data['cate_parent'])) {
            $catgIds[] = $data['cate_parent'];
        }
        if ($this->mainTableRecordId > 0) {
            $catgIds[] = $this->mainTableRecordId;
            if (!$catData = self::getAttributesById($this->mainTableRecordId, ['cate_id', 'cate_parent'])) {
                $this->error = Label::getLabel('LBL_INVALID_REQUEST');
                return false;
            }
            /* add old parent id from the edited category */
            if (!empty($catData['cate_parent'])) {
                $catgIds[] = $catData['cate_parent'];
            }
        }

        /* save category data */
        $this->assignValues($data);
        if ($this->mainTableRecordId < 1) {
            $this->setFldValue('cate_created', date('Y-m-d H:i:s'));
        }
        $this->setFldValue('cate_updated', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $db->rollbackTransaction();
            $this->error = $db->getError();
            return false;
        }

        /* save category lang data */
        if (!$this->addUpdateLangData($data)) {
            $db->rollbackTransaction();
            $this->error = $db->getError();
            return false;
        }
        /* update sub categories count */
        if (count($catgIds) > 0) {
            $catgIds = array_unique($catgIds);
            if (!$this->updateSubCatCount($catgIds)) {
                $db->rollbackTransaction();
                $this->error = $db->getError();
                return false;
            }
        }

        $db->commitTransaction();
        return true;
    }

    /**
     * Add/Edit Categories Lang Data
     *
     * @param array $data
     * @return bool
     */
    public function addUpdateLangData($data): bool
    {
        $assignValues = [
            'catelang_cate_id' => $this->getMainTableRecordId(),
            'catelang_lang_id' => $data['catelang_lang_id'],
            'cate_name' => $data['cate_name'],
            'cate_details' => $data['cate_details'],
            'catelang_id' => $data['catelang_id'],
        ];

        if (!FatApp::getDb()->insertFromArray(static::DB_LANG_TBL, $assignValues, false, [], $assignValues)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    /**
     * Delete
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$cateParent = self::getAttributesById($this->mainTableRecordId, ['cate_parent'])) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }

        $this->setFldValue('cate_deleted', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }

        /* update sub categories count */
        if (isset($cateParent['cate_parent']) && $cateParent['cate_parent'] > 0) {
            $this->updateSubCatCount([$cateParent['cate_parent']]);
        }

        return true;
    }

    /**
     * Function to update sub categories count
     *
     * @param array $catgIds
     * @return bool
     */
    private function updateSubCatCount(array $catgIds): bool
    {
        if (
            !FatApp::getDb()->query(
                "UPDATE `" . static::DB_TBL . "` cate 
                LEFT JOIN (
                    SELECT 
                        COUNT(cate_id) AS cate_subcategories,
                        cate_parent
                    FROM `" . static::DB_TBL . "` 
                    WHERE `cate_parent` IN(" . implode(',', $catgIds) . ") AND cate_deleted IS NULL
                    GROUP BY `cate_parent`
                ) c 
                ON cate.cate_id = c.cate_parent 
                SET cate.cate_subcategories = c.cate_subcategories
                WHERE  `cate_id` IN(" . implode(',', $catgIds) . ")"
            )
        ) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }

        return true;
    }

    /**
     * get search base class object
     *
     * @return object
     */
    public static function getSearchObject()
    {
        $srch = new SearchBase(self::DB_TBL, 'catg');
        return $srch;
    }

    /**
     * Get category data by id
     *
     * @param int $langId
     * @return array|bool
     */
    public function getDataById(int $langId)
    {
        $srch = static::getSearchObject();
        $srch->joinTable(
            self::DB_LANG_TBL,
            'LEFT OUTER JOIN',
            'catg.cate_id = catg_l.catelang_cate_id AND catg_l.catelang_lang_id = ' . $langId,
            'catg_l'
        );
        $srch->addCondition('catg.cate_id', '=', $this->getMainTableRecordId());
        $srch->addMultipleFields(
            [
                'catg.cate_id',
                'catg.cate_type',
                'catg.cate_parent',
                'catg.cate_order',
                'catg.cate_status',
                'catg.cate_created',
                'catg_l.cate_name',
                'catg_l.cate_details',
                'catg_l.catelang_lang_id',
                'catg_l.catelang_id',
            ]
        );
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    /**
     * Get all parent categories
     *
     * @param int  $langId
     * @param int  $catgId
     * @param int  $type
     * @param bool $havingCourses
     * @return array
     */
    public static function getCategoriesByParentId(int $langId, int $catgId = 0, int $type = Category::TYPE_COURSE, bool $havingCourses = false)
    {
        $srch = static::getSearchObject();
        $srch->joinTable(
            self::DB_LANG_TBL,
            'LEFT OUTER JOIN',
            'catg.cate_id = catg_l.catelang_cate_id AND catg_l.catelang_lang_id = ' . $langId,
            'catg_l'
        );
        $srch->addMultipleFields(
            [
                'catelang_cate_id',
                'cate_name'
            ]
        );
        $srch->addOrder('cate_order');
        $srch->addCondition('cate_parent', '=', $catgId);
        $srch->addCondition('cate_type', '=', $type);
        $srch->addCondition('cate_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        if ($havingCourses == true) {
            $srch->addCondition('cate_courses', '>', 0);
        }
        
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
    }

    /**
     * Function to get all categories with its sub categories
     *
     * @param int $type
     * @param int $langId
     * @return array
     */
    public static function getAll(int $type, int $langId)
    {
        /* get parent categories list */
        $parentCategories = static::getCategoriesByParentId($langId, 0, $type, true);
        if (count($parentCategories) < 1) {
            return [];
        }
        $list = [];
        foreach ($parentCategories as $catId => $category) {
            $list[$catId] = $category;
            $subCategories = static::getCategoriesByParentId($langId, $catId, $type, true);
            if (count($subCategories) > 0) {
                foreach ($subCategories as $subCatId => $subCategory) {
                    $list[$subCatId] = ' »» ' . $subCategory;
                }
            }
        }
        return $list;
    }

    /**
     * Check unique category
     *
     * @param string $name
     * @param int    $langId
     * @param int    $parent
     * @return void
     */
    public function checkUnique(string $name, int $langId, int $parent = 0)
    {
        $srch = new SearchBase(static::DB_TBL, 'catg');
        $srch->joinTable(
            static::DB_LANG_TBL,
            'INNER JOIN',
            'catg.cate_id = catg_l.catelang_cate_id AND catg_l.catelang_lang_id = ' . $langId,
            'catg_l'
        );
        $srch->addCondition('mysql_func_LOWER(cate_name)', '=', strtolower($name), 'AND', true);
        $srch->addCondition('cate_parent', '=', $parent);
        if ($this->getMainTableRecordId() > 0) {
            $srch->addCondition('catelang_cate_id', '!=', $this->getMainTableRecordId());
        }
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $category = FatApp::getDb()->fetch($srch->getResultSet());
        if (!empty($category)) {
            $this->error = Label::getLabel('LBL_CATEGORY_NAME_ALREADY_IN_USE');
            return false;
        }
        return true;
    }

    /**
     * Get Names
     * 
     * @param int   $langId
     * @param array $catgIds
     * @return array
     */
    public static function getNames(int $langId, array $catgIds): array
    {
        $catgIds = array_filter(array_unique($catgIds));
        if ($langId == 0 || empty($catgIds)) {
            return [];
        }
        $srch = new SearchBase(static::DB_TBL, 'cate');
        $srch->joinTable(static::DB_LANG_TBL, 'LEFT JOIN', 'catelang.catelang_cate_id = cate.cate_id and catelang.catelang_lang_id =' . $langId, 'catelang');
        $srch->addMultipleFields(['cate.cate_id', 'cate_name']);
        $srch->addCondition('cate.cate_id', 'IN', $catgIds);
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
    }
}
