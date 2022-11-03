<<<<<<< HEAD
# MyOnlineTutor (YoCoach RV-3.0)

=======
# YoCoach V4 (YoCoach RV-3.0)

## TV-4.1.7.20221028

Features: N/A

Updates: N/A
>>>>>>> develop-course-quiz

## TV-1.1.0.20220927

Features:

    Task-101486 - Question Categories | Admin
    Task-101487 - Certificate Management | Admin
    Task-101569 - Questions Listing | Admin
    Task-101618 - Question Bank Management Development
    Task-101915 - Quiz Creation
    Task-101916 - Quiz Settings
    Task-102194 - Quiz Listing Admin

<<<<<<< HEAD

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
=======
	#068114	The courses section design should be the same as the classes on the teacher detail page
	#068101	Multiple processing loaders are coming on the admin dashboard
	#068073	Some of the images are not coming properly in frontend courses listing
	#068040	Stop to show the loader when selecting a category
	#068038	Processing loader is coming twice when learner/teacher reset courses search form
	#068002	On course approval request submission not getting any response.
	#067866	Teacher is unable to remove resources attached to the lecture
	#067843	Deleted/Inactive languages are also coming in the manage courses language filter
	#067841	The category/subcategory name is not coming if only its identifier is added
	#067835	All subcategories are getting inactive when inactive the main category
	#067833	After filtration or pagination change, course page should redirect to the top of the listing
	#067832	In course listing filter criteria and pagination got reset after login from popup
	#067831	If entering 3.33 price in "price till" field then courses with 3.33 price are not coming
	#067813	Respective courses are not coming after clicking on course count in category listing
	#067811	Notices are coming when adding inactive category
	#067782	Same label is used for “courses from teacher” section as used in course detail page
	#067776	Add the “View all” link in the homepage "popular courses" section
	#067774	Learner is unable to mark course favorites from teacher detail page course slider
	#067770	Admin is able to deactivate course attached language from setup form
	#067769	Learner is unable to add lecture notes using “Add new note” button present in listing
	#067768	Warning is coming in course request detail > course tags
	#067766	Fatal error is coming in admin dashboard > top course categories section
	#067199	In RTL mode, Cursor is appearing from the left side in the description field
	#067184	The "Select" text is not coming in the Arabic language
	#067139	List content is not coming properly in the course preview and course learning page
	#067136	Course duration and lecture content are not coming correct in the Arabic language
	#067129	More courses from tutor slider is not working correctly in Arabic language
	#067038	Deactivate/delete course category/subcategory/language should be handled properly for drafted and submitted for approval courses
	#067020	Certificate restored default content should be more accurate
	#066782	List content is not aligned properly from left hand side
	#066770	Added note is not coming when course is completed
	#066637	Show course all the details in course approval request detail
	#066620	Show course completion percentage on the learner course listing
	#066563	Teacher details should be like course detail page on course learning page
	#066502	Cursor focused to description field when adding/editing course/lecture
	#066467	The filter is not fixed on the top when Scrolling the page
	#066272	Course price is coming 0 when entering rupee value less than 79(conversion value)
	#066221	If content is long then its hiding behind the letter counter
	#066142	Some Content are out of the screen box in Safari browser
	#066029	Instead of learner order id course name should come in the wallet transaction
	#066026	If enter special character in course name then 404 page is coming on opening its detail page
	#065996	Preview Video Thumbnail is not able to see and user not able to play the video



## TV-4.1.6.20221007

Enhancements:

	Task-102286 - Notifications and Alerts Module Merging
	Task-102425 - Categories Code Update

