<?php

/**
 * This Controller is used for handling courses
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CoursesController extends DashboardController
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
     * Render Search Form
     *
     */
    public function index()
    {
        $frm = $this->getSearchForm();
        $this->set('frm', $frm);
        $this->_template->addJs('js/jquery.barrating.min.js');
        $this->_template->render();
    }

    /**
     * Search & List Plans
     */
    public function search()
    {
        $frm = $this->getSearchForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), ['course_subcateid'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        /* get courses list */
        if ($this->siteUserType == User::LEARNER) {
            $srch = new OrderCourseSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
            $srch->addCondition('course.course_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
            $srch->addCondition('orders.order_payment_status', '=', Order::ISPAID);
            $srch->addSearchListingFields();
            $srch->applyPrimaryConditions();
            $srch->applySearchConditions($post);
            $srch->addOrder('crspro_status', 'ASC');
            $srch->addOrder('ordcrs_id', 'DESC');
        } else {
            $srch = new CourseSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
            $srch->addOrder('course_id', 'DESC');
            $srch->applyPrimaryConditions();
            $srch->addSearchListingFields();
            $srch->applySearchConditions($post);
        }
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['page']);
        $this->sets([
            'courses' => $srch->fetchAndFormat(),
            'post' => $post,
            'recordCount' => $srch->recordCount(),
            'courseStatuses' => Course::getStatuses(),
            'courseTypes' => Course::getTypes(),
            'orderStatuses' => CourseProgress::getStatuses(),
        ]);
        $this->_template->render(false, false);
    }
    /**
     * Render Course Manage Page
     *
     * @param mixed $courseId
     */
    public function form($courseId = 0)
    {
        if ($this->siteUserType == User::LEARNER) {
            FatUtility::exitWithErrorCode(404);
        }
        if (!empty($courseId) && FatUtility::int($courseId) < 1) {
            FatUtility::exitWithErrorCode(404);
        }
        $courseId = FatUtility::int($courseId);
        $courseTitle = '';
        if ($courseId > 0) {
            $srch = new CourseSearch($this->siteLangId, $this->siteUserId, User::TEACHER);
            $srch->applyPrimaryConditions();
            $srch->addMultipleFields(['course.course_id', 'course_title']);
            $srch->addCondition('course.course_id', '=', $courseId);
            $srch->addCondition('course.course_active', '=', AppConstant::ACTIVE);
            $srch->setPageSize(1);
            if (!$course = FatApp::getDb()->fetch($srch->getResultSet())) {
                Message::addErrorMessage(Label::getLabel('LBL_COURSE_NOT_FOUND'));
                FatApp::redirectUser(MyUtility::generateUrl('Courses'));
            }
            $courseTitle = $course['course_title'];
            $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
            if (!$course->canEditCourse()) {
                Message::addErrorMessage(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
                FatApp::redirectUser(MyUtility::generateUrl('Courses'));
            }
        }

        $this->set('courseTitle', $courseTitle);
        $this->set('courseId', $courseId);
        $this->set('siteLangId', $this->siteLangId);
        $this->set("includeEditor", true);
        $this->_template->addJs([
            'js/jquery.tagit.js',
            'js/jquery.ui.touch-punch.min.js',
            'attach-quizzes/page-js/index.js'
        ]);
        $this->_template->render();
    }

    /**
     * Render Basic Details Page
     *
     * @param int $courseId
     */
    public function generalForm(int $courseId = 0)
    {
        $course = [];
        if ($courseId > 0) {
            $srch = new CourseSearch($this->siteLangId, $this->siteUserId, User::TEACHER);
            $srch->applyPrimaryConditions();
            $srch->addMultipleFields([
                'course_title',
                'course_subtitle',
                'course_cate_id',
                'course_subcate_id',
                'course_clang_id',
                'course_level',
                'course_details',
                'course.course_id',
            ]);
            $srch->addCondition('course.course_id', '=', $courseId);
            $srch->addCondition('course.course_active', '=', AppConstant::ACTIVE);
            $srch->setPageSize(1);
            if (!$course = FatApp::getDb()->fetch($srch->getResultSet())) {
                FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_NOT_FOUND'));
            }
        }
        $frm = $this->getGeneralForm();
        $frm->fill($course);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    /**
     * Fetch sub categories for selected category
     *
     * @param int $catgId
     * @param int $subCatgId
     * @return html
     */
    public function getSubcategories(int $catgId, int $subCatgId = 0)
    {
        $catgId = FatUtility::int($catgId);
        $subcategories = [];
        if ($catgId > 0) {
            $subcategories = Category::getCategoriesByParentId($this->siteLangId, $catgId);
        }
        $this->set('subCatgId', $subCatgId);
        $this->set('subcategories', $subcategories);
        $this->_template->render(false, false);
    }

    /**
     * Setup basic details
     *
     * @return json
     */
    public function setup()
    {
        $frm = $this->getGeneralForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), [
            'course_cate_id', 'course_subcate_id', 'course_clang_id'
        ])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($post['course_id'], $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->setupGeneralData($post)) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess([
            'msg' => Label::getLabel('LBL_SETUP_SUCCESSFUL'),
            'courseId' => $course->getMainTableRecordId(),
            'title' => $post['course_title'],
        ]);
    }

    /**
     * Render Media Page
     *
     * @param int $courseId
     */
    public function mediaForm(int $courseId)
    {
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        /* validate course id */
        if (!Course::getAttributesById($courseId, 'course_id')) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        /* get form and fill */
        $frm = $this->getMediaForm();
        $frm->fill(['course_id' => $courseId]);
        /* get course image required dimensions */
        $file = new Afile(Afile::TYPE_COURSE_IMAGE);
        $image = $file->getFile($courseId);
        $dimensions = $file->getImageSizes(Afile::SIZE_LARGE);
        /* get video url */
        $file = new Afile(Afile::TYPE_COURSE_PREVIEW_VIDEO);
        $previewVideo = $file->getFile($courseId);
        $this->sets([
            'frm' => $frm,
            'courseId' => $courseId,
            'extensions' => Afile::getAllowedExts(Afile::TYPE_COURSE_IMAGE),
            'videoFormats' => Afile::getAllowedExts(Afile::TYPE_COURSE_PREVIEW_VIDEO),
            'dimensions' => $dimensions,
            'filesize' => MyUtility::convertBitesToMb(Afile::getAllowedUploadSize()),
            'previewVideo' => $previewVideo,
            'image' => $image,
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Setup course media
     *
     * @return json
     */
    public function setupMedia()
    {
        $frm = $this->getMediaForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $course = new Course($post['course_id'], $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->canEditCourse()) {
            FatUtility::dieWithError($course->getError());
        }
        if (empty($_FILES['course_image']['name']) && empty($_FILES['course_preview_video']['name'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_NO_MEDIA_SELECTED'));
        }
        $type = '';
        $files = [];
        if (!empty($_FILES['course_image']['name'])) {
            $type = Afile::TYPE_COURSE_IMAGE;
            $files = $_FILES['course_image'];
        }
        if (!empty($_FILES['course_preview_video']['name'])) {
            $type = Afile::TYPE_COURSE_PREVIEW_VIDEO;
            $files = $_FILES['course_preview_video'];
        }
        $file = new Afile($type);
        if (!$file->saveFile($files, $post['course_id'], true)) {
            FatUtility::dieJsonError($file->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_FILE_UPLOADED_SUCCESSFULLY'));
    }

    /**
     * Remove course media files
     *
     * @param int $courseId
     */
    public function removeMedia(int $courseId)
    {
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->canEditCourse()) {
            FatUtility::dieWithError($course->getError());
        }
        $type = FatApp::getPostedData('type');
        $file = new Afile($type);
        if (!$file->removeFile($courseId, 0, true)) {
            FatUtility::dieJsonError($file->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_FILE_REMOVED_SUCCESSFULLY'));
    }

    /**
     * Render Intended Learners Page
     *
     * @param int $courseId
     */
    public function intendedLearnersForm(int $courseId)
    {
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->get()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_NOT_FOUND'));
        }
        if (!$course->canEditCourse()) {
            FatUtility::dieJsonError($course->getError());
        }
        /* get form and fill */
        $frm = $this->getIntendedLearnersForm();
        $frm->fill(['course_id' => $courseId]);
        /* get saved responses */
        $learner = new IntendedLearner();
        $responses = $learner->get($courseId);
        $this->sets([
            'frm' => $frm,
            'courseId' => $courseId,
            'responses' => $responses,
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Setup Intended Learners Data
     *
     */
    public function setupIntendedLearners()
    {
        $frm = $this->getIntendedLearnersForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $course = new Course($post['course_id'], $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->canEditCourse()) {
            FatUtility::dieJsonError($course->getError());
        }
        $intended = new IntendedLearner();
        if (!$intended->setup($post)) {
            FatUtility::dieJsonError($intended->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
    }

    /**
     * Updating Intended Learner Records sort order
     *
     * @return json
     */
    public function updateIntendedOrder()
    {
        $ids = FatApp::getPostedData('order');
        $intended = new IntendedLearner();
        if (!$intended->updateOrder($ids)) {
            FatUtility::dieJsonError($intended->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_ORDER_SETUP_SUCCESSFUL'));
    }

    /**
     * function to delete course intended learner
     *
     * @param int $indLearnerId
     * @return json
     */
    public function deleteIntendedLearner(int $indLearnerId)
    {
        if ($indLearnerId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        /*  check if record exists */
        if (!$courseId = IntendedLearner::getAttributesById($indLearnerId, 'coinle_course_id')) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->canEditCourse()) {
            FatUtility::dieJsonError($course->getError());
        }
        $intended = new IntendedLearner($indLearnerId);
        if (!$intended->delete()) {
            FatUtility::dieJsonError($intended->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_REMOVED_SUCCESSFULLY'));
    }

    /**
     * Render Pricing Page
     *
     * @param int $courseId
     */
    public function priceForm(int $courseId)
    {
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        /* validate course id */
        $data = ['course_id' => $courseId];
        if (!$course = Course::getAttributesById($courseId, ['course_type', 'course_currency_id', 'course_price'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_NOT_FOUND'));
        }
        $courseObj = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$courseObj->canEditCourse()) {
            FatUtility::dieJsonError($courseObj->getError());
        }
        $data = array_merge($data, $course);

        /* get form and fill */
        $frm = $this->getPriceForm();
        $data['course_price'] = round(CourseUtility::convertToCurrency($data['course_price'], $data['course_currency_id']), 2);
        $frm->fill($data);
        $this->set('frm', $frm);
        $this->set('courseId', $courseId);
        $this->_template->render(false, false);
    }

    /**
     * Get Prices Data
     *
     * @return json
     */
    public function setupPrice()
    {
        $frm = $this->getPriceForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), ['course_currency_id'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $course = new Course($post['course_id'], $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->canEditCourse()) {
            FatUtility::dieJsonError($course->getError());
        }

        $price = 0;
        if ($post['course_type'] == Course::TYPE_PAID) {
            /* validate currency */
            $currencyId = FatUtility::int($post['course_currency_id']);
            if ($currencyId > 0 && Currency::getAttributesById($currencyId, 'currency_active') == AppConstant::INACTIVE) {
                FatUtility::dieJsonError(Label::getLabel('LBL_CURRENCY_NOT_AVAILABLE'));
            }
            if ($post['course_price'] > 0) {
                $price = CourseUtility::convertToSystemCurrency($post['course_price'], $post['course_currency_id']);
            }
            if ($price < 1) {
                $label = Label::getLabel('LBL_COURSE_PRICE_CANNOT_BE_LESS_THAN_1_{currency}');
                $label = str_replace('{currency}', MyUtility::getSystemCurrency()['currency_code'], $label);
                FatUtility::dieJsonError($label);
            }
            $maxPrice = 9999999;
            if ($price >= $maxPrice) {
                $label = Label::getLabel('LBL_COURSE_PRICE_CANNOT_BE_GREATER_THAN_{max-price}_{currency}');
                $label = str_replace(
                    ['{currency}', '{max-price}'], [MyUtility::getSystemCurrency()['currency_code'], $maxPrice], $label
                );
                FatUtility::dieJsonError($label);
            }
        }
        $course->assignValues([
            'course_type' => $post['course_type'],
            'course_currency_id' => $post['course_currency_id'],
            'course_price' => $price,
        ]);
        if (!$course->save()) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
    }

    /**
     * Render Curriculum Page
     *
     * @param int $courseId
     */
    public function curriculumForm(int $courseId)
    {
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        /* validate course id */
        if (!$course->get()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_NOT_FOUND'));
        }
        if (!$course->canEditCourse()) {
            FatUtility::dieJsonError($course->getError());
        }
        /* get form and fill */
        $frm = $this->getCurriculumForm();
        $frm->fill(['course_id' => $courseId]);
        $this->set('frm', $frm);
        $this->set('courseId', $courseId);
        $this->_template->render(false, false);
    }

    /**
     * Render Settings Page
     *
     * @param int $courseId
     */
    public function settingsForm(int $courseId)
    {
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        /* validate course id */
        if (!$courseData = Course::getAttributesById($courseId, [
            'course_id', 'course_certificate', 'course_certificate_type', 'course_quilin_id'
        ])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->canEditCourse()) {
            FatUtility::dieJsonError($course->getError());
        }
        /* create form data */
        $data = [
            'course_id' => $courseId,
            'course_certificate' => $courseData['course_certificate'],
            'course_certificate_type' => $courseData['course_certificate_type'],
            'course_quilin_id' => $courseData['course_quilin_id']
        ];
        /* get form data from lang table */
        $srch = new SearchBase(Course::DB_TBL_LANG);
        $srch->addCondition('course_id', '=', $courseId);
        $srch->addMultipleFields(['course_welcome', 'course_congrats', 'course_srchtags']);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        if ($course = FatApp::getDb()->fetch($srch->getResultSet())) {
            $crsTags = [];
            if (!empty($course['course_srchtags'])) {
                $crsTags = json_decode($course['course_srchtags']);
            }
            $course['course_tags'] = implode(',', $crsTags);
            $data = array_merge($data, $course);
        }
        /* check certificate available or not */
        $srch = CertificateTemplate::getSearchObject($this->siteLangId);
        $cnd = $srch->addCondition('certpl_code', '=', 'course_completion_certificate');
        $cnd->attachCondition('certpl_code', '=', 'course_evaluation_certificate');
        $srch->addCondition('certpl_status', '=', AppConstant::ACTIVE);
        $offerCetificate = false;
        if (FatApp::getDb()->fetch($srch->getResultSet())) {
            $offerCetificate = true;
        }
        /* get form and fill */
        $frm = $this->getSettingForm($offerCetificate);
        $frm->fill($data);
        $this->set('frm', $frm);

        /* get quiz detail */
        $data = QuizLinked::getQuizzes([$courseId], AppConstant::COURSE);
        $this->set('quiz', current($data));

        $this->set('offerCetificate', $offerCetificate);
        $this->set('courseId', $courseId);
        $this->_template->render(false, false);
    }

    /**
     * Setup Course Settings Data
     *
     * @return json
     */
    public function setupSettings()
    {
        $frm = $this->getSettingForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), ['course_certificate_type'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        if ($post['course_certificate'] == AppConstant::YES) {
            if ($post['course_certificate_type'] < 1) {
                FatUtility::dieJsonError(Label::getLabel('LBL_PLEASE_SELECT_CERTIFICATE'));
            } else {
                $code = 'course_completion_certificate';
                if ($post['course_certificate_type'] == Certificate::TYPE_COURSE_EVALUATION) {
                    $code = 'course_evaluation_certificate';
                }
                $srch = CertificateTemplate::getSearchObject($this->siteLangId);
                $srch->addCondition('certpl_code', '=', $code);
                $srch->addCondition('certpl_status', '=', AppConstant::ACTIVE);
                if (!FatApp::getDb()->fetch($srch->getResultSet())) {
                    FatUtility::dieJsonError(Label::getLabel('LBL_CERTIFICATE_NOT_AVIALABLE'));
                }
            }
        }
        if ($post['course_certificate_type'] == Certificate::TYPE_COURSE_EVALUATION && $post['course_quilin_id'] < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_PLEASE_ATTACH_QUIZ'));
        }
        $course = new Course($post['course_id'], $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->setupSettings($post)) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SETUP_SUCCESSFUL'));
    }

    /**
     * function to delete course
     *
     * @param int $courseId
     * @return json
     */
    public function remove(int $courseId)
    {
        $courseId = FatUtility::int($courseId);
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->delete()) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_REMOVED_SUCCESSFULLY'));
    }

    /**
     * function to delete course
     *
     * @return json
     */
    public function removeQuiz()
    {
        $courseId = FatApp::getPostedData('courseId', FatUtility::VAR_INT, 0);
        $quizLinkId = FatApp::getPostedData('quizLinkId', FatUtility::VAR_INT, 0);
        if ($courseId < 1 || $quizLinkId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->removeQuiz($quizLinkId)) {
            FatUtility::dieJsonError($course->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_REMOVED_SUCCESSFULLY'));
    }

    /**
     * Function to get eligibility status for all course steps.
     *
     * @param int $courseId
     * @return json
     */
    public function getEligibilityStatus(int $courseId)
    {
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType);
        $criteria = $course->isEligibleForApproval();
        FatUtility::dieJsonSuccess(['criteria' => $criteria]);
    }

    /**
     * Submitting course for approval from admin
     *
     * @param int $courseId
     * @return bool
     */
    public function submitForApproval(int $courseId)
    {
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$course->submitApprovalRequest()) {
            FatUtility::dieJsonError($course->getError());
        }
        Message::addMessage(Label::getLabel('LBL_APPROVAL_REQUESTED_SUCCESSFULLY'));
        FatUtility::dieJsonSuccess('');
    }

    /**
     * Add/Remove Course from user favorites list
     *
     * @return json
     */
    public function toggleFavorite()
    {
        $courseId = FatApp::getPostedData('course_id', FatUtility::VAR_INT, 0);
        if ($courseId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $db = FatApp::getDb();
        /* validate course id */
        $course = new Course($courseId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$data = $course->get()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_NOT_FOUND'));
        }
        if ($data['course_user_id'] == $this->siteUserId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_YOU_CANNOT_MARK_YOUR_OWN_COURSE_AS_FAVORITE'));
        }
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, AppConstant::NO);
        if ($status == AppConstant::NO) {
            /* check course already marked favorite */
            $srch = new SearchBase(User::DB_TBL_COURSE_FAVORITE);
            $srch->addCondition('ufc_user_id', '=', $this->siteUserId);
            $srch->addCondition('ufc_course_id', '=', $courseId);
            $srch->doNotCalculateRecords();
            $srch->setPageSize(1);
            if (FatApp::getDb()->fetch($srch->getResultSet())) {
                FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_IS_ALREADY_IN_YOUR_FAVORITES_LIST'));
            }
            /* add to favorites */
            $user = new User($this->siteUserId);
            if (!$user->setupFavoriteCourse($courseId)) {
                FatUtility::dieJsonError($user->getError());
            }
            FatUtility::dieJsonSuccess(Label::getLabel('LBL_COURSE_ADDED_TO_FAVORITES'));
        }
        /* remove from favorites */
        $where = [
            'smt' => 'ufc_user_id = ? AND ufc_course_id = ?',
            'vals' => [$this->siteUserId, $courseId]
        ];
        if (!$db->deleteRecords(User::DB_TBL_COURSE_FAVORITE, $where)) {
            FatUtility::dieJsonError($db->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_COURSE_REMOVED_FROM_FAVORITES'));
    }

    /**
     * Render cancellation popup
     */
    public function cancelForm()
    {
        $ordcrsId = FatApp::getPostedData('ordcrs_id', FatUtility::VAR_INT, 0);
        $order = new OrderCourse($ordcrsId, $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$order->getCourseToCancel()) {
            FatUtility::dieJsonError($order->getError());
        }
        $frm = $this->getCancelForm();
        $frm->fill(['ordcrs_id' => $ordcrsId]);
        $this->sets(['frm' => $frm]);
        $this->_template->render(false, false);
    }

    /**
     * Setup cancellation request
     *
     * @return bool
     */
    public function cancelSetup()
    {
        $frm = $this->getCancelForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $order = new OrderCourse($post['ordcrs_id'], $this->siteUserId, $this->siteUserType, $this->siteLangId);
        if (!$order->cancel($post['comment'])) {
            FatUtility::dieJsonError($order->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_CANCELLATION_REQUEST_SUBMITTED_SUCCESSFULLY'));
    }

    /**
     * Get Cancel Form
     *
     */
    private function getCancelForm(): Form
    {
        $frm = new Form('cancelFrm');
        $comment = $frm->addTextArea(Label::getLabel('LBL_COMMENTS'), 'comment');
        $comment->requirements()->setLength(10, 300);
        $comment->requirements()->setRequired();
        $frm->addHiddenField('', 'ordcrs_id')->requirements()->setRequired();
        $frm->addSubmitButton('', 'submit', Label::getLabel('LBL_SUBMIT'));
        return $frm;
    }

    /**
     * Get Search Form
     *
     */
    private function getSearchForm(): Form
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Label::getLabel('LBL_KEYWORD'), 'keyword');
        if ($this->siteUserType == User::TEACHER) {
            $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'course_status', Course::getStatuses(), '', [], Label::getLabel('LBL_SELECT'));
        } else {
            $frm->addSelectBox(Label::getLabel('LBL_STATUS'), 'crspro_status', OrderCourse::getStatuses(), '', [], Label::getLabel('LBL_SELECT'));
        }
        $frm->addSelectBox(Label::getLabel('LBL_TYPE'), 'course_type', Course::getTypes(), '', [], Label::getLabel('LBL_SELECT'));
        $categoryList = Category::getCategoriesByParentId($this->siteLangId);
        $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'course_cateid', $categoryList, '', [], Label::getLabel('LBL_SELECT'));
        $frm->addSelectBox(Label::getLabel('LBL_SUB_CATEGORY'), 'course_subcateid', [], '', [], Label::getLabel('LBL_SELECT'));
        $frm->addHiddenField('', 'pagesize', AppConstant::PAGESIZE)->requirements()->setInt();
        $frm->addHiddenField('', 'page', 1)->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        $frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_RESET'));
        return $frm;
    }

    /**
     * Basic Details Form
     *
     */
    private function getGeneralForm(): Form
    {
        $frm = new Form('frmCourses');
        $frm->addTextBox(Label::getLabel('LBL_COURSE_TITLE'), 'course_title')->requirements()->setRequired();
        $frm->addTextBox(Label::getLabel('LBL_COURSE_SUBTITLE'), 'course_subtitle')->requirements()->setRequired();
        $categories = Category::getCategoriesByParentId($this->siteLangId);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_CATEGORY'), 'course_cate_id', $categories, '', [], Label::getLabel('LBL_SELECT'));
        $fld->requirements()->setRequired();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_SUBCATEGORY'), 'course_subcate_id', [], '', [], Label::getLabel('LBL_SELECT'));
        $fld->requirements()->setInt();
        $langsList = (new CourseLanguage())->getAllLangs($this->siteLangId, true);
        $fld = $frm->addSelectBox(Label::getLabel('LBL_TEACHING_LANGUAGE'), 'course_clang_id', $langsList, '', [], Label::getLabel('LBL_SELECT'));
        $fld->requirements()->setRequired();
        $fld = $frm->addSelectBox(Label::getLabel('LBL_LEVEL'), 'course_level', Course::getCourseLevels(), '', [], Label::getLabel('LBL_SELECT'));
        $fld->requirements()->setRequired();
        $frm->addHtmlEditor(Label::getLabel('LBL_DESCRIPTION'), 'course_details')->requirements()->setRequired();
        $frm->addHiddenField('', 'course_id')->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_&_NEXT'));
        return $frm;
    }

    /**
     * Get Course Media Form
     *
     */
    private function getMediaForm(): Form
    {
        $frm = new Form('frmCourses');
        $frm->addFileUpload(Label::getLabel('LBl_COURSE_IMAGE'), 'course_image');
        $frm->addFileUpload(Label::getLabel('LBl_PREVIEW_VIDEO'), 'course_preview_video');
        $frm->addHiddenField('', 'course_id')->requirements()->setInt();
        $frm->addButton('', 'btn_next', Label::getLabel('LBL_SAVE_&_NEXT'));
        return $frm;
    }

    /**
     * Get Intended Learners Form
     *
     */
    private function getIntendedLearnersForm(): Form
    {
        $frm = new Form('frmCourses');
        $frm->addTextBox('', 'type_learnings[]')->requirements()->setRequired();
        $frm->addTextBox('', 'type_requirements[]')->requirements()->setRequired();
        $frm->addTextBox('', 'type_learners[]')->requirements()->setRequired();
        $frm->addHiddenField('', 'type_learnings_ids[]');
        $frm->addHiddenField('', 'type_requirements_ids[]');
        $frm->addHiddenField('', 'type_learners_ids[]');
        $frm->addHiddenField('', 'course_id')->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_&_NEXT'));
        return $frm;
    }

    /**
     * Get Prices Form
     *
     */
    private function getPriceForm(): Form
    {
        $frm = new Form('frmCourses');
        $fld = $frm->addRadioButtons(Label::getLabel('LBL_TYPE'), 'course_type', Course::getTypes());
        $fld->requirements()->setRequired();
        $frm->addSelectBox(
            Label::getLabel('LBL_CURRENCY'),
            'course_currency_id',
            Currency::getCurrencyNameWithCode($this->siteLangId), 
            '', 
            [], 
            Label::getLabel('LBL_SELECT')
        );
        $frm->addTextBox(Label::getLabel('LBL_PRICE'), 'course_price')->requirements()->setFloat();
        $frm->addHiddenField('', 'course_id')->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE_&_NEXT'));
        return $frm;
    }

    /**
     * Get Curriculum Form
     *
     */
    private function getCurriculumForm(): Form
    {
        $frm = new Form('frmCourses');
        $frm->addHiddenField('', 'course_id')->requirements()->setInt();
        $frm->addButton('', 'btn_next', Label::getLabel('LBL_SAVE_&_NEXT'));
        return $frm;
    }

    /**
     * Get Setting Form
     *
     * @param bool $offerCetificate
     */
    private function getSettingForm(bool $offerCetificate = true): Form
    {
        $frm = new Form('frmCourses');
        if ($offerCetificate == true) {
            $fld = $frm->addRadioButtons(Label::getLabel('LBL_OFFER_CERTIFICATE'), 'course_certificate', AppConstant::getYesNoArr(), AppConstant::NO);
            $fld->requirements()->setRequired();

        } else {
            $frm->addHiddenField('', 'course_certificate', AppConstant::NO);
        }
        $types = Certificate::getTypes();
        unset($types[Certificate::TYPE_QUIZ_EVALUATION]);
        $typeFld = $frm->addSelectBox(Label::getLabel('LBL_CERTIFICATE'), 'course_certificate_type', $types);

        
        $fld = $frm->addHiddenField(Label::getLabel('LBL_QUIZ'), 'course_quilin_id');
        $fld->requirements()->setInt();
        $fld->requirements()->setRequired(false);
        $fld->requirements()->setCustomErrorMessage(Label::getLabel('LBL_PLEASE_ATTACH_A_QUIZ'));
        $reqFld = new FormFieldRequirement('course_quilin_id', '');
        $reqFld->setRequired(true);
        $notReqFld = new FormFieldRequirement('course_quilin_id', '');
        $notReqFld->setRequired(false);

        $typeFld->requirements()->addOnChangerequirementUpdate(
            Certificate::TYPE_COURSE_EVALUATION,
            'eq',
            'course_quilin_id',
            $reqFld
        );
        $typeFld->requirements()->addOnChangerequirementUpdate(
            Certificate::TYPE_COURSE_EVALUATION,
            'ne',
            'course_quilin_id',
            $notReqFld
        );

        $frm->addTextBox(Label::getLabel('LBL_COURSE_TAGS'), 'course_tags')->requirements()->setRequired();
        $frm->addHiddenField('', 'course_id')->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_save', Label::getLabel('LBL_SAVE'));
        $frm->addButton('', 'btn_approval', Label::getLabel('LBL_SUBMIT_FOR_APPROVAL'));
        return $frm;
    }
}
