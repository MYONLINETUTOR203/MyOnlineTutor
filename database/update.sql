
--
-- Table structure for table `tbl_categories`
--
CREATE TABLE `tbl_categories` (
  `cate_id` int NOT NULL,
  `cate_type` int NOT NULL,
  `cate_parent` int NOT NULL,
  `cate_subcategories` int NOT NULL,
  `cate_courses` int NOT NULL,
  `cate_order` int NOT NULL,
  `cate_status` tinyint(1) NOT NULL,
  `cate_created` datetime NOT NULL,
  `cate_updated` datetime DEFAULT NULL,
  `cate_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_categories_lang`
--
CREATE TABLE `tbl_categories_lang` (
  `catelang_id` int NOT NULL,
  `catelang_lang_id` int NOT NULL,
  `catelang_cate_id` int NOT NULL,
  `cate_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cate_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_certificate_templates`
--
CREATE TABLE `tbl_certificate_templates` (
  `certpl_id` int NOT NULL,
  `certpl_lang_id` int NOT NULL,
  `certpl_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `certpl_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `certpl_body` json DEFAULT NULL,
  `certpl_vars` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `certpl_status` tinyint(1) NOT NULL,
  `certpl_created` datetime NOT NULL,
  `certpl_updated` datetime NOT NULL,
  `certpl_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Dumping data for table `tbl_certificate_templates`
--
INSERT INTO `tbl_certificate_templates` (`certpl_id`, `certpl_lang_id`, `certpl_code`, `certpl_name`, `certpl_body`, `certpl_vars`, `certpl_status`, `certpl_created`, `certpl_updated`, `certpl_deleted`) VALUES
(1, 1, 'course_completion_certificate', 'Course Completion Certificate', '{\"heading\": \"Certificate Of Completion\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{course-name}\\\" online course on {course-completed-date}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{course-name} Course Title <br>\r\n{course-language} Course Language<br>\r\n{course-completed-date} Course Completed On <br><br>', 1, '2022-03-15 04:00:00', '2022-05-30 09:18:54', NULL),
(2, 1, 'course_evaluation_certificate', 'Course Evaluation Certificate', NULL, '{learner-name} Learner name<br>\r\n{teacher-name} Teacher name <br>\r\n{course-name} Course Title<br>\r\n{course_grades} Course Grades <br>\r\n{course-language} Language Course <br>\r\n{course-completed-date} Course Completed On<br><br>', 0, '2022-03-15 04:00:00', '2022-03-16 09:47:34', NULL),
(3, 2, 'course_completion_certificate', 'Course Completion Certificate', '{\"heading\": \"CERTIFICATE OF COMPLETION(AR)\", \"learner\": \"{learner-name}\", \"trainer\": \"{teacher-name}\", \"content_part_1\": \"This is to certify that\", \"content_part_2\": \"has successfully completed \\\"{course-name}\\\" online course on {course-completed-date}.\", \"certificate_number\": \"{certificate-number}\"}', '{learner-name} Learner name <br>\r\n{teacher-name} Teacher name <br>\r\n{course-name} Course Title <br>\r\n{course-language} Course Language<br>\r\n{course-completed-date} Course Completed On <br><br>', 1, '2022-03-15 04:00:00', '2022-05-09 10:53:38', NULL),
(4, 2, 'course_evaluation_certificate', 'Course Evaluation Certificate', NULL, '{learner-name} Learner name<br>\r\n{teacher-name} Teacher name <br>\r\n{course-name} Course Title<br>\r\n{course_grades} Course Grades <br>\r\n{course-language} Language Course <br>\r\n{course-completed-date} Course Completed On<br><br>', 0, '2022-03-15 04:00:00', '2022-03-16 10:45:49', NULL);
-- --------------------------------------------------------
--
-- Table structure for table `tbl_courses`
--
CREATE TABLE `tbl_courses` (
  `course_id` int NOT NULL,
  `course_level` int NOT NULL,
  `course_cate_id` int NOT NULL,
  `course_subcate_id` int NOT NULL,
  `course_price` decimal(10,2) NOT NULL,
  `course_type` tinyint(1) NOT NULL,
  `course_currency_id` int NOT NULL,
  `course_user_id` int NOT NULL,
  `course_tlang_id` int NOT NULL,
  `course_duration` int NOT NULL,
  `course_sections` int NOT NULL,
  `course_lectures` int NOT NULL,
  `course_reviews` int NOT NULL,
  `course_students` int NOT NULL,
  `course_ratings` decimal(10,2) NOT NULL,
  `course_access` int NOT NULL,
  `course_certificate` tinyint(1) NOT NULL,
  `course_status` tinyint(1) NOT NULL,
  `course_created` datetime NOT NULL,
  `course_updated` datetime DEFAULT NULL,
  `course_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_courses_intended_learners`
--
CREATE TABLE `tbl_courses_intended_learners` (
  `coinle_id` int NOT NULL,
  `coinle_type` tinyint(1) NOT NULL,
  `coinle_course_id` int NOT NULL,
  `coinle_lang_id` int NOT NULL,
  `coinle_response` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coinle_order` int NOT NULL,
  `coinle_created` datetime NOT NULL,
  `coinle_updated` datetime DEFAULT NULL,
  `coinle_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_courses_lang`
--
CREATE TABLE `tbl_courses_lang` (
  `crslang_id` int NOT NULL,
  `crslang_lang_id` int NOT NULL,
  `crslang_course_id` int NOT NULL,
  `course_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `course_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `course_welcome` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `course_congrats` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_courses_tags`
--
CREATE TABLE `tbl_courses_tags` (
  `crstag_id` int NOT NULL,
  `crstag_lang_id` int NOT NULL,
  `crstag_course_id` int NOT NULL,
  `crstag_srchtags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_course_approval_requests`
--
CREATE TABLE `tbl_course_approval_requests` (
  `coapre_id` int NOT NULL,
  `coapre_course_id` int NOT NULL,
  `coapre_status` tinyint NOT NULL,
  `coapre_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coapre_created` datetime NOT NULL,
  `coapre_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_course_progresses`
--
CREATE TABLE `tbl_course_progresses` (
  `crspro_id` int NOT NULL,
  `crspro_ordcrs_id` int NOT NULL,
  `crspro_lecture_id` int NOT NULL COMMENT 'Current lecture',
  `crspro_progress` decimal(10,2) NOT NULL COMMENT 'In Percent',
  `crspro_covered` json DEFAULT NULL,
  `crspro_started` datetime NOT NULL,
  `crspro_completed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_course_refund_requests`
--
CREATE TABLE `tbl_course_refund_requests` (
  `corere_id` int NOT NULL,
  `corere_ordcrs_id` int NOT NULL,
  `corere_user_id` int NOT NULL,
  `corere_status` tinyint(1) NOT NULL,
  `corere_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `corere_created` datetime NOT NULL,
  `corere_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_lectures`
--
CREATE TABLE `tbl_lectures` (
  `lecture_id` int NOT NULL,
  `lecture_type` int NOT NULL,
  `lecture_is_trial` tinyint(1) NOT NULL,
  `lecture_duration` int NOT NULL,
  `lecture_course_id` int NOT NULL,
  `lecture_section_id` int NOT NULL,
  `lecture_order` int NOT NULL,
  `lecture_status` tinyint(1) NOT NULL,
  `lecture_created` datetime NOT NULL,
  `lecture_updated` datetime DEFAULT NULL,
  `lecture_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_lectures_lang`
--
CREATE TABLE `tbl_lectures_lang` (
  `leclang_id` int NOT NULL,
  `leclang_lang_id` int NOT NULL,
  `leclang_lecture_id` int NOT NULL,
  `lecture_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lecture_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_lectures_resources`
--
CREATE TABLE `tbl_lectures_resources` (
  `lecsrc_id` int NOT NULL,
  `lecsrc_type` int NOT NULL,
  `lecsrc_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lecsrc_duration` bigint NOT NULL,
  `lecsrc_resrc_id` int NOT NULL,
  `lecsrc_course_id` int NOT NULL,
  `lecsrc_lecture_id` int NOT NULL,
  `lecsrc_created` datetime NOT NULL,
  `lecsrc_updated` datetime DEFAULT NULL,
  `lecsrc_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_lecture_notes`
--
CREATE TABLE `tbl_lecture_notes` (
  `lecnote_id` int NOT NULL,
  `lecnote_user_id` int NOT NULL,
  `lecnote_course_id` int NOT NULL,
  `lecnote_lecture_id` int NOT NULL,
  `lecnote_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lecnote_created` datetime NOT NULL,
  `lecnote_updated` datetime NOT NULL,
  `lecnote_deleted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_order_courses`
--
CREATE TABLE `tbl_order_courses` (
  `ordcrs_id` bigint NOT NULL,
  `ordcrs_order_id` bigint NOT NULL,
  `ordcrs_course_id` int NOT NULL,
  `ordcrs_commission` decimal(10,2) NOT NULL,
  `ordcrs_earnings` decimal(10,2) DEFAULT NULL,
  `ordcrs_amount` decimal(10,2) NOT NULL,
  `ordcrs_discount` decimal(10,2) NOT NULL,
  `ordcrs_refund` decimal(10,2) DEFAULT NULL,
  `ordcrs_status` tinyint(1) NOT NULL,
  `ordcrs_teacher_paid` decimal(10,2) DEFAULT NULL,
  `ordcrs_payment` tinyint(1) NOT NULL,
  `ordcrs_certificate_number` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordcrs_reviewed` int NOT NULL,
  `ordcrs_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_resources`
--
CREATE TABLE `tbl_resources` (
  `resrc_id` int NOT NULL,
  `resrc_user_id` int NOT NULL,
  `resrc_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resrc_size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resrc_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resrc_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resrc_created` datetime DEFAULT NULL,
  `resrc_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_sections`
--
CREATE TABLE `tbl_sections` (
  `section_id` int NOT NULL,
  `section_course_id` int NOT NULL,
  `section_lectures` int NOT NULL,
  `section_duration` int NOT NULL,
  `section_order` int NOT NULL,
  `section_status` tinyint(1) NOT NULL,
  `section_created` datetime NOT NULL,
  `section_updated` datetime DEFAULT NULL,
  `section_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_sections_lang`
--
CREATE TABLE `tbl_sections_lang` (
  `seclang_id` int NOT NULL,
  `seclang_lang_id` int NOT NULL,
  `seclang_section_id` int NOT NULL,
  `section_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_temp_tokens`
--
CREATE TABLE `tbl_temp_tokens` (
  `tmptok_id` int NOT NULL,
  `tmptok_ordcrs_id` int NOT NULL,
  `tmptok_token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Table structure for table `tbl_user_favourite_courses`
--
CREATE TABLE `tbl_user_favourite_courses` (
  `ufc_id` int NOT NULL,
  `ufc_user_id` int NOT NULL,
  `ufc_course_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Indexes for dumped tables
--
--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`cate_id`);
--
-- Indexes for table `tbl_categories_lang`
--
ALTER TABLE `tbl_categories_lang`
  ADD PRIMARY KEY (`catelang_id`);
--
-- Indexes for table `tbl_certificate_templates`
--
ALTER TABLE `tbl_certificate_templates`
  ADD PRIMARY KEY (`certpl_id`),
  ADD UNIQUE KEY `certpl_lang_id` (`certpl_lang_id`,`certpl_code`);
--
-- Indexes for table `tbl_courses`
--
ALTER TABLE `tbl_courses`
  ADD PRIMARY KEY (`course_id`);
--
-- Indexes for table `tbl_courses_intended_learners`
--
ALTER TABLE `tbl_courses_intended_learners`
  ADD PRIMARY KEY (`coinle_id`);
--
-- Indexes for table `tbl_courses_lang`
--
ALTER TABLE `tbl_courses_lang`
  ADD PRIMARY KEY (`crslang_id`) USING BTREE;
--
-- Indexes for table `tbl_courses_tags`
--
ALTER TABLE `tbl_courses_tags`
  ADD PRIMARY KEY (`crstag_id`) USING BTREE;
--
-- Indexes for table `tbl_course_approval_requests`
--
ALTER TABLE `tbl_course_approval_requests`
  ADD PRIMARY KEY (`coapre_id`);
--
-- Indexes for table `tbl_course_progresses`
--
ALTER TABLE `tbl_course_progresses`
  ADD PRIMARY KEY (`crspro_id`);
--
-- Indexes for table `tbl_course_refund_requests`
--
ALTER TABLE `tbl_course_refund_requests`
  ADD PRIMARY KEY (`corere_id`);
--
-- Indexes for table `tbl_lectures`
--
ALTER TABLE `tbl_lectures`
  ADD PRIMARY KEY (`lecture_id`);
--
-- Indexes for table `tbl_lectures_lang`
--
ALTER TABLE `tbl_lectures_lang`
  ADD PRIMARY KEY (`leclang_id`);
--
-- Indexes for table `tbl_lectures_resources`
--
ALTER TABLE `tbl_lectures_resources`
  ADD PRIMARY KEY (`lecsrc_id`);
--
-- Indexes for table `tbl_lecture_notes`
--
ALTER TABLE `tbl_lecture_notes`
  ADD PRIMARY KEY (`lecnote_id`);
--
-- Indexes for table `tbl_order_courses`
--
ALTER TABLE `tbl_order_courses`
  ADD PRIMARY KEY (`ordcrs_id`);
--
-- Indexes for table `tbl_resources`
--
ALTER TABLE `tbl_resources`
  ADD PRIMARY KEY (`resrc_id`);
--
-- Indexes for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  ADD PRIMARY KEY (`section_id`);
--
-- Indexes for table `tbl_sections_lang`
--
ALTER TABLE `tbl_sections_lang`
  ADD PRIMARY KEY (`seclang_id`);
--
-- Indexes for table `tbl_temp_tokens`
--
ALTER TABLE `tbl_temp_tokens`
  ADD PRIMARY KEY (`tmptok_id`),
  ADD UNIQUE KEY `temtok_cert_id` (`tmptok_ordcrs_id`);
--
-- Indexes for table `tbl_user_favourite_courses`
--
ALTER TABLE `tbl_user_favourite_courses`
  ADD PRIMARY KEY (`ufc_id`),
  ADD UNIQUE KEY `ufc_user_id` (`ufc_user_id`,`ufc_course_id`);
--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `cate_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_categories_lang`
--
ALTER TABLE `tbl_categories_lang`
  MODIFY `catelang_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_certificate_templates`
--
ALTER TABLE `tbl_certificate_templates`
  MODIFY `certpl_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_courses`
--
ALTER TABLE `tbl_courses`
  MODIFY `course_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_courses_intended_learners`
--
ALTER TABLE `tbl_courses_intended_learners`
  MODIFY `coinle_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_courses_lang`
--
ALTER TABLE `tbl_courses_lang`
  MODIFY `crslang_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_courses_tags`
--
ALTER TABLE `tbl_courses_tags`
  MODIFY `crstag_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_course_approval_requests`
--
ALTER TABLE `tbl_course_approval_requests`
  MODIFY `coapre_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_course_progresses`
--
ALTER TABLE `tbl_course_progresses`
  MODIFY `crspro_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_course_refund_requests`
--
ALTER TABLE `tbl_course_refund_requests`
  MODIFY `corere_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_lectures`
--
ALTER TABLE `tbl_lectures`
  MODIFY `lecture_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_lectures_lang`
--
ALTER TABLE `tbl_lectures_lang`
  MODIFY `leclang_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_lectures_resources`
--
ALTER TABLE `tbl_lectures_resources`
  MODIFY `lecsrc_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_lecture_notes`
--
ALTER TABLE `tbl_lecture_notes`
  MODIFY `lecnote_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_order_courses`
--
ALTER TABLE `tbl_order_courses`
  MODIFY `ordcrs_id` bigint NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_resources`
--
ALTER TABLE `tbl_resources`
  MODIFY `resrc_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  MODIFY `section_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_sections_lang`
--
ALTER TABLE `tbl_sections_lang`
  MODIFY `seclang_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_temp_tokens`
--
ALTER TABLE `tbl_temp_tokens`
  MODIFY `tmptok_id` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_user_favourite_courses`
--
ALTER TABLE `tbl_user_favourite_courses`
  MODIFY `ufc_id` int NOT NULL AUTO_INCREMENT;
-- --------------------------------------------------------


INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('course_request_update_email_to_teacher', 1, 'Course Request Status Update', 'Course Request Processed', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Request Update</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {username}</h3>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We have processed your request for the course named \"{course_title}\". After verification, the request has been marked as \"{request_status}\".<br><br>\r\n								Comment : {admin_comment}</p>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Note: In case your request is declined, we recommend you to recheck and update your course details according to the feedback shared and re-submit.</p>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n\r\n', '{username} Teacher name<br>\r\n{course_title} Course title<br>\r\n{request_status} Approved or Declined<br>\r\n{admin_comment} Admin comments for decline<br>', 1, 0),
('course_request_update_email_to_teacher', 2, 'Course Request Status Update', 'Course Request Processed', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Request Update</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {username}</h3>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We have processed your request for the course named \"{course_title}\". After verification, the request has been marked as \"{request_status}\".<br><br>\r\n								Comment : {admin_comment}</p>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Note: In case your request is declined, we recommend you to recheck and update your course details according to the feedback shared and re-submit.</p>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n\r\n', '{username} Teacher name<br>\r\n{course_title} Course title<br>\r\n{request_status} Approved or Declined<br>\r\n{admin_comment} Admin comments for decline<br>', 1, 0);
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('course_approval_request_email_to_admin', 1, 'Course Approval Request Received', 'Course Approval Request Received', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Request Received</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin</h3>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">A new course named \"{course_title}\" has been submitted by {username} for approval. Please verify the details and process the request further.<br><br>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\"><a href=\"{course_link}\">View Course Request</a></p>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>', '{username} Teacher name<br>\r\n{course_title} Course title<br>\r\n{course_link} Link to course detail<br>', 1, 0),
('course_approval_request_email_to_admin', 2, 'Course Approval Request Received', 'Course Approval Request Received', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Request Received</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin</h3>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">A new course named \"{course_title}\" has been submitted by {username} for approval. Please verify the details and process the request further.<br><br>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\"><a href=\"{course_link}\">View Course Request</a></p>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>', '{username} Teacher name<br>\r\n{course_title} Course title<br>\r\n{course_link} Link to course detail<br>', 1, 0);

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_MANAGE_COURSE_RESOURCES_PAGE_SUB_HEADING', 1, 'Use this page to manage and review resources uploaded for course, their current status, etc.'),
('LBL_MANAGE_COURSE_PAGE_SUB_HEADING', 1, 'Use this page to manage and review information for courses created, their current status, etc.'),
('LBL_MANAGE_COURSE_SUB_HEADING', 1, 'You can change or edit the language specific course content by selecting the language option from right side.'),
('LBL_COURSE_IMAGE_INFO', 1, 'Upload your course image here. It must meet our course image quality standards to be accepted. Important guidelines: {dimensions} pixels; {extensions}. no text on the image.'),
('LBL_COURSE_PREVIEW_VIDEO_INFO', 1, 'Students who watch a well-made promo video are 5X more likely to enroll in your course. We\'ve seen that statistic go up to 10X for exceptionally awesome videos.'),
('LBL_LEARN_HOW_TO_MAKE_YOURS_AWESOME!', 1, 'Learn how to make yours awesome!'),
('LBL_INTENDED_LEARNERS_SHORT_INFO', 1, 'The following descriptions will be publicly visible on your Course Landing Page and will have a direct imapct on your course performance. These descriptions will help learners decide if your course is right for them.'),
('LBL_PRICING_SHORT_INFO_1', 1, 'Please select the price tier for your course below and click \'Save\'. The list price that students will see in other currencies is determined using the price tier matrix.'),
('LBL_PRICING_SHORT_INFO_2', 1, 'If you intend to offer your course for free, the total length of video content must be less than 2 hours.'),
('LBL_CURRICULUM_SHORT_INFO_1', 1, 'Start putting together your course by creating sections, lectures and practice assignments.'),
('LBL_CURRICULUM_SHORT_INFO_2', 1, 'If you\'re intending to offer your course for free, the total lenght of video content must be less than 2 hours.'),
('LBL_WHAT_WILL_STUDENT_LEARN', 1, 'What will students learn in your course?'),
('LBL_WHAT_WILL_STUDENT_LEARN_SUBTITLE', 1, 'You must enter at least 4 learning objectives or outcomes that learners can expect to achieve after completing your course.'),
('LBL_WHAT_ARE_THE_REQUIREMENTS', 1, 'What are the requirements or prerequisites for taking your course?'),
('LBL_WHAT_ARE_THE_REQUIREMENTS_SUBTITLE', 1, 'List the required skills, experience, tools or equipment learners should have prior to taking your course. If there are no requirements, use this space as an opportunity to lower the barrier for beginners.'),
('LBL_WHO_IS_THE_COURSE_FOR', 1, 'Who is the course for?'),
('LBL_WHO_IS_THE_COURSE_FOR_SUBTITLE', 1, 'Write a clear description of the intended learners for your course who will find your course content valuable. This will help you attract the right learners to your course.'),
('LBL_YOUTUBE_API_KEY_MESSAGE', 1, 'Google API key to get data using Google\'s YouTube Data API v3'),
('LBL_YOUTUBE_DATA_API_KEY', 1, 'YouTube Data API Key'),
('LBL_YOUTUBE_DATA_API', 1, 'YouTube Data API'),
('htmlAfterField_COURSE_CANCELLATION_DURATION_TEXT', 1, 'Number of days after purchase until cancellation is allowed'),
('LBL_SET_THE_DEFAULT_STATUS_WHEN_A_COURSE_CANCELLATION_REQUEST_IS_PLACED', 1, 'Set the default status of request when a course is marked cancelled.'),
('LBL_LAST_LECTURE_COMPLETED_SHORT_DESCRIPTION', 1, 'But still you have a few lectures pending to complete this course. <br>We request you to please learn them and then mark its status complete.'),
('LBL_NOW_YOU_ARE_ELIGIBLE_FOR_THE_CERTIFICATE', 1, 'Please click the download certificate button below to get your course certificate or you can select the course retake option to retake again.'),
('LBL_COURSE_LISTING_HEADING', 1, 'Choose our experienced tutors and get the best learning experience.');

INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
('CONF_COURSE_APPROVAL_ELIGIBILITY_CRITERIA', '[\"course_sections\",\"course_lectures\",\"course_welcome\",\"course_congrats\",\"course_tags\",\"course_currency_id\",\"course_price\",\"course_image\",\"course_preview_video\",\"courses_intended_learners\"]', 0),
('CONF_COURSE_CANCEL_DURATION', '7', 0),
('CONF_COURSE_DEFAULT_CANCELLATION_STATUS', '0', 0),
('CONF_COURSE_LEVELS', '[{\"id\": \"1\", \"name\": \"beginner\"}, {\"id\": \"2\", \"name\": \"intermediate\"}, {\"id\": \"3\", \"name\": \"expert\"}]', 0);

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Completed courses payment settlement', 'completedCourseSettlement', '60', '1'); 

INSERT INTO `tbl_navigation_links` (`nlink_id`, `nlink_nav_id`, `nlink_cpage_id`, `nlink_category_id`, `nlink_identifier`, `nlink_target`, `nlink_type`, `nlink_parent_id`, `nlink_login_protected`, `nlink_deleted`, `nlink_url`, `nlink_order`) VALUES (NULL, 2, 0, 0, 'Courses', '_self', 2, 0, 0, 0, '{siteroot}courses', 0);

ALTER TABLE `tbl_teacher_stats` ADD `testat_courses` INT NOT NULL AFTER `testat_classes`;

ALTER TABLE `tbl_sales_stats`  
    ADD `slstat_crs_sales` DECIMAL(10,2) NOT NULL  AFTER `slstat_cls_earnings`,  
    ADD `slstat_crs_refund` DECIMAL(10,2) NOT NULL  AFTER `slstat_crs_sales`,  
    ADD `slstat_crs_teacher_paid` DECIMAL(10,2) NOT NULL  AFTER `slstat_crs_refund`,  
    ADD `slstat_crs_discount` DECIMAL(10,2) NOT NULL  AFTER `slstat_crs_teacher_paid`,  
    ADD `slstat_crs_earnings` DECIMAL(10,2) NULL  AFTER `slstat_crs_discount`;

UPDATE `tbl_configurations` SET `conf_val` = 'TV-4.0.0.20220706' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';


ALTER TABLE `tbl_courses` ADD `course_slug` VARCHAR(255) NOT NULL AFTER `course_id`;
UPDATE tbl_courses INNER JOIN( SELECT course_title, crslang_course_id FROM tbl_courses_lang ) crslang ON course_id = crslang.crslang_course_id SET course_slug = REPLACE(LOWER(crslang.course_title), " ", "-");