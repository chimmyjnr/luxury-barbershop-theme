<?php

if (! defined('ABSPATH')) {
    exit;
}


/*======================================
 THEME STYLES & SCRIPTS
======================================*/

function luxury_barbershop_enqueue_styles() {

    wp_enqueue_style(
        'luxury-barbershop-style',
        get_stylesheet_uri()
    );

    wp_enqueue_style('dashicons');

    wp_enqueue_script(
        'luxury-barbershop-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0',
        true
    );

}

add_action(
    'wp_enqueue_scripts',
    'luxury_barbershop_enqueue_styles'
);


/*======================================
 THEME SETUP
======================================*/

function luxury_barbershop_setup() {

    add_theme_support('post-thumbnails');

    add_theme_support('title-tag');

    add_theme_support('custom-logo');

    register_nav_menus(
        array(
            'primary' => 'Primary Menu'
        )
    );

}

add_action(
    'after_setup_theme',
    'luxury_barbershop_setup'
);


/*======================================
 TEAM CUSTOM POST TYPE
======================================*/

function luxury_barbershop_register_team() {

    register_post_type(
        'team',
        array(

            'labels' => array(
                'name'          => 'Team',
                'singular_name' => 'Team Member',
                'add_new_item'  => 'Add New Team Member',
                'edit_item'     => 'Edit Team Member',
                'new_item'      => 'New Team Member',
                'view_item'     => 'View Team Member',
                'search_items'  => 'Search Team',
            ),

            'public' => true,

            'menu_icon' => 'dashicons-groups',

            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),

            'has_archive' => true,

            'rewrite' => array(
                'slug' => 'team',
            ),

        )
    );

}

add_action(
    'init',
    'luxury_barbershop_register_team'
);


/*======================================
 TEAM ROLE META BOX
======================================*/

function luxury_barbershop_add_team_role_meta_box() {

    add_meta_box(
        'team_role',
        'Team Role',
        'luxury_barbershop_team_role_callback',
        'team',
        'normal',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'luxury_barbershop_add_team_role_meta_box'
);


function luxury_barbershop_team_role_callback($post) {

    wp_nonce_field(
        'luxury_team_save',
        'luxury_team_nonce'
    );

    $team_role = get_post_meta(
        $post->ID,
        '_team_role',
        true
    );

    ?>

    <label for="team_role">Role</label>

    <input
        type="text"
        id="team_role"
        name="team_role"
        value="<?php echo esc_attr($team_role); ?>"
        style="width: 100%;"
    >

    <?php
}


function luxury_barbershop_save_team_role($post_id) {

    if (
        ! isset($_POST['luxury_team_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['luxury_team_nonce'])
            ),
            'luxury_team_save'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['team_role'])) {

        update_post_meta(
            $post_id,
            '_team_role',
            sanitize_text_field(
                wp_unslash($_POST['team_role'])
            )
        );

    }

}

add_action(
    'save_post_team',
    'luxury_barbershop_save_team_role'
);


/*======================================
 SERVICES CUSTOM POST TYPE
======================================*/

function luxury_barbershop_register_services() {

    register_post_type(
        'service',
        array(

            'labels' => array(
                'name'          => 'Services',
                'singular_name' => 'Service',
                'add_new_item'  => 'Add New Service',
                'edit_item'     => 'Edit Service',
            ),

            'public' => true,

            'menu_icon' => 'dashicons-admin-tools',

            'supports' => array(
                'title',
                'editor',
            ),

            'has_archive' => true,

            'rewrite' => array(
                'slug' => 'services',
            ),

        )
    );

}

add_action(
    'init',
    'luxury_barbershop_register_services'
);


/*======================================
 SERVICE PRICE / DURATION META BOX
======================================*/

function luxury_barbershop_add_service_price_meta_box() {

    add_meta_box(
        'service_price',
        'Service Price',
        'luxury_barbershop_service_price_callback',
        'service',
        'side',
        'default'
    );

}

add_action(
    'add_meta_boxes',
    'luxury_barbershop_add_service_price_meta_box'
);


function luxury_barbershop_service_price_callback($post) {

    wp_nonce_field(
        'luxury_service_save',
        'luxury_service_nonce'
    );

    $price = get_post_meta(
        $post->ID,
        'service_price',
        true
    );

    $duration = get_post_meta(
        $post->ID,
        '_service_duration',
        true
    );

    ?>

    <label for="service_price">Price</label>

    <input
        type="number"
        id="service_price"
        name="service_price"
        value="<?php echo esc_attr($price); ?>"
        style="width: 100%;"
        min="0"
    >

    <br><br>

    <label for="service_duration">Duration</label>

    <input
        type="text"
        id="service_duration"
        name="service_duration"
        value="<?php echo esc_attr($duration); ?>"
        style="width: 100%;"
        placeholder="45 min"
    >

    <?php
}


function luxury_barbershop_save_service_price($post_id) {

    if (
        ! isset($_POST['luxury_service_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['luxury_service_nonce'])
            ),
            'luxury_service_save'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['service_price'])) {

        update_post_meta(
            $post_id,
            'service_price',
            absint($_POST['service_price'])
        );

    }

    if (isset($_POST['service_duration'])) {

        update_post_meta(
            $post_id,
            '_service_duration',
            sanitize_text_field(
                wp_unslash($_POST['service_duration'])
            )
        );

    }

}

add_action(
    'save_post_service',
    'luxury_barbershop_save_service_price'
);


/*======================================
 SERVICE ICON META BOX
======================================*/

function luxury_barbershop_add_service_icon_meta_box() {

    add_meta_box(
        'service_icon',
        'Service Icon',
        'luxury_barbershop_service_icon_callback',
        'service',
        'side',
        'default'
    );

}

add_action(
    'add_meta_boxes',
    'luxury_barbershop_add_service_icon_meta_box'
);


function luxury_barbershop_service_icon_callback($post) {

    $icon = get_post_meta(
        $post->ID,
        'service_icon',
        true
    );

    ?>

    <label for="service_icon">Icon Name</label>

    <input
        type="text"
        id="service_icon"
        name="service_icon"
        value="<?php echo esc_attr($icon); ?>"
        style="width:100%;"
        placeholder="scissors"
    >

    <?php
}


function luxury_barbershop_save_service_icon($post_id) {

    if (
        ! isset($_POST['luxury_service_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['luxury_service_nonce'])
            ),
            'luxury_service_save'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['service_icon'])) {

        update_post_meta(
            $post_id,
            'service_icon',
            sanitize_text_field(
                wp_unslash($_POST['service_icon'])
            )
        );

    }

}

add_action(
    'save_post_service',
    'luxury_barbershop_save_service_icon'
);


/*======================================
 GALLERY CUSTOM POST TYPE
======================================*/

function luxury_barbershop_gallery_cpt() {

    register_post_type(
        'gallery',
        array(

            'labels' => array(
                'name'          => 'Gallery',
                'singular_name' => 'Gallery Image',
                'add_new_item'  => 'Add New Gallery Image',
            ),

            'public' => true,

            'menu_icon' => 'dashicons-format-gallery',

            'supports' => array(
                'title',
                'thumbnail',
                'page-attributes'
            ),

            'show_in_rest' => true,

            'has_archive' => false,

        )
    );

}

add_action(
    'init',
    'luxury_barbershop_gallery_cpt'
);


/*======================================
 PRICING CUSTOM POST TYPE
======================================*/


/*======================================
 PRICING META BOX
======================================*/

function luxury_barbershop_pricing_meta_box() {

    add_meta_box(
        'pricing_details',
        'Pricing Details',
        'luxury_barbershop_pricing_callback',
        'pricing',
        'normal',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'luxury_barbershop_pricing_meta_box'
);


function luxury_barbershop_pricing_callback($post) {

    wp_nonce_field(
        'luxury_pricing_save',
        'luxury_pricing_nonce'
    );

    $price = get_post_meta(
        $post->ID,
        '_service_price',
        true
    );

    $duration = get_post_meta(
        $post->ID,
        '_service_duration',
        true
    );

    ?>

    <p>
        <label><strong>Price</strong></label><br>

        <input
            type="text"
            name="service_price"
            value="<?php echo esc_attr($price); ?>"
            style="width:100%;"
            placeholder="$35"
        >
    </p>

    <p>
        <label><strong>Duration</strong></label><br>

        <input
            type="text"
            name="service_duration"
            value="<?php echo esc_attr($duration); ?>"
            style="width:100%;"
            placeholder="45 min"
        >
    </p>

    <?php
}


function luxury_barbershop_save_pricing($post_id) {

    if (
        ! isset($_POST['luxury_pricing_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['luxury_pricing_nonce'])
            ),
            'luxury_pricing_save'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['service_price'])) {

        update_post_meta(
            $post_id,
            '_service_price',
            absint($_POST['service_price'])
        );

    }

    if (isset($_POST['service_duration'])) {

        update_post_meta(
            $post_id,
            '_service_duration',
            sanitize_text_field(
                wp_unslash($_POST['service_duration'])
            )
        );

    }

}

add_action(
    'save_post_pricing',
    'luxury_barbershop_save_pricing'
);


/*======================================
 BOOKINGS CUSTOM POST TYPE
======================================*/

function luxury_barbershop_booking_cpt() {

    $args = array(

        'labels' => array(
            'name'          => 'Bookings',
            'singular_name' => 'Booking'
        ),

        'public'        => false,
        'show_ui'       => true,
        'menu_icon'     => 'dashicons-calendar-alt',
        'supports'      => array('title'),
        'menu_position' => 8

    );

    register_post_type(
        'booking',
        $args
    );

}

add_action(
    'init',
    'luxury_barbershop_booking_cpt'
);


/*======================================
 BOOKING SETTINGS
======================================*/

function luxury_barbershop_booking_settings_menu() {

    add_submenu_page(
        'edit.php?post_type=booking',
        'Booking Settings',
        'Settings',
        'manage_options',
        'booking-settings',
        'luxury_barbershop_booking_settings_page'
    );

}

add_action(
    'admin_menu',
    'luxury_barbershop_booking_settings_menu'
);


function luxury_barbershop_sanitize_currency_code($value) {

    $allowed = array(
        'NGN',
        'USD',
        'GBP',
        'EUR'
    );

    $value = sanitize_text_field(
        wp_unslash($value)
    );

    return in_array($value, $allowed, true)
        ? $value
        : 'NGN';
}


function luxury_barbershop_booking_settings() {

    register_setting(
        'luxury_booking_settings_group',
        'luxury_home_service_fee',
        array(
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 0
        )
    );

    register_setting(
        'luxury_booking_settings_group',
        'luxury_currency',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'luxury_barbershop_sanitize_currency_code',
            'default'           => 'NGN'
        )
    );

}

add_action(
    'admin_init',
    'luxury_barbershop_booking_settings'
);


function luxury_barbershop_booking_settings_page() {
    ?>

    <div class="wrap">

        <h1>Booking Settings</h1>

        <form method="post" action="options.php">

            <?php
            settings_fields('luxury_booking_settings_group');
            do_settings_sections('luxury_booking_settings_group');
            ?>

            <table class="form-table">

                <tr>
                    <th scope="row">
                        <label for="luxury_currency">
                            Currency
                        </label>
                    </th>

                    <td>

                        <select
                            id="luxury_currency"
                            name="luxury_currency"
                        >

                            <option
                                value="NGN"
                                <?php selected(
                                    get_option(
                                        'luxury_currency',
                                        'NGN'
                                    ),
                                    'NGN'
                                ); ?>
                            >
                                Nigerian Naira (₦)
                            </option>

                            <option
                                value="USD"
                                <?php selected(
                                    get_option('luxury_currency'),
                                    'USD'
                                ); ?>
                            >
                                US Dollar ($)
                            </option>

                            <option
                                value="GBP"
                                <?php selected(
                                    get_option('luxury_currency'),
                                    'GBP'
                                ); ?>
                            >
                                British Pound (£)
                            </option>

                            <option
                                value="EUR"
                                <?php selected(
                                    get_option('luxury_currency'),
                                    'EUR'
                                ); ?>
                            >
                                Euro (€)
                            </option>

                        </select>

                        <p class="description">
                            This currency will be used throughout the booking system.
                        </p>

                    </td>
                </tr>

                <tr>

                    <th scope="row">
                        <label for="luxury_home_service_fee">
                            Home Service Fee
                        </label>
                    </th>

                    <td>

                        <input
                            type="number"
                            id="luxury_home_service_fee"
                            name="luxury_home_service_fee"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_home_service_fee',
                                    0
                                )
                            ); ?>"
                            class="regular-text"
                            min="0"
                            step="500"
                        >

                        <p class="description">
                            Enter the amount only. The selected currency will be displayed automatically.
                        </p>

                    </td>

                </tr>

            </table>

            <?php submit_button('Save Settings'); ?>

        </form>

    </div>

    <?php
}