Fixes:

	Bug-067324 - Search button in all search filter forms are not working.
	Bug-067186 - Background image help text is not coming properly in Arabic language
	Bug-067185 - Delete buttons should come on the left side in Arabic language
	Bug-067184 - The "Select" text is not coming in the Arabic language
	Bug-067182 - “Searching” label is not available to change text for Arabic language
	Bug-067159 - The note is not coming as RTL when changing status of course refund request
	Bug-067157 - Course request details are not coming as per RTL mode in Manage course approval requests
	Bug-067156 - "Manage course approval request" search form is not coming as per RTL mode
	Bug-067154 - Course review details are not coming as per the RTL mode
	Bug-067149 - Notice is coming when open subcategory page in RTL mode
	Bug-067148 - Clear button text is not coming in the Arabic language
	Bug-067143 - Fatal error is coming when submitting course for approval in Arabic language
	Bug-067137 - Social media icons are not aligned properly on the certificate view page
	Bug-067134 - Separator line is not coming in the arabic language on video preview popup
	Bug-067133 - The label is hiding behind the other section on course learning and preview pages
	Bug-067130 - The separator is not coming in the correct place in Arabic language
	Bug-067129 - More courses from tutor slider is not working correctly in Arabic language
	Bug-067019 - Tag search is case sensitive in the frontend course listing
	Bug-067018 - Manage courses and meta tag courses are not matching
	Bug-067016 - Same detail page is opening for both courses if course name is same
	Bug-067014 - The same label is used for “course refund request detail” and “course request detail”
	Bug-067011 - Course completed value not coming properly on learner course listing page
	Bug-066931 - Course, class and teacher listing filters are fluctuating
	Bug-066930 - Fatal error is coming when updating lecture notes
	Bug-066829 - The main menu "Manage courses" label should be different
	Bug-066827 - Title and description should be dynamic when share certificate on social sites
	Bug-066825 - My messages page heading is coming incorrect
	Bug-066794 - The error message is not relevant if uploading course resources above 50MB
	Bug-066792 - Lecture complete progress is not coming on the course preview page after completing the lecture video
	Bug-066784 - Unable to proceed to 2nd step on course setup for a course on Firefox 
	Bug-066774 - Lectures are not adding in the correct order
	Bug-066770 - Added note is not coming when course is completed
	Bug-066724 - In RTL mode, Cross icon Overlap the Test in Setting while adding course tags
	Bug-066723 - In RTL Mode, icons to add Lecture and dropdown icon is not appearing on the Left side
	Bug-066684 - In RTL mode, course certificate page is not rendering properly
	Bug-066637 - Show course all the details in course approval request detail
	Bug-066620 - Show course completion percentage on the learner course listing
	Bug-066616 - Show teacher courses on the teacher profile page
	Bug-066608 - Some of the certificate logo are coming stretched on certificate pdf
	Bug-066571 - Course attached category, subcategory and language should not be deleted and deactivated
	Bug-066556 - Show the decline comment on refund request detail
	Bug-066542 - While Editing the user is not able to know on which page he is currently
	Bug-066536 - When left panel is open then user is able to access Profile Switch option
	Bug-066486 - All the free preview videos are coming preview video popup
	Bug-066320 - Show video icon in the course listing as its coming on the course detail page
	Bug-066309 - Text should be different when the course is marked as favorite
	Bug-066221 - If content is long then its hiding behind the letter counter
	Bug-066135 - View Course Details Video is not able to play in Safari browser
	Bug-066133 - Lecture Youtube Video does not display on Safari Browser
	Bug-065956 - Courses section should be available in the homepage 
	Bug-065539 - Warnings are coming when adding course without profile completion
	Bug-065478 - Fatal error is coming when searching with alphabetic value in price range


## TV-4.1.5.20220916

