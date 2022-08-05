<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="reviews-sorting">
    <div class="row justify-content-between align-items-center">
        <div class="col-sm-auto">
            <p class="margin-0">
                <?php
                $label = Label::getLabel('LBL_DISPLAYING_REVIEWS_{count}_OF_{total}');
                echo str_replace(
                    ['{count}', '{total}'],
                    [count($reviews), $recordCount],
                    $label
                );
                ?>
            </p>
        </div>
        <div class="col-sm-auto">
            <div class="reviews-sort">
                <?php $sorting = RatingReview::getSortTypes() ?>
                <select onchange="sortReviews(this.value);">
                    <?php foreach ($sorting as $type => $sort) { ?>
                        <option <?php echo ($post['sorting'] == $type) ? 'selected="delected"' : '' ?> value="<?php echo $type ?>"><?php echo $sort ?></option>
                    <?php } ?>
                </select>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="reviews-list">
    <?php
    if ($reviews) {
        foreach ($reviews as $review) { ?>
            <div class="review">
                <div class="review__media">
                    <div class="avtar" data-title="<?php echo CommonHelper::getFirstChar($review['user_first_name']); ?>">
                        <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $review['ratrev_user_id'], Afile::SIZE_SMALL]); ?>" alt="<?php echo $review['user_first_name']; ?>">
                    </div>
                </div>
                <div class="review__content">
                    <span class="review__author">
                        <?php echo $review['user_first_name'] . ' ' . $review['user_last_name']; ?>
                    </span>
                    <span class="review__title">
                        <?php echo $review['ratrev_title']; ?>
                    </span>
                    <div class="review__meta">
                        <div class="review__rating">
                            <div class="rating">
                                <svg class="rating__media">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL ?>images/sprite.svg#rating">
                                    </use>
                                </svg>
                                <span class="rating__value">
                                    <?php echo FatUtility::convertToType($review['ratrev_overall'], FatUtility::VAR_FLOAT); ?>
                                </span>
                            </div>
                        </div>
                        <div class="review__date"><?php echo MyDate::formatDate($review['ratrev_created']); ?></div>
                    </div>
                    <div class="review__message">
                        <p><?php echo $review['ratrev_detail']; ?></p>
                    </div>
                </div>
            </div>
            <?php //echo $review['ratrev_title']; 
            ?>
    <?php }
    } else {
        echo Label::getLabel('LBL_NO_REVIEWS_POSTED');
    }
    ?>
</div>
<div class="pagination pagination--centered margin-top-10">
    <?php
    echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmSearchPaging']);
    $pagingArr = ['page' => $post['pageno'], 'pageCount' => $pageCount, 'recordCount' => $recordCount, 'callBackJsFunc' => 'gotoPage'];
    $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
    ?>
</div>