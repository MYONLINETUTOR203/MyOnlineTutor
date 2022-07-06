<div class="layout--<?php echo $layoutDir; ?>">
    <div class="certificate certificateJs">
        <div class="certificate-media">
            <img src="<?php echo MyUtility::makeUrl('image', 'show', [Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE, 0, Afile::SIZE_LARGE], CONF_WEBROOT_FRONTEND) . '?time=' . time() ?>">
        </div>
        <div class="certificate-content">
            <h1 class="certificate-title contentHeadingJs"><?php echo $content['heading'] ?></h1>
            <div class="certificate-subtitle contentPart1Js">
                <?php echo $content['content_part_1'] ?>
            </div>
            <div class="certificate-author contentLearnerJs">
                <?php echo $content['learner'] ?>
            </div>
            <div class="certificate-meta contentPart2Js">
                <?php echo CommonHelper::renderHtml($content['content_part_2']) ?>
            </div>
            <div class="certificate-signs">
                <div class="certificate-signs__left">
                    <span> <?php echo Label::getLabel('LBL_TUTOR:'); ?> </span>
                    <div class="style-bold contentTrainerJs">
                        <?php echo $content['trainer'] ?>
                    </div>
                </div>
                <div class="certificate-signs__middle">
                    <div class="certificate-logo">
                        <img src="<?php echo FatCache::getCachedUrl(MyUtility::makeFullUrl('Image', 'show', [Afile::TYPE_FRONT_LOGO, 0, Afile::SIZE_LARGE]), CONF_IMG_CACHE_TIME, '.jpg'); ?>" alt="">
                    </div>
                </div>
                <div class="certificate-signs__right">
                    <span> <?php echo Label::getLabel('LBL_CERTIFICATE_NO.:'); ?></span>
                    <div class="style-bold contentCertNoJs">
                        <?php echo $content['certificate_number'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .layout--rtl .certificate {
        direction: rtl;
    }



    .certificate {
        width: 100%;
        position: relative;
        font-family: 'Open Sans', sans-serif;
    }

    .certificate::before {
        padding-bottom: 81.15%;
        content: "";
        display: block;
    }

    .certificate-media,
    .certificate-media img {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        margin: 0 auto;
        width: 100%;
    }


    .certificate-content {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 1;
        text-align: center;
        padding: 10% 12% 4%;
        display: flex;
        flex-direction: column;

        font-family: inherit;
        font-style: normal;
    }

    .certificate-title {
        font-size: 4.6rem;
        font-weight: 700;
        margin-bottom: 8%;
        font-style: italic;
    }

    .certificate-subtitle {
        font-size: 3rem;
        margin-bottom: 0;
        font-weight: normal;
        font-style: italic;
    }

    .certificate-author {
        font-size: 5rem;
        margin-bottom: 8%;

        font-weight: 700;
    }

    .certificate-meta {
        font-size: 4.2rem;
        font-weight: normal;
        line-height: 1.6;
        max-width: 90%;
        margin: 0 auto 5%;
        font-style: italic;
    }

    .certificate-signs {
        position: absolute;
        bottom: 6%;
        left: 12%;
        right: 12%;
        font-size: 24px;

    }

    .certificate-signs__left {
        position: absolute;
        left: 0;
        top: 0;
        text-align: left;
        width: 33.3%;
    }

    .certificate-signs__left>*,
    .certificate-signs__right>* {
        display: inline-block;
        vertical-align: middle;
    }

    .certificate-signs__right {

        width: 33.3%;
        position: absolute;
        right: 0;
        top: 0;
        text-align: right;
    }

    .certificate-signs__middle {
        width: 33.3%;
        margin: 0 auto;

    }

    .certificate-logo {
        max-width: 160px;
        margin: 0 auto;
    }

    .style-bold {
        font-weight: 700 !important;
    }


    .layout--rtr.certificate .certificate-signs__left {
        left: auto;
        right: 0;
        text-align: right;
    }

    .layout--rtr.certificate .certificate-signs__right {
        left: 0;
        right: auto;
        text-align: left;
    }
</style>