Fixes:

	Bug-066820 - Invalid video link is coming for some YouTube video URL
	Bug-066795 - Learner is able to access other learner lecture info using loadLecture('390');
	Bug-066788 - The progress bar is not changing when teacher completes the lecture on the preview page
	Bug-066787 - Notes getting added in DB by changing the course and lecture id 
	Bug-066785 - Courses count is not matching in admin dashboard and course listing
	Bug-066679 - In Search, the keyword textbox placeholder is displayed 2 times in the Arabic language
	Bug-066656 - Errors are coming when saving invalid YouTube video id in lecture media
	Bug-066655 - Teacher is able to add/edit lectures media in other teacher courses
	Bug-066645 - Learner is able to purchase inactive course if checkout popup is open
	Bug-066635 - Help text is not coming under certificate on the certificate view page
	Bug-066631 - Tutor and certificate number value not coming as RTL in Arabic certificate preview
	Bug-066627 - Preview option should not come on deleted course request
	Bug-066621 - In course order detail, admin commission should come and course status should not come
	Bug-066618 - The learner is able to cancel the course although payment is done to the teacher
	Bug-066615 - Sub categories are not getting reset after clearing the search
	Bug-066613 - Course popularity sorting should work on the basis of enrolled students, reviews and rating 
	Bug-066610 - Learner is able to access course resources page
	Bug-066597 - Show course name on the course review detail
	Bug-066596 - Remove welcome and congratulations note from admin panel
	Bug-066591 - Unpaid course order is not cancelling after same 
	Bug-066578 - Refund approved courses should come after clicking on cancelled courses tile
	Bug-066573 - The latest courses should come on top in meta tag
	Bug-066572 - Admin is able to add main category which has subcategories as subcategory of other category
	Bug-066571 - Course attached category, subcategory and language should not be deleted and deactivated
	Bug-066568 - "Go back to course listing" button should come on course completion page
	Bug-066565 - The tutor related label should come above the tutor information
	Bug-066558 - Course name should come instead of the "Course Detail" label
	Bug-066557 - Courses and classes should come in the dropdown menu on the learning page
	Bug-066556 - Show the decline comment on refund request detail
	Bug-066555 - Course total amount should come in the course order detail
	Bug-066554 - Pending status should not be there on course order filter > status
	Bug-066553 - Discount and net amount should also come in the course orders listing
	Bug-066503 - Course/Lecture duration is not coming if enter only numbers in lecture description
	Bug-066483 - Replace "View Course request" link with the button in the email
	Bug-066479 - Teacher search should also work without autocomplete selection
	Bug-066468 - Course duration is not coming on the certificate view page
	Bug-066460 - When user click on another Lecture then in same tab course Lecture, Lecture Details is appearing
	Bug-066307 - Remove status column from manage courses listing
	Bug-066305 - Latest resources should come on the top and no need of the date in lecture resources
	Bug-066211 - Provide “restore to default” functionality for certificates
	Bug-065999 - Rearrange sequence icon is not displaying on Ipad, tablets, and Android device
	Bug-065967 - Review title is not coming in the reviews listing
	Bug-065306 - Word limit validation is not working for the course description


## TV-4.1.4.20220907


Fixes:

	Bug-066545 - List Navigation Bar is displayed in 2 lines and the bottom page overlaps the bar when the of courses increases
	Bug-066538 - Teacher is able to add/edit lectures in other teacher courses
	Bug-066537 - Teacher is able to add/edit sections in other teacher courses
	Bug-066535 - Fatal error is coming when opening manage approval requests
	Bug-066534 - Error is coming when submitting course for approval
	Bug-066517 - Teacher is able to delete other teacher resources using remove('88');
	Bug-066515 - Teacher is able to remove other teacher lecture resources using removeLectureResrc('686', '363');
	Bug-066513 - Teacher is able to attach resources to other teacher lectures using getResources('363');
	Bug-066512 - Teacher is able to delete other teacher lecture using removeLecture('294', '492');
	Bug-066511 - Teacher is able to delete other teacher section using removeSection('201');
	Bug-066510 - Learner is able to access other learner notes using notesForm('39');
	Bug-066497 - First lecture is not opening when reorder and delete sections and lectures
	Bug-066496 - After deleting the lecture other section lectures numbering is incorrect
	Bug-066494 - Lectures numbering is not coming correct if sections order changed
	Bug-066486 - All the free preview videos are coming preview video popup
	Bug-066484 - Popup is coming in small size when giving rating a to the course
	Bug-066481 - Extra space is coming on section when adding lecture and cancels the form
	Bug-066475 - In RTL mode, course learning page is not rendering properly
	Bug-066474 - “Content not found” is coming when downloading certificate
	Bug-066473 - Deleted lectures time is coming in the course duration
	Bug-066468 - Course duration is not coming on the certificate view page
	Bug-066466 - Video Navigation Icon is overlapping the Left Panel in android device
	Bug-066462 - When the Student Open Progress bar Pop up window on the course preview page the background is accessible to the user to mark the lecture as complete and incomplete
	Bug-066456 - Notices are coming if enter integer value other than 1 & 2 in URL
	Bug-066454 - Inactive courses are coming in the sitemap
	Bug-066453 - “Add from library” button is not aligned with file upload field
	Bug-066452 - Status "NA" and comment are coming in course refund request detail
	Bug-066294 - Course subtitle is not coming complete in the listing
	Bug-066247 - Tag based filter is not working correctly in frontend course listing
	Bug-066221 - If content is long then its hiding behind the letter counter
	Bug-066125 - Mark Favorite Heart Icon does not display on the list of More Course from teacher
	Bug-066124 - Mark Favorite Heart Icon display over the Filter section
	Bug-066025 - Functionality is not working correctly when opening the learning page after the completion
	Bug-065982 - Heart Icon is not filled with the color when activate it
	Bug-065968 - Show reviews count on the right side of line
	Bug-065609 - Pagination is not working on course refund request page
	Bug-065552 - Teacher should not be able to enroll on his own courses
	Bug-065539 - Warnings are coming when adding course without profile completion
	Bug-065478 - Fatal error is coming when searching with alphabetic value in price range
	Bug-065457 - Pagination is not working on course approval requests page
	Bug-065455 - Admin permission is not there for course approval requests module

