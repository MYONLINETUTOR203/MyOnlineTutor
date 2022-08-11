
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

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`) VALUES
('LBL_COURSE_LANGUAGE_UPDATE_NOTICE', 1, 'Note: Courses binded with Inactive/Deleted languages will not be available for booking.');

--
-- Table structure for table `tbl_course_languages`
--

DROP TABLE IF EXISTS `tbl_course_languages`;
CREATE TABLE IF NOT EXISTS `tbl_course_languages` (
  `clang_id` int(11) NOT NULL AUTO_INCREMENT,
  `clang_order` int(11) NOT NULL,
  `clang_active` tinyint(1) NOT NULL,
  `clang_identifier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clang_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`clang_id`),
  UNIQUE KEY `clang_identifier` (`clang_identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_course_languages`
--

INSERT INTO `tbl_course_languages` (`clang_id`, `clang_order`, `clang_active`, `clang_identifier`, `clang_deleted`) VALUES
(1, 2, 1, 'Afar', NULL),
(2, 3, 1, 'Abkhazian', NULL),
(3, 4, 1, 'Avestan', NULL),
(4, 5, 1, 'Afrikaans', NULL),
(5, 6, 1, 'Amharic', NULL),
(6, 7, 1, 'Aragonese', NULL),
(7, 8, 1, 'Arabic', NULL),
(8, 9, 1, 'Assamese', NULL),
(9, 10, 1, 'Avaric', NULL),
(10, 11, 1, 'Aymara', NULL),
(11, 12, 1, 'Azerbaijani', NULL),
(12, 13, 1, 'Bashkir', NULL),
(13, 14, 1, 'Belarusian', NULL),
(14, 15, 1, 'Bulgarian', NULL),
(15, 16, 1, 'Bihari languages', NULL),
(16, 17, 1, 'Bislama', NULL),
(17, 18, 1, 'Bambara', NULL),
(18, 19, 1, 'Bengali', NULL),
(19, 20, 1, 'Tibetan', NULL),
(20, 21, 1, 'Breton', NULL),
(21, 22, 1, 'Bosnian', NULL),
(22, 23, 1, 'Catalan; Valencian', NULL),
(23, 24, 1, 'Chechen', NULL),
(24, 25, 1, 'Chamorro', NULL),
(25, 26, 1, 'Corsican', NULL),
(26, 27, 1, 'Cree', NULL),
(27, 28, 1, 'Czech', NULL),
(28, 29, 1, 'Church Slavic; Slavonic; Old Bulgarian', NULL),
(29, 30, 1, 'Chuvash', NULL),
(30, 31, 1, 'Welsh', NULL),
(31, 32, 1, 'Danish', NULL),
(32, 33, 1, 'German', NULL),
(33, 34, 1, 'Divehi; Dhivehi; Maldivian', NULL),
(34, 35, 1, 'Dzongkha', NULL),
(35, 36, 1, 'Ewe', NULL),
(36, 37, 1, 'Greek, Modern (1453-)', NULL),
(37, 38, 1, 'English', NULL),
(38, 39, 1, 'Esperanto', NULL),
(39, 40, 1, 'Spanish; Castilian', NULL),
(40, 41, 1, 'Estonian', NULL),
(41, 42, 1, 'Basque', NULL),
(42, 43, 1, 'Persian', NULL),
(43, 44, 1, 'Fulah', NULL),
(44, 45, 1, 'Finnish', NULL),
(45, 46, 1, 'Fijian', NULL),
(46, 47, 1, 'Faroese', NULL),
(47, 48, 1, 'French', NULL),
(48, 49, 1, 'Western Frisian', NULL),
(49, 50, 1, 'Irish', NULL),
(50, 51, 1, 'Gaelic; Scottish Gaelic', NULL),
(51, 52, 1, 'Galician', NULL),
(52, 53, 1, 'Guarani', NULL),
(53, 54, 1, 'Gujarati', NULL),
(54, 55, 1, 'Manx', NULL),
(55, 56, 1, 'Hausa', NULL),
(56, 57, 1, 'Hebrew', NULL),
(57, 58, 1, 'Hindi', NULL),
(58, 59, 1, 'Hiri Motu', NULL),
(59, 60, 1, 'Croatian', NULL),
(60, 61, 1, 'Haitian; Haitian Creole', NULL),
(61, 62, 1, 'Hungarian', NULL),
(62, 63, 1, 'Armenian', NULL),
(63, 64, 1, 'Herero', NULL),
(64, 65, 1, 'Interlingua', NULL),
(65, 66, 1, 'Indonesian', NULL),
(66, 67, 1, 'Occidental', NULL),
(67, 68, 1, 'Igbo', NULL),
(68, 69, 1, 'Sichuan Yi; Nuosu', NULL),
(69, 70, 1, 'Ido', NULL),
(70, 71, 1, 'Icelandic', NULL),
(71, 72, 1, 'Italian', NULL),
(72, 73, 1, 'Inuktitut', NULL),
(73, 74, 1, 'Japanese', NULL),
(74, 75, 1, 'Javanese', NULL),
(75, 76, 1, 'Georgian', NULL),
(76, 77, 1, 'Kongo', NULL),
(77, 78, 1, 'Kikuyu; Gikuyu', NULL),
(78, 79, 1, 'Kuanyama; Kwanyama', NULL),
(79, 80, 1, 'Kazakh', NULL),
(80, 81, 1, 'Kalaallisut; Greenlandic', NULL),
(81, 82, 1, 'Central Khmer', NULL),
(82, 83, 1, 'Kannada', NULL),
(83, 84, 1, 'Korean', NULL),
(84, 85, 1, 'Kanuri', NULL),
(85, 86, 1, 'Kashmiri', NULL),
(86, 87, 1, 'Kurdish', NULL),
(87, 88, 1, 'Komi', NULL),
(88, 89, 1, 'Cornish', NULL),
(89, 90, 1, 'Kirghiz; Kyrgyz', NULL),
(90, 91, 1, 'Latin', NULL),
(91, 92, 1, 'Luxembourgish; Letzeburgesch', NULL),
(92, 93, 1, 'Ganda', NULL),
(93, 94, 1, 'Limburgan; Limburger; Limburgish', NULL),
(94, 95, 1, 'Lingala', NULL),
(95, 96, 1, 'Lao', NULL),
(96, 97, 1, 'Lithuanian', NULL),
(97, 98, 1, 'Luba-Katanga', NULL),
(98, 99, 1, 'Latvian', NULL),
(99, 100, 1, 'Malagasy', NULL),
(100, 101, 1, 'Marshallese', NULL),
(101, 102, 1, 'Maori', NULL),
(102, 103, 1, 'Macedonian', NULL),
(103, 104, 1, 'Malayalam', NULL),
(104, 105, 1, 'Mongolian', NULL),
(105, 106, 1, 'Marathi', NULL),
(106, 107, 1, 'Malay', NULL),
(107, 108, 1, 'Maltese', NULL),
(108, 109, 1, 'Burmese', NULL),
(109, 110, 1, 'Nauru', NULL),
(110, 111, 1, 'Bokmål, Norwegian; Norwegian Bokmål', NULL),
(111, 112, 1, 'Ndebele, North; North Ndebele', NULL),
(112, 113, 1, 'Nepali', NULL),
(113, 114, 1, 'Ndonga', NULL),
(114, 115, 1, 'Dutch; Flemish', NULL),
(115, 116, 1, 'Norwegian Nynorsk; Nynorsk, Norwegian', NULL),
(116, 117, 1, 'Norwegian', NULL),
(117, 118, 1, 'Ndebele, South; South Ndebele', NULL),
(118, 119, 1, 'Navajo; Navaho', NULL),
(119, 120, 1, 'Chichewa; Chewa; Nyanja', NULL),
(120, 121, 1, 'Occitan (post 1500)', NULL),
(121, 122, 1, 'Ojibwa', NULL),
(122, 123, 1, 'Oromo', NULL),
(123, 124, 1, 'Oriya', NULL),
(124, 125, 1, 'Ossetian; Ossetic', NULL),
(125, 126, 1, 'Panjabi; Punjabi', NULL),
(126, 127, 1, 'Pali', NULL),
(127, 128, 1, 'Polish', NULL),
(128, 129, 1, 'Pushto; Pashto', NULL),
(129, 130, 1, 'Portuguese', NULL),
(130, 131, 1, 'Quechua', NULL),
(131, 132, 1, 'Romansh', NULL),
(132, 133, 1, 'Rundi', NULL),
(133, 134, 1, 'Romanian; Moldavian; Moldovan', NULL),
(134, 135, 1, 'Russian', NULL),
(135, 136, 1, 'Kinyarwanda', NULL),
(136, 137, 1, 'Sanskrit', NULL),
(137, 138, 1, 'Sardinian', NULL),
(138, 139, 1, 'Sindhi', NULL),
(139, 140, 1, 'Northern Sami', NULL),
(140, 141, 1, 'Sango', NULL),
(141, 142, 1, 'Sinhala; Sinhalese', NULL),
(142, 143, 1, 'Slovak', NULL),
(143, 144, 1, 'Slovenian', NULL),
(144, 145, 1, 'Samoan', NULL),
(145, 146, 1, 'Shona', NULL),
(146, 147, 1, 'Somali', NULL),
(147, 148, 1, 'Albanian', NULL),
(148, 149, 1, 'Serbian', NULL),
(149, 150, 1, 'Swati', NULL),
(150, 151, 1, 'Sotho, Southern', NULL),
(151, 152, 1, 'Sundanese', NULL),
(152, 153, 1, 'Swedish', NULL),
(153, 154, 1, 'Swahili', NULL),
(154, 155, 1, 'Tamil', NULL),
(155, 156, 1, 'Telugu', NULL),
(156, 157, 1, 'Tajik', NULL),
(157, 158, 1, 'Thai', NULL),
(158, 159, 1, 'Tigrinya', NULL),
(159, 160, 1, 'Turkmen', NULL),
(160, 161, 1, 'Tagalog', NULL),
(161, 162, 1, 'Tswana', NULL),
(162, 163, 1, 'Tonga (Tonga Islands)', NULL),
(163, 164, 1, 'Turkish', NULL),
(164, 165, 1, 'Tsonga', NULL),
(165, 166, 1, 'Tatar', NULL),
(166, 167, 1, 'Twi', NULL),
(167, 168, 1, 'Tahitian', NULL),
(168, 169, 1, 'Uighur; Uyghur', NULL),
(169, 170, 1, 'Ukrainian', NULL),
(170, 171, 1, 'Urdu', NULL),
(171, 172, 1, 'Uzbek', NULL),
(172, 173, 1, 'Venda', NULL),
(173, 174, 1, 'Vietnamese', NULL),
(174, 175, 1, 'Volapük', NULL),
(175, 176, 1, 'Walloon', NULL),
(176, 177, 1, 'Wolof', NULL),
(177, 178, 1, 'Xhosa', NULL),
(178, 179, 1, 'Yiddish', NULL),
(179, 180, 1, 'Yoruba', NULL),
(180, 181, 1, 'Zhuang; Chuang', NULL),
(181, 182, 1, 'Chinese', NULL),
(182, 183, 1, 'Zulu', NULL);

--
-- Table structure for table `tbl_course_languages_lang`
--

DROP TABLE IF EXISTS `tbl_course_languages_lang`;
CREATE TABLE IF NOT EXISTS `tbl_course_languages_lang` (
  `clanglang_clang_id` int(11) NOT NULL,
  `clanglang_lang_id` int(11) NOT NULL,
  `clang_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`clanglang_clang_id`,`clanglang_lang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_course_languages_lang`
--

INSERT INTO `tbl_course_languages_lang` (`clanglang_clang_id`, `clanglang_lang_id`, `clang_name`) VALUES
(1, 1, 'Afar'),
(1, 2, 'بعيد'),
(2, 1, 'Abkhazian'),
(2, 2, 'الابخازيه'),
(3, 1, 'Avestan'),
(3, 2, 'أفستان'),
(4, 1, 'Afrikaans'),
(4, 2, 'الأفريقانية'),
(5, 1, 'Amharic'),
(5, 2, 'الامهريه'),
(6, 1, 'Aragonese'),
(6, 2, 'أراغونيز'),
(7, 1, 'Arabic'),
(7, 2, 'العربية'),
(8, 1, 'Assamese'),
(8, 2, 'الاساميه'),
(9, 1, 'Avaric'),
(9, 2, 'افاريك'),
(10, 1, 'Aymara'),
(10, 2, 'ايمارا'),
(11, 1, 'Azerbaijani'),
(11, 2, 'الاذربيجانيه'),
(12, 1, 'Bashkir'),
(12, 2, 'باشكير'),
(13, 1, 'Belarusian'),
(13, 2, 'البيلاروسية'),
(14, 1, 'Bulgarian'),
(14, 2, 'البلغارية'),
(15, 1, 'Bihari languages'),
(15, 2, 'لغات البيهاري'),
(16, 1, 'Bislama'),
(16, 2, 'بيسلاما'),
(17, 1, 'Bambara'),
(17, 2, 'بامبارا'),
(18, 1, 'Bengali'),
(18, 2, 'البنغالية'),
(19, 1, 'Tibetan'),
(19, 2, 'التبت'),
(20, 1, 'Breton'),
(20, 2, 'بريتون'),
(21, 1, 'Bosnian'),
(21, 2, 'البوسنية'),
(22, 1, 'Catalan; Valencian'),
(22, 2, 'كاتالونيا; فالنسيا'),
(23, 1, 'Chechen'),
(23, 2, 'الشيشان'),
(24, 1, 'Chamorro'),
(24, 2, 'الشامورو'),
(25, 1, 'Corsican'),
(25, 2, 'الكورسيكيه'),
(26, 1, 'Cree'),
(26, 2, 'كري'),
(27, 1, 'Czech'),
(27, 2, 'التشيكية'),
(28, 1, 'Church Slavic; Slavonic; Old Bulgarian'),
(28, 2, 'الكنيسة السلافية; سلافونيك؛ البلغارية القديمة'),
(29, 1, 'Chuvash'),
(29, 2, 'تشوفاش'),
(30, 1, 'Welsh'),
(30, 2, 'الويلزية'),
(31, 1, 'Danish'),
(31, 2, 'الدانماركية'),
(32, 1, 'German'),
(32, 2, 'الألمانية'),
(33, 1, 'Divehi; Dhivehi; Maldivian'),
(33, 2, 'Divehi; Dhivehi; المالديف'),
(34, 1, 'Dzongkha'),
(34, 2, 'دزونغخا'),
(35, 1, 'Ewe'),
(35, 2, 'إيوي'),
(36, 1, 'Greek, Modern (1453-)'),
(36, 2, 'اليونانية, الحديثة (1453-)'),
(37, 1, 'English'),
(37, 2, 'الإنكليزية'),
(38, 1, 'Esperanto'),
(38, 2, 'الاسبرانتو'),
(39, 1, 'Spanish; Castilian'),
(39, 2, 'الإسبانية؛ القشتاليه'),
(40, 1, 'Estonian'),
(40, 2, 'الإستونية'),
(41, 1, 'Basque'),
(41, 2, 'الباسكية'),
(42, 1, 'Persian'),
(42, 2, 'فارسي'),
(43, 1, 'Fulah'),
(43, 2, 'فولا'),
(44, 1, 'Finnish'),
(44, 2, 'الفنلندية'),
(45, 1, 'Fijian'),
(45, 2, 'فيجي'),
(46, 1, 'Faroese'),
(46, 2, 'الفياروسية'),
(47, 1, 'French'),
(47, 2, 'الفرنسية'),
(48, 1, 'Western Frisian'),
(48, 2, 'غرب فريزيان'),
(49, 1, 'Irish'),
(49, 2, 'الأيرلندية'),
(50, 1, 'Gaelic; Scottish Gaelic'),
(50, 2, 'الغيلية; الغيلية الاسكتلندية'),
(51, 1, 'Galician'),
(51, 2, 'الغاليشية'),
(52, 1, 'Guarani'),
(52, 2, 'الجواراني'),
(53, 1, 'Gujarati'),
(53, 2, 'الجوجاراتية'),
(54, 1, 'Manx'),
(54, 2, 'مانكس'),
(55, 1, 'Hausa'),
(55, 2, 'الهوسا'),
(56, 1, 'Hebrew'),
(56, 2, 'العبرية'),
(57, 1, 'Hindi'),
(57, 2, 'الهندية'),
(58, 1, 'Hiri Motu'),
(58, 2, 'هيري موتو'),
(59, 1, 'Croatian'),
(59, 2, 'الكرواتية'),
(60, 1, 'Haitian; Haitian Creole'),
(60, 2, 'هايتي؛ هايتي'),
(61, 1, 'Hungarian'),
(61, 2, 'المجرية'),
(62, 1, 'Armenian'),
(62, 2, 'الأرمينية'),
(63, 1, 'Herero'),
(63, 2, 'هيريرو'),
(64, 1, 'Interlingua'),
(64, 2, 'إنتلينغوا'),
(65, 1, 'Indonesian'),
(65, 2, 'الإندونيسية'),
(66, 1, 'Occidental'),
(66, 2, 'اوكسيدنتال'),
(67, 1, 'Igbo'),
(67, 2, 'إيغبو'),
(68, 1, 'Sichuan Yi; Nuosu'),
(68, 2, 'سيتشوان يي; نوسو'),
(69, 1, 'Ido'),
(69, 2, 'ايدو'),
(70, 1, 'Icelandic'),
(70, 2, 'الأيسلندية'),
(71, 1, 'Italian'),
(71, 2, 'الإيطالية'),
(72, 1, 'Inuktitut'),
(72, 2, 'الانكتيتوتيه'),
(73, 1, 'Japanese'),
(73, 2, 'اليابانية'),
(74, 1, 'Javanese'),
(74, 2, 'الجاويه'),
(75, 1, 'Georgian'),
(75, 2, 'الجورجية'),
(76, 1, 'Kongo'),
(76, 2, 'كونغو'),
(77, 1, 'Kikuyu; Gikuyu'),
(77, 2, 'كيكويو؛ جكويو'),
(78, 1, 'Kuanyama; Kwanyama'),
(78, 2, 'كوانياما؛ كوانياما'),
(79, 1, 'Kazakh'),
(79, 2, 'الكازاخية'),
(80, 1, 'Kalaallisut; Greenlandic'),
(80, 2, 'كالاليسوت؛ غرينلاند'),
(81, 1, 'Central Khmer'),
(81, 2, 'الخمير الوسطى'),
(82, 1, 'Kannada'),
(82, 2, 'الكندية'),
(83, 1, 'Korean'),
(83, 2, 'الكورية'),
(84, 1, 'Kanuri'),
(84, 2, 'كانوري'),
(85, 1, 'Kashmiri'),
(85, 2, 'الكشميري'),
(86, 1, 'Kurdish'),
(86, 2, 'الكرديه'),
(87, 1, 'Komi'),
(87, 2, 'كومي'),
(88, 1, 'Cornish'),
(88, 2, 'كورنيش'),
(89, 1, 'Kirghiz; Kyrgyz'),
(89, 2, 'كيرغيز؛ القرقيزية'),
(90, 1, 'Latin'),
(90, 2, 'امريكا اللاتينيه'),
(91, 1, 'Luxembourgish; Letzeburgesch'),
(91, 2, 'اللكسمبرغية؛ ليتزبورغيش'),
(92, 1, 'Ganda'),
(92, 2, 'غاندا'),
(93, 1, 'Limburgan; Limburger; Limburgish'),
(93, 2, 'ليمبورغان؛ ليمبورغر; ليمبورغيش'),
(94, 1, 'Lingala'),
(94, 2, 'لينغالا'),
(95, 1, 'Lao'),
(95, 2, 'لاو'),
(96, 1, 'Lithuanian'),
(96, 2, 'الليتوانية'),
(97, 1, 'Luba-Katanga'),
(97, 2, 'لوبا - كاتانغا'),
(98, 1, 'Latvian'),
(98, 2, 'اللاتفية'),
(99, 1, 'Malagasy'),
(99, 2, 'الملغاشيه'),
(100, 1, 'Marshallese'),
(100, 2, 'مارشالية'),
(101, 1, 'Maori'),
(101, 2, 'الماوورية'),
(102, 1, 'Macedonian'),
(102, 2, 'مقدوني'),
(103, 1, 'Malayalam'),
(103, 2, 'المالايلامية'),
(104, 1, 'Mongolian'),
(104, 2, 'المنغولية'),
(105, 1, 'Marathi'),
(105, 2, 'الماراثية'),
(106, 1, 'Malay'),
(106, 2, 'الماليزية'),
(107, 1, 'Maltese'),
(107, 2, 'المالطية'),
(108, 1, 'Burmese'),
(108, 2, 'البورميه'),
(109, 1, 'Nauru'),
(109, 2, 'ناورو'),
(110, 1, 'Bokmål, Norwegian; Norwegian Bokmål'),
(110, 2, 'بوكمول، النرويجية; بوكمول النرويجية'),
(111, 1, 'Ndebele, North; North Ndebele'),
(111, 2, 'نديبيلي، شمال؛ شمال نديبيلي'),
(112, 1, 'Nepali'),
(112, 2, 'النيباليه'),
(113, 1, 'Ndonga'),
(113, 2, 'ندونغا'),
(114, 1, 'Dutch; Flemish'),
(114, 2, 'الهولندية؛ الفلمنكيه'),
(115, 1, 'Norwegian Nynorsk; Nynorsk, Norwegian'),
(115, 2, 'النرويجية نينورسك; نينورسك، النرويجية'),
(116, 1, 'Norwegian'),
(116, 2, 'النرويجية ‏'),
(117, 1, 'Ndebele, South; South Ndebele'),
(117, 2, 'نديبيلي، جنوب؛ جنوب نديبيلي'),
(118, 1, 'Navajo; Navaho'),
(118, 2, 'نافاجو؛ نافاهو'),
(119, 1, 'Chichewa; Chewa; Nyanja'),
(119, 2, 'تشيتشيوا؛ تشيوا؛ نيانجا'),
(120, 1, 'Occitan (post 1500)'),
(120, 2, 'أوكسيتان (آخر 1500)'),
(121, 1, 'Ojibwa'),
(121, 2, 'أوجيبوا'),
(122, 1, 'Oromo'),
(122, 2, 'اورومو'),
(123, 1, 'Oriya'),
(123, 2, 'الاوريا'),
(124, 1, 'Ossetian; Ossetic'),
(124, 2, 'أوسيتيا؛ Ossetic'),
(125, 1, 'Panjabi; Punjabi'),
(125, 2, 'بانجابي؛ البنجابية'),
(126, 1, 'Pali'),
(126, 2, 'الباليه'),
(127, 1, 'Polish'),
(127, 2, 'البولندية'),
(128, 1, 'Pushto; Pashto'),
(128, 2, 'بوشتو؛ الباشتو'),
(129, 1, 'Portuguese'),
(129, 2, 'البرتغالية'),
(130, 1, 'Quechua'),
(130, 2, 'الكيشوا'),
(131, 1, 'Romansh'),
(131, 2, 'روميش'),
(132, 1, 'Rundi'),
(132, 2, 'روندي'),
(133, 1, 'Romanian; Moldavian; Moldovan'),
(133, 2, 'الرومانية؛ مولدافيان; المولدوفيه'),
(134, 1, 'Russian'),
(134, 2, 'الروسية'),
(135, 1, 'Kinyarwanda'),
(135, 2, 'الكينياروانديه'),
(136, 1, 'Sanskrit'),
(136, 2, 'السنسكريتية'),
(137, 1, 'Sardinian'),
(137, 2, 'السردينيه'),
(138, 1, 'Sindhi'),
(138, 2, 'السندهيه'),
(139, 1, 'Northern Sami'),
(139, 2, 'سامي الشمالية'),
(140, 1, 'Sango'),
(140, 2, 'السانغو'),
(141, 1, 'Sinhala; Sinhalese'),
(141, 2, 'السنهالية؛ السنهاليه'),
(142, 1, 'Slovak'),
(142, 2, 'السلوفاكية'),
(143, 1, 'Slovenian'),
(143, 2, 'السلوفينية'),
(144, 1, 'Samoan'),
(144, 2, 'ساموا'),
(145, 1, 'Shona'),
(145, 2, 'شونا'),
(146, 1, 'Somali'),
(146, 2, 'الصوماليه'),
(147, 1, 'Albanian'),
(147, 2, 'الألبانية'),
(148, 1, 'Serbian'),
(148, 2, 'الصربية'),
(149, 1, 'Swati'),
(149, 2, 'سواتي'),
(150, 1, 'Sotho, Southern'),
(150, 2, 'سوثو، جنوبية'),
(151, 1, 'Sundanese'),
(151, 2, 'سوندانيز'),
(152, 1, 'Swedish'),
(152, 2, 'السويدية'),
(153, 1, 'Swahili'),
(153, 2, 'السواحلية'),
(154, 1, 'Tamil'),
(154, 2, 'التاميلية'),
(155, 1, 'Telugu'),
(155, 2, 'التيلوغية'),
(156, 1, 'Tajik'),
(156, 2, 'الطاجيكيه'),
(157, 1, 'Thai'),
(157, 2, 'التايلندية'),
(158, 1, 'Tigrinya'),
(158, 2, 'التيغرينيا'),
(159, 1, 'Turkmen'),
(159, 2, 'التركمانيه'),
(160, 1, 'Tagalog'),
(160, 2, 'التغالوغيه'),
(161, 1, 'Tswana'),
(161, 2, 'التسوانية'),
(162, 1, 'Tonga (Tonga Islands)'),
(162, 2, 'تونغا (جزر تونغا)'),
(163, 1, 'Turkish'),
(163, 2, 'التركية'),
(164, 1, 'Tsonga'),
(164, 2, 'تسونغا'),
(165, 1, 'Tatar'),
(165, 2, 'التتارية'),
(166, 1, 'Twi'),
(166, 2, 'توي'),
(167, 1, 'Tahitian'),
(167, 2, 'التاهيتي'),
(168, 1, 'Uighur; Uyghur'),
(168, 2, 'اليوغور؛ الاويغور'),
(169, 1, 'Ukrainian'),
(169, 2, 'الأوكرانية'),
(170, 1, 'Urdu'),
(170, 2, 'الأوردية'),
(171, 1, 'Uzbek'),
(171, 2, 'الأوزبكية'),
(172, 1, 'Venda'),
(172, 2, 'فيندا'),
(173, 1, 'Vietnamese'),
(173, 2, 'الفيتنامية'),
(174, 1, 'Volapük'),
(174, 2, 'فولابوك'),
(175, 1, 'Walloon'),
(175, 2, 'الون'),
(176, 1, 'Wolof'),
(176, 2, 'الولوف'),
(177, 1, 'Xhosa'),
(177, 2, 'زوسا'),
(178, 1, 'Yiddish'),
(178, 2, 'الييديه'),
(179, 1, 'Yoruba'),
(179, 2, 'اليوروبا'),
(180, 1, 'Zhuang; Chuang'),
(180, 2, 'تشوانغ؛ شوانغ'),
(181, 1, 'Chinese'),
(181, 2, 'الصينية'),
(182, 1, 'Zulu'),
(182, 2, 'الزولوية');

RENAME TABLE `tbl_courses_lang` TO `tbl_course_details`; 
ALTER TABLE `tbl_courses` CHANGE `course_tlang_id` `course_clang_id` INT(11) NOT NULL; 
ALTER TABLE `tbl_course_details` CHANGE `crslang_course_id` `course_id` INT(11) NOT NULL; 
ALTER TABLE `tbl_course_details` ADD PRIMARY KEY(`course_id`);
ALTER TABLE `tbl_course_details` DROP `crslang_id`;
ALTER TABLE `tbl_course_details` DROP `crslang_lang_id`;
ALTER TABLE `tbl_courses_intended_learners` DROP `coinle_lang_id`;
ALTER TABLE `tbl_sections`  ADD `section_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL  AFTER `section_course_id`,  ADD `section_details` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL  AFTER `section_title`;
DROP TABLE `tbl_sections_lang`;
ALTER TABLE `tbl_sections` DROP `section_status`;
ALTER TABLE `tbl_courses` DROP `course_access`;
ALTER TABLE `tbl_course_details` DROP `course_features`;
ALTER TABLE `tbl_lectures`
  DROP `lecture_type`,
  DROP `lecture_status`; 
ALTER TABLE `tbl_lectures`  ADD `lecture_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL  AFTER `lecture_id`,  ADD `lecture_details` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL  AFTER `lecture_title`;
DROP TABLE ` tbl_lectures_lang`;
ALTER TABLE `tbl_course_details` ADD `course_srchtags` JSON NULL AFTER `course_congrats`;
DROP TABLE ` tbl_courses_tags`;

UPDATE `tbl_certificate_templates` SET `certpl_body` = '{\r\n    \"heading\": \"Certificate Of Completion\",\r\n    \"learner\": \"{learner-name}\",\r\n    \"trainer\": \"{teacher-name}\",\r\n    \"content_part_1\": \"This is to certify that\",\r\n    \"content_part_2\": \"has successfully completed \\\"{course-name}\\\" online course on {course-completed-date}.\",\r\n    \"certificate_number\": \"{certificate-number}\"\r\n}' WHERE `tbl_certificate_templates`.`certpl_id` = 2; UPDATE `tbl_certificate_templates` SET `certpl_body` = '{\r\n    \"heading\": \"شهادة إتمام\",\r\n    \"learner\": \"{learner-name}\",\r\n    \"trainer\": \"{teacher-name}\",\r\n    \"content_part_1\": \"هذا لتاكيد ان\",\r\n    \"content_part_2\": \"أكمل بنجاح الدورة التدريبية عبر الإنترنت \\\"{course-name}\\\" في {course-completed-date}.\",\r\n    \"certificate_number\": \"{certificate-number}\"\r\n}' WHERE `tbl_certificate_templates`.`certpl_id` = 4;

ALTER TABLE `tbl_course_progresses` ADD `crspro_status` TINYINT(1) NOT NULL AFTER `crspro_started`;
UPDATE `tbl_configurations` SET `conf_val` = 'TV-4.1.0.20220721' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';

ALTER TABLE `tbl_courses` ADD `course_active` TINYINT(1) NOT NULL AFTER `course_status`;

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('course_refund_update_email_to_learner', 1, 'Course Refund Request Status Update', 'Course Refund Request Processed', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Refund Request Update</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {username}</h3>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We have processed your refund request for the course named \"{course_title}\". After verification, the request has been marked as \"{request_status}\".<br><br>\r\n								Comment : {admin_comment}</p>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">For more details, please contact admin.</p>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n\r\n', '{username} Learner name<br>\r\n{course_title} Course title<br>\r\n{request_status} Approved or Declined<br>\r\n{admin_comment} Admin comments for decline<br>', 1, 0),
('course_refund_update_email_to_learner', 2, 'Course Refund Request Status Update', 'Course Refund Request Processed', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Refund Request Update</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {username}</h3>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We have processed your refund request for the course named \"{course_title}\". After verification, the request has been marked as \"{request_status}\".<br><br>\r\n								Comment : {admin_comment}</p>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">For more details, please contact admin.</p>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n\r\n', '{username} Learner name<br>\r\n{course_title} Course title<br>\r\n{request_status} Approved or Declined<br>\r\n{admin_comment} Admin comments for decline<br>', 1, 0);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('course_cancellation_request_email_to_admin', 1, 'Course Cancellation Request To Admin', 'Course Cancellation Requested By Learner at {website_name}', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0;\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Cancellation</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin </h3>\r\n                                <p style=\"font-size: 14px; line-height: 20px;color: #676767;\">A course cancellation request has been received from a learner. Please process the request and update the status. <br>Below are the complete details:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Title</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Price</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_price}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Progress</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{progress_percent}%</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Learner Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{learner_full_name}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Teacher Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{teacher_full_name}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Cancellation Reason/Comment</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{learner_comment}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n', '{course_title} Course Title<br>\r\n{course_price} Course Price<br>\r\n{progress_percent} Course Progress<br>\r\n{learner_full_name} Learner Name <br>\r\n{teacher_full_name} Teacher Name<br>\r\n{learner_comment} Teacher Comment<br>', 1, 0),
('course_cancellation_request_email_to_admin', 2, 'Course Cancellation Request To Admin', 'Course Cancellation Requested By Learner at {website_name}', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0;\">\r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Cancellation</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin </h3>\r\n                                <p style=\"font-size: 14px; line-height: 20px;color: #676767;\">A course cancellation request has been received from a learner. Please process the request and update the status. <br>Below are the complete details:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Title</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Price</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_price}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Progress</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{progress_percent}%</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Learner Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{learner_full_name}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Teacher Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{teacher_full_name}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Cancellation Reason/Comment</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{learner_comment}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n', '{course_title} Course Title<br>\r\n{course_price} Course Price<br>\r\n{progress_percent} Course Progress<br>\r\n{learner_full_name} Learner Name <br>\r\n{teacher_full_name} Teacher Name<br>\r\n{learner_comment} Teacher Comment<br>', 1, 0);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('course_booking_email_to_learner', 1, 'Course Booking Email To Learner', 'Course Booking Confirmed', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"> \r\n	<tbody> \r\n		<tr> \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"> \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"> \r\n					<tbody> \r\n						<tr> \r\n							<td style=\"padding:20px 0;\"> \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5> \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Booking Successful!</h2> </td> \r\n						</tr> \r\n					</tbody> \r\n				</table> </td> \r\n		</tr> \r\n		<tr> \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \"> \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"> \r\n					<tbody> \r\n						<tr> \r\n							<td style=\"padding:40px 0 60px;\"> \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {learner_name} </h3> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Congratulations!!<br />\r\n								 </p> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">You have successfully completed the booking process.</p> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Your booking for the course \"{course_title}\" is confirmed.</p> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\"><a href=\"{course_link}\" target=\"_blank\">Click here to start course learning</a><br />\r\n									 Thanks for booking with us!!</p> </td> \r\n						</tr> \r\n					</tbody> \r\n				</table> </td> \r\n		</tr> \r\n	</tbody>\r\n</table>', '{learner_name} Learner Name\r\n{course_title} Course Title\r\n{course_link} Course tutorial link\r\n{website_name} website name<br>\r\n{website_url}Website url\r\n', 1, 0),
('course_booking_email_to_learner', 2, 'Course Booking Email To Learner', 'Course Booking Confirmed', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"> \r\n	<tbody> \r\n		<tr> \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"> \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"> \r\n					<tbody> \r\n						<tr> \r\n							<td style=\"padding:20px 0;\"> \r\n								<h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5> \r\n								<h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Booking Successful!</h2> </td> \r\n						</tr> \r\n					</tbody> \r\n				</table> </td> \r\n		</tr> \r\n		<tr> \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \"> \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"> \r\n					<tbody> \r\n						<tr> \r\n							<td style=\"padding:40px 0 60px;\"> \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear {learner_name} </h3> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Congratulations!!<br />\r\n								 </p> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">You have successfully completed the booking process.</p> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Your booking for the course \"{course_title}\" is confirmed.</p> \r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\"><a href=\"{course_link}\" target=\"_blank\">Click here to start course learning</a><br />\r\n									 Thanks for booking with us!!</p> </td> \r\n						</tr> \r\n					</tbody> \r\n				</table> </td> \r\n		</tr> \r\n	</tbody>\r\n</table>', '{learner_name} Learner Name\r\n{course_title} Course Title\r\n{course_link} Course tutorial link\r\n{website_name} Website name<br>\r\n{website_url} Website url\r\n', 1, 0);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES
('course_booking_email_to_admin', 1, 'Course Booking Email To Admin', 'Course Booking Received', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0;\"> \r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Booked</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin </h3>\r\n                                <p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that a course booking has been received at {website_name}.</p>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below are the complete details of learner and the course:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Title</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Price</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_price}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Learner Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{learner_name}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Teacher Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{teacher_name}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>', '{course_title} Course Title<br>\r\n{course_price} Course Price<br>\r\n{learner_name} Learner Name<br>\r\n{teacher_name} Teacher Name<br>\r\n{website_name} Website name<br>\r\n{website_url} Website url<br>\r\n', 1, 0),
('course_booking_email_to_admin', 2, 'Course Booking Email To Admin', 'Course Booking Received', '<table width=\"600\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n    <tbody>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:20px 0;\"> \r\n                                <h5 style=\"margin: 0;padding: 0; text-transform: uppercase; font-size: 16px;font-weight: 500;color: #333;\"></h5>\r\n                                <h2 style=\"margin:8px 0 0;padding: 0; font-size:30px;font-weight: 700;color: {primary-color};text-align:center;\">Course Booked</h2>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">\r\n                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n                    <tbody>\r\n                        <tr>\r\n                            <td style=\"padding:40px 0 60px;\">\r\n                                <h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin </h3>\r\n                                <p style=\"font-size: 14px; line-height: 20px;color: #676767;\">We are glad to inform you that a course booking has been received at {website_name}.</p>\r\n								<p style=\"font-size: 14px; line-height: 20px;color: #676767;\">Below are the complete details of learner and the course:</p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Title</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_title}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Course Price</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{course_price}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Learner Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{learner_name}</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Teacher Name</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{teacher_name}</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n                            </td>\r\n                        </tr>\r\n                    </tbody>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>', '{course_title} Course Title<br>\r\n{course_price} Course Price<br>\r\n{learner_name} Learner Name<br>\r\n{teacher_name} Teacher Name<br>\r\n{website_name} Website name<br>\r\n{website_url} Website url<br>\r\n', 1, 0);
UPDATE `tbl_meeting_tools` SET `metool_settings` = '[{\"key\":\"api_key\",\"value\":\"KrK84VX6Q1WkFSTHe5tZ1Q\"},{\"key\":\"api_secret\",\"value\":\"cgrP5fwMf9czo0loqHomZDGAMAZuu71S7kvs\"},{\"key\":\"jwt_token\",\"value\":\"\"},{\"key\":\"license_count\",\"value\":\"\"}]' WHERE `tbl_meeting_tools`.`metool_code` = 'ZoomMeeting';
ALTER TABLE `tbl_zoom_users` ADD `zmusr_zoom_type` INT NOT NULL AFTER `zmusr_zoom_id`;
UPDATE `tbl_zoom_users` SET `zmusr_zoom_type` = '1';
INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_vars`, `etpl_status`, `etpl_quick_send`) VALUES ('license_alert', '1', '{meeting_tool} License Alert', '{meeting_tool} license alert {website_name}', '<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">    \r\n	<tbody>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee;\"><span style=\"font-size: 30px; font-weight: bold;\">License Alert</span></td>        \r\n		</tr>        \r\n		<tr>            \r\n			<td style=\"background:#fff;padding:0 40px; text-align:center; color:#999;vertical-align:top; border-bottom:1px solid #eee; \">                \r\n				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">                    \r\n					<tbody>                        \r\n						<tr>                            \r\n							<td style=\"padding:40px 0 60px;\">                                \r\n								<h3 style=\"margin: 0 0 10px;font-size: 24px; font-weight: 500; padding: 0;color: #333;text-align:center;\">Dear Admin</h3>                                \r\n								<p style=\"line-height: 20px;\"><span style=\"color: rgb(103, 103, 103); font-size: 14px;\">This is an update regarding sessions on the platform. Meeting tool licenses available on the platform are less than the classes scheduled simultaneously. Please find details of the classes below:</span></p>\r\n								<table style=\"border:1px solid #ddd; border-collapse:collapse; width:100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Start Time</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{start_time}</td>\r\n										</tr>                                      \r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">End Time</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{end_time}</td>\r\n										</tr>                                      \r\n										<tr>\r\n											<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"40%\">Scheduled Sessions&nbsp;</td>\r\n											<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"60%\">{session_count}</td>\r\n										</tr>  \r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>            </td>        \r\n		</tr>    \r\n	</tbody>\r\n</table>', '{start_time} class Start Time <br>{end_time} class End Time <br>{session_count} Total Scheduled Sessions count', '1', '0');
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

UPDATE `tbl_certificate_templates` SET certpl_deleted = NOW() WHERE certpl_code = 'course_evaluation_certificate';

UPDATE `tbl_language_labels` SET `label_caption` = 'Report Generated On {datetime}' WHERE `label_key` = 'LBL_REPORT_GENERATED_ON_{datetime}';
UPDATE `tbl_configurations` SET `conf_val` = 'TV-4.1.1.20220805' WHERE `tbl_configurations`.`conf_name` = 'CONF_YOCOACH_VERSION';
ALTER TABLE `tbl_course_progresses` CHANGE `crspro_started` `crspro_started` DATETIME NULL; 
UPDATE `tbl_course_progresses` SET `crspro_started` = NULL WHERE crspro_started = '0000-00-00 00:00:00';
