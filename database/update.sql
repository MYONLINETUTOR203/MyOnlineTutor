
-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

DROP TABLE IF EXISTS `tbl_categories`;
CREATE TABLE IF NOT EXISTS `tbl_categories` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_type` int(11) NOT NULL,
  `cate_parent` int(11) NOT NULL,
  `cate_subcategories` int(11) NOT NULL,
  `cate_courses` int(11) NOT NULL,
  `cate_order` int(11) NOT NULL,
  `cate_status` tinyint(1) NOT NULL,
  `cate_created` datetime NOT NULL,
  `cate_updated` datetime DEFAULT NULL,
  `cate_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories_lang`
--

DROP TABLE IF EXISTS `tbl_categories_lang`;
CREATE TABLE IF NOT EXISTS `tbl_categories_lang` (
  `catelang_id` int(11) NOT NULL AUTO_INCREMENT,
  `catelang_lang_id` int(11) NOT NULL,
  `catelang_cate_id` int(11) NOT NULL,
  `cate_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cate_details` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`catelang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certificate_templates`
--

DROP TABLE IF EXISTS `tbl_certificate_templates`;
CREATE TABLE IF NOT EXISTS `tbl_certificate_templates` (
  `certpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `certpl_lang_id` int(11) NOT NULL,
  `certpl_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certpl_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certpl_body` json DEFAULT NULL,
  `certpl_vars` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `certpl_status` tinyint(1) NOT NULL,
  `certpl_created` datetime NOT NULL,
  `certpl_updated` datetime NOT NULL,
  `certpl_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`certpl_id`),
  UNIQUE KEY `certpl_lang_id` (`certpl_lang_id`,`certpl_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_certificate_templates`
--

INSERT INTO `tbl_certificate_templates` (`certpl_id`, `certpl_lang_id`, `certpl_code`, `certpl_name`, `certpl_body`, `certpl_vars`, `certpl_status`, `certpl_created`, `certpl_updated`, `certpl_deleted`) VALUES
(2, 1, 'course_evaluation_certificate', 'Course Evaluation Certificate', '{\"heading\": \"Certificate Of Completion\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{course-name}\\\" online course on {course-completed-date}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name<br>\r\n{teacher-name} Teacher name <br>\r\n{course-name} Course Title<br>\r\n{course_grades} Course Grades <br>\r\n{course-language} Language Course <br>\r\n{course-completed-date} Course Completed On<br><br>', 0, '2022-03-15 04:00:00', '2022-03-16 09:47:34', '2022-08-04 09:54:58'),
(4, 2, 'course_evaluation_certificate', 'Course Evaluation Certificate', '{\"heading\": \"شهادة إتمام\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"هذا لتاكيد ان\", \"content_part_2\": \"أكمل بنجاح الدورة التدريبية عبر الإنترنت \\\"{course-name}\\\" في {course-completed-date}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name<br>\r\n{teacher-name} Teacher name <br>\r\n{course-name} Course Title<br>\r\n{course_grades} Course Grades <br>\r\n{course-language} Language Course <br>\r\n{course-completed-date} Course Completed On<br><br>', 0, '2022-03-15 04:00:00', '2022-03-16 10:45:49', '2022-08-04 09:54:58'),
(5, 1, 'quiz_completion_certificate', 'Quiz Completion Certificate', '{\"heading\": \"Certificate Of Completion\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{quiz-name}\\\" online quiz on {quiz-completed-date} in {quiz-duration}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{quiz-name} Quiz Title <br>\r\n{quiz-language} Quiz Language<br>\r\n{quiz-completed-date} Quiz Completed On <br>\r\n{certificate-number} Certificate Number<br>\r\n{quiz-duration} Quiz Duration<br><br>', 1, '2022-03-15 04:00:00', '2022-05-30 09:18:54', NULL);



-- ------------------ QUIZ MODULE --------------------------------------

--
-- Table structure for table `tbl_questions`
--

DROP TABLE IF EXISTS `tbl_questions`;
CREATE TABLE IF NOT EXISTS `tbl_questions` (
  `ques_id` int(11) NOT NULL AUTO_INCREMENT,
  `ques_type` tinyint(4) NOT NULL,
  `ques_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ques_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ques_cate_id` int(11) NOT NULL,
  `ques_subcate_id` int(11) DEFAULT NULL,
  `ques_user_id` int(11) NOT NULL,
  `ques_clang_id` int(11) NOT NULL,
  `ques_answer` json NOT NULL COMMENT '[''4'',''5'',''1'',''2'',''3'']',
  `ques_hint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ques_marks` int(11) NOT NULL,
  `ques_status` tinyint(4) NOT NULL,
  `ques_created` datetime NOT NULL,
  `ques_updated` datetime DEFAULT NULL,
  `ques_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`ques_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_question_options`