## TV-4.1.3.20220831


Fixes:

	Bug-066356 - Remove welcome and congratulations fields from course settings form
	Bug-066050 - If teacher have no biography then its heading should not come
	Bug-066247 - Tag based filter is not working correctly in frontend course listing
	Bug-066272 - Course price is coming 0 when entering rupee value less than 79(conversion value)
	Bug-066249 - Course price is getting changed after saving it
	Bug-066355 - Teacher is unable to upload the resource files 
	Bug-066354 - Search clear functionality is not working in courses listing
	Bug-065536 - Course purchase related emails are not going to anyone [Done]
	Bug-065968 - Show reviews count on the right side of line [Done]
	Bug-066244 - Fatal Error display in view popup window in deleted teacher request [Working fine]
	Bug-066044 - Some of the special characters are not rendering correctly [Working fine]
	Bug-066306 - If course details changed then in the old course approval request it should not change
	Bug-066264 - File size upload validation is available but not mentioned in the help text
	Bug-065824 - Record which is deleted will not able to add as new record 
	Bug-066318 - Youtube videos not playing on secure urls. 
	Bug-066502 - Cursor focused to description field when adding/editing course/lecture
	Bug-066500 - Manage courses items are not coming as per pagination
	Bug-066495 - In course preview, sections are not coming as per display order
	Bug-066477 - Certificate background image is not coming if the certificate logo is not available
	Bug-066403 - The {certificate-number} variable is missing in replacement vars
	Bug-066469 - Teacher ratings and reviews are incorrect on the certificate view page
	Bug-066470 - Teacher profile image is not coming on the certificate view page
	Bug-066472 - Course and teacher reviews links are not working correctly on the certificate view page 
	Bug-066471 - 404 page is opening when clicking on course name

## TV-4.1.2.20220822


Tasks:

	Task-100708 - Certificate generation with TCPDF 
	
