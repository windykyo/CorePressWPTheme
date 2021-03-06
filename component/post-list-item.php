<?php
global $set;

$postitem['post_meta'] = json_decode(get_post_meta($post->ID, 'corepress_post_meta', true),true);


if (has_excerpt()) {
    $postitem['content'] = get_the_excerpt();
    if (strlen(preg_replace("/[\s]{2,}/", "", $postitem['content'])) == 0) {
        $postitem['content'] = mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, $set['routine']['summary_lenth'], "……");
    }
} else {
    $postitem['content'] = mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, $set['routine']['summary_lenth'], "……");
}

if (post_password_required()) {
    $postitem['content'] = '内容已加密，请输入密码以后查看';
}
$postitem['thumbnail'] = null;

if (has_post_thumbnail()) {
    if ($postitem['post_meta']['postshow'] == 1) {
        $postitem['thumbnail'] = get_the_post_thumbnail_url($post, 'full');
    } else {
        $postitem['thumbnail'] = get_the_post_thumbnail_url($post, 'thumbnail');
    }


} else if ($set['routine']['autothumbnail'] == 1) {
    $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
    preg_match($preg, $post->post_content, $imgArr);
    if (count($imgArr) != 0) {
        $postitem['thumbnail'] = $imgArr[1];
    }
}
if ($postitem['thumbnail'] == null) {
    $postitem['thumbnail'] = $set['routine']['defaultthumbnail'];
}

$postitem['views'] = null;
if (function_exists('the_views')) {
    $postitem['views'] = intval(get_post_meta($post->ID, 'views', true));
}
$postitem['url'] = get_the_permalink();
$postitem['author'] = get_the_author();
$postitem['time'] = get_the_time('Y-m-d');
$postitem['commentsnum'] = get_comments_number();
$postitem['title'] = get_the_title();
$postitem['category'] = get_the_category();


foreach ($postitem['category'] as $item) {
    $item->url = get_category_link($item->cat_ID);
}
$target = '';
if ($set['routine']['opennewlink'] == 1) {
    $target = '_blank';
}
if ($set['module']['imglazyload'] == 1) {
    $pathname = 'data-original';
    $imgtag = '<img src="'.file_get_img_url('loading.png').'" data-original="' . $postitem['thumbnail'] . '">';
}else
{
    $imgtag = '<img src="' . $postitem['thumbnail'] . '">';
}

if ($postitem['post_meta']['postshow'] == 1) {
    ?>
    <li class="post-item post-item-type1">
        <h2>
            <?php
            if (is_sticky(get_the_ID())) {
                ?>
                <span class="post-item-sticky">置顶</span>
                <?
            };
            ?><a href="<?php echo $postitem['url'] ?>"
                 target="<?php echo $target; ?>"><?php echo $postitem['title']; ?></a>
        </h2>
        <div class="post-item-thumbnail-type1">
            <a href="<?php echo $postitem['url'] ?>" target="<?php echo $target; ?>"><?php echo $imgtag?></a>
        </div>
        <div class="post-item-content post-item-content-type1">
            <?php echo $postitem['content'] ?>
        </div>
        <div class="post-item-info post-item-info-type1">
            <div class="post-item-tags">
                <?php
                foreach ($postitem['category'] as $catite) {
                    ?>
                    <i class="cat-item-mark"></i><span class="cat-item"><a
                                target="<?php echo $target ?>"
                                href="<?php echo $catite->url ?>"><?php echo $catite->name ?></a></span>
                    <?php
                }
                ?>
            </div>
            <div class="post-item-meta">
                <div class="post-item-meta-time">
                    <?php echo $postitem['author'] ?>
                    <span class="post-item-time"><?php echo diffBetweenTwoDay($postitem['time']); ?></span>
                </div>
                <div class="item-post-meta-other">
                    <?php
                    if ($postitem['views'] !== null) {
                        echo '<span><i class="fas fa-eye"
                                 aria-hidden="true"></i>';
                        echo $postitem['views'] . '</span>';
                    }
                    ?>
                    <span><i class="fas fa-comments"></i><?php echo $postitem['commentsnum'] ?></span>
                </div>
            </div>
        </div>
    </li>
    <?php
}else{
    ?>
    <li class="post-item">
        <div class="post-item-container">
            <div class="post-item-thumbnail">
                <a href="<?php echo $postitem['url'] ?>" target="<?php echo $target; ?>"><?php echo $imgtag?></a>
            </div>
            <div class="post-item-main">
                <h2>
                    <?php
                    if (is_sticky(get_the_ID())) {
                        ?>
                        <span class="post-item-sticky">置顶</span>
                        <?
                    };
                    ?><a href="<?php echo $postitem['url'] ?>"
                         target="<?php echo $target; ?>"><?php echo $postitem['title']; ?></a>
                </h2>
                <div class="post-item-content">
                    <?php echo $postitem['content'] ?>
                </div>
                <div class="post-item-info">
                    <div class="post-item-tags">
                        <?php
                        foreach ($postitem['category'] as $catite) {
                            ?>
                            <i class="cat-item-mark"></i><span class="cat-item"><a
                                        target="<?php echo $target ?>"
                                        href="<?php echo $catite->url ?>"><?php echo $catite->name ?></a></span>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="post-item-meta">
                        <div class="post-item-meta-time">
                            <?php echo $postitem['author'] ?>
                            <span class="post-item-time"><?php echo diffBetweenTwoDay($postitem['time']); ?></span>
                        </div>
                        <div class="item-post-meta-other">
                            <?php
                            if ($postitem['views'] !== null) {
                                echo '<span><i class="fas fa-eye"
                                 aria-hidden="true"></i>';
                                echo $postitem['views'] . '</span>';
                            }
                            ?>
                            <span><i class="fas fa-comments"></i><?php echo $postitem['commentsnum'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php
}
?>