--

DROP TABLE IF EXISTS `tbl_question_options`;
CREATE TABLE IF NOT EXISTS `tbl_question_options` (
  `queopt_id` int(11) NOT NULL AUTO_INCREMENT,
  `queopt_ques_id` int(11) NOT NULL,
  `queopt_title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queopt_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queopt_order` tinyint(4) NOT NULL,
  PRIMARY KEY (`queopt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quizzes`
--

DROP TABLE IF EXISTS `tbl_quizzes`;
CREATE TABLE IF NOT EXISTS `tbl_quizzes` (
  `quiz_id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_type` tinyint(4) NOT NULL,
  `quiz_title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_user_id` int(11) NOT NULL,
  `quiz_duration` bigint(20) DEFAULT NULL COMMENT 'In Seconds',
  `quiz_attempts` tinyint(4) DEFAULT NULL,
  `quiz_passmark` decimal(8,2) DEFAULT NULL COMMENT 'In percent',
  `quiz_failmsg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_passmsg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_status` tinyint(4) NOT NULL,
  `quiz_created` datetime NOT NULL,
  `quiz_updated` datetime DEFAULT NULL,
  `quiz_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quizzes_questions`
--

DROP TABLE IF EXISTS `tbl_quizzes_questions`;
CREATE TABLE IF NOT EXISTS `tbl_quizzes_questions` (
  `quique_quiz_id` int(11) NOT NULL,
  `quique_ques_id` int(11) NOT NULL,
  `quique_order` int(11) NOT NULL,
  PRIMARY KEY (`quique_quiz_id`,`quique_ques_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quiz_certificates`
--

DROP TABLE IF EXISTS `tbl_quiz_certificates`;
CREATE TABLE IF NOT EXISTS `tbl_quiz_certificates` (
  `quicer_userquiz_id` int(11) NOT NULL,
  `quicer_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quicer_created` datetime NOT NULL,
  PRIMARY KEY (`quicer_userquiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quiz_linked`
--

DROP TABLE IF EXISTS `tbl_quiz_linked`;
CREATE TABLE IF NOT EXISTS `tbl_quiz_linked` (
  `quilin_id` int(11) NOT NULL AUTO_INCREMENT,
  `quilin_quiz_id` int(11) NOT NULL,
  `quilin_type` tinyint(4) NOT NULL,
  `quilin_title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quilin_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quilin_user_id` int(11) NOT NULL,
  `quilin_record_id` int(11) NOT NULL,
  `quilin_record_type` int(11) NOT NULL,
  `quilin_duration` bigint(20) DEFAULT NULL COMMENT 'In seconds',
  `quilin_attempts` int(11) DEFAULT NULL,
  `quilin_passmark` decimal(8,2) DEFAULT NULL COMMENT 'In percent',
  `quilin_failmsg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quilin_passmsg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quilin_order` int(11) NOT NULL,
  `quilin_created` datetime NOT NULL,
  `quilin_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`quilin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quiz_linked_questions`
--

DROP TABLE IF EXISTS `tbl_quiz_linked_questions`;
CREATE TABLE IF NOT EXISTS `tbl_quiz_linked_questions` (
  `qulinqu_id` int(11) NOT NULL AUTO_INCREMENT,
  `qulinqu_type` tinyint(4) NOT NULL,
  `qulinqu_quilin_id` int(11) NOT NULL,
  `qulinqu_ques_id` int(11) NOT NULL,
  `qulinqu_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qulinqu_detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qulinqu_hint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qulinqu_answer` json NOT NULL,
  `qulinqu_options` json DEFAULT NULL,
  PRIMARY KEY (`qulinqu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_quizzes`
--

DROP TABLE IF EXISTS `tbl_users_quizzes`;
CREATE TABLE IF NOT EXISTS `tbl_users_quizzes` (
  `userquiz_id` int(11) NOT NULL AUTO_INCREMENT,
  `userquiz_quilin_id` int(11) NOT NULL,
  `userquiz_user_id` int(11) NOT NULL,
  `userquiz_scored` decimal(8,2) DEFAULT NULL,
  `userquiz_status` tinyint(4) NOT NULL,
  `userquiz_created` datetime NOT NULL,
  `userquiz_updated` datetime NOT NULL,
  PRIMARY KEY (`userquiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `tbl_categories` CHANGE `cate_courses` `cate_records` INT(11) NOT NULL COMMENT 'Courses count or Questions count'; 
ALTER TABLE `tbl_categories` ADD `cate_identifier` VARCHAR(100) NOT NULL AFTER `cate_type`;
ALTER TABLE `tbl_questions` ADD `ques_options_count` INT NOT NULL COMMENT 'Number of options attached with the question' AFTER `ques_clang_id`;


DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'NOTIFI_DESC_TYPE_REDEEM_GIFTCARD';
INSERT INTO `tbl_language_labels` (`label_lang_id`, `label_key`, `label_caption`) VALUES
(1, 'NOTIFI_DESC_TYPE_REDEEM_GIFTCARD', 'Receiver have redeemed gift card.'),
(2, 'NOTIFI_DESC_TYPE_REDEEM_GIFTCARD', 'قام المستلم باسترداد بطاقة الهدايا.');

-- -----------------------
-- After 15 September 2022
-- -----------------------
UPDATE `tbl_navigation_links` SET `nlink_url` = '{siteroot}blog/contribution-form' WHERE `tbl_navigation_links`.`nlink_id` = 76;
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_LEARNER_FAILURE_ORDER_{CONTACTURL}';
INSERT INTO `tbl_language_labels` (`label_lang_id`, `label_key`, `label_caption`) VALUES
(1, 'LBL_TEACHER_PRICING', 'Pricing'),(2, 'LBL_TEACHER_PRICING', 'التسعير');

ALTER TABLE `tbl_quizzes` ADD `quiz_active` TINYINT(1) NOT NULL AFTER `quiz_passmsg`;

ALTER TABLE tbl_quizzes  ADD quiz_validity INT NOT NULL  AFTER quiz_passmark,  ADD quiz_certificate TINYINT(1) NOT NULL  AFTER quiz_validity,  ADD quiz_questions INT NOT NULL  AFTER quiz_certificate;

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_DURATION_(IN_MINS)', 'Duration (In Mins)');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_VALIDITY_(IN_HOURS)', 'Validity (In Hours)');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_QUIZ_VALIDITY_INSTRUCTIONS', 'The validity of the quiz will be counted from the day of attachment.');

DELETE FROM `tbl_certificate_templates` WHERE `certpl_code` = 'course_evaluation_certificate';
DELETE FROM `tbl_certificate_templates` WHERE `certpl_code` = 'quiz_completion_certificate';

INSERT INTO `tbl_certificate_templates` (`certpl_lang_id`, `certpl_code`, `certpl_name`, `certpl_body`, `certpl_vars`, `certpl_status`, `certpl_created`, `certpl_updated`, `certpl_deleted`) VALUES 
(1, 'evaluation_certificate', 'Evaluation Certificate', '{\"heading\": \"Certificate Of Evaluation\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{quiz-name}\\\" online quiz on {quiz-completed-date} in {quiz-duration}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{quiz-name} Quiz Title <br>\r\n{quiz-completed-date} Quiz Completed On <br>\r\n{certificate-number} Certificate Number<br>\r\n{quiz-duration} Quiz Duration<br><br>', 1, '2022-09-19 04:00:00', '2022-09-19 04:00:00', NULL), 
(2, 'evaluation_certificate', 'Evaluation Certificate', '{\"heading\": \"Certificate Of Evaluation\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{quiz-name}\\\" online quiz on {quiz-completed-date} in {quiz-duration}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{quiz-name} Quiz Title <br>\r\n{quiz-completed-date} Quiz Completed On <br>\r\n{certificate-number} Certificate Number<br>\r\n{quiz-duration} Quiz Duration<br><br>', 1, '2022-09-19 04:00:00', '2022-09-19 04:00:00', NULL);

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_BINDED_QUESTION_REMOVAL_CONFIRMATION', 'This question is binded with the quizzes and will be removed from them. Do you still want to remove question?');


UPDATE `tbl_configurations` SET `conf_val` = 'TV-1.1.0.20220927' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_MOBILE_SELECT_SLOT_INFO', 'Please press & hold for a few seconds to select a slot.'); 