/*======================================
 BOOKING DETAILS META BOX
======================================*/

function luxury_barbershop_booking_meta_box() {

    add_meta_box(
        'booking_customer_details',
        'Customer Details',
        'luxury_barbershop_booking_details_callback',
        'booking',
        'normal',
        'high'
    );

}

add_action(
    'add_meta_boxes',
    'luxury_barbershop_booking_meta_box'
);


/*======================================
 BOOKING DETAILS CALLBACK
======================================*/

function luxury_barbershop_booking_details_callback($post) {

    wp_nonce_field(
        'luxury_booking_save',
        'luxury_booking_nonce'
    );

    $booking_email = get_post_meta(
        $post->ID,
        '_booking_email',
        true
    );

    $booking_phone = get_post_meta(
        $post->ID,
        '_booking_phone',
        true
    );

    ?>

    <p>
        <label for="booking_email">
            Customer Email
        </label>
    </p>

    <input
        type="email"
        id="booking_email"
        name="booking_email"
        value="<?php echo esc_attr($booking_email); ?>"
        style="width: 100%;"
    >

    <p>
        <label for="booking_phone">
            Customer Phone
        </label>
    </p>

    <input
        type="tel"
        id="booking_phone"
        name="booking_phone"
        value="<?php echo esc_attr($booking_phone); ?>"
        style="width: 100%;"
    >

    <p>
        <label for="booking_service">
            Service
        </label>
    </p>

    <select
        id="booking_service"
        name="booking_service"
        style="width:100%;"
    >

        <option value="">
            Select a service
        </option>

        <?php

        $selected_service = get_post_meta(
            $post->ID,
            '_booking_service',
            true
        );

        $services = get_posts(
            array(
                'post_type'      => 'service',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'post_status'    => 'publish'
            )
        );

        foreach ($services as $service) :

        ?>

            <option
                value="<?php echo esc_attr(
                    $service->post_title
                ); ?>"
                <?php selected(
                    $selected_service,
                    $service->post_title
                ); ?>
            >

                <?php echo esc_html(
                    $service->post_title
                ); ?>

            </option>

        <?php endforeach; ?>

    </select>

    <p>
        <label for="booking_barber">
            Barber
        </label>
    </p>

    <select
        id="booking_barber"
        name="booking_barber"
        style="width:100%;"
    >

        <option value="">
            Select a barber
        </option>

        <?php

        $selected_barber = get_post_meta(
            $post->ID,
            '_booking_barber',
            true
        );

        $team = get_posts(
            array(
                'post_type'      => 'team',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'post_status'    => 'publish'
            )
        );

        foreach ($team as $barber) :

        ?>

            <option
                value="<?php echo esc_attr(
                    $barber->post_title
                ); ?>"
                <?php selected(
                    $selected_barber,
                    $barber->post_title
                ); ?>
            >

                <?php echo esc_html(
                    $barber->post_title
                ); ?>

            </option>

        <?php endforeach; ?>

    </select>

    <?php

    $booking_type = get_post_meta(
        $post->ID,
        '_booking_type',
        true
    );

    ?>

    <p>
        <label for="booking_type">
            Appointment Type
        </label>
    </p>

    <select
        id="booking_type"
        name="booking_type"
        style="width:100%;"
    >

        <option
            value="salon"
            <?php selected(
                $booking_type,
                'salon'
            ); ?>
        >
            Visit the Salon
        </option>

        <option
            value="home"
            <?php selected(
                $booking_type,
                'home'
            ); ?>
        >
            Home Appointment
        </option>

    </select>

    <?php

    $booking_address = get_post_meta(
        $post->ID,
        '_booking_address',
        true
    );

    $booking_home_fee = get_post_meta(
        $post->ID,
        '_booking_home_fee',
        true
    );

    $booking_service_price = get_post_meta(
        $post->ID,
        '_booking_service_price',
        true
    );

    $booking_total = get_post_meta(
        $post->ID,
        '_booking_total',
        true
    );

    $booking_currency = get_post_meta(
        $post->ID,
        '_booking_currency',
        true
    );

    ?>

    <div
        id="booking-home-address"
        style="<?php echo $booking_type === 'home'
            ? ''
            : 'display:none;'; ?>"
    >

        <p>
            <label for="booking_address">
                Home Address
            </label>
        </p>

        <textarea
            id="booking_address"
            name="booking_address"
            rows="3"
            style="width:100%;"
        ><?php echo esc_textarea(
            $booking_address
        ); ?></textarea>

        <p>

            <strong>
                Home Service Fee:
            </strong>

            <?php

            $currency_symbols = array(
                'NGN' => '₦',
                'USD' => '$',
                'GBP' => '£',
                'EUR' => '€'
            );

            $currency_symbol = isset(
                $currency_symbols[$booking_currency]
            )
                ? $currency_symbols[$booking_currency]
                : $booking_currency;

            echo esc_html(
                $currency_symbol .
                number_format(
                    (float) $booking_home_fee
                )
            );

            ?>

        </p>

        <hr>

        <p>

            <strong>
                Service Price:
            </strong>

            <?php

            echo esc_html(
                $currency_symbol .
                number_format(
                    (float) $booking_service_price
                )
            );

            ?>

        </p>

        <p>

            <strong>
                Booking Total:
            </strong>

            <?php

            echo esc_html(
                $currency_symbol .
                number_format(
                    (float) $booking_total
                )
            );

            ?>

        </p>

    </div>

    <p>
        <label for="booking_date">
            Booking Date
        </label>
    </p>

    <input
        type="date"
        id="booking_date"
        name="booking_date"
        min="<?php echo esc_attr(
            current_time('Y-m-d')
        ); ?>"
        value="<?php echo esc_attr(
            get_post_meta(
                $post->ID,
                '_booking_date',
                true
            )
        ); ?>"
        style="width: 100%;"
    >

    <p>
        <label for="booking_time">
            Booking Time
        </label>
    </p>

    <input
        type="time"
        id="booking_time"
        name="booking_time"
        min="09:00"
        max="20:00"
        step="1800"
        value="<?php echo esc_attr(
            get_post_meta(
                $post->ID,
                '_booking_time',
                true
            )
        ); ?>"
        style="width: 100%;"
    >

    <?php

    $booking_notes = get_post_meta(
        $post->ID,
        '_booking_notes',
        true
    );

    ?>

    <p>
        <label for="booking_notes">
            Customer Notes
        </label>
    </p>

    <textarea
        id="booking_notes"
        name="booking_notes"
        rows="5"
        style="width: 100%;"
    ><?php echo esc_textarea(
        $booking_notes
    ); ?></textarea>

    <?php

        /* Display Paystack payment details */

    $payment_status = get_post_meta(
        $post->ID,
        '_payment_status',
        true
    );

    $payment_reference = get_post_meta(
        $post->ID,
        '_payment_reference',
        true
    );

    $payment_amount = get_post_meta(
        $post->ID,
        '_payment_amount',
        true
    );

    $payment_verified_at = get_post_meta(
        $post->ID,
        '_payment_verified_at',
        true
    );

    ?>

    <hr>

    <h3>Payment Details</h3>

    <p>
        <strong>Payment Status:</strong>
        <?php echo esc_html(
            $payment_status ?: 'Not recorded'
        ); ?>
    </p>

    <p>
        <strong>Payment Reference:</strong><br>

        <?php if ($payment_reference) : ?>

            <code><?php echo esc_html(
                $payment_reference
            ); ?></code>

        <?php else : ?>

            Not available

        <?php endif; ?>
    </p>

    <p>
        <strong>Payment Amount:</strong>

        <?php
        echo esc_html(
            get_post_meta(
                $post->ID,
                '_payment_currency',
                true
            ) . ' ' .
            number_format(
                (float) $payment_amount,
                2
            )
        );
        ?>
    </p>

    <p>
        <strong>Payment Verified At:</strong>

        <?php echo esc_html(
            $payment_verified_at ?: 'Not verified'
        ); ?>
    </p>

    <hr>

    <?php

    $booking_status = get_post_meta(
        $post->ID,
        '_booking_status',
        true
    );

    if (
        ! in_array(
            $booking_status,
            array(
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ),
            true
        )
    ) {
        $booking_status = 'pending';
    }

    ?>

    <p>
        <label for="booking_status">
            Booking Status
        </label>
    </p>

    <select
        id="booking_status"
        name="booking_status"
        style="width:100%;"
    >

        <option
            value="pending"
            <?php selected(
                $booking_status,
                'pending'
            ); ?>
        >
            Pending
        </option>

        <option
            value="confirmed"
            <?php selected(
                $booking_status,
                'confirmed'
            ); ?>
        >
            Confirmed
        </option>

        <option
            value="completed"
            <?php selected(
                $booking_status,
                'completed'
            ); ?>
        >
            Completed
        </option>

        <option
            value="cancelled"
            <?php selected(
                $booking_status,
                'cancelled'
            ); ?>
        >
            Cancelled
        </option>

    </select>

    <?php
}