Fixes:

	Bug-066061 - Download and Deduction spellings are incorrect
	Bug-066055 - Favorite spelling is different in menu and page
	Bug-066054 - Decimal value should not be allowed for “Course Cancellation Duration” setting
	Bug-066047 - Add asterisk with comment when decline the course and course refund request
	Bug-066050 - If teacher have no biography then its heading should not come
	Bug-066039 - "No class found" is coming if no record found in course listing
	Bug-066055 - Favorite spelling is different in menu and page
	Bug-066054 - Decimal value should not be allowed for “Course Cancellation Duration” setting
	Bug-066047 - Add asterisk with comment when decline the course and course refund request
	Bug-066050 - If teacher have no biography then its heading should not come
	Bug-066039 - "No class found" is coming if no record found in course listing
	Bug-066027 - Course description is not rendering correctly on course refund request detail 
	Bug-066060 - Time is coming without minutes. Add “m” or “mins” text
	Bug-066010 - Section and Lecture listing section is not displaying in tablet, Ipad and android device 
	Bug-066057 - Cross icon remains on search field after clear the notes search
	Bug-066042 - Success message should be relevant when favorite/unfavorite courses
	Bug-066045 - More filters(teacher, status, date etc) should be there to filter down the course approval requests
	Bug-066041 - There should be more filters in the course refund request page
	Bug-065906 - Course unpaid bank transfer order is not getting canceled
	Bug-066020 - Fatal error is coming if course currency is inactive 
	Bug-066059 - Textarea should come instead of text field in notes setup 
	Bug-066052 - Some of the details are not visible properly 
	Bug-066038 - No action is performed when clicking on reviews 
	Bug-066028 - Unpaid course order status is coming completed 
	Bug-066049 - After decline the course request, status is coming active and also declined comment is coming 
	Bug-065949 - Commission from courses is not increasing in the graph  
	Bug-066053 - More filters should be there on learner courses page
	Bug-066030 - Teacher payment is not coming complete although there is no way to set commission for courses 
	Bug-065948 - Admin earning and teacher paid amount is incorrect
	Bug-066024 - 404 page is coming when downloading the course certificate
	Bug-066056 - Data is not aligned properly if the learner has no last name
	Bug-066036 - Teacher courses and students count is not coming correct in the course detail page
	Bug-066023 - Course duration is coming wrong in the course detail
	Bug-066021 - Teacher is able to add course with inactive category, subcategory, language and currency
	Bug-065476 - Fatal error is coming when clicking on “Save & Preview” button
	Bug-065968 - Show reviews count on the right side of line
	Bug-066009 - Course Complete Progress bar Content is not displaying in the Ipad, Android Device and tablet
	Bug-066044 - Some of the special characters are not rendering correctly
	Bug-065824 - Record which is deleted will not able to add as new record
	Bug-065899 - Published courses are not coming in the sitemap
	Bug-065961 - Reviews numbering is not coming correct on second page of pagination
	Bug-065372 - The lectures count is not coming correct
	Bug-065926 - Sub Category in Search Filter is missing
	Bug-066121 - Rating & Reviews Section is not displaying in Android mobile device
	Bug-066122 - Full section is missing in the mobile and Ipad browser 
	Bug-066124 - Mark Favorite Heart Icon display over the Filter section 
	Bug-066138 - The teacher is able to preview the other teacher's courses
	Bug-066156 - Orders menu is not coming if have only course orders module permission
	Bug-066159 - Courses menu is not coming if have only course languages module permission 
	Bug-066161 - Status filter is not working for pending status in learner courses
	Bug-066162 - If there is no review on course then “1” is coming on detail page
	Bug-066165 - Save and Next button Square border line highlight with the Primary color.
	Bug-066166 - Save and Next button Square border line highlight with the Primary color 
	Bug-066177 - Course fee is not coming in commission history
	Bug-066179 - Inactive course is coming in the course quick search
	Bug-066201 - Error message is not coming on changing categories display order
	Bug-066204 - Icons should be relevant in course request and course refund request detail
	Bug-066206 - Page heading is not aligned with search and table section
	Bug-066208 - Left sidebar menu should be active if edit certificate page is open 
	Bug-066218 - Commission deduction from teacher payment is not correct
	Bug-066219 - Instead of "Select" option, "Root category" option should come in category setup 
	Bug-066225 - Latest courses should come on the top in manage courses
	Bug-066228 - Course published date should be when its approved
	Bug-066230 - Heading row should not come above table headings as per new UI
	Bug-066231 - Mention the values on which basis keyword search is working
	Bug-066269 - Error message should be relevant when submitting form without selecting any file
	Bug-066265 - Currencies are not coming as per the display order set by admin
	Bug-066281 - Language Label is not added in the admin panel for "Add a new note" in the note section
	Bug-066285 - After cancel or close the lecture form, not able to change display order of lectures 
	Bug-066287 - Course preview button should come after course section added
	Bug-066266 - The "Submit for approval" button should not come if all steps are not completed
	Bug-066267 - Mention the lecture resource extensions which are allowed to upload
	Bug-066279 - Course title should come in the form when edit the course
	Bug-066282 - If Intended learners description not saved then stop its display order change
	Bug-065611 - Completed course note is coming although course status is pending
	Bug-065990 - Status Wise Course Status Text is not displaying in a different Color
	Bug-066289 - Language Label is not added in the admin panel for "Search Categories" Place holder in the filter
	Bug-066301 - Popularity should be the default sorting of courses
	Bug-066293 - Inactive teacher courses are coming in search suggestions
	Bug-066196 - Drafted, "submitted for approval" courses are coming in the course meta tags 
	Bug-066298 - Category and subcategory should be clickable in listing, detail and more courses card
	Bug-066264 - File size upload validation is available but not mentioned in the help text
	Bug-066262 - Same Label is used in the Price and setting section while adding the Course
	Bug-066202 - Button should be there instead of link in course purchase email
	Bug-066318 - Youtube videos not playing on secure urls.
	Bug-066308 - Frontend transparent logo should come on the certificate
	Bug-066222 - Certificate setting should not come if certificate is inactive
	Bug-066209 - Line height is not proper for the certificate content
	Bug-066153 - Explore Languages drop-down Text should also display in color on mouse hover as per theme setting
	Bug-066141 - Next and Previous button is not displaying properly in safari browser
	Bug-066046 - In teacher/learner profile, some fields are not aligned properly
	Bug-066040 - Filters are coming over the courses listing in the frontend
	Bug-066037 - Tabs are not visible when clicking on previous tab or scroll up
	Bug-066012 - Text overlaps the Tick Icon in the more filtered language sections
	Bug-066008 - Teaches Icon touching to the Text field
	Bug-066006 - If attachment name is bigger then Content does not display properly
	Bug-066000 - Icon Allignment is not proper in Ipad, tablet and Android device
	Bug-065999 - Rearrange sequence icon is not displaying on Ipad, tablets, and Android device
	Bug-065990 - Status Wise Course Status Text is not displaying in a different Color
	Bug-065988 - Content are displaying out of the boundary in firefox browser
	Bug-065987 - Overview Description is displaying out of the box
	Bug-065982 - Heart Icon is not filled with the color when activate it
	Bug-065979 - UI disturbed, Content display out of the Box
	Bug-065977 - Title text Overlap the Certificate Text
	Bug-065967 - Review title is not coming in the reviews listing
	Bug-065941 - When low resolution then Rating point touches the Star logo
	Bug-065939 - Content is not displaying properly when Word is bigger in low resolutions
	Bug-065937 - Section title is touching to its border line in low resolutions
	Bug-065934 - In Low-Resolution video not coming in the center position and its play button not displaying properly
	Bug-065828 - Popup window Cross icon overlap the Toast message

