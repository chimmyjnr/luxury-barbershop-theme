<footer class="site-footer">

    <div class="container">

        <div class="footer-grid">

            <div class="footer-about">

                <h3><?php bloginfo('name'); ?></h3>

                <p>
                    Premium barbering, precision cuts, beard grooming,
                    and luxury service crafted for the modern gentleman.
                </p>

            </div>

            <div class="footer-links">

                <h4>Quick Links</h4>

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'footer-menu'
                ));
                ?>

            </div>

            <div class="footer-contact">

                <h4>Contact</h4>

                    <p>
                        <span class="dashicons dashicons-location"></span>
                        <strong>Address:</strong>
                        <?php echo esc_html(get_option('luxury_address')); ?>
                    </p>

                    <p>
                        <span class="dashicons dashicons-phone"></span>
                        <strong>Phone:</strong>
                        <a href="tel:<?php echo esc_attr(get_option('luxury_phone')); ?>">
                            <?php echo esc_html(get_option('luxury_phone')); ?>
                        </a>
                    </p>

                    <p>
                        <span class="dashicons dashicons-email"></span>
                        <strong>Email:</strong>
                        <a href="mailto:<?php echo esc_attr(get_option('luxury_email')); ?>">
                            <?php echo esc_html(get_option('luxury_email')); ?>
                        </a>
                    </p>


            </div>

        </div>

        <div class="footer-bottom">

            <div class="footer-social">

                <?php if(get_option('luxury_instagram')) : ?>
                    <a href="<?php echo esc_url(get_option('luxury_instagram')); ?>" target="_blank">
                        <span class="dashicons dashicons-instagram"></span>
                    </a>
                <?php endif; ?>

                <?php if(get_option('luxury_facebook')) : ?>
                    <a href="<?php echo esc_url(get_option('luxury_facebook')); ?>" target="_blank">
                        <span class="dashicons dashicons-facebook"></span>
                    </a>
                <?php endif; ?>

                <?php if(get_option('luxury_tiktok')) : ?>
                    <a href="<?php echo esc_url(get_option('luxury_tiktok')); ?>" target="_blank">
                        <span class="dashicons dashicons-video-alt3"></span>
                    </a>
                <?php endif; ?>

                <?php if(get_option('luxury_x')) : ?>
                    <a href="<?php echo esc_url(get_option('luxury_x')); ?>" target="_blank">
                        X
                    </a>
                <?php endif; ?>

            </div>

            <p>
                © <?php echo date('Y'); ?>
                <?php bloginfo('name'); ?>. All rights reserved.
            </p>

        </div>

    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>