/*======================================
 SAVE BOOKING DETAILS
======================================*/

function luxury_barbershop_save_booking_email($post_id) {

    if (
        ! isset($_POST['luxury_booking_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['luxury_booking_nonce'])
            ),
            'luxury_booking_save'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (
        get_post_type($post_id) !== 'booking'
    ) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    $old_status = get_post_meta(
        $post_id,
        '_booking_status',
        true
    );

    if (isset($_POST['booking_email'])) {

        $email = sanitize_email(
            wp_unslash($_POST['booking_email'])
        );

        if (
            empty($email) ||
            is_email($email)
        ) {

            update_post_meta(
                $post_id,
                '_booking_email',
                $email
            );

        }

    }

    if (isset($_POST['booking_phone'])) {

        update_post_meta(
            $post_id,
            '_booking_phone',
            sanitize_text_field(
                wp_unslash($_POST['booking_phone'])
            )
        );

    }

    if (isset($_POST['booking_service'])) {

        $service = sanitize_text_field(
            wp_unslash($_POST['booking_service'])
        );

        $service_post = get_posts(
            array(
                'post_type'      => 'service',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'title'          => $service
            )
        );

        if (! empty($service_post)) {

            update_post_meta(
                $post_id,
                '_booking_service',
                $service
            );

        }

    }

    if (isset($_POST['booking_barber'])) {

        $barber = sanitize_text_field(
            wp_unslash($_POST['booking_barber'])
        );

        $barber_post = get_posts(
            array(
                'post_type'      => 'team',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'title'          => $barber
            )
        );

        if (! empty($barber_post)) {

            update_post_meta(
                $post_id,
                '_booking_barber',
                $barber
            );

        }

    }

    if (isset($_POST['booking_address'])) {

        update_post_meta(
            $post_id,
            '_booking_address',
            sanitize_textarea_field(
                wp_unslash($_POST['booking_address'])
            )
        );

    }

    if (isset($_POST['booking_type'])) {

        $booking_type = sanitize_text_field(
            wp_unslash($_POST['booking_type'])
        );

        if (
            in_array(
                $booking_type,
                array('salon', 'home'),
                true
            )
        ) {

            update_post_meta(
                $post_id,
                '_booking_type',
                $booking_type
            );

        }

    }

    if (isset($_POST['booking_date'])) {

        $booking_date = sanitize_text_field(
            wp_unslash($_POST['booking_date'])
        );

        $date_object = DateTime::createFromFormat(
            'Y-m-d',
            $booking_date
        );

        $date_errors = DateTime::getLastErrors();

        if (
            $date_object &&
            (
                $date_errors === false ||
                (
                    $date_errors['warning_count'] === 0 &&
                    $date_errors['error_count'] === 0
                )
            ) &&
            $date_object->format('Y-m-d') === $booking_date
        ) {

            update_post_meta(
                $post_id,
                '_booking_date',
                $booking_date
            );

        }

    }

    if (isset($_POST['booking_time'])) {

        $booking_time = sanitize_text_field(
            wp_unslash($_POST['booking_time'])
        );

        $time_object = DateTime::createFromFormat(
            'H:i',
            $booking_time
        );

        $time_errors = DateTime::getLastErrors();

        if (
            $time_object &&
            (
                $time_errors === false ||
                (
                    $time_errors['warning_count'] === 0 &&
                    $time_errors['error_count'] === 0
                )
            ) &&
            $time_object->format('H:i') === $booking_time
        ) {

            update_post_meta(
                $post_id,
                '_booking_time',
                $booking_time
            );

        }

    }

    if (isset($_POST['booking_notes'])) {

        update_post_meta(
            $post_id,
            '_booking_notes',
            sanitize_textarea_field(
                wp_unslash($_POST['booking_notes'])
            )
        );

    }

    if (isset($_POST['booking_status'])) {

        $new_status = sanitize_text_field(
            wp_unslash($_POST['booking_status'])
        );

        $allowed_statuses = array(
            'pending',
            'confirmed',
            'completed',
            'cancelled'
        );

        if (
            ! in_array(
                $new_status,
                $allowed_statuses,
                true
            )
        ) {
            return;
        }

        update_post_meta(
            $post_id,
            '_booking_status',
            $new_status
        );

        if ($old_status !== $new_status) {

            if (
                in_array(
                    $new_status,
                    array(
                        'confirmed',
                        'cancelled',
                        'completed'
                    ),
                    true
                )
            ) {

                luxury_barbershop_send_booking_status_email(
                    $post_id,
                    $new_status
                );

            }

        }

    }

}

add_action(
    'save_post_booking',
    'luxury_barbershop_save_booking_email'
);


/*======================================
 FRONTEND BOOKING HANDLER
======================================*/

function luxury_barbershop_submit_booking() {

    /* Security check */

    if (
        ! isset($_POST['booking_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['booking_nonce'])
            ),
            'luxury_barbershop_booking'
        )
    ) {
        wp_die('Security check failed.');
    }


    /* Collect form data */

    $name = isset($_POST['name'])
        ? sanitize_text_field(
            wp_unslash($_POST['name'])
        )
        : '';

    $email = isset($_POST['email'])
        ? sanitize_email(
            wp_unslash($_POST['email'])
        )
        : '';

    $phone = isset($_POST['phone'])
        ? sanitize_text_field(
            wp_unslash($_POST['phone'])
        )
        : '';

    $service = isset($_POST['service'])
        ? sanitize_text_field(
            wp_unslash($_POST['service'])
        )
        : '';

    $barber = isset($_POST['barber'])
        ? sanitize_text_field(
            wp_unslash($_POST['barber'])
        )
        : '';

    $appointment_type = isset($_POST['appointment_type'])
        ? sanitize_text_field(
            wp_unslash($_POST['appointment_type'])
        )
        : '';

    $booking_address = isset($_POST['booking_address'])
        ? sanitize_textarea_field(
            wp_unslash($_POST['booking_address'])
        )
        : '';

    $date = isset($_POST['date'])
        ? sanitize_text_field(
            wp_unslash($_POST['date'])
        )
        : '';

    $time = isset($_POST['time'])
        ? sanitize_text_field(
            wp_unslash($_POST['time'])
        )
        : '';

    $notes = isset($_POST['notes'])
        ? sanitize_textarea_field(
            wp_unslash($_POST['notes'])
        )
        : '';


    /* Helper for returning booking errors */

    $booking_page = get_page_by_path('bookings');

    $booking_url = $booking_page
        ? get_permalink($booking_page)
        : home_url('/bookings/');


    /* Validate name */

    if (empty($name)) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'name',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate email */

    if (
        empty($email) ||
        ! is_email($email)
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'email',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate phone */

    if (empty($phone)) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'phone',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate service */

    if (empty($service)) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'service',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate barber */

    if (empty($barber)) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'barber',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate appointment type */

    if (
        ! in_array(
            $appointment_type,
            array('salon', 'home'),
            true
        )
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'appointment_type',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate home address */

    if (
        $appointment_type === 'home' &&
        empty($booking_address)
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'address',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Salon appointments do not need an address */

    if ($appointment_type === 'salon') {
        $booking_address = '';
    }


    /* Validate selected service exists */

    $service_query = new WP_Query(
        array(
            'post_type'      => 'service',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'title'          => $service
        )
    );

    if (! $service_query->have_posts()) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'service',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }

    $service_query->the_post();

    $service_id = get_the_ID();

    $service_price = absint(
        get_post_meta(
            $service_id,
            'service_price',
            true
        )
    );

    wp_reset_postdata();


    /* Validate selected barber exists */

    $barber_query = new WP_Query(
        array(
            'post_type'      => 'team',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'title'          => $barber
        )
    );

    if (! $barber_query->have_posts()) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'barber',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }

    wp_reset_postdata();


    /* Get home service fee */

    $home_service_fee = 0;

    if ($appointment_type === 'home') {

        $home_service_fee = absint(
            get_option(
                'luxury_home_service_fee',
                0
            )
        );

    }


    /* Calculate booking total */

    $booking_total =
        $service_price +
        $home_service_fee;


    /* Validate date */

    if (empty($date)) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'date',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }

    $date_object = DateTime::createFromFormat(
        'Y-m-d',
        $date
    );

    $date_errors = DateTime::getLastErrors();

    if (
        ! $date_object ||
        (
            $date_errors !== false &&
            (
                $date_errors['warning_count'] > 0 ||
                $date_errors['error_count'] > 0
            )
        ) ||
        $date_object->format('Y-m-d') !== $date
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'date',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Prevent past-date bookings */

    if (
        $date < current_time('Y-m-d')
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'date',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Validate time */

    if (empty($time)) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'time',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }

    $time_object = DateTime::createFromFormat(
        'H:i',
        $time
    );

    $time_errors = DateTime::getLastErrors();

    if (
        ! $time_object ||
        (
            $time_errors !== false &&
            (
                $time_errors['warning_count'] > 0 ||
                $time_errors['error_count'] > 0
            )
        ) ||
        $time_object->format('H:i') !== $time
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'time',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Enforce 30-minute booking intervals */

    $minute = (int) $time_object->format('i');

    if (
        $minute !== 0 &&
        $minute !== 30
    ) {

        wp_safe_redirect(
            add_query_arg(
                'booking_error',
                'time',
                remove_query_arg(
                    'booking',
                    $booking_url
                )
            )
        );

        exit;
    }


    /* Get opening hours */

    $selected_day = (int) $date_object->format('w');

    if ($selected_day === 0) {

        $opening_time = get_option(
            'luxury_sunday_open',
            '10:00'
        );

        $closing_time = get_option(
            'luxury_sunday_close',
            '18:00'
        );

    } else {

        $opening_time = get_option(
            'luxury_week_open',
            '09:00'
        );

        $closing_time = get_option(
            'luxury_week_close',
            '20:00'
        );

    }


    /* Validate booking time against opening hours */

    if (
        $time < $opening_time ||
        $time > $closing_time
    ) {

        wp_safe_redirect(
            $booking_url . '?booking=closed'
        );

        exit;
    }


    /* Check for existing booking */

    $existing_booking = new WP_Query(
        array(
            'post_type'      => 'booking',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'meta_query'     => array(

                'relation' => 'AND',

                array(
                    'key'   => '_booking_barber',
                    'value' => $barber
                ),

                array(
                    'key'   => '_booking_date',
                    'value' => $date
                ),

                array(
                    'key'   => '_booking_time',
                    'value' => $time
                )

            )
        )
    );


    /* Prevent duplicate booking */

    if ($existing_booking->have_posts()) {

        wp_safe_redirect(
            $booking_url . '?booking=unavailable'
        );

        exit;
    }

    wp_reset_postdata();


    /* Create booking as a draft until payment is verified */

    $booking_id = wp_insert_post(
        array(
            'post_type'   => 'booking',
            'post_status' => 'draft',
            'post_title'  => $name
        ),
        true
    );


    if (
        is_wp_error($booking_id) ||
        ! $booking_id
    ) {

        wp_die(
            'Unable to create booking.'
        );

    }


    /* Save booking details */

    update_post_meta(
        $booking_id,
        '_booking_email',
        $email
    );

    update_post_meta(
        $booking_id,
        '_booking_phone',
        $phone
    );

    update_post_meta(
        $booking_id,
        '_booking_service',
        $service
    );

    update_post_meta(
        $booking_id,
        '_booking_barber',
        $barber
    );

    update_post_meta(
        $booking_id,
        '_booking_type',
        $appointment_type
    );

    update_post_meta(
        $booking_id,
        '_booking_address',
        $booking_address
    );

    update_post_meta(
        $booking_id,
        '_booking_home_fee',
        $home_service_fee
    );

    update_post_meta(
        $booking_id,
        '_booking_service_price',
        $service_price
    );

    update_post_meta(
        $booking_id,
        '_booking_total',
        $booking_total
    );

    $booking_currency = luxury_barbershop_sanitize_currency_code(
        get_option(
            'luxury_currency',
            'NGN'
        )
    );

    update_post_meta(
        $booking_id,
        '_booking_currency',
        $booking_currency
    );

    update_post_meta(
        $booking_id,
        '_booking_date',
        $date
    );

    update_post_meta(
        $booking_id,
        '_booking_time',
        $time
    );

    update_post_meta(
        $booking_id,
        '_booking_notes',
        $notes
    );

    update_post_meta(
        $booking_id,
        '_booking_status',
        'payment_pending'
    );


    /* Initialize Paystack payment */

    $paystack_payment = luxury_barbershop_initialize_paystack_transaction(
        $booking_id
    );


    /* Handle Paystack initialization failure */

    if (is_wp_error($paystack_payment)) {

        wp_delete_post($booking_id, true);

        wp_die(
            'Paystack Error: ' . $paystack_payment->get_error_message()
        );

    }


    /* Payment initialized successfully */

    wp_redirect(
        $paystack_payment['authorization_url']
    );

    exit;

}


add_action(
    'admin_post_nopriv_luxury_barbershop_submit_booking',
    'luxury_barbershop_submit_booking'
);

add_action(
    'admin_post_luxury_barbershop_submit_booking',
    'luxury_barbershop_submit_booking'
);


/*======================================
 CONTACT SETTINGS MENU
======================================*/

function luxury_barbershop_contact_settings() {

    add_theme_page(
        'Contact Settings',
        'Contact Settings',
        'manage_options',
        'contact-settings',
        'luxury_barbershop_contact_settings_page'
    );

}

add_action(
    'admin_menu',
    'luxury_barbershop_contact_settings'
);


/*======================================
 CONTACT SETTINGS SANITIZERS
======================================*/

function luxury_barbershop_sanitize_contact_text($value) {

    return sanitize_text_field(
        wp_unslash($value)
    );
}


function luxury_barbershop_sanitize_contact_url($value) {

    return esc_url_raw(
        wp_unslash($value)
    );
}


function luxury_barbershop_sanitize_time($value) {

    $value = sanitize_text_field(
        wp_unslash($value)
    );

    $time = DateTime::createFromFormat(
        'H:i',
        $value
    );

    $errors = DateTime::getLastErrors();

    if (
        ! $time ||
        (
            $errors !== false &&
            (
                $errors['warning_count'] > 0 ||
                $errors['error_count'] > 0
            )
        ) ||
        $time->format('H:i') !== $value
    ) {
        return '';
    }

    return $value;
}


function luxury_barbershop_sanitize_contact_currency($value) {

    $allowed = array(
        '₦',
        '$',
        '£',
        '€'
    );

    $value = sanitize_text_field(
        wp_unslash($value)
    );

    return in_array(
        $value,
        $allowed,
        true
    )
        ? $value
        : '₦';
}


/*======================================
 CONTACT SETTINGS PAGE
======================================*/

function luxury_barbershop_contact_settings_page() {
    ?>

    <div class="wrap">

        <h1>Contact Settings</h1>

        <form
            method="POST"
            action="options.php"
        >

            <?php
            settings_fields(
                'luxury_contact_group'
            );
            ?>

            <table class="form-table">

                <tr>

                    <th>Address</th>

                    <td>

                        <input
                            type="text"
                            name="luxury_address"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_address'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>

                <tr>

                    <th>Phone</th>

                    <td>

                        <input
                            type="text"
                            name="luxury_phone"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_phone'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>

                <tr>

                    <th>Email</th>

                    <td>

                        <input
                            type="email"
                            name="luxury_email"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_email'
                                )
                            ); ?>"
                            class="regular-text"
                        >

                    </td>

                </tr>

                <tr>

                    <th>Currency</th>

                    <td>

                        <select name="luxury_currency">

                            <?php
                            $currency = get_option(
                                'luxury_currency',
                                '₦'
                            );
                            ?>

                            <option
                                value="₦"
                                <?php selected(
                                    $currency,
                                    '₦'
                                ); ?>
                            >
                                ₦ — Nigerian Naira
                            </option>

                            <option
                                value="$"
                                <?php selected(
                                    $currency,
                                    '$'
                                ); ?>
                            >
                                $ — US Dollar
                            </option>

                            <option
                                value="£"
                                <?php selected(
                                    $currency,
                                    '£'
                                ); ?>
                            >
                                £ — British Pound
                            </option>

                            <option
                                value="€"
                                <?php selected(
                                    $currency,
                                    '€'
                                ); ?>
                            >
                                € — Euro
                            </option>

                        </select>

                        <p class="description">
                            Currency symbol used for service and pricing amounts.
                        </p>

                    </td>

                </tr>

                <tr>

                    <th>
                        Monday – Saturday Opening
                    </th>

                    <td>

                        <input
                            type="time"
                            name="luxury_week_open"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_week_open',
                                    '09:00'
                                )
                            ); ?>"
                            step="1800"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        Monday – Saturday Closing
                    </th>

                    <td>

                        <input
                            type="time"
                            name="luxury_week_close"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_week_close',
                                    '20:00'
                                )
                            ); ?>"
                            step="1800"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        Sunday Opening
                    </th>

                    <td>

                        <input
                            type="time"
                            name="luxury_sunday_open"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_sunday_open',
                                    '10:00'
                                )
                            ); ?>"
                            step="1800"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        Sunday Closing
                    </th>

                    <td>

                        <input
                            type="time"
                            name="luxury_sunday_close"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_sunday_close',
                                    '18:00'
                                )
                            ); ?>"
                            step="1800"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        Google Maps Embed URL
                    </th>

                    <td>

                        <input
                            type="url"
                            name="luxury_map_url"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_map_url'
                                )
                            ); ?>"
                            class="regular-text"
                            placeholder="Paste Google Maps embed URL"
                        >

                        <p class="description">
                            Paste the Google Maps embed URL for the business location.
                        </p>

                    </td>

                </tr>

                <tr>

                    <th>
                        Instagram URL
                    </th>

                    <td>

                        <input
                            type="url"
                            name="luxury_instagram"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_instagram'
                                )
                            ); ?>"
                            class="regular-text"
                            placeholder="https://instagram.com/yourpage"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        Facebook URL
                    </th>

                    <td>

                        <input
                            type="url"
                            name="luxury_facebook"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_facebook'
                                )
                            ); ?>"
                            class="regular-text"
                            placeholder="https://facebook.com/yourpage"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        TikTok URL
                    </th>

                    <td>

                        <input
                            type="url"
                            name="luxury_tiktok"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_tiktok'
                                )
                            ); ?>"
                            class="regular-text"
                            placeholder="https://tiktok.com/@yourpage"
                        >

                    </td>

                </tr>

                <tr>

                    <th>
                        X (Twitter) URL
                    </th>

                    <td>

                        <input
                            type="url"
                            name="luxury_x"
                            value="<?php echo esc_attr(
                                get_option(
                                    'luxury_x'
                                )
                            ); ?>"
                            class="regular-text"
                            placeholder="https://x.com/yourpage"
                        >

                    </td>

                </tr>

            </table>

            <?php
            submit_button(
                'Save Contact Settings'
            );
            ?>

        </form>

    </div>

    <?php
}