## TV-4.1.1.20220805

Fixes:

	Bug-065594 - Course refund request datetime is not coming in the admin timezone
	Bug-065592 - Course order datetime is not coming in the admin timezone
	Bug-065595 - Teacher name is coming instead of learner name in course refund request
	Bug-065593 - Keyword search is not working on the basis of order id
	Bug-065590 - Pagination is not working on the course orders page
	Bug-065463 - There should be option to change status of the course in the listing
	Bug-065559 - Course resources count is not coming correct
	Bug-065609 - Pagination is not working on course refund request page 
	Bug-065608 - Admin permission is not there for course orders module
	Bug-065611 - Completed course note is coming although course status is pending
	Bug-065612 - Course status remains pending if course cancellation approved directly or by admin
	Bug-065459 - Data is not aligned properly if the teacher has no last name
	Bug-065610 - User is not getting "course refund request" status change email
	Bug-065589 - Admin is not getting course cancellation request email
	Bug-065373 - For gif resource file, jpg file type icon is coming
	Bug-065536 - Course purchase related emails are not going to anyone
	Bug-065867 - Fatal error is coming when opening courses meta tags tab
	Bug-065848 - Left menu, List Icon are not same as HTML design Page
	Bug-065830 - If Sub-admin doesn't have permission to write then he is not getting an Error message when trying to change the Status 
	Bug-065831 - If Sub-admin doesn't have permission to write then he is not getting an Error message when trying to change the list of the arrangements
	Bug-065826 - After the Rearrangement of the list, Sr-no is not updating in real-time 
	Bug-065823 - The Title of the Pop up is wrong on other sub-tab page
	Bug-065822 - If User try to Switch sub tab without saving General Data while add new then display InValid Toast Message
	Bug-065785 - Notice is coming when changing course review status
	Bug-065784 - Teacher reviews menu is coming active when the course reviews page is open
	Bug-065808 - Error message should be meaningful when marking already favorite course as favorite
	Bug-065800 - Course category/subcategory should come instead of subtitle in favorite courses listing
	Bug-065801 - Learner courses display order should be latest pending, inprogress, completed and cancelled
	Bug-065797 - Favorite courses menu is not active when its page is open
	Bug-065798 - Favorite courses menu is not coming when teacher switch to learner profile 
	Bug-065789 - The learner is able to preview the teacher's courses 
	Bug-065780 - When changing lectures then open tab is not coming correct
	Bug-065794 - Sections are not coming as per the display order in the learning page
	Bug-065792 - Lecture resource icos is not coming correct on the learning page
	Bug-065782 - Unable to add section if existing section edit is open
	Bug-065775 - Teacher got paid for the course on which cancellation request is created
	Bug-065774 - Teacher got paid before the cancellation time ends
	Bug-065788 - Course rating is coming if its status is changed to pending or declined from approved
	Bug-065556 - Course evaluation certificate should not come
	Bug-065913 - Deleted course languages are coming in the course setup
	Bug-065912 - Deleted course languages are coming in the course language filter
	Bug-065814 - “Enroll now” button is not available on unpurchased courses in the favorite courses listing
	Bug-065818 - If section have no lecture then its counted in count but its detail is not coming
	Bug-065899 - Published courses are not coming in the sitemap
	Bug-065908 - Teacher is not coming in teacher performance report
	Bug-065911 - Courses count is not coming correct in teacher performance report
	Bug-065938 - When No lecture is added then Extra space display in the section
	Bug-065929 - Full content is not displaying properly when mouse hover on the half text
	Bug-065926 - Sub Category in Search Filter is missing
	Bug-065931 - The certificate is not displayed on the page
	Bug-065953 - Report generated on date is not coming in dashboard, sales and settlement reports
	Bug-065952 - Correct page is not opening when clicking on cancelled courses tile
	Bug-065951 - Course orders page is not opening when clicking on course revenue tile 
	Bug-065959 - Lecture preview video is playing in backend after closing the video popup 
	Bug-065958 - Course video is playing in backend after closing the video popup
	Bug-065909 - Section Title and description Character limit is not same as HTML design page
	Bug-065907 - Course Title and Subtitle Character limit is not same as HTML design page
	Bug-065918 - Course complete Content is missing as per HTML design page
	Bug-065955 - Months are not coming in sequence in admin commission graph 
	Bug-065990 - Status Wise Course Status Text is not displaying in a different Color 
	Bug-065985 - If the Course is for free then display Free Text in place of Prise Text
	Bug-065980 - White Lable is displayed on the Course List page when there is no certification available
	Bug-065961 - Reviews numbering is not coming correct on second page of pagination
	Bug-065954 - Option is not available to set the commission for courses 
