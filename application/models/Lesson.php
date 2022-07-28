<?php

/**
 * This class is used to handle Lesson
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class Lesson extends MyAppModel
{

    const DB_TBL = 'tbl_order_lessons';
    const DB_TBL_PREFIX = 'ordles_';
    /* Lesson Status */
    const UNSCHEDULED = 1;
    const SCHEDULED = 2;
    const COMPLETED = 3;
    const CANCELLED = 4;

    /* Lesson Types */
    const TYPE_FTRAIL = 1;
    const TYPE_REGULAR = 2;
    const TYPE_SUBCRIP = 3;

    private $userId;
    private $userType;

    /**
     * Initialize Lesson
     * 
     * @param int $lessonId
     * @param int $userId
     * @param int $userType
     */
    public function __construct(int $lessonId, int $userId, int $userType)
    {
        $this->userId = $userId;
        $this->userType = $userType;
        parent::__construct(static::DB_TBL, 'ordles_id', $lessonId);
    }

    /**
     * Get Types
     * 
     * @param int $key
     * @return type
     */
    public static function getTypes(int $key = null)
    {
        $arr = [
            static::TYPE_FTRAIL => Label::getLabel('TYPE_FREE_TRAIL'),
            static::TYPE_REGULAR => Label::getLabel('TYPE_REGULAR'),
            static::TYPE_SUBCRIP => Label::getLabel('TYPE_SUBSCRIPTION')
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Get Search Object
     * 
     * @return SearchBase
     */
    public function getSearchObject(): SearchBase
    {
        $srch = new SearchBase(static::DB_TBL, 'ordles');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id = ordles.ordles_order_id', 'orders');
        if (!empty($this->mainTableRecordId)) {
            $srch->addCondition('ordles.ordles_id', '=', $this->mainTableRecordId);
        }
        if ($this->userType == User::LEARNER) {
            $srch->addCondition('order_user_id', '=', $this->userId);
            $srch->addCondition('order_payment_status', '=', Order::ISPAID);
        } elseif ($this->userType == User::TEACHER) {
            $srch->addCondition('ordles_teacher_id', '=', $this->userId);
            $srch->addCondition('order_payment_status', '=', Order::ISPAID);
        }
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return $srch;
    }

    /**
     * Start Lesson
     * 
     * @param array $lesson
     * @return bool
     */
    public function start(array $lesson): bool
    {
        if ($this->userType == User::LEARNER && empty($lesson['ordles_teacher_starttime'])) {
            $this->error = Label::getLabel('LBL_PLEASE_WAIT_LET_TEACHER_START_MEETING');
            return false;
        }
        $field = ($this->userType == User::LEARNER) ? 'ordles_student_starttime' : 'ordles_teacher_starttime';
        if (empty($lesson[$field])) {
            $this->setFldValue($field, date('Y-m-d H:i:s'));
        }
        $this->setFldValue('ordles_metool_id', $lesson['ordles_metool_id']);
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Complete Lesson
     * 
     * @param array $lesson
     * @return bool
     */
    public function complete(array $lesson): bool
    {
        if ($lesson['ordles_status'] == Lesson::COMPLETED) {
            return true;
        }
        $db = FatApp::getDb();
        $date = date('Y-m-d H:i:s');
        $this->assignValues([
            'ordles_teacher_endtime' => $date,
            'ordles_student_endtime' => $date,
            'ordles_status' => Lesson::COMPLETED,
            'ordles_ended_by' => $this->userType
        ]);
        $db->startTransaction();
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        /** add the Lesson log */
        $sessionLog = new SessionLog($this->mainTableRecordId, AppConstant::LESSON);
        if (!$sessionLog->addCompletedLessonLog($this->userId, $this->userType)) {
            $this->error = $sessionLog->getError();
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        $this->sendCompletedLessonNotiToTeacher($lesson);
        $this->sendCompletedLessonNotiToLearner($lesson);
        return true;
    }

    /**
     * Schedule Lesson
     * 
     * @param array $post
     * @param int $langId
     * @return bool
     */
    public function schedule($post, int $langId): bool
    {
        if (!$lesson = $this->getLessonToSchedule(true)) {
            return false;
        }
        $post['ordles_lesson_starttime'] = MyDate::formatToSystemTimezone($post['ordles_lesson_starttime']);
        $post['ordles_lesson_endtime'] = MyDate::formatToSystemTimezone($post['ordles_lesson_endtime']);
        if (!userSetting::validateBookingBefore($post['ordles_lesson_starttime'], $lesson['user_book_before'])) {
            $this->error = Label::getLabel('LBL_TEACHER_DISABLE_THE_BOOKING');
            return false;
        }
        if ($lesson['ordles_type'] == LESSON::TYPE_SUBCRIP) {
            $subscription = Subscription::getSubsByOrderId($lesson['ordles_order_id'], ['ordsub_startdate', 'ordsub_enddate']);
            if (!empty($subscription['ordsub_startdate'])) {
                if (
                        strtotime($post['ordles_lesson_starttime']) < strtotime($subscription['ordsub_startdate']) ||
                        strtotime($post['ordles_lesson_endtime']) > strtotime($subscription['ordsub_enddate'])
                ) {
                    $this->error = Label::getLabel('LBL_YOU_CAN_NOT_BOOK_LESSON_ON_THIS_TIME');
                    return false;
                }
            }
        }


        /** check teacher availability */
        $availability = new Availability($lesson['teacher_id']);
        if (!$availability->isAvailable($post['ordles_lesson_starttime'], $post['ordles_lesson_endtime'])) {
            $this->error = $availability->getError();
            return false;
        }
        /** check teacher slot availability */
        if (!$availability->isUserAvailable($post['ordles_lesson_endtime'], $post['ordles_lesson_endtime'])) {
            $this->error = $availability->getError();
            return false;
        }
        /** check Learner slot availability */
        $availability = new Availability($this->userId);
        if (!$availability->isUserAvailable($post['ordles_lesson_endtime'], $post['ordles_lesson_endtime'])) {
            $this->error = $availability->getError();
            return false;
        }

        $post['ordles_status'] = static::SCHEDULED;
        $post['ordles_updated'] = date('Y-m-d H:i:s');
        $this->assignValues($post);
        $db = FatApp::getDb();
        $db->startTransaction();
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        /** add the Lesson log */
        $sessionLog = new SessionLog($this->mainTableRecordId, AppConstant::LESSON);
        if (!$sessionLog->addScheduledLessonLog($this->userId, $post['ordles_lesson_starttime'], $post['ordles_lesson_endtime'])) {
            $this->error = $sessionLog->getError();
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        $tlanguageNames = TeachLanguage::getNames($langId, [$lesson['ordles_tlang_id']]);
        $lesson['tlang_name'] = $tlanguageNames[$lesson['ordles_tlang_id']] ?? '';
        $lesson = array_merge($lesson, $post);
        /**
         * Add the Event for learner and teacher
         */
        $this->addGoogleEvent($lesson);
        $this->sendScheduledMail($lesson, User::LEARNER, $langId);
        $meetingTool = new Meeting(0, 0);
        $meetingTool->checkLicense($post['ordles_lesson_starttime'], $post['ordles_lesson_endtime'], $lesson['ordles_duration']);
        return true;
    }

    /**
     * Reschedule Lesson
     * 
     * @param array $post
     * @param int $langId
     * @return bool
     */
    public function reschedule(array $post, int $langId): bool
    {
        if (!$lesson = $this->getLessonToReschedule(true)) {
            return false;
        }
        if ($this->userType == User::LEARNER) {
            $post['ordles_lesson_starttime'] = MyDate::formatToSystemTimezone($post['ordles_lesson_starttime']);
            $post['ordles_lesson_endtime'] = MyDate::formatToSystemTimezone($post['ordles_lesson_endtime']);
            if (!userSetting::validateBookingBefore($post['ordles_lesson_starttime'], $lesson['user_book_before'])) {
                $this->error = Label::getLabel('LBL_TEACHER_DISABLE_THE_BOOKING');
                return false;
            }
            if ($lesson['ordles_type'] == LESSON::TYPE_SUBCRIP) {
                $subscription = Subscription::getSubsByOrderId($lesson['ordles_order_id'], ['ordsub_startdate', 'ordsub_enddate']);
                if (!empty($subscription['ordsub_startdate'])) {
                    if (
                            strtotime($post['ordles_lesson_starttime']) < strtotime($subscription['ordsub_startdate']) ||
                            strtotime($post['ordles_lesson_endtime']) > strtotime($subscription['ordsub_enddate'])
                    ) {
                        $this->error = Label::getLabel('LBL_YOU_CAN_NOT_BOOK_LESSON_ON_THIS_TIME');
                        return false;
                    }
                }
            }
            /** check teacher availability */
            $availability = new Availability($lesson['ordles_teacher_id']);
            if (!$availability->isAvailable($post['ordles_lesson_starttime'], $post['ordles_lesson_endtime'])) {
                $this->error = $availability->getError();
                return false;
            }
            /** check teacher slot availability */
            if (!$availability->isUserAvailable($post['ordles_lesson_endtime'], $post['ordles_lesson_endtime'])) {
                $this->error = $availability->getError();
                return false;
            }
            /** check Learner slot availability */
            $availability = new Availability($this->userId);
            if (!$availability->isUserAvailable($post['ordles_lesson_endtime'], $post['ordles_lesson_endtime'])) {
                $this->error = $availability->getError();
                return false;
            }
            $post['ordles_status'] = static::SCHEDULED;
        } elseif ($this->userType == User::TEACHER) {
            $post['ordles_status'] = static::UNSCHEDULED;
            $post['ordles_lesson_starttime'] = null;
            $post['ordles_lesson_endtime'] = null;
        }
        $post['ordles_updated'] = date('Y-m-d H:i:s');
        $this->assignValues($post);
        if (!$this->save()) {
            return false;
        }
        $this->addRescheduleLog($lesson, $post);
        $tlanguageNames = TeachLanguage::getNames($langId, [$lesson['ordles_tlang_id']]);
        $lesson['tlang_name'] = $tlanguageNames[$lesson['ordles_tlang_id']] ?? '';
        $lesson = array_merge($lesson, $post);
        $this->deleteGoogleEvent($lesson);
        if ($this->userType == User::LEARNER) {
            $this->addGoogleEvent($lesson);
            $this->sendRescheduledMailToTeacher($lesson);
        } elseif ($this->userType == User::TEACHER) {
            $this->sendRescheduledMailToLearner($lesson);
        }
        $meetingTool = new Meeting(0, 0);
        $meetingTool->checkLicense($post['ordles_lesson_starttime'], $post['ordles_lesson_endtime'], $lesson['ordles_duration']);
        return true;
    }

    /**
     * Add Reschedule Log
     * 
     * @param array $lesson
     * @param array $post
     * @return bool
     */
    public function addRescheduleLog(array $lesson, array $post): bool
    {
        $sessionLog = new SessionLog($this->mainTableRecordId, AppConstant::LESSON);
        if ($this->userType == User::LEARNER) {
            if (!$sessionLog->addRescheduledLessonLog($this->userId, $lesson, $post)) {
                $this->error = $sessionLog->getError();
                return false;
            }
            return true;
        } elseif ($this->userType == User::TEACHER) {
            $lesson['comment'] = $post['comment'];
            if (!$sessionLog->addUnscheduledLessonLog($this->userId, $lesson)) {
                $this->error = $sessionLog->getError();
                return false;
            }
            return true;
        }
        $this->error = Label::getLabel('LBL_INVALID_REQUEST');
        return false;
    }

    /**
     * Cancel Lesson
     * 
     * @param array $post
     * @return bool
     */
    public function cancel(array $post): bool
    {
        if (!$lesson = $this->getLessonToCancel(true)) {
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $this->assignValues(['ordles_status' => static::CANCELLED, 'ordles_updated' => date('Y-m-d H:i:s')]);
        if (!$this->save()) {
            return false;
        }
        if (FatUtility::float($lesson['order_net_amount']) > 0) {
            $refundPercent = $this->getRefundPercentage($lesson['ordles_status'], $lesson['ordles_lesson_starttime']);
            if (!$this->refundToLearner($lesson, $refundPercent)) {
                $db->rollbackTransaction();
                return false;
            }
        }
        $sessionLog = new SessionLog($this->mainTableRecordId, AppConstant::LESSON);
        if (!$sessionLog->addCanceledLessonLog($this->userId, $this->userType, $lesson['ordles_status'], $post['comment'])) {
            $this->error = $sessionLog->getError();
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        if ($lesson['ordles_status'] == Lesson::SCHEDULED) {
            $this->deleteGoogleEvent($lesson);
        }
        $lesson['comment'] = $post['comment'];
        if ($this->userType == User::LEARNER) {
            $this->sendCancelledNotifiToTeacher($lesson);
        } elseif ($this->userType == User::TEACHER) {
            $this->sendCancelledNotifiToLearner($lesson);
        }
        return true;
    }

    /**
     * Feedback Lesson
     * 
     * @param array $post
     * @return bool
     */
    public function feedback(array $post): bool
    {
        if (!$lesson = $this->getLessonToFeedback()) {
            return false;
        }
        $db = FatApp::getDb();
        $db->startTransaction();
        $ratingReview = new RatingReview($lesson['ordles_teacher_id'], $this->userId);
        if (!$ratingReview->addReview(AppConstant::LESSON, $lesson['ordles_id'], $post)) {
            $db->rollbackTransaction();
            $this->error = $this->getError();
            return false;
        }
        $this->assignValues(['ordles_reviewed' => AppConstant::YES, 'ordles_updated' => date('Y-m-d H:i:s')]);
        if (!$this->save()) {
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        if (FatApp::getConfig('CONF_DEFAULT_REVIEW_STATUS') == RatingReview::STATUS_APPROVED) {
            $ratingReview->sendMailToTeacher($lesson);
        } else {
            $ratingReview->sendMailToAdmin($lesson);
        }
        return true;
    }

    /**
     * Get Lesson To Start
     * 
     * @return bool|array
     */
    public function getLessonToStart()
    {
        $srch = $this->getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = ordles_teacher_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = order_user_id', 'learner');
        if ($this->userType == User::LEARNER) {
            $srch->addCondition('order_user_id', '=', $this->userId);
        } else {
            $srch->addCondition('ordles_teacher_id', '=', $this->userId);
        }
        $currentDate = date('Y-m-d H:i:s');
        $srch->addCondition('ordles_status', '=', Lesson::SCHEDULED);
        $srch->addCondition('ordles_lesson_starttime', '<=', $currentDate);
        $srch->addCondition('ordles_lesson_endtime', '>', $currentDate);
        $srch->addMultipleFields([
            'ordles.ordles_lesson_starttime', 'ordles.ordles_amount', 'order_discount_value',
            'ordles.ordles_order_id', 'ordles.ordles_id', 'ordles.ordles_tlang_id', 'ordles_duration',
            'ordles.ordles_lesson_endtime', 'ordles.ordles_teacher_id', 'ordles.ordles_teacher_id as teacher_id', 'orders.order_user_id',
            'teacher.user_first_name as teacher_first_name', 'teacher.user_last_name as teacher_last_name',
            'teacher.user_email as teacher_email', 'teacher.user_timezone as teacher_timezone',
            'learner.user_first_name as learner_first_name', 'learner.user_last_name as learner_last_name',
            'learner.user_timezone as learner_timezone', 'learner.user_email as learner_email', 'ordles_status',
            'order_user_id', 'order_user_id as learner_id', 'ordles_teacher_starttime', 'ordles_metool_id'
        ]);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        return $row;
    }

    /**
     * Get Lesson To Complete

     * @return bool|array
     */
    public function getLessonToComplete()
    {
        $srch = $this->getSearchObject();
        if ($this->userType == User::LEARNER) {
            $srch->addCondition('order_user_id', '=', $this->userId);
            $srch->addDirectCondition('ordles_student_starttime IS NOT NULL');
        } elseif ($this->userType == User::TEACHER) {
            $srch->addCondition('ordles_teacher_id', '=', $this->userId);
            $srch->addDirectCondition('ordles_teacher_starttime IS NOT NULL');
        }
        $srch->addCondition('ordles_status', 'IN', [static::SCHEDULED, static::COMPLETED]);
        $srch->addCondition('ordles_lesson_starttime', '<', date('Y-m-d H:i:s'));
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        if ($this->userType == User::TEACHER && !empty($row['ordles_teacher_starttime'])) {
            $durtaion = FatApp::getConfig('CONF_ALLOW_TEACHER_END_LESSON');
            $toTime = strtotime('+' . $durtaion . ' minutes', strtotime($row['ordles_teacher_starttime']));
            $toTime = min($toTime, strtotime($row['ordles_lesson_endtime']));
            if (time() < $toTime) {
                $this->error = Label::getLabel('LBL_CANNOT_END_LESSON_SO_EARLY!');
                return false;
            }
        }
        return $row;
    }

    /**
     * Get Lesson To Schedule
     * 
     * @param bool $joinSettings
     * @return bool|array
     */
    public function getLessonToSchedule(bool $joinSettings = false)
    {
        $srch = $this->getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = ordles_teacher_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = order_user_id', 'learner');
        if ($joinSettings) {
            $srch->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'lsetting.user_id = learner.user_id', 'lsetting');
            $srch->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'tsetting.user_id = teacher.user_id', 'tsetting');
            $srch->addMultipleFields([
                'tsetting.user_book_before',
                'IFNULL(lsetting.user_google_token,"") as learner_google_token',
                'IFNULL(tsetting.user_google_token,"") as teacher_google_token',
            ]);
        }
        $srch->addCondition('order_user_id', '=', $this->userId);
        $srch->addCondition('ordles_status', '=', static::UNSCHEDULED);
        $srch->addMultipleFields([
            'ordles.*', 'order_user_id', 'order_user_id as leaner_id',
            'ordles_teacher_id', 'ordles_teacher_id as teacher_id',
            'learner.user_lang_id as learner_lang_id',
            'teacher.user_lang_id as teacher_lang_id',
            'teacher.user_first_name as teacher_first_name',
            'teacher.user_last_name as teacher_last_name',
            'teacher.user_timezone as teacher_timezone',
            'teacher.user_country_id as teacher_country_id',
            'teacher.user_email as teacher_email',
            'learner.user_first_name as learner_first_name',
            'learner.user_last_name as learner_last_name'
        ]);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        return $row;
    }

    /**
     * Get Lesson To Reschedule
     * 
     * @param bool $joinSettings
     * @return bool|array
     */
    public function getLessonToReschedule(bool $joinSettings = false)
    {
        $srch = $this->getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = ordles_teacher_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = order_user_id', 'learner');
        if ($joinSettings) {
            $srch->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'lsetting.user_id = learner.user_id', 'lsetting');
            $srch->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'tsetting.user_id = teacher.user_id', 'tsetting');
            $srch->addMultipleFields([
                'tsetting.user_book_before',
                'IFNULL(lsetting.user_google_token,"") as learner_google_token',
                'IFNULL(tsetting.user_google_token,"") as teacher_google_token',
            ]);
        }
        $srch->addCondition('order_status', '=', Order::STATUS_COMPLETED);
        $srch->addCondition('ordles_status', '=', static::SCHEDULED);
        $srch->addMultipleFields([
            'ordles.*',
            'learner.user_lang_id as learner_lang_id',
            'teacher.user_lang_id as teacher_lang_id',
            'teacher.user_first_name as teacher_first_name',
            'teacher.user_last_name as teacher_last_name',
            'teacher.user_country_id as teacher_country_id',
            'teacher.user_email as teacher_email',
            'teacher.user_timezone as teacher_timezone',
            'ordles_teacher_id', 'ordles_teacher_id as teacher_id', 'order_user_id',
            'order_user_id as learner_id',
            'learner.user_email as learner_email',
            'learner.user_first_name as learner_first_name',
            'learner.user_last_name as learner_last_name',
        ]);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        $updateLessonWindow = FatApp::getConfig('CONF_LESSON_RESCHEDULE_DURATION', FatUtility::VAR_FLOAT, 24);
        $hoursDiff = MyDate::hoursDiff($row['ordles_lesson_starttime']);
        if ($updateLessonWindow > $hoursDiff) {
            $this->error = Label::getLabel('LBL_YOU_CAN_NOT_RESCHEDULE_LESSON_NOW');
            return false;
        }
        return $row;
    }

    /**
     * Get Lesson To Cancel
     * 
     * @param bool $joinSettings
     * @return bool|array
     */
    public function getLessonToCancel(bool $joinSettings = false)
    {
        $srch = $this->getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = ordles_teacher_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = order_user_id', 'learner');
        if ($joinSettings) {
            $srch->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'lsetting.user_id = learner.user_id', 'lsetting');
            $srch->joinTable(UserSetting::DB_TBL, 'LEFT JOIN', 'tsetting.user_id = teacher.user_id', 'tsetting');
            $srch->addMultipleFields([
                'IFNULL(lsetting.user_google_token,"") as learner_google_token',
                'IFNULL(tsetting.user_google_token,"") as teacher_google_token',
            ]);
        }
        $srch->addCondition('order_status', '=', Order::STATUS_COMPLETED);
        $srch->addCondition('ordles_status', 'IN', [static::UNSCHEDULED, static::SCHEDULED]);
        $srch->addMultipleFields([
            'ordles.ordles_lesson_starttime', 'ordles.ordles_amount', 'order_discount_value', 'order_net_amount', 'ordles_discount',
            'ordles.ordles_order_id', 'ordles.ordles_id', 'ordles.ordles_tlang_id', 'order_item_count',
            'ordles.ordles_lesson_endtime', 'ordles.ordles_teacher_id', 'ordles.ordles_teacher_id as teacher_id', 'order_total_amount',
            'teacher.user_first_name as teacher_first_name', 'teacher.user_last_name as teacher_last_name',
            'teacher.user_email as teacher_email', 'teacher.user_timezone as teacher_timezone',
            'teacher.user_lang_id as teacher_lang_id',
            'learner.user_first_name as learner_first_name', 'learner.user_last_name as learner_last_name',
            'learner.user_lang_id as learner_lang_id',
            'learner.user_timezone as learner_timezone', 'learner.user_email as learner_email', 'ordles_status',
            'order_user_id', 'order_user_id as learner_id'
        ]);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        $duration = FatApp::getConfig('CONF_LESSON_CANCEL_DURATION');
        $startTime = strtotime($row['ordles_lesson_starttime'] . ' -' . $duration . ' hours');
        if (static::SCHEDULED == $row['ordles_status'] && time() >= $startTime) {
            $this->error = Label::getLabel('LBL_TIME_TO_CANCEL_LESSON_PASSED');
            return false;
        }
        return $row;
    }

    /**
     * Get Lesson To Feedback
     * 
     * @return bool|array
     */
    public function getLessonToFeedback()
    {
        if ($this->userType != User::LEARNER) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }
        if (FatApp::getConfig('CONF_ALLOW_REVIEWS') != AppConstant::YES) {
            $this->error = Label::getLabel('LBL_REVIEW_NOT_ALLOWED');
            return false;
        }
        $srch = $this->getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = ordles_teacher_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = order_user_id', 'learner');
        $srch->addCondition('ordles_status', '=', Lesson::COMPLETED);
        $srch->addCondition('ordles_reviewed', '=', AppConstant::NO);
        $srch->addMultipleFields([
            'order_user_id',
            'ordles_id',
            'ordles_teacher_id',
            'teacher.user_first_name as teacher_first_name',
            'teacher.user_last_name as teacher_last_name',
            'teacher.user_lang_id as teacher_lang_id',
            'teacher.user_email as teacher_email',
            'learner.user_first_name as learner_first_name',
            'learner.user_last_name as learner_last_name',
        ]);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        return $row;
    }

    /**
     * Get Lesson To Report
     * 
     * @return bool|array
     */
    public function getLessonToReport()
    {
        $reportHours = FatApp::getConfig('CONF_REPORT_ISSUE_HOURS_AFTER_COMPLETION', FatUtility::VAR_INT, 0);
        if (0 >= $reportHours) {
            $this->error = Label::getLabel('LBL_YOU_CAN_NOT_REPORT_NOW');
            return false;
        }
        $srch = $this->getSearchObject();
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'teacher.user_id = ordles_teacher_id', 'teacher');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'learner.user_id = order_user_id', 'learner');
        $srch->joinTable(Issue::DB_TBL, 'LEFT JOIN', 'repiss.repiss_record_id = ordles_id and repiss.repiss_record_type = ' . AppConstant::LESSON, 'repiss');
        $srch->addCondition('orders.order_status', '=', Order::STATUS_COMPLETED);
        $srch->addCondition('orders.order_user_id', '=', $this->userId);
        $srch->addCondition('ordles.ordles_type', '!=', static::TYPE_FTRAIL);
        $srch->addDirectCondition('ordles.ordles_teacher_paid IS NULL');
        $srch->addCondition('ordles.ordles_status', 'IN', [static::COMPLETED, static::SCHEDULED]);
        $srch->addDirectCondition('repiss.repiss_record_id IS NULL');
        $srch->addMultipleFields([
            'ordles.*',
            'teacher.user_first_name as teacher_first_name',
            'teacher.user_last_name as teacher_last_name',
            'teacher.user_timezone as teacher_timezone',
            'teacher.user_country_id as teacher_country_id',
            'teacher.user_email as teacher_email',
            'learner.user_first_name as learner_first_name',
            'learner.user_last_name as learner_last_name',
        ]);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($row)) {
            $this->error = Label::getLabel('LBL_LESSON_NOT_FOUND');
            return false;
        }
        $endTimeUnix = strtotime($row['ordles_lesson_endtime']);
        $reportTime = strtotime(" +" . $reportHours . " hour", $endTimeUnix);
        if (
                ($row['ordles_status'] == Lesson::COMPLETED || ($row['ordles_status'] == Lesson::SCHEDULED && empty($row['ordles_teacher_starttime']) && time() > $endTimeUnix)) && $reportTime > time()
        ) {
            return $row;
        }
        $this->error = Label::getLabel('LBL_INVALID_REQUSET');
        return false;
    }

    /**
     * Get Lesson Price
     * 
     * @param array $lesson
     * @return bool|array
     */
    public function getLessonPrice(array $lesson)
    {
        if ($lesson['ordles_type'] == Lesson::TYPE_FTRAIL) {
            $lesson['ordles_quantity'] = 1;
            $lesson['ordles_amount'] = 0.0;
            $lesson['ordles_tlang_id'] = null;
            $lesson['ordles_tlang'] = '';
            $lesson['ordles_duration'] = FatApp::getConfig('CONF_TRIAL_LESSON_DURATION');
            $userSettings = new UserSetting($lesson['ordles_teacher_id']);
            if (!$userSettings->validateFreeTrial($this->userId, $lesson['ordles_starttime'], $lesson['ordles_endtime'])) {
                $this->error = $userSettings->getError();
                return false;
            }
            $lesson['lessons'][0] = $lesson;
        } elseif (
                $lesson['ordles_type'] == Lesson::TYPE_REGULAR ||
                $lesson['ordles_type'] == Lesson::TYPE_SUBCRIP
        ) {
            $lesson['ordles_amount'] = Lesson::getPrice($lesson);
            if (empty($lesson['ordles_amount'])) {
                $this->error = Label::getLabel('LBL_LESSON_NOT_AVAILABLE');
                return false;
            }
            if (!$lesson = $this->formatAndValidate($lesson)) {
                return false;
            }
        }
        return $lesson;
    }

    private function formatAndValidate(array $lesson)
    {
        $setting = UserSetting::getSettings($lesson['ordles_teacher_id'], ['user_book_before']);
        /* conver hours to minutes */
        $bookBefore = $setting['user_book_before'] * 60;
        $validDate = strtotime('+' . $bookBefore . ' minutes');
        $lessons = [];
        foreach ($lesson['lessons'] as $key => $value) {
            if ($lesson['ordles_type'] == Lesson::TYPE_REGULAR && (empty($value['ordles_starttime']) || empty($value['ordles_endtime']))) {
                continue;
            }
            if (strtotime($value['ordles_starttime']) > strtotime($value['ordles_endtime'])) {
                $this->error = Label::getLabel('LBL_START_TIME_MUST_BE_GREATER_THEN_END_TIME');
                return false;
            }
            if (strtotime($value['ordles_starttime']) < $validDate) {
                $this->error = Label::getLabel('LBL_YOU_CAN_NOT_BOOKED_LESSON_ON_THIS_TIME');
                return false;
            }
            foreach ($lessons as $time) {
                if (
                        strtotime($time['ordles_starttime']) < strtotime($value['ordles_endtime']) && strtotime($time['ordles_endtime']) > strtotime($value['ordles_starttime'])
                ) {
                    $this->error = Label::getLabel('LBL_LESSON_TIME_ARE_COLLAPES_WITH_EACH_OTHER');
                    return false;
                }
            }
            $avail = new Availability($lesson['ordles_teacher_id']);
            if (!$avail->isAvailable($value['ordles_starttime'], $value['ordles_endtime'])) {
                $this->error = $avail->getError();
                return false;
            }
            if (!$avail->isUserAvailable($value['ordles_starttime'], $value['ordles_endtime'])) {
                $this->error = $avail->getError();
                return false;
            }
            $avail = new Availability($this->userId);
            if (!$avail->isUserAvailable($value['ordles_starttime'], $value['ordles_endtime'])) {
                $this->error = $avail->getError();
                return false;
            }
            $lessons[] = $value;
            $lesson['lessons'][$key]['ordles_amount'] = $lesson['ordles_amount'];
        }
        return $lesson;
    }

    /**
     * Get Lesson Price
     * 
     * @param array $lesson = [
     *          ordles_teacher_id, ordles_tlang_id, ordles_durFation, ordles_type,
     *          ordles_quantity, ordles_starttime, ordles_endtime
     *          ];
     * @return float
     */
    public static function getPrice(array $lesson): float
    {
        $srch = new SearchBase('tbl_users', 'teacher');
        $srch->joinTable('tbl_teacher_stats', 'INNER JOIN', 'testat.testat_user_id = teacher.user_id', 'testat');
        $srch->joinTable('tbl_user_teach_languages', 'INNER JOIN', 'ustelg.utlang_user_id = teacher.user_id', 'ustelg');
        $srch->joinTable('tbl_user_teach_lang_prices', 'INNER JOIN', 'ustelgpr.ustelgpr_utlang_id=ustelg.utlang_id', 'ustelgpr');
        $srch->addCondition('teacher.user_id', '=', $lesson['ordles_teacher_id']);
        $srch->addCondition('ustelg.utlang_tlang_id', '=', $lesson['ordles_tlang_id']);
        $srch->addCondition('ustelgpr.ustelgpr_slot', '=', $lesson['ordles_duration']);
        $srch->addCondition('ustelgpr.ustelgpr_min_slab', '<=', $lesson['ordles_quantity']);
        $srch->addCondition('ustelgpr.ustelgpr_max_slab', '>=', $lesson['ordles_quantity']);
        $srch->addCondition('ustelgpr.ustelgpr_price', '>', 0);
        $srch->addCondition('teacher.user_is_teacher', '=', 1);
        $srch->addCondition('teacher.user_country_id', '>', 0);
        $srch->addCondition('teacher.user_username', '!=', "");
        $srch->addCondition('teacher.user_active', '=', 1);
        $srch->addDirectCondition('teacher.user_deleted IS NULL');
        $srch->addDirectCondition('teacher.user_verified IS NOT NULL');
        $srch->addCondition('testat.testat_preference', '=', 1);
        $srch->addCondition('testat.testat_teachlang', '=', 1);
        $srch->addCondition('testat.testat_speaklang', '=', 1);
        $srch->addCondition('testat.testat_availability', '=', 1);
        $srch->addCondition('testat.testat_qualification', '=', 1);
        $srch->addFld('ustelgpr.ustelgpr_price');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        return FatUtility::float($row['ustelgpr_price'] ?? 0);
    }

    /**
     * Get Statuses
     * 
     * @param int $key
     * @param int $langId
     * @return string|array
     */
    public static function getStatuses(int $key = null, int $langId = null)
    {
        $langId = $langId ?? MyUtility::getSiteLangId();
        $arr = [
            static::UNSCHEDULED => Label::getLabel('LBL_UNSCHEDULED'),
            static::SCHEDULED => Label::getLabel('LBL_SCHEDULED'),
            static::COMPLETED => Label::getLabel('LBL_COMPLETED'),
            static::CANCELLED => Label::getLabel('LBL_CANCELLED')
        ];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Get Languages
     * 
     * @param int $key
     * @return type
     */
    public static function getLanguages(int $key = null)
    {
        $arr = [];
        return AppConstant::returArrValue($arr, $key);
    }

    /**
     * Send Scheduled MailF
     * 
     * @param array $lesson
     */
    private function sendScheduledMail(array $lesson)
    {
        $link = MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']]);
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{user-name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{link}' => $link
        ];
        $notifi = new Notification($lesson['ordles_teacher_id'], Notification::TYPE_LESSON_SCHEDULED);
        $notifi->sendNotification($vars, User::TEACHER);
        $startTime = MyDate::formatDate($lesson['ordles_lesson_starttime'], 'Y-m-d H:i:s', $lesson['teacher_timezone']);
        $startTime = strtotime($startTime);
        $vars = [
            '{learner_name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{teacher_name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{lesson_name}' => $lesson['tlang_name'],
            '{lesson_date}' => date('Y-m-d', $startTime),
            '{lesson_start_time}' => date('H:i:s', $startTime),
            '{lesson_end_time}' => MyDate::formatDate($lesson['ordles_lesson_endtime'], 'H:i:s', $lesson['teacher_timezone']),
            '{action}' => Label::getLabel('LBL_SCHEDULED'),
            '{learner_comment}' => Label::getLabel('LBL_N/A'),
        ];
        $mail = new FatMailer($lesson['teacher_lang_id'], 'learner_schedule_email');
        $mail->setVariables($vars);
        if (!$mail->sendMail([$lesson['teacher_email']])) {
            $this->error = $mail->getError();
            return false;
        }
        return true;
    }

    /**
     * Send Rescheduled Mail To Teacher
     * 
     * @param array $lesson
     */
    private function sendRescheduledMailToTeacher(array $lesson)
    {
        $link = MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']]);
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{user-name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{link}' => $link
        ];
        $notifi = new Notification($lesson['ordles_teacher_id'], Notification::TYPE_LESSON_RESCHEDULED);
        $notifi->sendNotification($vars, User::LEARNER);
        $startTime = MyDate::formatDate($lesson['ordles_lesson_starttime'], 'Y-m-d H:i:s', $lesson['teacher_timezone']);
        $startTime = strtotime($startTime);
        $vars = [
            '{learner_name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{teacher_name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{lesson_name}' => $lesson['tlang_name'],
            '{lesson_date}' => date('Y-m-d', $startTime),
            '{lesson_start_time}' => date('H:i:s', $startTime),
            '{lesson_end_time}' => MyDate::formatDate($lesson['ordles_lesson_endtime'], 'H:i:s', $lesson['teacher_timezone']),
            '{action}' => Label::getLabel('LBL_RESCHEDULED'),
            '{lesson_url}' => $link,
            '{learner_comment}' => $lesson['comment']
        ];
        $mail = new FatMailer($lesson['teacher_lang_id'], 'learner_schedule_email');
        $mail->setVariables($vars);
        if (!$mail->sendMail([$lesson['teacher_email']])) {
            $this->error = $mail->getError();
            return false;
        }
        return true;
    }

    /**
     * Send Rescheduled Mail To Learner
     * 
     * @param array $lesson
     * @return bool
     */
    private function sendRescheduledMailToLearner(array $lesson): bool
    {
        $link = MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']]);
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{user-name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{link}' => $link
        ];
        $notifi = new Notification($lesson['order_user_id'], Notification::TYPE_LESSON_RESCHEDULED);
        $notifi->sendNotification($vars, User::LEARNER);
        $vars = [
            '{learner_name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{teacher_name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{lesson_name}' => $lesson['tlang_name'],
            '{action}' => Label::getLabel('LBL_RESCHEDULED'),
            '{lesson_url}' => $link,
            '{teacher_comment}' => $lesson['comment']
        ];
        $mail = new FatMailer($lesson['learner_lang_id'], 'teacher_reschedule_email');
        $mail->setVariables($vars);
        if (!$mail->sendMail([$lesson['learner_email']])) {
            $this->error = $mail->getError();
            return false;
        }
        return true;
    }

    /**
     * Send Cancel lesson system notification and email to learner 
     *
     * @param array $lesson
     * @return bool
     */
    private function sendCancelledNotifiToLearner(array $lesson): bool
    {
        $link = MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']]);
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{user-name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{comment}' => $lesson['comment'],
            '{link}' => $link
        ];
        $notifi = new Notification($lesson['order_user_id'], Notification::TYPE_LESSON_CANCELLED);
        $notifi->sendNotification($vars, User::LEARNER);
        $tlangId = FatUtility::int($lesson['ordles_tlang_id']);
        $langId = FatUtility::int($lesson['learner_lang_id']);
        $languageName = TeachLanguage::getLangById($tlangId, $langId);
        $vars = [
            '{learner_name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{teacher_name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{lesson_name}' => $languageName,
            '{teacher_comment}' => $lesson['comment'],
            '{action}' => Label::getLabel('LBL_CANCELED'),
            '{lesson_url}' => $link,
        ];
        $mail = new FatMailer($lesson['learner_lang_id'], 'teacher_lesson_cancelled_email');
        $mail->setVariables($vars);
        if (!$mail->sendMail([$lesson['learner_email']])) {
            $this->error = $mail->getError();
            return false;
        }
        return true;
    }

    /**
     * Send Cancel lesson system notification and email to Teacher 
     *
     * @param array $lesson
     * @return bool
     */
    private function sendCancelledNotifiToTeacher(array $lesson): bool
    {
        $link = MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']]);
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{user-name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{comment}' => $lesson['comment'],
            '{link}' => $link
        ];
        $notifi = new Notification($lesson['ordles_teacher_id'], Notification::TYPE_LESSON_CANCELLED);
        $notifi->sendNotification($vars, User::TEACHER);
        $tlangId = FatUtility::int($lesson['ordles_tlang_id']);
        $langId = FatUtility::int($lesson['teacher_lang_id']);
        $languageName = TeachLanguage::getLangById($tlangId, $langId);
        $vars = [
            '{learner_name}' => $lesson['learner_first_name'] . ' ' . $lesson['learner_last_name'],
            '{teacher_name}' => $lesson['teacher_first_name'] . ' ' . $lesson['teacher_last_name'],
            '{lesson_name}' => $languageName,
            '{lesson_date}' => '',
            '{lesson_start_time}' => '',
            '{lesson_end_time}' => '',
            '{learner_comment}' => $lesson['comment'],
            '{action}' => Label::getLabel('LBL_CANCELED'),
            '{lesson_url}' => $link,
        ];
        if (!empty($lesson['ordles_lesson_starttime'])) {
            $startTime = MyDate::formatDate($lesson['ordles_lesson_starttime'], 'Y-m-d H:i:s', $lesson['teacher_timezone']);
            $startTime = strtotime($startTime);
            $vars['{lesson_date}'] = date('Y-m-d', $startTime);
            $vars['{lesson_start_time}'] = date('H:i:s', $startTime);
            $vars['{lesson_end_time}'] = MyDate::formatDate($lesson['ordles_lesson_endtime'], 'H:i:s', $lesson['teacher_timezone']);
        }
        $mail = new FatMailer($lesson['teacher_lang_id'], 'learner_cancelled_lesson_email');
        $mail->setVariables($vars);
        if (!$mail->sendMail([$lesson['teacher_email']])) {
            $this->error = $mail->getError();
            return false;
        }
        return true;
    }

    /**
     * Get Refund Percentage
     * Please pass the lesson start time in system timezone  
     * 
     * @param int $status Pass the lesson status 
     * @param  $lessonStartTime
     * @return float
     */
    public function getRefundPercentage(int $status, $lessonStartTime): float
    {
        /**
         * set refund percentage 100 if user type is teacher else depend on configuration 
         */
        if (!in_array($status, [static::SCHEDULED, static::UNSCHEDULED])) {
            return 0;
        }
        if ($this->userType != User::LEARNER) {
            return 100.00;
        }
        $refundPercent = FatApp::getConfig('CONF_UNSCHEDULE_LESSON_REFUND_PERCENTAGE', FatUtility::VAR_FLOAT, 100);
        if ($status == static::SCHEDULED) {
            $refundDuration = FatApp::getConfig('CONF_LESSON_REFUND_DURATION', FatUtility::VAR_FLOAT, 24);
            $refundPercent = FatApp::getConfig('CONF_LESSON_REFUND_PERCENTAGE_AFTER_DURATION', FatUtility::VAR_FLOAT, 50);

            if (MyDate::hoursDiff($lessonStartTime) > $refundDuration) {
                $refundPercent = FatApp::getConfig('CONF_LESSON_REFUND_PERCENTAGE_BEFORE_DURATION', FatUtility::VAR_FLOAT, 100);
            }
        }
        return FatUtility::float($refundPercent);
    }

    /**
     * Free Trail Availed
     * 
     * @param int $learnerId
     * @param int $teacherId
     * @return bool
     */
    public static function isTrailAvailed(int $learnerId, int $teacherId): bool
    {

        $srch = new SearchBase(static::DB_TBL, 'ordles');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id = ordles.ordles_order_id', 'orders');
        $srch->addCondition('orders.order_user_id', '=', $learnerId);
        $srch->addCondition('ordles.ordles_teacher_id', '=', $teacherId);
        $srch->addCondition('ordles.ordles_type', '=', static::TYPE_FTRAIL);
        $srch->addCondition('ordles.ordles_status', '!=', static::CANCELLED);
        $srch->addCondition('orders.order_payment_status', '=', Order::ISPAID);
        $srch->addCondition('orders.order_status', '=', Order::STATUS_COMPLETED);
        $srch->addFld('COUNT(*) AS totalCount');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        return ($row['totalCount'] > 0);
    }

    /**
     * Get Lessons By Order Id
     * 
     * @param int $orderId
     * @return int
     */
    public function getLessonsByOrderId(int $orderId): int
    {
        $srch = new SearchBase(static::DB_TBL, 'ordles');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id=ordles.ordles_order_id', 'orders');
        $srch->addCondition('order_id', '=', $orderId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->getResultSet();
        return $srch->recordCount();
    }

    /**
     * Refund To Learner
     * 
     * @param array $lesson
     * @param float $refundPercent
     * @return bool
     */
    private function refundToLearner(array $lesson, float $refundPercent): bool
    {
        $price = $lesson['ordles_amount'] - $lesson['ordles_discount'];
        $refund = FatUtility::float(($refundPercent / 100) * $price);
        $earnings = FatUtility::float($price - $refund);
        $this->setFldValue('ordles_refund', $refund);
        $this->setFldValue('ordles_earnings', $earnings);
        $this->setFldValue('ordles_updated', date('Y-m-d H:i:s'));
        if (!$this->save()) {
            return false;
        }
        if ($refund > 0) {
            $txn = new Transaction($lesson['order_user_id'], Transaction::TYPE_LEARNER_REFUND);
            $comment = Label::getLabel('LBL_CANCEL_LESSON_REFUND_{lesson-id}_{refund-percentage}');
            $comment = str_replace(['{lesson-id}', '{refund-percentage}'], [$lesson['ordles_id'], $refundPercent], $comment);
            if (!$txn->credit($refund, $comment)) {
                $this->error = $txn->getError();
                return false;
            }
            $txn->sendEmail();
        }
        return true;
    }

    /**
     * Get Lessons Count By Order Id
     * 
     * @param int $orderId
     * @return int
     */
    public static function getLessonsCountByOrderId(int $orderId): int
    {
        $srch = new SearchBase(static::DB_TBL, 'ordles');
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id=ordles.ordles_order_id', 'orders');
        $srch->addCondition('order_id', '=', $orderId);
        $srch->getResultSet();
        return $srch->recordCount();
    }

    /**
     * Get Less Stats Count
     * 
     * @return array
     */
    public function getLessStatsCount(): array
    {
        $srch = $this->getSearchObject();
        $srch->addMultipleFields([
            'count(ordles_id) as totalLesson',
            'count(IF(ordles.ordles_status = ' . static::SCHEDULED . ', 1, null)) as schLessonCount',
            'count(IF(ordles.ordles_status = ' . static::SCHEDULED . ' and ordles.ordles_lesson_starttime >= "' . date('Y-m-d H:i:s') . '", 1, null)) as upcomingLesson'
        ]);
        $data = FatApp::getDb()->fetch($srch->getResultSet());
        return [
            'totalLesson' => FatUtility::int($data['totalLesson'] ?? 0),
            'schLessonCount' => FatUtility::int($data['schLessonCount'] ?? 0),
            'upcomingLesson' => FatUtility::int($data['upcomingLesson'] ?? 0)
        ];
    }

    /**
     * Send Completed Lesson Notification To Teacher
     * 
     * @param array $lesson
     * @return bool
     */
    private function sendCompletedLessonNotiToTeacher(array $lesson): bool
    {
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{link}' => MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']])
        ];
        $notifi = new Notification($lesson['ordles_teacher_id'], Notification::TYPE_LESSON_COMPLETED);
        return $notifi->sendNotification($vars, User::TEACHER);
    }

    /**
     * Send Completed Lesson Notification To Learner
     * 
     * @param array $lesson
     * @return bool
     */
    private function sendCompletedLessonNotiToLearner(array $lesson): bool
    {
        $vars = [
            '{lesson-id}' => $lesson['ordles_id'],
            '{link}' => MyUtility::makeUrl('lessons', 'view', [$lesson['ordles_id']])
        ];
        $notifi = new Notification($lesson['order_user_id'], Notification::TYPE_LESSON_COMPLETED);
        return $notifi->sendNotification($vars, User::LEARNER);
    }

    /**
     * Add Google Event
     * 
     * @param array $lesson
     * @return bool
     */
    public function addGoogleEvent(array $lesson): bool
    {
        if (!empty($lesson['learner_google_token'])) {
            $lesson['google_token'] = $lesson['learner_google_token'];
            $lesson['lang_id'] = $lesson['learner_lang_id'];
            $googleCalendar = new GoogleCalendarEvent($this->userId, $this->mainTableRecordId, AppConstant::LESSON);
            if (!$googleCalendar->addLessonEvent($lesson)) {
                $this->error = $googleCalendar->getError();
                return false;
            }
        }
        if (!empty($lesson['teacher_google_token'])) {
            $lesson['google_token'] = $lesson['teacher_google_token'];
            $lesson['lang_id'] = $lesson['teacher_lang_id'];
            $googleCalendar = new GoogleCalendarEvent($lesson['ordles_teacher_id'], $this->mainTableRecordId, AppConstant::LESSON);
            if (!$googleCalendar->addLessonEvent($lesson)) {
                $this->error = $googleCalendar->getError();
                return false;
            }
        }
        return true;
    }

    /**
     * Delete Google Event
     * 
     * @param array $lesson
     * @return bool
     */
    public function deleteGoogleEvent(array $lesson): bool
    {
        $googCalEvent = new GoogleCalendarEvent($lesson['teacher_id'], $lesson['ordles_id'], AppConstant::LESSON);
        $events = $googCalEvent->getEventsByRecordId();
        if (!empty($lesson['teacher_google_token']) && !empty($events[$lesson['teacher_id']])) {
            if (!$googCalEvent->deletEvent($lesson['teacher_google_token'], $events[$lesson['teacher_id']]['gocaev_event_id'])) {
                $this->error = $googCalEvent->getError();
                return false;
            }
        }
        if (!empty($lesson['learner_google_token']) && !empty($events[$lesson['learner_id']])) {
            $googCalEvent = new GoogleCalendarEvent($lesson['learner_id'], 0, 0);
            if (!$googCalEvent->deletEvent($lesson['learner_google_token'], $events[$lesson['learner_id']]['gocaev_event_id'])) {
                $this->error = $googCalEvent->getError();
                return false;
            }
        }
        return true;
    }

    /**
     * Get Lesson Types
     * 
     * @return array
     */
    public static function getLessonTypes()
    {
        return [
            Lesson::TYPE_FTRAIL => Label::getLabel('TYPE_TRAIL_LESSON'),
            Lesson::TYPE_REGULAR => Label::getLabel('TYPE_REGULAR_LESSON'),
            Lesson::TYPE_SUBCRIP => Label::getLabel('TYPE_SUBSCRIPTION')
        ];
    }

    /**
     * Get Refunded Lesson Count
     * 
     * @param int $orderId
     * @return int
     */
    public static function getRefundedLessonCount(int $orderId): int
    {
        $srch = new SearchBase(static::DB_TBL, 'ordles');
        $srch->addFld(['count(ordles_id) recordCount']);
        $srch->addCondition('ordles_order_id', '=', $orderId);
        $srch->addDirectCondition('ordles_refund IS NOT NULL');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->getResultSet();
        $record = FatApp::getDb()->fetch($srch->getResultSet());
        return $record['recordCount'] ?? 0;
    }

    /**
     * Get Discount Refund Price
     * 
     * @param array $lesson
     * @return float
     */
    public static function getDiscountRefundPrice(array $lesson): float
    {
        $refundAmt = 0;
        $pendingLessons = $lesson['order_item_count'] - $lesson['refundedLessonCount'];
        $itemCount = floor($lesson['order_net_amount'] / $lesson['ordles_amount']);
        if ($pendingLessons == ($itemCount + 1)) {
            $refundAmt = fmod($lesson['order_net_amount'], $lesson['ordles_amount']);
        }
        if ($pendingLessons <= $itemCount) {
            $refundAmt = $lesson['ordles_amount'];
        }
        return FatUtility::float($refundAmt);
    }

    public static function getScheduledLessonCount(string $startTime, string $endTime, int $duration): array
    {
        $srch = new SearchBase(static::DB_TBL, 'ordles');
        $srch->addMultipleFields(['count(*) totalCount', 'min(ordles_lesson_starttime) as startTime', 'max(ordles_lesson_endtime) as endTime']);
        $srch->joinTable(Order::DB_TBL, 'INNER JOIN', 'orders.order_id = ordles.ordles_order_id', 'orders');
        $srch->addCondition('order_payment_status', '=', Order::ISPAID);
        $srch->addCondition('order_status', '=', Order::STATUS_COMPLETED);
        $srch->addCondition('ordles_lesson_starttime', '<', $endTime);
        $srch->addCondition('ordles_lesson_endtime', '>', $startTime);
        $srch->addCondition('ordles_duration', '>', $duration);
        $srch->addCondition('ordles_status', '=', Lesson::SCHEDULED);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $row = FatApp::getDb()->fetch($srch->getResultSet());
        return [
            'totalCount' => $row['totalCount'] ?? 0,
            'startTime' => $row['startTime'] ?? null,
            'endTime' => $row['endTime'] ?? null,
        ];
    }

}
