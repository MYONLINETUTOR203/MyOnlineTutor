-- ------------------
-- BR_RV-3.0.0_HOT_FIX
-- ------------------

ALTER TABLE `tbl_zoom_users` ADD `zmusr_zoom_type` INT NOT NULL AFTER `zmusr_zoom_id`;
UPDATE `tbl_zoom_users` SET `zmusr_zoom_type` = '1';
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES 
('license_alert', '1', '{meeting_tool} License Alert', '{meeting_tool} license alert {website_name}', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"><span style=\"font-size: 30px; font-weight: bold;\">License Alert</span></td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin</h3>                                \r\n								<p style=\"line-height: 20px;\"><span style=\"color: rgb(103, 103, 103); font-size: 14px;\">This is an update regarding sessions on the platform. Meeting tool licenses available on the platform are less than the classes scheduled simultaneously. Please find details of the classes below:</span></p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Start Time</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{start_time}</td>\r\n										</tr>                                      \r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">End Time</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{end_time}</td>\r\n										</tr>                                      \r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Scheduled Sessions&nbsp;</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{session_count}</td>\r\n										</tr>  \r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>            </td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{start_time} class Start Time <br>{end_time} class End Time <br>{session_count} Total Scheduled Sessions count', '1', '0'),
('license_alert', '2', '{meeting_tool} License Alert', '{meeting_tool} license alert {website_name}', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"><span style=\"font-size: 30px; font-weight: bold;\">License Alert</span></td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin</h3>                                \r\n								<p style=\"line-height: 20px;\"><span style=\"color: rgb(103, 103, 103); font-size: 14px;\">This is an update regarding sessions on the platform. Meeting tool licenses available on the platform are less than the classes scheduled simultaneously. Please find details of the classes below:</span></p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Start Time</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{start_time}</td>\r\n										</tr>                                      \r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">End Time</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{end_time}</td>\r\n										</tr>                                      \r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Scheduled Sessions&nbsp;</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{session_count}</td>\r\n										</tr>  \r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>            </td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{start_time} class Start Time <br>{end_time} class End Time <br>{session_count} Total Scheduled Sessions count', '1', '0');
INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES ('CONF_ZOOM_FREE_MEETING_DURATION', '45', '');
INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'shuffle/revoke Zoom License', 'shuffleZoomLicense', '1', '1');

UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/55x55_1.png", "images/55x55_1.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/55x55_2.png", "images/55x55_2.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/55x55_3.png", "images/55x55_3.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/55x55_4.png", "images/55x55_4.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/55x55_5.png", "images/55x55_5.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/55x55_6.png", "images/55x55_6.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/120x120_3.png", "images/120x120_3.png");
UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, "https://teach.yo-coach.com/images/120x120_4.png", "images/120x120_4.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650025272-mission.png", "image/editor-image/1650025272-mission.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650025364-vision.png", "image/editor-image/1650025364-vision.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650349417-ceo364x364.png", "image/editor-image/1650349417-ceo364x364.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650349567-marketinghead364x3641.png", "image/editor-image/1650349567-marketinghead364x3641.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650349739-creativedirector364x3642.png", "image/editor-image/1650349739-creativedirector364x3642.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650349751-techlead364x3643.png", "image/editor-image/1650349751-techlead364x3643.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650349756-saleshead364x3644.png", "image/editor-image/1650349756-saleshead364x3644.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650349761-creativedirector2364x3645.png", "image/editor-image/1650349761-creativedirector2364x3645.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650021362-search.png", "image/editor-image/1650021362-search.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650021522-Book.png", "image/editor-image/1650021522-Book.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650021648-learn.png", "image/editor-image/1650021648-learn.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650351210-translater.png", "image/editor-image/1650351210-translater.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650351215-teacher.png", "image/editor-image/1650351215-teacher.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/image/editor-image/1650351220-learner.png", "image/editor-image/1650351220-learner.png");
UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, "https://yocoach3.bestech.4qcteam.com/admin/content-pages", "/teachers");

ALTER TABLE `tbl_coupons`  DROP `coupon_deleted`;

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES
(null, 'Send Wallet Balance maintain Reminder for subscription before one day ', 'sendWalletBalanceReminder/2', 1, 1),
(null, 'Send Wallet Balance maintain Reminder for subscription before 3 day', 'sendWalletBalanceReminder/3', 1, 1),
(null, 'Send Wallet Balance maintain Reminder for subscription before 7 day', 'sendWalletBalanceReminder/4', 1, 1);
ALTER TABLE `tbl_email_archives` ADD `earch_attempted` DATETIME NULL AFTER `earch_attachemnts`;

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

