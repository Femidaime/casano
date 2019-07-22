<?php
$socials = casano_get_option('casano_header_social');
$socials_content = casano_get_option('user_all_social');
?>
<?php if (!empty($socials)) : ?>
    <div class="menu-social">
        <?php foreach ($socials as $social) :
            if (isset($socials_content[$social])):
                $content = $socials_content[$social]; ?>
                <a href="<?php echo esc_url($content['link_social']); ?>">
                    <?php echo esc_html($content['title_social']); ?>
                </a>
                <?php
            endif;
        endforeach; ?>
    </div>
<?php endif; ?>
