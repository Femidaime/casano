<?php
$post_format = get_post_format();
do_action( 'casano_before_single_blog_content' );
?>
    <article <?php post_class( 'post-item post-single' ); ?>>
        <div class="post-inner">
			<?php casano_post_format(); ?>
            <div class="post-content">
                <div class="post-meta clearfix">
					<?php
					casano_post_category();
					casano_post_date();
					?>
                </div>
                <div class="post-info">
					<?php
					casano_post_title();
					casano_post_author();
					casano_post_full_content();
					?>
                </div>
            </div>
            <?php if (!empty(get_the_terms(get_the_ID(), 'post_tag'))) : ?>
                <div class="post-footer clearfix">
                    <?php
                    casano_post_tags();
                    casano_share_button();
                    ?>
                </div>
            <?php endif;?>
        </div>
    </article>
<?php
do_action( 'casano_after_single_blog_content' );