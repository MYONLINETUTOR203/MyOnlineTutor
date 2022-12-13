# MyOnlineTutor (YoCoach RV-3.0)

## TV-1.1.0.20220927

Features:

    Task-101486 - Question Categories | Admin
    Task-101487 - Certificate Management | Admin
    Task-101569 - Questions Listing | Admin
    Task-101618 - Question Bank Management Development
    Task-101915 - Quiz Creation
    Task-101916 - Quiz Settings
    Task-102194 - Quiz Listing Admin

## TV-1.2.0.20221014

Features:

    Task-101917 - Link Quiz with Lesson, Class
    Task-102468 - Quiz Solving
    Task-102624 - Quiz Reattempt Development
    Task-102647 - Certificate Generation & Download

Fixes:

    Bug-067657 - Sub admin is able to search teachers without permission in questions search
    Bug-067655 - Old questions are coming first in the admin question listing
    Bug-067653 - Show “NA” if the description and hint are not available in the question details
    Bug-067649 - The button text is coming “Save” instead of “Save & Next” in the first step of the quiz setup
    Bug-067644 - The dynamic parameter should be available for “quiz grades” in the edit certificate
    Bug-067637 - The currently selected step should be highlighted in the quiz setup
    Bug-067636 - Teacher based search should also work without autocomplete selection
    Bug-067630 - Quizzes menu is not coming if have only quizzes module permission
    Bug-067628 - A Fatal error is coming in quiz detail if settings are not added for the quiz
    Bug-067627 - Special characters are not rendering correctly in the quiz listing
    Bug-067625 - Quiz drafted and published process is not working correctly
    Bug-067623 - Full content is not getting saved in quiz setup for title, instructions, fail and pass message
    Bug-067621 - Table heading row of question listing is not coming in the quiz setup
    Bug-067618 - Deleted questions are also counted in the category/subcategory questions count
    Bug-067615 - All subcategories are getting inactive when inactive the main category
    Bug-067431 - Notices are coming instead of question options and answers
    Bug-067430 - Special characters are not rendering correctly in questions listing
    Bug-067429 - Full content is not getting saved in question setup for description, hints and options
    Bug-067428 - Wrong subcategory is getting saved in the question due to slow internet speed
    Bug-067427 - Teacher is able to save different option counts as per available options
    Bug-067424 - Teacher is unable to add/remove options when editing the question
    Bug-067374 - Teacher is unable to edit manual type question due to option field
    Bug-067373 - Add "Restore to default" functionality for evaluation certificate
    Bug-067372 - Fatal error is coming when “save and preview” the evaluation certificate
    Bug-067281 - Show category identifier as well in the category/subcategory listing
    Bug-067279 - Inactive category is not coming in the category setup
    Bug-067278 - Old category is coming selected after changing the main category of subcategory
    Bug-067273 - Sr. No. should update after changing the display order of categories/subcategories
    Bug-067272 - Admin is able to inactive the question attached category/subcategory from setup form
    Bug-067271 - Labels are not coming in Arabic language in category setup
    Bug-067269 - Notice is coming on subcategory page if only category identifier is added


## TV-1.2.1.20221019

Features:

    Task-102648 - Quiz Review
    Task-102835 - Cancel Pending Quizzes

Fixes:

    Bug-068029 - The hint section should not come if the question hint is not available
    Bug-068028 - Learner is not getting an email when lesson attached quiz is removed
    Bug-067948 - Learner is unable to access the links available in quiz attached email
    Bug-067947 - Validity datetime is not coming correct as per learner time zone
    Bug-067946 - Learner name is coming instead of teacher name in quiz attached email
    Bug-067937 - The quiz validity value is not coming in the admin quiz detail
    Bug-067932 - Notices are coming when adding inactive category/subcategory
    Bug-067659 - Show the count of questions attached with a quiz in the teacher and admin quiz listing
    Bug-067645 - “+&” special characters are not accepted in the certificate content
    Bug-067634 - No. of questions count is not coming correct in quiz detail
    Bug-067624 - Question display order change process is not smooth in the quiz
    Bug-067621 - Table heading row of question listing is not coming in the quiz setup
    Bug-067429 - Full content is not getting saved in question setup for description, hints and options
    Bug-067281 - Show category identifier as well in the category/subcategory listing