/*======================================
 REGISTER CONTACT SETTINGS
======================================*/

function luxury_barbershop_register_contact_settings() {

    register_setting(
        'luxury_contact_group',
        'luxury_address',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_text'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_phone',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_text'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_email',
        array(
            'sanitize_callback' =>
                'sanitize_email'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_currency',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_currency'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_week_open',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_time'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_week_close',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_time'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_sunday_open',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_time'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_sunday_close',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_time'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_map_url',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_url'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_instagram',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_url'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_facebook',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_url'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_tiktok',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_url'
        )
    );

    register_setting(
        'luxury_contact_group',
        'luxury_x',
        array(
            'sanitize_callback' =>
                'luxury_barbershop_sanitize_contact_url'
        )
    );

}

add_action(
    'admin_init',
    'luxury_barbershop_register_contact_settings'
);


/*======================================
 BOOKING ADMIN COLUMNS
======================================*/

function luxury_barbershop_booking_columns($columns) {

    return array(

        'cb'           => $columns['cb'],
        'title'        => 'Customer',
        'service'      => 'Service',
        'barber'       => 'Barber',
        'booking_date' => 'Appointment Date',
        'time'         => 'Time',
        'status'       => 'Status',
        'date'         => 'Published'

    );

}

add_filter(
    'manage_booking_posts_columns',
    'luxury_barbershop_booking_columns'
);