## TV-4.1.0.20220721

Features:

	Task-99637 - Code Review Fixes - Certificate Module
	Task-99703 - Stats Management
	Task-99704 - SEO Management
	Task-99792 - Course Language Update

Fixes:

	Bug-065295 - Admin permission is not available for course categories module
	Bug-065303 - Fatal error is coming when setup course as free
	Bug-065375 - Remove "type" column from course categories listing
	Bug-065288 - Search and pagination should not be there on course categories page
	Bug-065298 - Inactive categories should come at the last in the listing
	Bug-065296 - Parent category should come selected when adding sub category from sub category page
	Bug-065289 - Add category option is not coming as per new theme
	Bug-065480 - 404 page is coming when clicking on “View Detail” button of some of the course
	Bug-065479 - Learner is unable to enroll on the course
	Bug-065457 - Pagination is not working on course approval requests page
	Bug-065287 - Course category menu should remain active when opening subcategories page
	Bug-065464 - Pagination is not available on the manage courses page
	Bug-065291 - Confirmation message is not coming when deleting course category
	Bug-065357 - Unable to uploaded docx extension file in course resources 
	Bug-065299 - When updating category then "added on" date is getting changed
	Bug-065304 - Fatal error is coming when adding alphabetic value in course price
	Bug-065310 - Teacher is unable to unset the subcategory of the course
	Bug-065311 - After changing the image or video old files are coming
	Bug-065347 - Only one file uploaded when uploading multiple files in course resources
	Bug-065302 - Admin is unable to change sub category to main category 
	Bug-065300 - Subcategories count is not decreasing after deleting the subcategory
	Bug-065290 - Current category is coming in parent option when editing category
	Bug-065305 - Word count is not decreasing if paste the more content than limit
	Bug-065372 - The lectures count is not coming correct 
	Bug-065348 - Course resource DateTime is not coming as per the teacher timezone
	Bug-065478 - Fatal error is coming when searching with alphabetic value in price range
	Bug-065475 - Admin permission is not there for manage certificate module
	Bug-065462 - Admin permission is not there for manage courses module
	Bug-065455 - Admin permission is not there for course approval requests module
	Bug-065443 - Admin is unable to add same category after deleting a category
	Bug-065444 - If add space before the text then able to add duplicate category
	Bug-065445 - Admin is able to add a duplicate name of the subcategory
	Bug-065446 - When deleting section then its lecture is not deleting
	Bug-065456 - Course approval requests datetime is not coming as per the admin timezone
	Bug-065458 - Course description is not rendering correctly when admin views the detail
	Bug-065461 - Blank page is also coming when clicking on preview option in manage courses
	Bug-065465 - Edit option should not come in manage courses actions
	Bug-065466 - Invalid request error is coming when viewing course detail which have no sub category
	Bug-065469 - Course description is not rendering correctly in course detail
	Bug-065477 - Fatal error is coming when opening courses page in arabic language
	Bug-065473 - Keyword search is not working on the basis of teacher's full name
	Bug-065452 - Course category/subcategory and title should come in the listing
	Bug-065309 - In course listing, subtitle is coming more prominent than title
	Bug-065301 - On subcategory page, main category name is not coming
	Bug-065454 - Add More filters on the basis of category, subcategory, free/paid
	Bug-065371 - Fatal error is coming if enter alphabetic value in edit course URL
	Bug-065374 - Linked courses should come in category listing
	Bug-065531 - Warnings are coming when triggering the cron
	Bug-065532 - Drafted and submitted for approval courses are coming in “more courses from teacher” section
	Bug-065534 - Payment history is coming twice for some of the courses
	Bug-065539 - Warnings are coming when adding course without profile completion
	Bug-065543 - Course sections are not coming as per the display order set by teacher 
	Bug-065548 - Unable to preview lecture video without refresh if once previewed it
	Bug-065549 - Course description is not coming on the course detail page
	Bug-065553 - Search is not working on the basis of teacher full name
	Bug-065555 - Language search should also work without autocomplete selection
	Bug-065557 - Notices are coming when updating payment method settings 
	Bug-065558 - Bank transfer payment method is not available
	Bug-065560 - Notice is coming on course checkout page for some of the courses
	Bug-065552 - Teacher should not be able to enroll on his own courses
	Bug-065563 - Learner is able to purchase same course multiple times
	Bug-065562 - New purchased course status is coming completed by default

