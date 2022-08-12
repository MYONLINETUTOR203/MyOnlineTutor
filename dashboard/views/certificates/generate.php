<div class="layout--<?php echo $layoutDir; ?>" style="width:100%;">
    <div class="certificate " style="width:100%; height:100%;background-image: url('<?php echo MyUtility::makeFullUrl('image', 'show', [Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE, 0, Afile::SIZE_LARGE], CONF_WEBROOT_FRONTEND) . '?time=' . time() ?>'); background-size: 100% 100%; background-repeat: no-repeat;">
        <div class="certificate-content">
            <h1 class="certificate-title "><b><?php echo $content['heading'] ?></b></h1>
            <div class="certificate-subtitle ">
                <?php echo $content['content_part_1'] ?>
            </div>
            <div class="certificate-author ">
                <b><?php echo $content['learner'] ?></b>
            </div>
            <div class="certificate-meta ">
                <?php echo CommonHelper::renderHtml($content['content_part_2']) ?>
            </div>
            <div class="certificate-signs">
                <table border="0" cellspacing="0" style="width:100%">
                    <tr>
                        <td width="33.3%" class="certificate-signs__left">
                            <?php echo Label::getLabel('LBL_TUTOR:'); ?>
                            <b>
                                <?php echo $content['trainer'] ?>
                            </b>
                        </td>
                        <td width="33.3%" class="certificate-signs__middle">
                            <img src="<?php echo MyUtility::makeFullUrl('Image', 'show', [Afile::TYPE_CERTIFICATE_LOGO, 0, Afile::SIZE_MEDIUM, $this->siteLangId]); ?>" alt="">
                        </td>
                        <td width="33.3%" class="certificate-signs__right">
                            <?php echo Label::getLabel('LBL_CERTIFICATE_NO.:'); ?>
                            <b>
                                <?php echo $content['certificate_number'] ?>
                            </b>
                        </td>
                    </tr>
                </table>
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

    .certificate-content {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        text-align: center;
        padding: 8% 8% 4%;
        display: flex;
        flex-direction: column;

        font-family: inherit;
        font-style: normal;
    }

    .certificate-title {
        font-size: 3.6rem;
        font-weight: 900;
        margin-bottom: 7%;
        font-style: italic;
    }

    .certificate-subtitle {
        font-size: 2rem;
        margin-bottom: 3%;
        font-weight: normal;
        font-style: italic;
    }

    .certificate-author {
        font-size: 2rem;
        margin-bottom: 5%;
    }

    .certificate-meta {
        font-size: 1.5rem;
        font-weight: normal;
        line-height: 1.6;
        max-width: 90%;
        margin: 0 auto 5%;
        font-style: italic;
        height: 200px;
    }

    .certificate-signs {
        font-size: 14px;
    }

    .certificate-signs__left {
        text-align: left;
    }

    .certificate-signs__left>*,
    .certificate-signs__right>* {
        display: inline-block;
        vertical-align: middle;
    }

    .certificate-signs__right {
        text-align: right;
    }

    .certificate-signs__middle {
        text-align: center;
    }

    .certificate-logo {
        max-width: 160px;
        margin: 0 auto;
    }

    .style-bold {
        font-weight: 700 !important;
    }


    .layout--rtl .certificate .certificate-signs__left {
        left: auto;
        right: 0;
        text-align: right;
    }

    .layout--rtl .certificate .certificate-signs__right {
        left: 0;
        right: auto;
        text-align: left;
    }
</style>