function luxury_barbershop_booking_column_content(
    $column,
    $post_id
) {

    switch ($column) {

        case 'service':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_booking_service',
                    true
                )
            );

            break;


        case 'barber':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_booking_barber',
                    true
                )
            );

            break;


        case 'booking_date':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_booking_date',
                    true
                )
            );

            break;


        case 'time':

            echo esc_html(
                get_post_meta(
                    $post_id,
                    '_booking_time',
                    true
                )
            );

            break;


        case 'status':

            $status = get_post_meta(
                $post_id,
                '_booking_status',
                true
            );

            if (
                ! in_array(
                    $status,
                    array(
                        'pending',
                        'confirmed',
                        'completed',
                        'cancelled'
                    ),
                    true
                )
            ) {
                $status = 'pending';
            }

            ?>

            <select
                class="booking-status-select"
                data-booking-id="<?php echo esc_attr(
                    $post_id
                ); ?>"
            >

                <option
                    value="pending"
                    <?php selected(
                        $status,
                        'pending'
                    ); ?>
                >
                    Pending
                </option>

                <option
                    value="confirmed"
                    <?php selected(
                        $status,
                        'confirmed'
                    ); ?>
                >
                    Confirmed
                </option>

                <option
                    value="completed"
                    <?php selected(
                        $status,
                        'completed'
                    ); ?>
                >
                    Completed
                </option>

                <option
                    value="cancelled"
                    <?php selected(
                        $status,
                        'cancelled'
                    ); ?>
                >
                    Cancelled
                </option>

            </select>

            <?php

            break;

    }

}

add_action(
    'manage_booking_posts_custom_column',
    'luxury_barbershop_booking_column_content',
    10,
    2
);


/*======================================
 BOOKING STATUS EMAIL
======================================*/

function luxury_barbershop_send_booking_status_email(
    $booking_id,
    $status
) {

    if (
        get_post_type($booking_id) !== 'booking'
    ) {
        return;
    }

    $allowed_statuses = array(
        'confirmed',
        'cancelled',
        'completed'
    );

    if (
        ! in_array(
            $status,
            $allowed_statuses,
            true
        )
    ) {
        return;
    }

    $customer_email = sanitize_email(
        get_post_meta(
            $booking_id,
            '_booking_email',
            true
        )
    );

    if (! is_email($customer_email)) {
        return;
    }

    $customer_name = esc_html(
        get_the_title($booking_id)
    );

    $service = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_service',
            true
        )
    );

    $barber = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_barber',
            true
        )
    );

    $date = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_date',
            true
        )
    );

    $time = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_time',
            true
        )
    );


    if ($status === 'confirmed') {

        $subject =
            'Your Barbershop Appointment is Confirmed';

        $heading =
            'Appointment Confirmed';

        $intro =
            'Great news! Your appointment has been confirmed. We look forward to seeing you.';

    } elseif ($status === 'cancelled') {

        $subject =
            'Your Barbershop Appointment Has Been Cancelled';

        $heading =
            'Appointment Cancelled';

        $intro =
            'We’re sorry to let you know that your appointment has been cancelled. Please contact us if you would like to arrange another appointment.';

    } elseif ($status === 'completed') {

        $subject =
            'Thank You for Visiting Luxury Barbershop';

        $heading =
            'Appointment Completed';

        $intro =
            'Thank you for visiting Luxury Barbershop. We hope you enjoyed your experience and look forward to welcoming you again.';

    } else {

        return;

    }


    $message = "
    <html>
    <body style='margin:0; padding:0; background-color:#0f0f0f; font-family:Arial, sans-serif; color:#ffffff;'>

        <div style='max-width:600px; margin:40px auto; background-color:#111827; border-radius:10px; overflow:hidden;'>

            <div style='padding:30px; text-align:center; background-color:#0f0f0f;'>

                <h1 style='margin:0; color:#d4af37; font-size:28px;'>
                    Luxury Barbershop
                </h1>

                <p style='margin:10px 0 0; color:#ffffff; font-size:14px;'>
                    PREMIUM GROOMING EXPERIENCE
                </p>

            </div>

            <div style='padding:35px;'>

                <h2 style='margin-top:0; color:#d4af37;'>
                    {$heading}
                </h2>

                <p style='font-size:16px; line-height:1.6;'>
                    Hi {$customer_name},
                </p>

                <p style='font-size:16px; line-height:1.6;'>
                    {$intro}
                </p>

                <div style='margin:30px 0; padding:20px; background-color:#0f0f0f; border-left:4px solid #d4af37;'>

                    <p style='margin:8px 0;'>
                        <strong style='color:#d4af37;'>Service:</strong>
                        {$service}
                    </p>

                    <p style='margin:8px 0;'>
                        <strong style='color:#d4af37;'>Barber:</strong>
                        {$barber}
                    </p>

                    <p style='margin:8px 0;'>
                        <strong style='color:#d4af37;'>Date:</strong>
                        {$date}
                    </p>

                    <p style='margin:8px 0;'>
                        <strong style='color:#d4af37;'>Time:</strong>
                        {$time}
                    </p>

                </div>

                <p style='font-size:15px; line-height:1.6;'>
                    Thank you for choosing Luxury Barbershop.
                </p>

            </div>

            <div style='padding:20px; text-align:center; background-color:#0f0f0f; color:#888888; font-size:12px;'>
                Luxury Barbershop
            </div>

        </div>

    </body>
    </html>
    ";


    $headers = array(
        'Content-Type: text/html; charset=UTF-8'
    );

    wp_mail(
        $customer_email,
        $subject,
        $message,
        $headers
    );

}


/*======================================
 ADMIN BOOKING NOTIFICATION
======================================*/

function luxury_barbershop_send_admin_booking_notification(
    $booking_id
) {

    if (
        get_post_type($booking_id) !== 'booking'
    ) {
        return;
    }

    $admin_email = get_option(
        'admin_email'
    );

    if (! is_email($admin_email)) {
        return;
    }

    $customer_email = sanitize_email(
        get_post_meta(
            $booking_id,
            '_booking_email',
            true
        )
    );

    $customer_name = esc_html(
        get_the_title($booking_id)
    );

    $customer_phone = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_phone',
            true
        )
    );

    $service = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_service',
            true
        )
    );

    $barber = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_barber',
            true
        )
    );

    $booking_type = get_post_meta(
        $booking_id,
        '_booking_type',
        true
    );

    $booking_address = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_address',
            true
        )
    );

    $service_price = (float) get_post_meta(
        $booking_id,
        '_booking_service_price',
        true
    );

    $home_fee = (float) get_post_meta(
        $booking_id,
        '_booking_home_fee',
        true
    );

    $booking_total = (float) get_post_meta(
        $booking_id,
        '_booking_total',
        true
    );

    $currency = get_post_meta(
        $booking_id,
        '_booking_currency',
        true
    );

    $currency_symbols = array(
        'NGN' => '₦',
        'USD' => '$',
        'GBP' => '£',
        'EUR' => '€'
    );

    $currency_symbol = isset(
        $currency_symbols[$currency]
    )
        ? $currency_symbols[$currency]
        : '₦';

    $appointment_label =
        $booking_type === 'home'
        ? 'Home Appointment'
        : 'Visit the Salon';

    $date = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_date',
            true
        )
    );

    $time = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_time',
            true
        )
    );

    $notes = esc_html(
        get_post_meta(
            $booking_id,
            '_booking_notes',
            true
        )
    );

    $booking_url = admin_url(
        'post.php?post=' .
        absint($booking_id) .
        '&action=edit'
    );

    $payment_status = get_post_meta(
        $booking_id,
        '_payment_status',
        true
    );

    $needs_review = get_post_meta(
        $booking_id,
        '_payment_review_required',
        true
    ) === 'yes';

    $subject = $needs_review
        ? 'ACTION REQUIRED: Late Payment — Booking #' . $booking_id
        : 'New Booking Received';

    $review_notice = $needs_review
        ? "
            <div style='padding:15px; margin-bottom:20px; background:#7f1d1d; color:#ffffff; border-radius:5px;'>
                <strong>MANUAL REVIEW REQUIRED</strong>
                <p>This customer paid after the booking payment expired. The payment has been verified, but the appointment is NOT confirmed. Please review the booking and decide whether to accept the appointment or arrange a refund.</p>
            </div>
        "
        : '';


    $message = "
    <html>
    <body style='margin:0; padding:0; background:#0f0f0f; font-family:Arial,sans-serif;'>

        <div style='max-width:600px; margin:40px auto; background:#111827; border-radius:10px; overflow:hidden;'>

            <div style='background:#0f0f0f; padding:25px; text-align:center;'>

                <h1 style='margin:0; color:#d4af37;'>
                    Luxury Barbershop
                </h1>

                <p style='color:#ffffff; margin:8px 0 0;'>
                    New Booking Notification
                </p>

            </div>

            <div style='padding:30px; color:#ffffff;'>

                {$review_notice}

                <h2 style='color:#d4af37; margin-top:0;'>
                    A customer has booked an appointment
                </h2>

                <table style='width:100%; border-collapse:collapse;'>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Name</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$customer_name}
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Email</strong>
                        </td>
                        <td style='padding:10px;'>
                            " . esc_html($customer_email) . "
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Phone</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$customer_phone}
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Service</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$service}
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Barber</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$barber}
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Appointment</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$appointment_label}
                        </td>
                    </tr>

                    " .
                    (
                        $booking_type === 'home'
                        ? "
                    <tr>
                        <td style='padding:10px; color:#d4af37; vertical-align:top;'>
                            <strong>Address</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$booking_address}
                        </td>
                    </tr>
                    "
                        : ''
                    )
                    . "

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Date</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$date}
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Time</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$time}
                        </td>
                    </tr>

                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Service Price</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$currency_symbol}" .
                            number_format($service_price) .
                            "
                        </td>
                    </tr>

                    " .
                    (
                        $booking_type === 'home'
                        ? "
                    <tr>
                        <td style='padding:10px; color:#d4af37;'>
                            <strong>Home Service Fee</strong>
                        </td>
                        <td style='padding:10px;'>
                            {$currency_symbol}" .
                            number_format($home_fee) .
                            "
                        </td>
                    </tr>
                    "
                        : ''
                    )
                    . "

                    <tr style='border-top:1px solid #374151;'>

                        <td style='padding:14px 10px; color:#d4af37;'>
                            <strong>Booking Total</strong>
                        </td>

                        <td style='padding:14px 10px; font-size:18px; font-weight:bold; color:#ffffff;'>
                            {$currency_symbol}" .
                            number_format($booking_total) .
                            "
                        </td>

                    </tr>

                    <tr>

                        <td style='padding:10px; color:#d4af37; vertical-align:top;'>
                            <strong>Notes</strong>
                        </td>

                        <td style='padding:10px;'>
                            {$notes}
                        </td>

                    </tr>

                </table>

                <div style='text-align:center; margin-top:30px;'>

                    <a
                        href='" . esc_url($booking_url) . "'
                        style='display:inline-block; padding:14px 25px; background:#d4af37; color:#0f0f0f; text-decoration:none; font-weight:bold; border-radius:5px;'
                    >
                        View Booking
                    </a>

                </div>

            </div>

        </div>

    </body>
    </html>
    ";


    $headers = array(
        'Content-Type: text/html; charset=UTF-8'
    );

    wp_mail(
        $admin_email,
        $subject,
        $message,
        $headers
    );

}


