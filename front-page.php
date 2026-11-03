<?php get_header(); ?>

<main>

    <section id="hero" class="hero" style="background-image:
    linear-gradient(rgba(15,15,15,0.65), rgba(15,15,15,0.65)),
    url('<?php echo get_template_directory_uri(); ?>/assets/images/hero.jpg');">

        <div class="container">

            <p class="hero-subtitle">
                PREMIUM GROOMING EXPERIENCE
            </p>

            <h1 class="hero-title">
                Where Style Meets <span class="accent">Precision</span>
            </h1>

            <p class="hero-text">
                Experience world-class barbering with expert cuts,
                beard grooming, and luxury service.
            </p>

            <a
                href="<?php echo esc_url( get_permalink( get_page_by_path( 'bookings' ) ) ); ?>"
                class="hero-btn"
            >
                Book an Appointment
            </a>

        </div>

    </section>

    <section id="about" class="about">

        <div class="container">

            <div class="about-grid">

                <div class="about-image">
                     <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about.jpg" alt="Professional barber cutting a client's hair">
                </div>

                <div class="about-content">

                    <p class="section-subtitle">ABOUT US</p>

                    <h2 class="section-title">
                        Where Precision Meets Personal Style
                    </h2>

                    <p class="about-text">
                        At Velour Grooming Studio, we believe grooming is more than a haircut — it's an experience.
                        Our barbers combine precision, modern techniques, and attention to detail to deliver premium
                        cuts, beard grooming, and styling in a relaxed, refined environment.
                    </p>

                    <a href="<?php echo home_url('/#contact'); ?>" class="hero-btn">
                        Contact Us
                    </a>

                </div>

            </div>

        </div>

    </section>

    <section id="services" class="services">
        <div class="container">

            <p class="section-subtitle">OUR SERVICES</p>
            <h2 class="section-title">Crafted For Modern Gentlemen</h2>

            <div class="services-grid">

                <?php
                    $services = new WP_Query(array(
                        'post_type' => 'service',
                        'posts_per_page' => 9
                    ));

                    if ($services->have_posts()) :

                        while ($services->have_posts()) : $services->the_post();

                            $price = get_post_meta(get_the_ID(), 'service_price', true);
                    ?>

                    <div class="service-card">

                        <div class="service-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="scissors-icon">
                                <circle cx="6" cy="6" r="3"/>
                                <circle cx="6" cy="18" r="3"/>
                                <path d="M20 4L8.5 15.5"/>
                                <path d="M20 20L8.5 8.5"/>
                            </svg>
                        </div>

                        <h3><?php the_title(); ?></h3>

                        <p><?php the_content(); ?></p>

                        <span class="service-price">
                            <?php
                            $currency = get_option('luxury_currency', '₦');
                            echo esc_html($currency) . number_format((float) $price);
                            ?>
                        </span>

                    </div>

                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
            </div>

        </div>
    </section>

    <section id="gallery" class="gallery">

        <div class="container">

            <p class="section-subtitle">OUR WORK</p>
            <h2 class="section-title">A Look at Our Craft</h2>
            <p class="section-text">Precision, Passion, and attention to details in every cut. Explore some of our work.</p>

            <div class="gallery-grid">

                <?php

                    $gallery = new WP_Query(array(
                        'post_type'      => 'gallery',
                        'posts_per_page' => -1,
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC'
                    ));

                    if ($gallery->have_posts()) :

                        while ($gallery->have_posts()) : $gallery->the_post();

                            ?>

                            <div class="gallery-item">
                                <?php the_post_thumbnail('large'); ?>
                            </div>

                            <?php

                        endwhile;

                        wp_reset_postdata();

                    endif;

                    ?>

            </div>

        </div>

    </section>

    <section id="team" class="team">

        <div class="container">

            <p class="section-subtitle">MEET THE TEAM</p>
            <h2 class="section-title">Masters of the Craft</h2>

            <div class="team-grid">

            <?php
            $team = new WP_Query(array(
                'post_type' => 'team',
                'posts_per_page' => 3
            ));

            if($team->have_posts()) :

                while($team->have_posts()) : $team->the_post();

                $role = get_post_meta(get_the_ID(), '_team_role', true);
            ?>

                <div class="team-card">

                    <div class="team-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>

                    <div class="team-content">

                        <h3><?php the_title(); ?></h3>

                        <p class="team-role">
                            <?php echo esc_html($role); ?>
                        </p>

                        <div class="team-bio">
                            <?php echo wp_trim_words(get_the_content(), 18, '...'); ?>
                        </div>

                    </div>

                </div>

            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>

            </div>

        </div>

    </section>

    <section id="pricing" class="pricing">

        <div class="container">

            <p class="section-subtitle">OUR PRICING</p>
            <h2 class="section-title">Simple, Transparent Pricing</h2>
            <p class="section-text">
                Premium grooming, crafted with precision and tailored to your style.
            </p>

            <div class="pricing-grid">
                <?php

                    $pricing = new WP_Query(array(
                        'post_type'      => 'service',
                        'posts_per_page' => -1,
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC'
                    ));

                    if ($pricing->have_posts()) :

                        while ($pricing->have_posts()) : $pricing->the_post();

                            $price = get_post_meta(get_the_ID(), 'service_price', true);
                            $duration = get_post_meta(get_the_ID(), '_service_duration', true);

                            ?>

                            <div class="pricing-item">

                                <h3><?php the_title(); ?></h3>

                                <div class="pricing-divider"></div>

                                <div class="pricing-details">
                                    <span class="pricing-price">
                                        <?php
                                        $currency = get_option('luxury_currency', '₦');
                                        echo esc_html($currency) . number_format((float) $price);
                                        ?>
                                    </span>

                                    <span class="pricing-duration">
                                        <?php echo esc_html($duration); ?>
                                    </span>
                                </div>

                            </div>

                            <?php

                        endwhile;

                        wp_reset_postdata();

                    endif;

                    ?>

            </div>

        </div>

    </section>

    <section class="booking-cta">

        <div class="container">

            <p class="section-subtitle">READY WHEN YOU ARE</p>

            <h2 class="section-title">Reserve Your Chair</h2>

            <p class="section-text">
                Choose your preferred service, barber, date, and time.
                We'll take care of the rest.
            </p>

            <a
                href="<?php echo esc_url( get_permalink( get_page_by_path( 'bookings' ) ) ); ?>"
                class="booking-cta-button"
            >
                Book Your Appointment
            </a>

        </div>

    </section>

    <section id="contact" class="contact">

        <div class="container">

            <p class="section-subtitle">CONTACT US</p>

            <h2 class="section-title">Visit Our Studio</h2>

            <p class="section-text">
                We'd love to welcome you. Get in touch or visit our luxury grooming studio.
            </p>

            <div class="contact-wrapper">

                <div class="contact-details">

                    <div class="contact-item">
                        <div class="contact-icon">
                            <span class="dashicons dashicons-location"></span>
                        </div>
                        <div>
                            <h3>Address</h3>
                            <p><?php echo esc_html(get_option('luxury_address')); ?></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <span class="dashicons dashicons-phone"></span>
                        </div>
                        <div>
                            <h3>Phone</h3>
                            <p>
                                <a href="tel:<?php echo esc_attr(get_option('luxury_phone')); ?>">
                                    <?php echo esc_html(get_option('luxury_phone')); ?>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <span class="dashicons dashicons-email"></span>
                        </div>
                        <div>
                            <h3>Email</h3>
                            <p>
                                <a href="mailto:<?php echo esc_attr(get_option('luxury_email')); ?>">
                                    <?php echo esc_html(get_option('luxury_email')); ?>
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <span class="dashicons dashicons-clock"></span>
                        </div>
                        <div>
                            <h3>Opening Hours</h3>
                            <p>
                                Monday – Saturday:
                                <?php
                                echo esc_html(
                                    date('g:i A', strtotime(get_option('luxury_week_open', '09:00')))
                                );
                                ?>
                                –
                                <?php
                                echo esc_html(
                                    date('g:i A', strtotime(get_option('luxury_week_close', '20:00')))
                                );
                                ?>
                            </p>

                            <p>
                                Sunday:
                                <?php
                                echo esc_html(
                                    date('g:i A', strtotime(get_option('luxury_sunday_open', '10:00')))
                                );
                                ?>
                                –
                                <?php
                                echo esc_html(
                                    date('g:i A', strtotime(get_option('luxury_sunday_close', '18:00')))
                                );
                                ?>
                            </p>
                        </div>
                    </div>

                </div>
                
                <div class="contact-map">

                    <?php
                    $map_url = get_option('luxury_map_url');
                    ?>

                    <?php if ($map_url) : ?>

                        <iframe
                            src="<?php echo esc_url($map_url); ?>"
                            width="100%"
                            height="400"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin">
                        </iframe>

                    <?php else : ?>

                        <div class="map-placeholder">
                            Google Map
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>



</main>

<?php get_footer(); ?>