<?php

class CoursesController extends MyAppController
{

    /**
     * Initialize Courses
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Course list
     *
     * @return void
     */
    public function index()
    {
        $srchFrm = CourseSearch::getSearchForm($this->siteLangId);
        $this->set('srchFrm', $srchFrm);
        $this->set('filterTypes', Course::getFilterTypes());
        $this->_template->render();
    }

    /**
     * Find Teachers
     */
    public function search()
    {
        $posts = FatApp::getPostedData();
        $posts['pageno'] = $posts['pageno'] ?? 1;
        $posts['pagesize'] = AppConstant::PAGESIZE;
        $posts['price_sorting'] = FatApp::getPostedData('price_sorting', FatUtility::VAR_INT, AppConstant::SORT_PRICE_ASC);
        $frm = CourseSearch::getSearchForm($this->siteLangId);
        if (!$post = $frm->getFormDataFromArray($posts)) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $post['course_status'] = Course::PUBLISHED;
        $srch = new CourseSearch($this->siteLangId, $this->siteUserId, 0);
        $srch->addSearchListingFields();
        $srch->applyPrimaryConditions();
        $srch->applySearchConditions($post);
        $srch->applyOrderBy($posts['price_sorting']);
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['pageno']);
        $courses = $srch->fetchAndFormat();
        $recordCount = $srch->recordCount();
        /* checkout form */
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        $checkoutForm = $cart->getCheckoutForm([0 => Label::getLabel('LBL_NA')]);
        $checkoutForm->fill(['order_type' => Order::TYPE_COURSE]);
        $this->sets([
            'post' => $post,
            'courses' => $courses,
            'recordCount' => $recordCount,
            'pageCount' => ceil($recordCount / $posts['pagesize']),
            'levels' => Course::getCourseLevels(),
            'types' => Course::getTypes(),
            'checkoutForm' => $checkoutForm
        ]);
        $this->_template->render(false, false);
    }

    /**
     * View course detail
     *
     * @param string $slug
     * @return void
     */
    public function view(string $slug)
    {
        if (empty($slug)) {
            FatUtility::exitWithErrorCode(404);
        }
        /* get course details */
        $srch = new CourseSearch($this->siteLangId, $this->siteUserId, 0);
        $srch->addSearchDetailFields();
        $srch->applyPrimaryConditions();
        $srch->addCondition('course_slug', '=', $slug);
        $srch->joinTable(TeacherStat::DB_TBL, 'INNER JOIN', 'testat.testat_user_id = teacher.user_id', 'testat');
        $srch->joinTable(
            User::DB_TBL_LANG,
            'INNER JOIN',
            'userlang.userlang_user_id = teacher.user_id AND userlang.userlang_lang_id = ' . $this->siteLangId,
            'userlang'
        );
        $srch->setPageSize(1);
        $courses = $srch->fetchAndFormat(true);
        if (empty($courses)) {
            FatUtility::exitWithErrorCode(404);
        }
        $course = current($courses);
        /* get more course by the same teacher */
        $courseObj = new CourseSearch($this->siteLangId, $this->siteUserId, 0);
        $moreCourses = $courseObj->getMoreCourses($course['course_teacher_id'], $course['course_id']);
        /* get intended learner section details */       
        $intended = new IntendedLearner();
        $intendedLearners = $intended->get($course['course_id'], $this->siteLangId);
        /* get curriculum */
        $curriculum = $this->curriculum($course['course_id']);
        /* fetch rating data */
        $revObj = new CourseRatingReview();
        $reviews = $revObj->getRatingStats($course['course_id']);
        /* Get order course data */
        $orderCourse = OrderCourse::getAttributesById($course['ordcrs_id'], ['ordcrs_status', 'ordcrs_reviewed']);
        $canRate = false;
        if ($orderCourse) {
            $canRate = OrderCourseSearch::canRate($orderCourse, $this->siteUserType);
        }
        /* Get and fill form data */
        $frm = $this->getReviewSrchForm();
        $frm->fill(['course_id' => $course['course_id']]);
        /* checkout form */
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        $checkoutForm = $cart->getCheckoutForm([0 => Label::getLabel('LBL_NA')]);
        $checkoutForm->fill(['order_type' => Order::TYPE_COURSE]);
        $this->sets([
            'course' => $course,
            'moreCourses' => $moreCourses,
            'frm' => $frm,
            'intendedLearners' => $intendedLearners,
            'sections' => $curriculum['sections'],
            'videos' => $curriculum['videos'],
            'reviews' => $reviews,
            'canRate' => $canRate,
            'checkoutForm' => $checkoutForm,
        ]);
        $this->_template->render();
    }

    /**
     * Preview video in popoup
     *
     * @param int $courseId
     * @return void
     */
    public function previewVideo(int $courseId)
    {
        $courseId = FatUtility::int($courseId);
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $this->set('courseId', $courseId);
        /* get course details */
        $srch = new SearchBase(Course::DB_TBL, 'course');
        $srch->joinTable(
            Course::DB_TBL_LANG,
            'LEFT JOIN',
            'crsdetail.course_id = course.course_id',
            'crsdetail'
        );
        $srch->addFld('crsdetail.course_title');
        $srch->addCondition('course.course_id', '=', $courseId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $this->set('course', FatApp::getDb()->fetch($srch->getResultSet()));
        $this->_template->render(false, false);
    }

    /**
     * Get curriculum list
     *
     * @param int $courseId
     * @return array
     */
    private function curriculum(int $courseId)
    {
        $srch = new SectionSearch($this->siteLangId, $this->siteUserId, User::LEARNER);
        $srch->addSearchListingFields();
        $srch->addCondition('section.section_course_id', '=', $courseId);
        $srch->applyPrimaryConditions();
        $sections = $srch->fetchAndFormat();
        /* get list of lecture ids */
        $lectureIds = Lecture::getIds($sections);
        $videos = (count($lectureIds) > 0) ? Lecture::getVideos($lectureIds) : [];
        return [
            'videos' => $videos,
            'sections' => $sections,
        ];
    }

    /**
     * Render course reviews
     *
     */
    public function reviews()
    {
        $frm = $this->getReviewSrchForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $srch = new SearchBase(RatingReview::DB_TBL, 'ratrev');
        $srch->joinTable(Course::DB_TBL, 'INNER JOIN', 'course.course_id = ratrev.ratrev_type_id', 'course');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = ratrev.ratrev_user_id', 'learner');
        $srch->addCondition('ratrev.ratrev_status', '=', RatingReview::STATUS_APPROVED);
        $srch->addCondition('ratrev.ratrev_type', '=', AppConstant::COURSE);
        $srch->addCondition('ratrev.ratrev_type_id', '=', $post['course_id']);
        $srch->addMultipleFields([
            'user_first_name', 'user_last_name', 'ratrev_id', 'ratrev_user_id',
            'ratrev_title', 'ratrev_detail', 'ratrev_overall', 'ratrev_created', 'course_reviews'
        ]);
        $srch->addOrder('ratrev.ratrev_id', $post['sorting']);
        $srch->setPageSize(AppConstant::PAGESIZE);
        $srch->setPageNumber($post['pageno']);
        $this->sets([
            'reviews' => FatApp::getDb()->fetchAll($srch->getResultSet()),
            'pageCount' => $srch->pages(),
            'post' => $post,
            'recordCount' => $srch->recordCount(),
            'frm' => $frm,
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Get Review Form
     * 
     * @return Form
     */
    private function getReviewSrchForm(): Form
    {
        $frm = new Form('reviewFrm');
        $fld = $frm->addHiddenField('', 'course_id');
        $fld->requirements()->setRequired(true);
        $fld->requirements()->setIntPositive();
        $frm->addHiddenField('', 'sorting', RatingReview::SORTBY_NEWEST);
        $frm->addHiddenField('', 'pageno', 1);
        return $frm;
    }

    /**
     * Get video content for preview
     *
     * @param int $resourceId
     */
    public function resource(int $resourceId)
    {
        $resourceId = FatUtility::int($resourceId);
        if ($resourceId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $srch = new SearchBase(Lecture::DB_TBL_LECTURE_RESOURCE, 'lecsrc');
        $srch->joinTable(
            Lecture::DB_TBL,
            'INNER JOIN',
            'lecture.lecture_id = lecsrc.lecsrc_lecture_id',
            'lecture'
        );
        $srch->addCondition('lecsrc.lecsrc_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addMultipleFields(['lecsrc_link', 'lecture.lecture_title', 'lecsrc_course_id', 'lecsrc_id', 'lecsrc_lecture_id']);
        $srch->doNotCalculateRecords();
        $srch1 = clone $srch;

        $srch->addCondition('lecsrc.lecsrc_id', '=', $resourceId);
        $srch->setPageSize(1);
        $resource = FatApp::getDb()->fetch($srch->getResultSet());
        $this->set('resource', $resource);
        /* get free lectures */
        $srch1->joinTable(
            Lecture::DB_TBL,
            'INNER JOIN',
            'lecture.lecture_id = lecsrc.lecsrc_lecture_id',
            'lecture'
        );
        $srch1->addFld('lecture_duration');
        $srch1->addCondition('lecsrc_course_id', '=', $resource['lecsrc_course_id']);
        $srch1->addCondition('lecture_is_trial', '=', AppConstant::YES);
        $srch1->addCondition('lecsrc_type', '=', Lecture::TYPE_RESOURCE_EXTERNAL_URL);
        $this->set('lectures', FatApp::getDb()->fetchAll($srch1->getResultSet()));
        $this->_template->render(false, false);
    }

    /**
     * Auto Complete JSON
     */
    public function autoComplete()
    {
        $keyword = FatApp::getPostedData('term', FatUtility::VAR_STRING, '');
        if (empty($keyword)) {
            FatUtility::dieJsonSuccess(['data' => []]);
        }
        $filterTypes = Course::getFilterTypes();
        $langId = MyUtility::getSiteLangId();
        
        $courses = $this->getCourses($keyword);
        $data = [];
        if ($courses) {
            $data[] = $this->formatFiltersData($courses, Course::FILTER_COURSE);
        }
        /* find teachers */
        $teachers = $this->getTeachers($keyword);
        if (count($teachers) > 0) {
            $data[] = $this->formatFiltersData($teachers, Course::FILTER_TEACHER);
        }
        /* find tags */
        $tagsList = $this->getTags($keyword);
        if (count($tagsList)) {
            $child = [];
            foreach ($tagsList as $tags) {
                $tags = json_decode($tags['course_srchtags']);
                if (count($tags) > 0) {
                    foreach ($tags as $tag) {
                        $child[] = [
                                "id" => $tag,
                                "text" => $tag
                            ];
                    }
                }
            }
            $data[] = [
                'text' => $filterTypes[Course::FILTER_TAGS],
                'type' => Course::FILTER_TAGS,
                'children' => $child
            ];
        }
        echo json_encode($data);
        die;
    }

    /**
     * Function to format autocomplete filter data
     *
     * @param array $filtersData
     * @param int   $type
     * @return array
     */
    private function formatFiltersData(array $filtersData, int $type)
    {
        $filterTypes = Course::getFilterTypes();
        $child = [];
        foreach ($filtersData as $data) {
            $child[] = [
                "id" => $data['id'],
                "text" => $data['name']
            ];
        }
        return [
            'text' => $filterTypes[$type],
            'type' => $type,
            'children' => $child
        ];
    }

    /**
     * Function to get courses for autocomplete filter
     *
     * @param string $keyword
     * @return array
     */
    private function getCourses($keyword = '')
    {
        $srch = new SearchBase(Course::DB_TBL, 'course');
        $srch->joinTable(
            Course::DB_TBL_LANG,
            'LEFT JOIN',
            'crsdetail.course_id = course.course_id',
            'crsdetail'
        );
        $srch->addCondition('course.course_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addCondition('course.course_status', '=', Course::PUBLISHED);
        $srch->addMultiplefields(['course.course_id as id', 'crsdetail.course_title as name']);
        if (!empty($keyword)) {
            $srch->addCondition('crsdetail.course_title', 'LIKE', '%' . $keyword . '%');
        }
        $srch->setPageSize(5);
        $srch->doNotCalculateRecords();
        $courses = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (!empty($courses)) {
            return $courses;
        }
        return [];
    }

    /**
     * Function to get teachers for autocomplete filter
     *
     * @param string $keyword
     * @return array
     */
    private function getTeachers($keyword = '')
    {
        $srch = new TeacherSearch($this->siteLangId, $this->siteUserId, User::LEARNER);
        $srch->applyPrimaryConditions();
        $cnd = $srch->addCondition('teacher.user_first_name', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('teacher.user_last_name', 'LIKE', '%' . $keyword . '%', 'OR');
        $srch->addOrder('teacher.user_first_name', 'ASC');
        $srch->addMultiplefields(['teacher.user_id as id', 'CONCAT(teacher.user_first_name, " ", teacher.user_last_name) as name']);
        $srch->setPageSize(5);
        $srch->doNotCalculateRecords();
        $teachers = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (!empty($teachers)) {
            return $teachers;
        }
        return [];
    }

    /**
     * Function to get tags for autocomplete filter
     *
     * @param string $keyword
     * @return array
     */
    private function getTags($keyword = '')
    {
        $srch = new SearchBase(Course::DB_TBL_LANG);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(5);
        $srch->addDirectCondition('JSON_CONTAINS(course_srchtags, ' . '\'"' . $keyword . '"\')');
        $srch->addFld('course_srchtags');
        $tagsList = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (!empty($tagsList)) {
            return $tagsList;
        }
        return [];
    }
}