/*======================================
 AJAX BOOKING STATUS UPDATE
======================================*/

function luxury_barbershop_update_booking_status() {

    check_ajax_referer(
        'booking_status_nonce',
        'nonce'
    );


    if (
        ! isset($_POST['booking_id']) ||
        ! isset($_POST['status'])
    ) {
        wp_send_json_error();
    }


    $booking_id = absint(
        $_POST['booking_id']
    );

    $new_status = sanitize_text_field(
        wp_unslash($_POST['status'])
    );


    if (
        ! $booking_id ||
        get_post_type($booking_id) !== 'booking'
    ) {
        wp_send_json_error();
    }


    if (
        ! current_user_can(
            'edit_post',
            $booking_id
        )
    ) {
        wp_send_json_error();
    }


    $allowed_statuses = array(
        'pending',
        'confirmed',
        'completed',
        'cancelled'
    );


    if (
        ! in_array(
            $new_status,
            $allowed_statuses,
            true
        )
    ) {
        wp_send_json_error();
    }


    $old_status = get_post_meta(
        $booking_id,
        '_booking_status',
        true
    );


    update_post_meta(
        $booking_id,
        '_booking_status',
        $new_status
    );


    if ($old_status !== $new_status) {

        if (
            in_array(
                $new_status,
                array(
                    'confirmed',
                    'cancelled',
                    'completed'
                ),
                true
            )
        ) {

            luxury_barbershop_send_booking_status_email(
                $booking_id,
                $new_status
            );

        }

    }


    wp_send_json_success();

}

add_action(
    'wp_ajax_update_booking_status',
    'luxury_barbershop_update_booking_status'
);


/*======================================
 ADMIN BOOKING SCRIPTS
======================================*/

function luxury_barbershop_admin_scripts($hook) {

    if (
        $hook !== 'edit.php' ||
        get_post_type() !== 'booking'
    ) {
        return;
    }


    wp_enqueue_style(
        'luxury-barbershop-admin',
        get_template_directory_uri() .
        '/assets/css/admin.css',
        array(),
        '1.0'
    );


    wp_enqueue_script(
        'luxury-barbershop-admin',
        get_template_directory_uri() .
        '/assets/js/main.js',
        array(),
        '1.0',
        true
    );


    wp_localize_script(
        'luxury-barbershop-admin',
        'luxuryBooking',
        array(
            'ajaxurl' => admin_url(
                'admin-ajax.php'
            ),
            'nonce' => wp_create_nonce(
                'booking_status_nonce'
            )
        )
    );

}

add_action(
    'admin_enqueue_scripts',
    'luxury_barbershop_admin_scripts'
);


/*======================================
 BOOKING STATUS FILTER
======================================*/

function luxury_barbershop_booking_status_filter() {

    global $typenow;

    if ($typenow !== 'booking') {
        return;
    }


    $allowed_statuses = array(
        'pending',
        'confirmed',
        'completed',
        'cancelled'
    );


    $selected_status = isset(
        $_GET['booking_status']
    )
        ? sanitize_text_field(
            wp_unslash(
                $_GET['booking_status']
            )
        )
        : '';


    if (
        ! in_array(
            $selected_status,
            $allowed_statuses,
            true
        )
    ) {
        $selected_status = '';
    }

    ?>

    <select name="booking_status">

        <option value="">
            All statuses
        </option>

        <option
            value="pending"
            <?php selected(
                $selected_status,
                'pending'
            ); ?>
        >
            Pending
        </option>

        <option
            value="confirmed"
            <?php selected(
                $selected_status,
                'confirmed'
            ); ?>
        >
            Confirmed
        </option>

        <option
            value="completed"
            <?php selected(
                $selected_status,
                'completed'
            ); ?>
        >
            Completed
        </option>

        <option
            value="cancelled"
            <?php selected(
                $selected_status,
                'cancelled'
            ); ?>
        >
            Cancelled
        </option>

    </select>

    <?php
}

add_action(
    'restrict_manage_posts',
    'luxury_barbershop_booking_status_filter'
);


/*======================================
 FILTER BOOKINGS BY STATUS
======================================*/

function luxury_barbershop_filter_bookings_by_status(
    $query
) {

    if (
        ! is_admin() ||
        ! $query->is_main_query() ||
        $query->get('post_type') !== 'booking'
    ) {
        return;
    }


    $allowed_statuses = array(
        'pending',
        'confirmed',
        'completed',
        'cancelled'
    );


    if (
        isset($_GET['booking_status']) &&
        ! empty($_GET['booking_status'])
    ) {

        $status = sanitize_text_field(
            wp_unslash(
                $_GET['booking_status']
            )
        );


        if (
            in_array(
                $status,
                $allowed_statuses,
                true
            )
        ) {

            $query->set(
                'meta_query',
                array(
                    array(
                        'key'     => '_booking_status',
                        'value'   => $status,
                        'compare' => '='
                    )
                )
            );

        }

    }


    $query->set(
        'meta_key',
        '_booking_date'
    );

    $query->set(
        'orderby',
        'meta_value'
    );

    $query->set(
        'order',
        'ASC'
    );

}

add_action(
    'pre_get_posts',
    'luxury_barbershop_filter_bookings_by_status'
);


/*======================================
 BOOKING DASHBOARD
======================================*/

function luxury_barbershop_booking_dashboard(
    $which
) {

    global $typenow;

    if ($typenow !== 'booking') {
        return;
    }

    if ($which !== 'top') {
        return;
    }


    $today = current_time(
        'Y-m-d'
    );


    $today_bookings = new WP_Query(
        array(
            'post_type'  => 'booking',
            'meta_key'   => '_booking_date',
            'meta_value' => $today,
            'fields'     => 'ids'
        )
    );


    $pending = new WP_Query(
        array(
            'post_type'  => 'booking',
            'meta_key'   => '_booking_status',
            'meta_value' => 'pending',
            'fields'     => 'ids'
        )
    );


    $confirmed = new WP_Query(
        array(
            'post_type'  => 'booking',
            'meta_key'   => '_booking_status',
            'meta_value' => 'confirmed',
            'fields'     => 'ids'
        )
    );


    $booking_counts = wp_count_posts(
        'booking'
    );

    $total = isset(
        $booking_counts->publish
    )
        ? (int) $booking_counts->publish
        : 0;

    ?>

    <div class="booking-dashboard">

        <div class="booking-card">

            <span>
                Today's Bookings
            </span>

            <h2>
                <?php echo esc_html(
                    $today_bookings->found_posts
                ); ?>
            </h2>

        </div>


        <div class="booking-card">

            <span>
                Pending
            </span>

            <h2>
                <?php echo esc_html(
                    $pending->found_posts
                ); ?>
            </h2>

        </div>


        <div class="booking-card">

            <span>
                Confirmed
            </span>

            <h2>
                <?php echo esc_html(
                    $confirmed->found_posts
                ); ?>
            </h2>

        </div>


        <div class="booking-card">

            <span>
                Total Bookings
            </span>

            <h2>
                <?php echo esc_html(
                    $total
                ); ?>
            </h2>

        </div>

    </div>

    <?php
}

add_action(
    'manage_posts_extra_tablenav',
    'luxury_barbershop_booking_dashboard',
    5
);


/*======================================
 DELETE PAST BOOKINGS BUTTON
======================================*/

function luxury_barbershop_delete_past_bookings_button(
    $which
) {

    global $typenow;

    if ($typenow !== 'booking') {
        return;
    }

    if ($which !== 'top') {
        return;
    }

    ?>

    <div class="booking-cleanup">

        <a
            href="<?php echo esc_url(
                wp_nonce_url(
                    admin_url(
                        'edit.php?post_type=booking&delete_past_bookings=1'
                    ),
                    'delete_past_bookings'
                )
            ); ?>"
            class="button"
            onclick="return confirm('Move all past bookings to Trash?');"
        >
            Delete Past Bookings
        </a>

    </div>

    <?php
}

add_action(
    'manage_posts_extra_tablenav',
    'luxury_barbershop_delete_past_bookings_button',
    20
);


/*======================================
 DELETE PAST BOOKINGS
======================================*/

