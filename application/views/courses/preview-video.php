<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="facebox-panel">
    <div class="facebox-panel__head padding-bottom-6">
        <h5><?php echo $course['course_title'] ?></h5>
    </div>
    <div class="facebox-panel__body padding-0">
        <div class="preview-video ratio ratio--16by9">
            <div id="YTPlayerJs"></div>
        </div>
    </div>
</div>
<?php $videoId = YouTube::getVideoId($course['course_preview_video']); ?>
<script>
    $(document).ready(function() {
        new YT.Player('YTPlayerJs', {
            width: '100%',
            height: '100%',
            videoId: "<?php echo $videoId ?>",
            playerVars: {'autoplay': 1}
        });
    });
</script>