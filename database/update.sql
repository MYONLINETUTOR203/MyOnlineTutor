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