function luxury_barbershop_delete_past_bookings() {

    if (
        ! isset(
            $_GET['delete_past_bookings']
        )
    ) {
        return;
    }


    if (
        ! current_user_can(
            'manage_options'
        )
    ) {
        return;
    }


    check_admin_referer(
        'delete_past_bookings'
    );


    $today = current_time(
        'Y-m-d'
    );


    $past_bookings = new WP_Query(
        array(
            'post_type'      => 'booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => '_booking_date',
            'meta_value'     => $today,
            'meta_compare'   => '<',
            'meta_type'      => 'DATE',
            'fields'         => 'ids'
        )
    );


    foreach (
        $past_bookings->posts
        as $booking_id
    ) {

        if (
            current_user_can(
                'delete_post',
                $booking_id
            )
        ) {

            wp_trash_post(
                $booking_id
            );

        }

    }


    wp_safe_redirect(
        admin_url(
            'edit.php?post_type=booking&past_bookings_deleted=1'
        )
    );

    exit;
}

add_action(
    'admin_init',
    'luxury_barbershop_delete_past_bookings'
);


/*======================================
 MAILPIT DEVELOPMENT CONFIGURATION
======================================*/

/*
 * Local development only.
 *
 * Remove this configuration before
 * deploying the theme to production.
 */

function luxury_barbershop_mailpit_config(
    $phpmailer
) {

    $phpmailer->isSMTP();

    $phpmailer->Host = '127.0.0.1';

    $phpmailer->Port = 1025;

    $phpmailer->SMTPAuth = false;

    $phpmailer->SMTPSecure = '';

}

add_action(
    'phpmailer_init',
    'luxury_barbershop_mailpit_config'
);

/**
 * Theme Setup
 */
function luxury_barbershop_theme_setup() {

    /*
     * Create Bookings page if it does not already exist.
     */
    $bookings_page = get_page_by_path('bookings');

    if (! $bookings_page) {

        wp_insert_post([
            'post_title'   => 'Bookings',
            'post_name'    => 'bookings',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);

    }

}

 add_action(
    'after_switch_theme',
    'luxury_barbershop_theme_setup'
);

/*======================================
 DEMO CONTENT SETUP
======================================*/

function luxury_barbershop_create_demo_services() {

    // Prevent duplicates
    if (get_option('luxury_demo_services_created')) {
        return;
    }

    $services = array(

        array(
            'title'       => 'Luxury Haircut',
            'description' => 'A clean, precise cut tailored to your style and personality.',
            'price'       => 35,
            'duration'    => '45 min',
            'icon'        => 'editor-cut'
        ),

        array(
            'title'       => 'Beard Sculpting',
            'description' => 'Sharp beard shaping with hot towel finishing.',
            'price'       => 20,
            'duration'    => '30 min',
            'icon'        => 'admin-customizer'
        ),

        array(
            'title'       => 'Haircut & Beard Combo',
            'description' => 'Our complete grooming experience for the modern gentleman.',
            'price'       => 50,
            'duration'    => '60 min',
            'icon'        => 'star-filled'
        )

    );

    foreach ($services as $service) {

        $existing = get_page_by_title(
            $service['title'],
            OBJECT,
            'service'
        );

        if ($existing) {
            continue;
        }

        $post_id = wp_insert_post(array(
            'post_type'   => 'service',
            'post_status' => 'publish',
            'post_title'  => $service['title'],
            'post_content'=> $service['description']
        ));

        update_post_meta($post_id, 'service_price', $service['price']);
        update_post_meta($post_id, '_service_duration', $service['duration']);
        update_post_meta($post_id, 'service_icon', $service['icon']);

    }

    update_option('luxury_demo_services_created', true);

}

function luxury_barbershop_create_demo_services_on_activation() {

    luxury_barbershop_create_demo_services();

}

add_action(
    'after_switch_theme',
    'luxury_barbershop_create_demo_services_on_activation'
);

function luxury_barbershop_create_demo_team() {

    // Prevent duplicate demo team members
    if ( get_option( 'luxury_barbershop_demo_team_created' ) ) {
        return;
    }

    $team_members = array(

        array(
            'name' => 'James Carter',
            'role' => 'Master Barber',
        ),

        array(
            'name' => 'Michael Brown',
            'role' => 'Senior Barber',
        ),

        array(
            'name' => 'Daniel Wilson',
            'role' => 'Barber',
        ),

    );

    foreach ( $team_members as $member ) {

        $team_id = wp_insert_post(
            array(
                'post_title'  => $member['name'],
                'post_type'   => 'team',
                'post_status' => 'publish',
            )
        );

        if ( $team_id && ! is_wp_error( $team_id ) ) {

            update_post_meta(
                $team_id,
                '_team_role',
                $member['role']
            );

        }
    }

    update_option(
        'luxury_barbershop_demo_team_created',
        true
    );
}
function luxury_barbershop_create_demo_team_on_activation() {

    luxury_barbershop_create_demo_team();

}

add_action(
    'after_switch_theme',
    'luxury_barbershop_create_demo_team_on_activation'
);

function luxury_barbershop_create_demo_gallery() {

    // Prevent duplicate demo gallery items
    if ( get_option( 'luxury_barbershop_demo_gallery_created' ) ) {
        return;
    }

    $gallery_items = array(
        'Classic Fade',
        'Luxury Beard Trim',
        'Premium Haircut',
        'Precision Cut',
        'Gentleman Style',
        'Modern Grooming',
    );

    foreach ( $gallery_items as $index => $title ) {

        $gallery_id = wp_insert_post(
            array(
                'post_title'     => $title,
                'post_type'      => 'gallery',
                'post_status'    => 'publish',
                'menu_order'     => $index,
            )
        );
    }

    update_option(
        'luxury_barbershop_demo_gallery_created',
        true
    );
}
function luxury_barbershop_create_demo_gallery_on_activation() {

    luxury_barbershop_create_demo_gallery();

}

add_action(
    'after_switch_theme',
    'luxury_barbershop_create_demo_gallery_on_activation'
);

function luxury_barbershop_create_core_pages() {

    $pages = array(
        'Home',
        'About',
        'Services',
        'Gallery',
        'Team',
        'Pricing',
        'Bookings',
        'Contact'
    );

    $home_id = 0;

    foreach ( $pages as $title ) {

        $page = get_page_by_path( sanitize_title( $title ) );

        if ( ! $page ) {

            $page_id = wp_insert_post( array(
                'post_title'  => $title,
                'post_name'   => sanitize_title( $title ),
                'post_type'   => 'page',
                'post_status' => 'publish'
            ) );

        } else {

            $page_id = $page->ID;

        }

        if ( $title === 'Home' ) {
            $home_id = $page_id;
        }
    }

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home_id );
}

function luxury_barbershop_setup_pages_on_activation() {

    luxury_barbershop_create_core_pages();

    luxury_barbershop_create_primary_menu();

}

add_action(
    'after_switch_theme',
    'luxury_barbershop_setup_pages_on_activation'
);
function luxury_barbershop_create_primary_menu() {

    // Check if the menu already exists
    $menu = wp_get_nav_menu_object( 'Primary Menu' );

    if ( $menu ) {

        $menu_id = $menu->term_id;

    } else {

        // Create the menu
        $menu_id = wp_create_nav_menu( 'Primary Menu' );

        if ( is_wp_error( $menu_id ) ) {
            return;
        }

        $menu_items = array(
            'Home'     => home_url( '/#home' ),
            'About'    => home_url( '/#about' ),
            'Services' => home_url( '/#services' ),
            'Gallery'  => home_url( '/#gallery' ),
            'Team'     => home_url( '/#team' ),
            'Pricing'  => home_url( '/#pricing' ),
            'Bookings' => home_url( '/bookings/' ),
            'Contact'  => home_url( '/#contact' ),
        );

        foreach ( $menu_items as $title => $url ) {

            wp_update_nav_menu_item(
                $menu_id,
                0,
                array(
                    'menu-item-title'  => $title,
                    'menu-item-url'    => $url,
                    'menu-item-status' => 'publish',
                )
            );
        }
    }

    // Assign the menu to the Primary Menu location
    $locations = get_theme_mod( 'nav_menu_locations' );

    if ( ! is_array( $locations ) ) {
        $locations = array();
    }

    $locations['primary'] = $menu_id;

    set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Initialize Paystack transaction for a booking.
 */
function luxury_barbershop_initialize_paystack_transaction($booking_id) {

    // Make sure we have a valid booking.
    if (!$booking_id || get_post_type($booking_id) !== 'booking') {
        return new WP_Error(
            'invalid_booking',
            'Invalid booking.'
        );
    }

    // Get booking information from WordPress.
    $customer_email = get_post_meta(
        $booking_id,
        '_booking_email',
        true
    );

    $service = get_post_meta(
        $booking_id,
        '_booking_service',
        true
    );

    $booking_type = get_post_meta(
        $booking_id,
        '_booking_type',
        true
    );

    $currency = luxury_barbershop_sanitize_currency_code(
        get_post_meta(
            $booking_id,
            '_booking_currency',
            true
        )
    );

    // Get the service price from the actual service selected.
    $service_query = new WP_Query([
        'post_type'      => 'service',
        'title'          => $service,
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);

    if (!$service_query->have_posts()) {
        return new WP_Error(
            'service_not_found',
            'The selected service could not be found.'
        );
    }

    $service_id = $service_query->posts[0];

    // Get the service price stored in WordPress.
    $service_price = (float) get_post_meta(
        $service_id,
        'service_price',
        true
    );

    // Get the home appointment fee from WordPress settings.
    $home_fee = 0;

    if ($booking_type === 'home') {
        $home_fee = (float) get_option(
            'luxury_home_service_fee',
            0
        );
    }

    // Calculate the final amount on the server.
    $total_amount = $service_price + $home_fee;

    // Make sure the total is valid.
    if ($total_amount <= 0) {
        return new WP_Error(
            'invalid_amount',
            'Invalid booking amount.'
        );
    }

    // Paystack expects the amount in the smallest currency unit.
    $amount_in_subunits = (int) round($total_amount * 100);

    // Get the Paystack secret key.
    $secret_key = defined('PAYSTACK_SECRET_KEY')
        ? PAYSTACK_SECRET_KEY
        : '';

    if (empty($secret_key)) {
        return new WP_Error(
            'missing_paystack_key',
            'Paystack secret key is not configured.'
        );
    }

    // Prepare a callback URL that Paystack will redirect to after checkout.
    $callback_url = add_query_arg(
        'paystack_callback',
        '1',
        get_permalink(
            get_page_by_path('bookings')
        )
            ? get_permalink(get_page_by_path('bookings'))
            : home_url('/bookings/')
    );

    // Prepare the Paystack request.
    $response = wp_remote_post(
        'https://api.paystack.co/transaction/initialize',
        [
            'timeout' => 30,

            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type'  => 'application/json',
            ],

            'body' => wp_json_encode([
                'email'        => $customer_email,
                'amount'       => $amount_in_subunits,
                'currency'     => $currency,
                'callback_url' => $callback_url,
                'metadata'     => [
                    'booking_id' => (int) $booking_id,
                ],
            ]),
        ]
    );

    // Check for a WordPress HTTP error.
    if (is_wp_error($response)) {
        return $response;
    }

    // Decode Paystack's response.
    $body = json_decode(
        wp_remote_retrieve_body($response),
        true
    );

    // Make sure Paystack responded successfully.
    if (
        empty($body['status']) ||
        empty($body['data']['authorization_url']) ||
        empty($body['data']['reference'])
    ) {
        return new WP_Error(
            'paystack_initialization_failed',
            !empty($body['message'])
                ? $body['message']
                : 'Unable to initialize Paystack transaction.'
        );
    }

    // Save useful payment information against the booking.
    update_post_meta(
        $booking_id,
        '_payment_reference',
        sanitize_text_field($body['data']['reference'])
    );

    update_post_meta(
        $booking_id,
        '_payment_amount',
        $total_amount
    );

    update_post_meta(
        $booking_id,
        '_payment_currency',
        $currency
    );

    update_post_meta(
        $booking_id,
        '_payment_status',
        'pending'
    );

    update_post_meta(
        $booking_id,
        '_payment_initialized_at',
        current_time('mysql')
    );

    // Return the Paystack payment URL.
    return [
        'authorization_url' => esc_url_raw(
            $body['data']['authorization_url']
        ),
        'reference' => sanitize_text_field(
            $body['data']['reference']
        ),
        'amount' => $total_amount,
    ];
}


function luxury_barbershop_transition_payment_status(
    $booking_id,
    $from_status,
    $to_status
) {
    global $wpdb;

    $allowed = array('pending', 'expired', 'success');

    if (
        ! in_array($from_status, $allowed, true) ||
        ! in_array($to_status, $allowed, true)
    ) {
        return false;
    }

    $changed = $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$wpdb->postmeta}
             SET meta_value = %s
             WHERE post_id = %d
             AND meta_key = %s
             AND meta_value = %s",
            $to_status,
            absint($booking_id),
            '_payment_status',
            $from_status
        )
    );

    wp_cache_delete($booking_id, 'post_meta');

    return $changed === 1;
}