ALTER TABLE `tbl_quiz_linked` ADD `quilin_validity` INT NOT NULL AFTER `quilin_passmsg`, ADD `quilin_certificate` TINYINT(1) NOT NULL AFTER `quilin_validity`, ADD `quilin_questions` INT NOT NULL AFTER `quilin_certificate`; 
ALTER TABLE `tbl_quiz_linked_questions` ADD `qulinqu_marks` INT NOT NULL AFTER `qulinqu_hint`; 
ALTER TABLE `tbl_quiz_linked` CHANGE `quilin_validity` `quilin_validity` DATETIME NOT NULL; 

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'NOTIFI_TITLE_TYPE_QUIZ_ATTACHED', 'Quiz(s) Attached');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'NOTIFI_DESC_TYPE_QUIZ_ATTACHED', 'Quiz(s) attached with your booked {session}');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'NOTIFI_TITLE_TYPE_QUIZ_REMOVED', 'Quiz(s) Removed');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'NOTIFI_DESC_TYPE_QUIZ_REMOVED', 'Quiz(s) removed from your booked {session}');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('quiz_attached_email', 1, 'Quiz(s) Attached Email To Learner', 'Quiz(s) Attached', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\">                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz(s) Attached</h2>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {learner_full_name} </h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that you have received quizzes for evaluation purpose. Your teacher {teacher_full_name} has assigned quizzes for one of your {session_type}.</p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below is the list of attached quiz(s):</p>{quizzes_list} \r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{quizzes_list} - Quizzes List<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{website_name} Name of the website<br>\r\n{website_url} Website url<br>', 1, 0),
('quiz_attached_email', 2, 'Quiz(s) Attached Email To Learner', 'Quiz(s) Attached', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\">                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz(s) Attached</h2>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {learner_full_name} </h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that you have received quizzes for evaluation purpose. Your teacher {teacher_full_name} has assigned quizzes for one of your {session_type}.</p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below is the list of attached quiz(s):</p>{quizzes_list} \r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{quizzes_list} - Quizzes List<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{website_name} Name of the website<br>\r\n{website_url} Website url<br>', 1, 0);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('quiz_removed_email', 1, 'Quiz Removed Email To Learner', 'Quiz Removed', '\r\n<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\"> \r\n                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz Removed</h2>                            </td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>            </td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {learner_full_name}</h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\"></p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">This is to inform you that teacher {teacher_full_name} has removed quiz \"{quiz_title}\" from your scheduled {session_type}.</p>                                \r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{quiz_title} - Quiz Title<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{website_name} Name of the website<br />\r\n{website_url} Website url<br>', 1, 0),
('quiz_removed_email', 2, 'Quiz Removed Email To Learner', 'Quiz Removed', '\r\n<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\"> \r\n                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz Removed</h2>                            </td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>            </td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {learner_full_name}</h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\"></p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">This is to inform you that teacher {teacher_full_name} has removed quiz \"{quiz_title}\" from your scheduled {session_type}.</p>                                \r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{quiz_title} - Quiz Title<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{website_name} Name of the website<br />\r\n{website_url} Website url<br>', 1, 0);

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_QUIZ_SOLVING_INSTRUCTIONS_HEADING', 'Please read the Instructions carefully to start the quiz.');


DROP TABLE `tbl_users_quizzes`;
--
-- Table structure for table `tbl_quiz_attempts`
--

DROP TABLE IF EXISTS `tbl_quiz_attempts`;
CREATE TABLE IF NOT EXISTS `tbl_quiz_attempts` (
  `quizat_id` int(11) NOT NULL AUTO_INCREMENT,
  `quizat_quilin_id` int(11) NOT NULL,
  `quizat_user_id` int(11) NOT NULL,
  `quizat_scored` decimal(8,2) DEFAULT NULL,
  `quizat_status` tinyint(4) NOT NULL,
  `quizat_created` datetime NOT NULL,
  `quizat_updated` datetime NOT NULL,
  PRIMARY KEY (`quizat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quiz_attempts_questions`
--

DROP TABLE IF EXISTS `tbl_quiz_attempts_questions`;
CREATE TABLE IF NOT EXISTS `tbl_quiz_attempts_questions` (
  `quatqu_id` int(11) NOT NULL AUTO_INCREMENT,
  `quatqu_qulinqu_id` int(11) NOT NULL,
  `quatqu_scored` decimal(8,2) NOT NULL,
  `quatqu_answer` json NOT NULL,
  PRIMARY KEY (`quatqu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tbl_quiz_attempts` ADD `quizat_progress` DECIMAL(8,2) NOT NULL AFTER `quizat_scored`, ADD `quizat_qulinqu_id` INT NOT NULL AFTER `quizat_progress`; 

ALTER TABLE `tbl_quiz_linked_questions` ADD `qulinqu_order` INT NOT NULL AFTER `qulinqu_options`;
ALTER TABLE `tbl_quizzes` ADD `quiz_marks` DECIMAL(8,2) NOT NULL AFTER `quiz_attempts`;
ALTER TABLE `tbl_quiz_linked` ADD `quilin_marks` DECIMAL(8,2) NOT NULL AFTER `quilin_attempts`;
ALTER TABLE `tbl_quiz_attempts_questions` ADD `quatqu_quizat_id` INT NOT NULL AFTER `quatqu_id`; 

ALTER TABLE `tbl_quizzes` CHANGE `quiz_detail` `quiz_detail` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 
ALTER TABLE `tbl_quiz_linked` CHANGE `quilin_detail` `quilin_detail` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 

ALTER TABLE `tbl_quiz_attempts` ADD `quizat_marks` DECIMAL(8,2) NOT NULL AFTER `quizat_scored`; 
ALTER TABLE `tbl_quiz_attempts` ADD `quizat_evaluation` TINYINT(1) NOT NULL AFTER `quizat_qulinqu_id`; 

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_QUIZ_PASS_MSG_HEADING', 'Congratulations {username}');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_QUIZ_FAIL_MSG_HEADING', 'Dear {username}');
ALTER TABLE `tbl_quiz_attempts` ADD `quizat_started` DATETIME NOT NULL AFTER `quizat_status`; 

ALTER TABLE `tbl_questions` CHANGE `ques_detail` `ques_detail` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 
ALTER TABLE `tbl_quiz_attempts` ADD `quizat_active` TINYINT(1) NOT NULL AFTER `quizat_status`;
ALTER TABLE `tbl_quiz_attempts` ADD `quizat_certificate_number` VARCHAR(25) NOT NULL AFTER `quizat_evaluation`;  

UPDATE `tbl_certificate_templates` SET `certpl_body` = '{\"heading\": \"Certificate Of Evaluation\", \"learner\": \"{learner-name}\", \"trainer\": \"<span>Tutor: </span> <b>{teacher-name}</b>\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{quiz-name}\\\" online quiz on {quiz-completed-date} in {quiz-duration}.\", \"certificate_number\": \"<span>Certificate No.: </span> <b>{certificate-number}</b>\"}' WHERE `tbl_certificate_templates`.`certpl_code` = 'evaluation_certificate'; 
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_EVALUATION_CERTIFICATE_BOTTOM_TEXT', 'The certificate indicates the entire quiz was completed as validated by the student. The quiz duration represents the total time spent by the student for the quiz completion.');
UPDATE `tbl_language_labels` SET `label_caption` = 'Oopss..!! Failed' WHERE `label_key` = 'LBL_QUIZ_FAIL_MSG_HEADING';

INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'NOTIFI_TITLE_TYPE_QUIZ_COMPLETED', 'Quiz Completed');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'NOTIFI_DESC_TYPE_QUIZ_COMPLETED', 'Quiz completed for your booked {session}');
INSERT INTO `tbl_language_labels` (`label_id`, `label_lang_id`, `label_key`, `label_caption`) VALUES (NULL, '1', 'LBL_MANUAL_QUIZ_COMPLETED_MESSAGE', 'Your quiz has been submitted successfully. Teacher has been informed and soon you will receive the evaluation details via an email.');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('graded_quiz_completion_email', 1, 'Auto-Graded Quiz Completion Email To Teacher', 'Quiz Completed', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\">                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz Completed</h2>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {teacher_full_name} </h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that learner {learner_full_name} has submitted a quiz for one of your {session_type}.</p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below are the details of the quiz:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Title</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{quiz_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Progress</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{progress_percentage}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Evaluation Status</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{pass_fail_status}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Marks</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{marks_acheived}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Score</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{score_percentage}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Time Spent</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{completion_time}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>\r\n', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{quiz_title} - Quiz Title<br>\r\n{progress_percentage} - Quiz Attended Progress<br>\r\n{pass_fail_status} - Evaluation Status<br>\r\n{marks_acheived} - Marks Acheived<br>\r\n{score_percentage} - Total Score Percentage<br>\r\n{completion_time} - Total Time Spent<br>\r\n{website_name} Name of the website<br>\r\n{website_url} Website url<br>', 1, 0),
('graded_quiz_completion_email', 2, 'Auto-Graded Quiz Completion Email To Teacher', 'Quiz Completed', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\">                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz Completed</h2>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {teacher_full_name} </h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that learner {learner_full_name} has submitted a quiz for one of your {session_type}.</p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below are the details of the quiz:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Title</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{quiz_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Progress</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{progress_percentage}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Evaluation Status</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{pass_fail_status}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Marks</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{marks_acheived}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Score</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{score_percentage}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Time Spent</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{completion_time}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>\r\n', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{quiz_title} - Quiz Title<br>\r\n{progress_percentage} - Quiz Attended Progress<br>\r\n{pass_fail_status} - Evaluation Status<br>\r\n{marks_acheived} - Marks Acheived<br>\r\n{score_percentage} - Total Score Percentage<br>\r\n{completion_time} - Total Time Spent<br>\r\n{website_name} Name of the website<br>\r\n{website_url} Website url<br>', 1, 0),
('nongraded_quiz_completion_email', 1, 'Non-Graded Quiz Completion Email To Teacher', 'Quiz Completed', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\">                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz Completed</h2>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {teacher_full_name} </h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that learner {learner_full_name} has submitted a quiz for one of your {session_type}. Please review and perform the evaluation.</p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below are the details of the quiz:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Title</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{quiz_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Progress</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{progress_percentage}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Evaluation Status</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{pass_fail_status}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Time Spent</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{completion_time}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{quiz_title} - Quiz Title<br>\r\n{progress_percentage} - Quiz Attended Progress<br>\r\n{pass_fail_status} - Evaluation Status<br>\r\n{completion_time} - Total Time Spent<br>\r\n{website_name} Name of the website<br>\r\n{website_url} Website url<br>', 1, 0),
('nongraded_quiz_completion_email', 2, 'Non-Graded Quiz Completion Email To Teacher', 'Quiz Completed', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:20px 0;\">                                \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>                                \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Quiz Completed</h2>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:left; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {teacher_full_name} </h3>                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that learner {learner_full_name} has submitted a quiz for one of your {session_type}. Please review and perform the evaluation.</p>                                                                \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below are the details of the quiz:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Title</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{quiz_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Progress</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{progress_percentage}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Evaluation Status</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{pass_fail_status}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">Time Spent</td>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333;\">{completion_time}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>                        \r\n						</tr>                    \r\n					</tbody>                \r\n				</table>\r\n			</td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{learner_full_name} - Learner Full Name<br>\r\n{teacher_full_name} - Teacher Full Name<br>\r\n{session_type} - Class, Lesson Or Course<br>\r\n{quiz_title} - Quiz Title<br>\r\n{progress_percentage} - Quiz Attended Progress<br>\r\n{pass_fail_status} - Evaluation Status<br>\r\n{completion_time} - Total Time Spent<br>\r\n{website_name} Name of the website<br>\r\n{website_url} Website url<br>', 1, 0);
-- -----------------------
-- 30 September 2022
-- -----------------------
UPDATE tbl_email_templates SET etpl_vars = "{user_full_name} Full Name of the email receiver" WHERE etpl_code = "forgot_password";
-- -----------------------
-- 10 October 2022
-- -----------------------
DELETE FROM `tbl_attached_files` WHERE `file_type` IN (39,40,41);

UPDATE `tbl_certificate_templates` SET `certpl_body` = '{\"heading\": \"Certificate Of Evaluation\", \"learner\": \"{learner-name}\", \"trainer\": \"Tutor:  <b>{teacher-name}</b>\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{quiz-name}\\\" online quiz on {quiz-completed-date} in {quiz-duration} and has achieved {quiz-score} score.\", \"certificate_number\": \"Certificate No.:  <b>{certificate-number}</b>\"}', `certpl_vars` = '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{quiz-name} Quiz Title <br>\r\n{quiz-completed-date} Quiz Completed On <br>\r\n{certificate-number} Certificate Number<br>\r\n{quiz-duration} Quiz Duration<br>\r\n{quiz-score} Score in Percent<br><br>' WHERE `certpl_code` = 'evaluation_certificate' AND `certpl_lang_id` = 1;
UPDATE `tbl_certificate_templates` SET `certpl_body` = '{\"heading\": \"شهادة تقييم\", \"learner\": \"{learner-name}\", \"trainer\": \"المعلم: <b>{teacher-name}</b>\", \"content_part_1\": \"هذا لتاكيد ان\", \"content_part_2\": \"أكمل بنجاح \\\"{quiz-name}\\\" الاختبار عبر الإنترنت في {quiz-complete-date} في {quiz-duration} وحقق النتيجة {quiz-score}.\", \"certificate_number\": \"رقم الشهادة: <b>{certificate-number}</b>\"}', `certpl_vars` = '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{quiz-name} Quiz Title <br>\r\n{quiz-completed-date} Quiz Completed On <br>\r\n{certificate-number} Certificate Number<br>\r\n{quiz-duration} Quiz Duration<br>\r\n{quiz-score} Score in Percent<br><br>' WHERE `certpl_code` = 'evaluation_certificate' AND `certpl_lang_id` = 2;