<?php get_header(); ?>

<main>

    <section id="booking" class="booking">

        <?php
            $error_messages = array(
                'name'    => 'Please enter your name.',
                'email'   => 'Please enter your email address.',
                'phone'   => 'Please enter your phone number.',
                'service' => 'Please select a service.',
                'barber'  => 'Please select a barber.',
                'date'    => 'Please select a booking date.',
                'time'    => 'Please select a booking time.'
            );

            if (isset($_GET['booking_error']) && array_key_exists($_GET['booking_error'], $error_messages)) : ?>

                <div class="booking-error">
                    <strong>Booking Error</strong>
                    <p><?php echo esc_html($error_messages[$_GET['booking_error']]); ?></p>
                </div>

            <?php elseif (isset($_GET['booking']) && $_GET['booking'] === 'success') : ?>

            <div class="booking-success">

                <strong>Booking Received</strong>

                <p>
                    Thank you for choosing Luxury Barbershop.
                    Your appointment request has been received successfully.
                    We'll contact you shortly to confirm your booking.
                </p>

            </div>

            <?php elseif (isset($_GET['booking']) && $_GET['booking'] === 'unavailable') : ?>

                <div class="booking-success booking-unavailable">
                    <strong>Time Slot Unavailable</strong>
                    <p>
                        Sorry, that barber is already booked for the selected
                        date and time. Please choose another time.
                    </p>
                </div>

            <?php elseif (isset($_GET['booking']) && $_GET['booking'] === 'closed') : ?>

                <div class="booking-success booking-unavailable">
                    <strong>Outside Business Hours</strong>
                    <p>
                        Sorry, the selected time is outside our opening hours.
                        Please choose a time during business hours.
                    </p>
                </div>

            <?php endif; ?>

        <div class="container">

            <p class="section-subtitle">BOOK YOUR VISIT</p>

            <h2 class="section-title">Reserve Your Chair</h2>

            <p class="section-text">
                Choose your preferred service, barber, date, and time.
                We'll take care of the rest.
            </p>

            <?php
            $home_fee = absint(get_option('luxury_home_service_fee', 0));
            $currency = get_option('luxury_currency', 'NGN');

            $currency_symbols = array(
                'NGN' => '₦',
                'USD' => '$',
                'GBP' => '£',
                'EUR' => '€'
            );

            $currency_symbol = $currency_symbols[$currency] ?? '₦';
            ?>

            <div class="booking-wrapper">

                <!-- BOOKING FORM -->

                <div class="booking-form">

                    <form
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                        method="POST">

                        <input
                            type="hidden"
                            name="action"
                            value="luxury_barbershop_submit_booking"
                        >

                        <?php wp_nonce_field(
                            'luxury_barbershop_booking',
                            'booking_nonce'
                        ); ?>

                        <div class="form-group">
                            <label for="booking-name">Full Name</label>
                            <input
                                type="text"
                                id="booking-name"
                                name="name"
                                placeholder="Your full name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="booking-email">Email Address</label>
                            <input
                                type="email"
                                id="booking-email"
                                name="email"
                                placeholder="you@example.com"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="booking-phone">Phone Number</label>
                            <input
                                type="tel"
                                id="booking-phone"
                                name="phone"
                                placeholder="Your phone number"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="booking-service">Select Service</label>

                            <select id="booking-service" name="service">

                                <option value="">Choose a service</option>

                                <?php
                                $services = new WP_Query(array(
                                    'post_type'      => 'service',
                                    'posts_per_page' => -1,
                                    'orderby'        => 'menu_order',
                                    'order'          => 'ASC'
                                ));

                                if ($services->have_posts()) :
                                    while ($services->have_posts()) : $services->the_post();
                                ?>

                                    <option
                                        value="<?php echo esc_attr(get_the_title()); ?>"
                                        data-price="<?php echo esc_attr(get_post_meta(get_the_ID(), 'service_price', true)); ?>"
                                    >
                                        <?php the_title(); ?>
                                    </option>

                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="booking-barber">Select Barber</label>

                            <select id="booking-barber" name="barber" required>

                                <option value="">Choose a barber</option>

                                <?php
                                $team = new WP_Query(array(
                                    'post_type'      => 'team',
                                    'posts_per_page' => -1,
                                    'orderby'        => 'menu_order',
                                    'order'          => 'ASC'
                                ));

                                if ($team->have_posts()) :
                                    while ($team->have_posts()) : $team->the_post();
                                ?>

                                    <option value="<?php echo esc_attr(get_the_title()); ?>">
                                        <?php the_title(); ?>
                                    </option>

                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Appointment Type</label>

                            <div class="appointment-type">

                                <label class="radio-option">
                                    <input
                                        type="radio"
                                        name="appointment_type"
                                        value="salon"
                                        checked
                                    >
                                    <span>Visit the Salon</span>
                                </label>

                                <label class="radio-option">
                                    <input
                                        type="radio"
                                        name="appointment_type"
                                        value="home"
                                    >
                                    <span>Home Appointment</span>
                                </label>

                            </div>

                        </div>

                        <div class="form-group home-address-group" style="display: none;">

                            <label for="booking-address">Home Address</label>

                            <textarea
                                id="booking-address"
                                name="booking_address"
                                rows="3"
                                placeholder="Enter the address where you'd like the barber to come"
                            ></textarea>

                        </div>

                        <div class="booking-summary">
                            <h3>Booking Summary</h3>
                            <div class="summary-row" id="home-fee-row" style="display: none;">
                                <span>Home Service Fee</span>

                                <strong
                                    id="home-fee"
                                    data-fee="<?php echo esc_attr($home_fee); ?>"
                                >
                                    <?php echo esc_html($currency_symbol . number_format($home_fee)); ?>
                                </strong>
                            </div>


                            <div class="summary-total">
                                <span>Total Extra</span>

                                <strong id="booking-total">
                                    <?php echo esc_html($currency_symbol . '0'); ?>
                                </strong>
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group">

                                <label for="booking-date">Date</label>

                                <input
                                    type="date"
                                    id="booking-date"
                                    name="date"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    required
                                >

                            </div>

                            <div class="form-group">

                                <label for="booking-time">Time</label>

                                <input
                                    type="time"
                                    id="booking-time"
                                    name="time"
                                    step="1800"
                                    required
                                >

                            </div>

                        </div>

                        <div class="form-group">

                            <label for="booking-notes">Additional Notes</label>

                            <textarea
                                id="booking-notes"
                                name="notes"
                                rows="5"
                                placeholder="Anything you'd like us to know?"
                            ></textarea>

                        </div>

                        <button type="submit" class="booking-button">
                            Book Appointment
                        </button>

                    </form>

                </div>

                <!-- BOOKING INFORMATION -->

                <div class="booking-info">

                    <h3>Why Book With Us?</h3>

                    <ul class="booking-benefits">

                        <li>Experienced professional barbers</li>

                        <li>Premium grooming experience</li>

                        <li>Personalized attention</li>

                        <li>Flexible appointment times</li>

                    </ul>

                    <div class="booking-hours">

                        <h3>Opening Hours</h3>
                        <p>
                            Monday – Saturday
                            <span>
                                <?php
                                echo esc_html(
                                    date('g:i A', strtotime(get_option('luxury_week_open', '09:00')))
                                    . ' – ' .
                                    date('g:i A', strtotime(get_option('luxury_week_close', '20:00')))
                                );
                                ?>
                            </span>
                        </p>

                        <p>
                            Sunday
                            <span>
                                <?php
                                echo esc_html(
                                    date('g:i A', strtotime(get_option('luxury_sunday_open', '10:00')))
                                    . ' – ' .
                                    date('g:i A', strtotime(get_option('luxury_sunday_close', '18:00')))
                                );
                                ?>
                            </span>
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </section>

</main>

<script>
    const bookingHours = {
        weekOpen: "<?php echo esc_js(get_option('luxury_week_open', '09:00')); ?>",
        weekClose: "<?php echo esc_js(get_option('luxury_week_close', '20:00')); ?>",
        sundayOpen: "<?php echo esc_js(get_option('luxury_sunday_open', '10:00')); ?>",
        sundayClose: "<?php echo esc_js(get_option('luxury_sunday_close', '18:00')); ?>"
    };
</script>
<script>
    const bookingDate = document.getElementById('booking-date');
    const bookingTime = document.getElementById('booking-time');

    bookingDate.addEventListener('change', function () {

        const selectedDate = new Date(this.value + 'T00:00:00');
        const day = selectedDate.getDay();

        if (day === 0) {
            bookingTime.min = bookingHours.sundayOpen;
            bookingTime.max = bookingHours.sundayClose;
        } else {
            bookingTime.min = bookingHours.weekOpen;
            bookingTime.max = bookingHours.weekClose;
        }

        bookingTime.value = '';
    });
</script>

<?php get_footer(); ?>