## TV-1.2.2.20221103

Features:

    Task-103135 - Add Questions under Quiz

Fixes:

    #068166 - Quiz instructions should display properly everywhere
    #068160 - Show the no. of attempts(5/5) on the user quiz page and quiz review page
    #068158 - Show "valid till" value in quiz detail on teacher panel
    #068156 - Show lesson and class details with which the quiz is attached in related emails
    #068146 - Tutor and certificate values are not properly align when preview Arabic evaluation certificate
    #068145 - Success message is not relevant when review the quiz
    #068144 - Error is coming sometimes when opening certificate page
    #068143 - Package link is not coming correct on evaluation certificate page
    #068124 - Multiple answers choice question marks calculation is not correct
    #068121 - The quiz processing bar color is coming little bit green if 0% quiz attempted
    #068103 - The quiz is getting attached to the learner who cancelled the class earlier
    #068102 - The fatal error is coming when attaching a quiz with a class which is not booked yet
    #068068 - Color scheme is not coming correct on quiz review page
    #068067 - Teacher is unable to view the completed quiz of the class
    #068062 - Quiz review page questions are not coming correctly every time this page opened
    #068060 - Warnings are coming when the teacher/learner opens the quiz review page
    #068034 - A quiz with no time limit is getting completed if the learner refreshes the page
    #068033 - The teacher profile link is coming on the quiz detail on the certificate detail page
    #068031 - “Page not found” is coming when teacher clicks on quiz link in the quiz completion email
    #068030 - The quiz timer got stop after the quiz completion confirmation alert is coming
    #067624 - Question display order change process is not smooth in the quiz
    #067429 - Full content is not getting saved in question setup for description, hints and options

## TV-1.3.0.20221109

Features:

    Task-103291 - Manual Quiz Review
    Task-103337 - Courses Module Merging

Updates: N/A

Fixes: N/A

## TV-1.4.0.20221124

Features:

    Task-102494 - Two Factor Authentication
    Task-103483 - Quiz Linking with Course

Updates: N/A

Fixes:

    #068620 - Show alert message before ending the quiz so that learner can save his quiz answers
    #068619 - There is no option to come back to attach question listing without saving the question
    #068618 - Quiz time spent is not coming if the quiz has no duration
    #068617 - Stop processing loader when changing category values in the search filter
    #068615 - Teachers have no option to add comments for the answers in case of non-graded quiz
    #068612 - Background image of course completion and quiz evaluation certificates should be different
    #068607 - Show help text that once the certificate is downloaded then he can not retake the quiz
    #068591 - Course preview video is coming by default on the newly added course
    #068551 - The non-timer in-progress quiz should get completed instead of cancelled
    #068544 - A fatal error is coming in the course refund requests page
    #068543 - Time should not come along with the course/quiz completion date
    #068542 - Course duration variable is not coming in replacement variables of course completion certificate
    #068541 - Teacher is unable to set course price as free
    #068539 - Question subcategories are not coming on the basis of category
    #068485 - Error message is not displaying when learner tries to retake quiz which certificate is downloaded
    #068484 - Show "NA" if the manual question answer is not provided by the learner
    #068483 - Processing and success message are not coming when teacher submits question marks
    #068482 - The answer to the manual question is not coming in the format in which its added
    #068481 - Error message is not displaying when learner tries to start removed quiz
    #068480 - Quiz evaluation email is coming every time when teacher/learner clicks on the finish button in quiz review
    #068479 - Teacher is able to add manual question to auto-graded quiz and vice versa
    #068478 - The latest added questions should come on the top in quiz question listing
    #068409 - Next and back buttons not working correctly if teacher and learner review same quiz simultaneously
    #068408 - Multiple choice question number is coming in green color although some options are incorrect
    #068407 - Free trial lesson detail is coming incorrect in related emails
    #068166 - Quiz instructions should display properly everywhere
    #068160 - Show the no. of attempts(5/5) on the user quiz page and quiz review page
    #068124 - Multiple answers choice question marks calculation is not correct


