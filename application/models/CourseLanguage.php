<?php

/**
 * This class is used to handle course Language
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class CourseLanguage extends MyAppModel
{

    const DB_TBL = 'tbl_course_languages';
    const DB_TBL_LANG = 'tbl_course_languages_lang';
    const DB_TBL_PREFIX = 'clang_';

    /**
     * Initialize Lang
     * 
     * @param int $id
     */
    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, 'clang_id', $id);
    }
}
