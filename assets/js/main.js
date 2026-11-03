const menuToggle = document.querySelector('.menu-toggle');
const menuContainer = document.querySelector('.menu-primary-menu-container');
const body = document.body;

if (menuToggle && menuContainer) {

    menuToggle.addEventListener('click', () => {

        menuContainer.classList.toggle('active');
        body.classList.toggle('menu-open');

        menuToggle.textContent =
            menuContainer.classList.contains('active') ? '✕' : '☰';

    });

    document.querySelectorAll('.menu a').forEach(link => {

        link.addEventListener('click', () => {

            menuContainer.classList.remove('active');
            body.classList.remove('menu-open');
            menuToggle.textContent = '☰';

        });

    });

    // Reset the hamburger on every page load
    menuContainer.classList.remove('active');
    body.classList.remove('menu-open');
    menuToggle.textContent = '☰';

}


// Hide booking success message after 5 seconds

const bookingSuccess = document.querySelector('.booking-success');

if (bookingSuccess) {

    setTimeout(() => {

        bookingSuccess.style.opacity = '0';

        setTimeout(() => {
            bookingSuccess.style.display = 'none';
        }, 500);

    }, 5000);

}

const bookingError = document.querySelector('.booking-error');

if (bookingError) {
    setTimeout(function () {
        bookingError.style.opacity = '0';
        bookingError.style.transform = 'translateY(-10px)';

        setTimeout(function () {
            bookingError.remove();
        }, 400);
    }, 4000);
}

/* ==========================
   BOOKING STATUS AJAX
========================== */

const statusSelects = document.querySelectorAll('.booking-status-select');

statusSelects.forEach(function (select) {

    select.addEventListener('change', function () {

        const bookingId = this.dataset.bookingId;
        const status = this.value;

        const formData = new FormData();
        formData.append('action', 'update_booking_status');
        formData.append('booking_id', bookingId);
        formData.append('status', status);

        fetch(luxuryBooking.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.style.borderColor = '#16a34a';

                setTimeout(() => {
                    this.style.borderColor = '';
                }, 1500);
            }
        });

    });

});

const appointmentTypeInputs = document.querySelectorAll(
    'input[name="appointment_type"]'
);

const homeAddressGroup = document.querySelector('.home-address-group');
const homeAddressInput = document.querySelector('#booking-address');
const homeFeeElement = document.querySelector('#home-fee');
const homeFeeRow = document.querySelector('#home-fee-row');
const bookingTotal = document.querySelector('#booking-total');
const bookingService = document.querySelector('#booking-service');

if (
    appointmentTypeInputs.length &&
    homeAddressGroup &&
    homeAddressInput &&
    homeFeeElement &&
    homeFeeRow &&
    bookingTotal &&
    bookingService
) {

    const fee = parseInt(homeFeeElement.dataset.fee) || 0;
    const symbol = homeFeeElement.textContent.trim().charAt(0);

    function updateBookingSummary() {

        const selectedOption =
            bookingService.options[bookingService.selectedIndex];

        const servicePrice =
            parseInt(selectedOption.dataset.price) || 0;

        const appointmentType =
            document.querySelector(
                'input[name="appointment_type"]:checked'
            );

        if (appointmentType && appointmentType.value === 'home') {

            homeAddressGroup.style.display = 'block';
            homeAddressInput.required = true;
            homeFeeRow.style.display = 'flex';

            const total = servicePrice + fee;

            bookingTotal.textContent =
                symbol + total.toLocaleString();

        } else {

            homeAddressGroup.style.display = 'none';
            homeAddressInput.required = false;
            homeAddressInput.value = '';
            homeFeeRow.style.display = 'none';


            bookingTotal.textContent =
                symbol + servicePrice.toLocaleString();

        }

    }

    appointmentTypeInputs.forEach(input => {

        input.addEventListener('change', function () {
            updateBookingSummary();
        });

    });

    bookingService.addEventListener('change', function () {
        updateBookingSummary();
    });

    updateBookingSummary();

}

const adminBookingType = document.querySelector('#booking_type');
const adminHomeAddress = document.querySelector('#booking-home-address');

if (adminBookingType && adminHomeAddress) {

    adminBookingType.addEventListener('change', function () {

        if (this.value === 'home') {

            adminHomeAddress.style.display = 'block';

        } else {

            adminHomeAddress.style.display = 'none';

        }

    });

}