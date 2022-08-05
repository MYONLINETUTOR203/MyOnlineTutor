# YoCoach V4 (YoCoach RV-3.0)

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
