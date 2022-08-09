<div class="layout--<?php echo $layoutDir; ?>" style="width:100%; text-align:center">
    <table style="text-align:center; width:100%">
        <tr>
            <td width="5%"></td>
            <td width="90%">
                <table style="width:100%; text-align:center">
                    <tr>
                        <td height="40">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size:18px;">
                            <h1 style="font-size:30px;"><?php echo $content['heading'] ?></h1>
                        </td>
                    </tr>
                    <tr>
                        <td height="20">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size:18px;"><?php echo $content['content_part_1'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; font-size:18px;"><?php echo $content['learner'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:18px; height: 165px; line-height:27px;"><?php echo CommonHelper::renderHtml($content['content_part_2']) ?></td>
                    </tr>
                    <tr>
                        <td height="80">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table border="1" cellspacing="1" style="width:100%">
                                <tr>
                                    <td class="certificate-signs__left" align="<?php echo ($layoutDir == 'ltr') ? 'left' : 'right'; ?>" style="font-size:12px;line-height:10px;width:33%;">
                                        <?php echo Label::getLabel('LBL_TUTOR:'); ?>
                                        <b>
                                            <?php echo $content['trainer'] ?>
                                        </b>
                                    </td>
                                    <td align="center" style="line-height:10px;width:33%; text-align:center;">
                                        <img align="middle" src="<?php echo MyUtility::makeFullUrl('Image', 'show', [Afile::TYPE_FRONT_LOGO, 0, Afile::SIZE_MEDIUM]); ?>" alt="" />
                                    </td>
                                    <td class="certificate-signs__right" align="<?php echo ($layoutDir == 'ltr') ? 'right' : 'left'; ?>" style="font-size:12px;line-height:10px;width:33%">
                                        <?php echo Label::getLabel('LBL_CERTIFICATE_NO.:'); ?>
                                        <b>
                                            <?php echo $content['certificate_number'] ?>
                                        </b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="5%"></td>
        </tr>
    </table>
</div>
<style>
    * {
        line-height: 32px;
    }

    .layout--rtl table,
    .layout--rtl table td {
        direction: rtl;
    }

    .layout--rtl .certificate-signs__left {
        left: auto;
        right: 0;
        text-align: right;
    }

    .layout--rtl .certificate-signs__right {
        left: 0;
        right: auto;
        text-align: left;
    }
</style>