/*======================================
 PAYSTACK PAYMENT CALLBACK
======================================*/

/**
 * Handle Paystack payment callback and verify the transaction server-side.
 */

function luxury_barbershop_handle_paystack_callback() {

    if (
        ! isset($_GET['paystack_callback']) ||
        sanitize_text_field(
            wp_unslash($_GET['paystack_callback'])
        ) !== '1'
    ) {
        return;
    }

    $booking_page = get_page_by_path('bookings');

    $booking_url = $booking_page
        ? get_permalink($booking_page)
        : home_url('/bookings/');

    $redirect_payment = function ($status) use ($booking_url) {
        wp_safe_redirect(
            add_query_arg('payment', $status, $booking_url)
        );
        exit;
    };

    $reference = isset($_GET['reference'])
        ? sanitize_text_field(
            wp_unslash($_GET['reference'])
        )
        : '';

    if (empty($reference)) {
        $redirect_payment('failed');
    }

    $secret_key = defined('PAYSTACK_SECRET_KEY')
        ? PAYSTACK_SECRET_KEY
        : '';

    if (empty($secret_key)) {
        wp_die('Paystack secret key is not configured.');
    }

    // Verify the transaction directly with Paystack.
    $response = wp_remote_get(
        'https://api.paystack.co/transaction/verify/' .
        rawurlencode($reference),
        array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
            ),
        )
    );

    if (
        is_wp_error($response) ||
        wp_remote_retrieve_response_code($response) !== 200
    ) {
        $redirect_payment('failed');
    }

    $body = json_decode(
        wp_remote_retrieve_body($response),
        true
    );

    if (
        empty($body['status']) ||
        empty($body['data'])
    ) {
        $redirect_payment('failed');
    }

    $transaction = $body['data'];

    if (
        empty($transaction['status']) ||
        $transaction['status'] !== 'success'
    ) {
        $redirect_payment('failed');
    }

    // Identify the booking associated with this transaction.
    $booking_id = isset(
        $transaction['metadata']['booking_id']
    )
        ? absint($transaction['metadata']['booking_id'])
        : 0;

    if (
        ! $booking_id ||
        get_post_type($booking_id) !== 'booking'
    ) {
        $redirect_payment('failed');
    }

    // Confirm that the reference belongs to this booking.
    $saved_reference = get_post_meta(
        $booking_id,
        '_payment_reference',
        true
    );

    if (
        empty($saved_reference) ||
        ! hash_equals(
            (string) $saved_reference,
            (string) $reference
        )
    ) {
        $redirect_payment('failed');
    }

    // Confirm the exact amount calculated by WordPress.
    $expected_amount = (float) get_post_meta(
        $booking_id,
        '_booking_total',
        true
    );

    $paid_amount = isset($transaction['amount'])
        ? (int) $transaction['amount']
        : 0;

    $expected_amount_in_subunits = (int) round(
        $expected_amount * 100
    );

    if (
        $paid_amount !== $expected_amount_in_subunits
    ) {
        update_post_meta(
            $booking_id,
            '_payment_status',
            'amount_mismatch'
        );

        $redirect_payment('failed');
    }

    // Check currency.
    $expected_currency =
        luxury_barbershop_sanitize_currency_code(
            get_post_meta(
                $booking_id,
                '_booking_currency',
                true
            )
        );

    if (
        ! empty($transaction['currency']) &&
        strtoupper($transaction['currency']) !==
        strtoupper($expected_currency)
    ) {
        update_post_meta(
            $booking_id,
            '_payment_status',
            'currency_mismatch'
        );

        $redirect_payment('failed');
    }

    // Do not send duplicate notifications for an already
    // verified payment. All transaction checks happen first.
    $payment_status = get_post_meta(
    $booking_id,
        '_payment_status',
        true
    );

    if ($payment_status === 'success') {
        $redirect_payment('success');
    }

    $late_payment = false;

    if ($payment_status === 'expired') {
        $late_payment =
            luxury_barbershop_transition_payment_status(
                $booking_id,
                'expired',
                'success'
            );
    } elseif ($payment_status === 'pending') {
        $normal_payment =
            luxury_barbershop_transition_payment_status(
                $booking_id,
                'pending',
                'success'
            );
    } else {
        $redirect_payment('failed');
    }

    if (
        ! $late_payment &&
        empty($normal_payment)
    ) {
        $redirect_payment('failed');
    }

    // Keep the appointment unconfirmed until the owner reviews it.
    wp_update_post(
        array(
            'ID'          => $booking_id,
            'post_status' => 'publish',
        )
    );

    update_post_meta(
        $booking_id,
        '_booking_status',
        'pending'
    );

    update_post_meta(
        $booking_id,
        '_payment_reference',
        $reference
    );

    update_post_meta(
        $booking_id,
        '_payment_verified_at',
        current_time('mysql')
    );

    if ($late_payment) {

        // Verified payment received after the expiry cutoff.
        update_post_meta(
            $booking_id,
            '_payment_review_required',
            'yes'
        );

        update_post_meta(
            $booking_id,
            '_payment_review_reason',
            'late_payment'
        );

        update_post_meta(
            $booking_id,
            '_payment_review_flagged_at',
            current_time('mysql')
        );

    } else {

        update_post_meta(
            $booking_id,
            '_payment_review_required',
            'no'
        );
    }

    // Notify the owner after the verified payment is recorded.
    luxury_barbershop_send_admin_booking_notification(
        $booking_id
    );

    // Appointment confirmation is still a manual decision.
    $redirect_payment('success');
}

add_action(
    'template_redirect',
    'luxury_barbershop_handle_paystack_callback'
);

/**
 * Schedule abandoned Paystack payment checks.
 */
function luxury_barbershop_schedule_payment_expiry() {
    if ( ! wp_next_scheduled( 'luxury_barbershop_expire_pending_payments' ) ) {
        wp_schedule_event(
            time() + 300,
            'five_minutes',
            'luxury_barbershop_expire_pending_payments'
        );
    }
}
add_action( 'init', 'luxury_barbershop_schedule_payment_expiry' );

/**
 * Add a five-minute WP-Cron interval.
 */
function luxury_barbershop_payment_expiry_cron_interval( $schedules ) {
    $schedules['five_minutes'] = array(
        'interval' => 300,
        'display'  => 'Every Five Minutes',
    );

    return $schedules;
}
add_filter( 'cron_schedules', 'luxury_barbershop_payment_expiry_cron_interval' );

/**
 * Mark abandoned, unpaid bookings as expired after 30 minutes.
 */
function luxury_barbershop_expire_pending_payments() {
    $cutoff_date = wp_date(
        'Y-m-d H:i:s',
        time() - ( 30 * MINUTE_IN_SECONDS )
    );
    $bookings = get_posts( array(
        'post_type'      => 'booking',
        'post_status'    => array( 'publish', 'pending', 'draft', 'private' ),
        'posts_per_page' => 100,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_payment_status',
                'value'   => 'pending',
                'compare' => '=',
            ),
            array(
                'key'     => '_payment_initialized_at',
                'value'   => $cutoff_date,
                'compare' => '<=',
                'type'    => 'DATETIME',
            ),
        ),
    ) );

    foreach ( $bookings as $booking_id ) {

    // Expire only if the payment is still pending.
    $expired = luxury_barbershop_transition_payment_status(
    $booking_id,
        'pending',
        'expired'
    );

    if (!$expired) {
        continue;
    }

    // Record when the payment expired.
    update_post_meta(
        $booking_id,
        '_payment_expired_at',
        current_time( 'mysql' )
    );
}
}
add_action(
    'luxury_barbershop_expire_pending_payments',
    'luxury_barbershop_expire_pending_payments'
);