## TV-1.4.1.20221213

Features: N/A

Updates: N/A

Fixes:

    #069045 - Help text should come under the 2-factor authentication setting
    #069043 - Two scroll bar are coming in the quiz attached popup
    #069042 - Show quiz-related details on the frontend course detail page
    #069041 - The teacher should not get a course quiz completion email
    #069040 - The processing loader is coming twice when uploading certificate background image
    #069038 - After course quiz timer completion result page is not opening directly after processing loader
    #069034 - User is unable to paste the OTP in the verification code field
    #069031 - Some of the content is not correct in the verification code popup
    #069027 - Resend OTP link should not come if timer is running and if timer is 0 then timer should not come
    #069025 - The cursor is not going to the next or previous field after submitting the incorrect code
    #069023 - 2-factor authentication is not working if the user doing login from the dashboard pages
    #069019 - Search criteria are getting removed in teacher, courses and classes listing after login with 2-factor authentication
    #069018 - Unable to submit the code again if once submitted the incorrect code
    #069016 - No error is coming if submit special characters in the verification code
    #069013 - Code field size is decreasing if submit invalid code and then delete the entered code
    #069011 - Nothing is happening when clicking on validate button without entering the code
    #069007 - "Question not found" error is coming if reviewing the same/different quiz in multiple tabs
    #069001 - Show the certificate type and which quiz is attached to a course in the admin panel
    #069000 - Simplify the quiz attachment to the course process
    #068997 - The next button should come on the last lecture video if the quiz is available after that
    #068995 - There are some differences in all certificates
    #068992 - Content under the course evaluation certificate is not relevant to the certificate
    #068990 - Change the message as now quiz attached question can not be deleted
    #068989 - The quiz section is remains active if the learner goes to a lecture using the previous button
    #068988 - "Valid till" and "Offer certificate" values are coming on the course attached quiz
    #068986 - Course completion and evaluation certificates content is not coming in Arabic language
    #068985 - "Reset to default" content is not coming correct of course evaluation certificate
    #068984 - Due to cache, updated content is not coming in the certificate preview
    #068983 - When clicking on corners of the quiz section, the quiz is not getting open
    #068982 - Question not found error is coming if press multiple clicks on next and back buttons
    #068981 - Search form UI is distorted when coming back to quiz question attach listing
    #068980 - Success message should be more accurate when teacher provide marks of manual question
    #068979 - Notices are coming if reviewing the quiz which is reattempted
    #068977 - Sometimes fatal error is coming when downloading the quiz certificate
    #068972 - The cross(x) icon is coming over the user profile image in quiz header
    #068926 - Cross(x) icon is coming which is not clickable when opens the quiz on course learning page
    #068925 - Download certificate option is not coming on course if quiz "offer certificate" setting is no
    #068924 - The "offer certificate" setting is not updating correctly
    #068894 - Common "attach" button should not come as multiple quizzes attachment is not allowed
    #068893 - "Type" filter should not come in search section if only auto-graded quizzes will come
    #068892 - Teacher is unable to change the quiz attached to the course after saving the settings
    #068891 - "Offer certificate" setting is not coming if the course completion certificate is inactive
    #068890 - Course evaluation certificate option is coming although its inactive from admin panel
    #068889 - Quiz total marks are not updated if the question marks updated
    #068620 - Show alert message before ending the quiz so that learner can save his quiz answers
    #068618 - Quiz time spent is not coming if the quiz has no duration
    #068478 - The latest added questions should come on the top in quiz question listing
    #068407 - Free trial lesson detail is coming incorrect in related emails