## TV-4.0.0.20220706

Features:

	Task-96951 - Course Search
	Task-97084 - Course Detail Page
	Task-97454 - Course Booking by Learner
	Task-99007 - Preview Course for Admin
	Task-99141 - Course Listing Page Design Integration
	Task-99318 - Course Detail Page Design Integration
	Task-97743 - Notes & Material
	Task-97455 - Course Learning Page - Content Area
	Task-97808 - Course Completion Page
	Task-97809 - Course Certificate Page
	Task-98337 - Design Integration
	Task-98407 - Learner Course Pages design integration
	Task-98526 - Course Certificate Design Integration
	Task-97847 - Course Cancellation
	Task-95823 - Course Categories Management
	Task-95945 - Course Orders Listing in All Orders
	Task-95824 - Course Approval Requests
	Task-96006 - Course Listing for Admin
	Task-96109 - Certificate Templates Management
	Task-96122 - Refunds Cancellation Request
	Task-96269 - Create a Course(Tutor)
	Task-96198 - Resource Management in Tutor Dashboard
	Task-96270 - Create Course Curriculum
	Task-96804 - Course Pricing Settings and Content Creation
	Task-98164 - Design Integration Tutor Dashboard

Note:
	This repository is moved from YoCoach V2 repository on 06th July 2022
>>>>>>> develop-course-quiz
