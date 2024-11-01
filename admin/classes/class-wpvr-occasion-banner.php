<?php

/**
 * SpecialOccasionBanner Class
 *
 * This class is responsible for displaying a special occasion banner in the WordPress admin.
 *
 */
class WPVR_Special_Occasion_Banner
{

    /**
     * The occasion identifier.
     *
     * @var string
     */
    private $occasion;

    /**
     * The start date and time for displaying the banner.
     *
     * @var int
     */
    private $start_date;

    /**
     * The end date and time for displaying the banner.
     *
     * @var int
     */
    private $end_date;

    /**
     * Constructor method for SpecialOccasionBanner class.
     *
     * @param string $occasion   The occasion identifier.
     * @param string $start_date The start date and time for displaying the banner.
     * @param string $end_date   The end date and time for displaying the banner.
     */
    public function __construct($occasion, $start_date, $end_date)
    {
        $this->occasion     = "rex_wpvr_{$occasion}";
        $this->start_date   = strtotime($start_date);
        $this->end_date     = strtotime($end_date);

        if (!defined('WPVR_PRO_VERSION') && 'hidden' !== get_option( $this->occasion, '') ) {
            // Hook into the admin_notices action to display the banner
            add_action('admin_notices', array($this, 'display_banner'));

            // Add styles
            add_action('admin_head', array($this, 'add_styles'));

	        add_action( 'wp_ajax_rex_wpvr_hide_deal_notice', [ $this, 'hide_special_deal_notice' ] );
        }
    }

    /**
     * Calculate time remaining until Halloween
     *
     * @return array Time remaining in days, hours, and minutes
     */
	public function rex_get_halloween_countdown() {
		$diff = $this->end_date - current_time( 'timestamp' );
		return [
			'days'  => sprintf("%02d", floor( $diff / ( 60 * 60 * 24 ) )),
			'hours' => sprintf("%02d", floor( ( $diff % ( 60 * 60 * 24 ) ) / ( 60 * 60 ) ) ),
			'mins'  => sprintf("%02d", floor( ( $diff % ( 60 * 60 ) ) / 60 ) )
		];
	}


    /**
     * Displays the special occasion banner if the current date and time are within the specified range.
     */
    public function display_banner()
    {
        $screen                     = get_current_screen();
        $promotional_notice_pages   = ['dashboard', 'plugins', 'edit-wpvr_item', 'toplevel_page_wpvr', 'wp-vr_page_wpvr-setup-wizard', 'wpvr_item', 'wp-vr_page_wpvr-addons'];
        $current_date_time          = current_time('timestamp');

        if (!in_array($screen->id, $promotional_notice_pages)) {
            return;
        }

        if ( $current_date_time >= $this->start_date && $current_date_time <= $this->end_date ) {
	        echo '<input type="hidden" id="rex_wpvr_special_occasion" name="rex_wpvr_special_occasion" value="'.$this->occasion.'">';

        // Calculate the time remaining in seconds
        $time_remaining = $this->end_date - $current_date_time;

        $countdown = $this->rex_get_halloween_countdown();
    ?>

        <div class="rex-feed-tb__notification wpvr-banner" id="rex_wpvr_deal_notification">

            <div class="banner-overflow">
                <div class="rex-notification-counter">
                    <div class="rex-notification-counter__container">
                        <div class="rex-notification-counter__content">

                            <figure class="rex-notification-counter__figure-logo">
                                <img src="<?php echo esc_url(WPVR_PLUGIN_DIR_URL . 'admin/icon/halloween/halloween-logo.webp'); ?>" alt="<?php esc_attr_e('Halloween special offer banner', 'wpvr'); ?>" class="rex-notification-counter__img" >
                            </figure>

                            <figure class="rex-notification-counter__figure-percentage">
                                <img src="<?php echo esc_url(WPVR_PLUGIN_DIR_URL . 'admin/icon/halloween/discount-percent.webp'); ?>" alt="<?php esc_attr_e('Halloween special offer banner', 'wpvr'); ?>" class="rex-notification-counter__img" >
                            </figure>

                            <div id="rex-halloween-countdown" class="rex-notification-counter__countdown" aria-live="polite">
                                <h3 class="screen-reader-text"><?php esc_html_e('Offer Countdown', 'wpvr'); ?></h3>
                                <ul class="rex-notification-counter__list">
                                    <?php foreach (['days', 'hours', 'mins'] as $unit): ?>
                                        <li class="rex-notification-counter__item">
                                            <span id="rex-wpvr-halloween-<?php echo esc_attr($unit); ?>" class="rex-notification-counter__time">
                                                <?php echo esc_html($countdown[$unit]); ?>
                                            </span>
                                            <span class="rex-notification-counter__label">
                                                <?php echo esc_html($unit); ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="rex-notification-counter__btn-area">
                                <a 
                                    href="<?php echo esc_url( 'https://rextheme.com/wpvr/wpvr-pricing/?utm_source=plugin-CTA&utm_medium=wpvr-free-plugin&utm_campaign=halloween-2024' ); ?>"
                                    class="rex-notification-counter__btn"
                                    target="_blank"
                                >

                                    <span class="screen-reader-text"><?php esc_html_e('Click to view Halloween sale products', 'wpvr'); ?></span>

                                    <?php esc_html_e('FLAT', 'wpvr'); ?> 
                                    <strong class="rex-notification-counter__stroke-font">30%</strong> 
                                    <?php esc_html_e('OFF', 'wpvr'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            <a class="close-promotional-banner wpvr-black-friday-close-promotional-banner rex-feed-tb__cross-top" type="button" aria-label="close banner" id="wpvr-black-friday-close-button">
                <svg width="12" height="13" fill="none" viewBox="0 0 12 13" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="#7A8B9A" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 1.97L1 11.96m0-9.99l10 9.99" />
                </svg>
            </a>

        </div>
        <!-- .rex-feed-tb-notification end -->

        <script>
            rexfeed_deal_countdown_handler();
            /**
             * Handles count down on deal notice
             *
             * @since 7.3.18
             */
            function rexfeed_deal_countdown_handler() {
                // Pass the calculated time remaining to JavaScript
                let timeRemaining = <?php echo $time_remaining; ?>;

                // Update the countdown every second
                setInterval(function() {
                    const daysElement = document.getElementById('rex-wpvr-halloween-days');
                    const hoursElement = document.getElementById('rex-wpvr-halloween-hours');
                    const minutesElement = document.getElementById('rex-wpvr-halloween-mins');
                    //const secondsElement = document.getElementById('seconds');

                    timeRemaining--;

                    if (daysElement && hoursElement && minutesElement) {
                        // Decrease the remaining time

                        // Calculate new days, hours, minutes, and seconds
                        let days = Math.floor(timeRemaining / (60 * 60 * 24)).toString().padStart(2, '0');
                        let hours = Math.floor((timeRemaining % (60 * 60 * 24)) / (60 * 60)).toString().padStart(2, '0');
                        let minutes = Math.floor((timeRemaining % (60 * 60)) / 60).toString().padStart(2, '0');

                        // Update the HTML
                        daysElement.textContent = days;
                        hoursElement.textContent = hours;
                        minutesElement.textContent = minutes;
                    }
                    // Check if the countdown has ended
                    if (timeRemaining <= 0) {
                        rexfeed_hide_deal_notice();
                    }
                }, 1000); // Update every second
            }

            document.getElementById('wpvr-black-friday-close-button').addEventListener('click', rexfeed_hide_deal_notice);

            /**
             * Hide deal notice and save parameter to keep it hidden for future
             *
             * @since 7.3.2
             */
            function rexfeed_hide_deal_notice() {
                document.getElementById('rex_wpvr_deal_notification').style.display = 'none';

                jQuery.ajax({
                    type: "POST",
                    url: wpvr_global_obj?.ajaxurl,
                    data: {
                        action: "rex_wpvr_hide_deal_notice",
                        nonce : wpvr_global_obj.ajax_nonce,
                        occasion: document.getElementById('rex_wpvr_special_occasion')?.value
                    },
                });
            }
        </script>
    <?php
        }
    }


	/**
	 * Hides the special deal notice.
	 *
	 * This method updates the option to hide the special deal notice
	 * based on the provided payload.
	 *
	 * @return array The status of the operation.
	 */
	public function hide_special_deal_notice() {
        if( !current_user_can( 'manage_options' ) ){
            wp_send_json_error( array( 'message' => 'Unauthorized user' ), 403 );
            return;
        }
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! wp_verify_nonce( $nonce, 'wpvr' ) ) {
			return [ 'status' => false ];
		}

		$occasion = filter_input( INPUT_POST, 'occasion', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( $occasion ) {
			update_option( $occasion, 'hidden' );

			return [ 'status' => true ];
		}

		return [ 'status' => false ];
	}

    /**
     * Adds internal CSS styles for the special occasion banners.
     */
    public function add_styles()
    {
        $plugin_dir_url = plugin_dir_url(__FILE__);
    ?>
        <style id="wpvr-promotional-banner-style" type="text/css">
            @font-face {
                font-family: 'Inter';
                src: url(<?php echo WPVR_PLUGIN_DIR_URL . 'admin/fonts/campaign-font/Inter-Bold.woff2'; ?>) format('woff2');
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Inter';
                src: url(<?php echo WPVR_PLUGIN_DIR_URL . 'admin/fonts/campaign-font/Inter-SemiBold.woff2'; ?>) format('woff2');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: "Circular Std Book";
                src: url(<?php echo WPVR_PLUGIN_DIR_URL . 'admin/fonts/campaign-font/CircularStd-Book.woff2'; ?>) format('woff2');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }

            .rex-feed-tb__notification,
            .rex-feed-tb__notification * {
                box-sizing: border-box;
            }

            .rex-feed-tb__notification.wpvr-banner {
                background-color: #03031e;
                width: calc(100% - 20px);
                margin: 50px 0 20px;
                background-image: url(<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/halloween/promotional-banner-bg.webp'; ?>);
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
                border: none;
                box-shadow: none;
                display: block;
                max-height: 110px;
                object-fit: cover;
            }

            .wpvr-banner .rex-notification-counter {
                position: relative;
                z-index: 1111;
                padding: 9px 0 4px;
            }
            .wpvr-banner .rex-notification-counter__container {
                position: relative;
                width: 100%;
                max-height: 110px;
                max-width: 1310px;
                margin: 0 auto;
                padding: 0px 15px;
            }
            .wpvr-banner .rex-notification-counter__content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 20px;
            }
            .wpvr-banner .rex-notification-counter__figure-logo {
                max-width: 268px;
                margin: 0;
            }
            .wpvr-banner .rex-notification-counter__figure-percentage {
                max-width: 248px;
                margin: 0;
            }
            .wpvr-banner .rex-notification-counter__img {
                width: 100%;
                max-width: 100%;
                display: block;
            }
            .wpvr-banner .rex-notification-counter__list {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            @media only screen and (max-width: 991px) {
                .wpvr-banner .rex-notification-counter__list {
                    gap: 10px;
                }
            }
            @media only screen and (max-width: 767px) {
                .wpvr-banner .rex-notification-counter__list {
                    align-items: center;
                    justify-content: center;
                    gap: 15px;
                }
            }
            .wpvr-banner .rex-notification-counter__item {
                display: flex;
                flex-direction: column;
                width: 56.14px;
                font-family: "Circular Std Book";
                font-size: 15px;
                font-weight: 500;
                line-height: normal;
                letter-spacing: 0.75px;
                text-transform: uppercase;
                text-align: center;
                color: #fff;
                margin: 0;
            }
            @media only screen and (max-width: 1199px) {
                .wpvr-banner .rex-notification-counter__item {
                    width: 44px;
                    font-size: 12px;
                }
            }
            @media only screen and (max-width: 991px) {
                .wpvr-banner .rex-notification-counter__item {
                    font-size: 10px;
                }
            }
            @media only screen and (max-width: 767px) {
                .wpvr-banner .rex-notification-counter__item {
                    font-size: 13px;
                    width: 47px;
                }
            }
            .wpvr-banner .rex-notification-counter__time {
                font-size: 32px;
                font-family: "Inter";
                font-style: normal;
                font-weight: 700;
                line-height: normal;
                color: #fff;
                text-align: center;
                margin-bottom: 6px;
                border-radius: 3px 3px 10px 10px;
                border: 1px solid #201cfe;
                border-bottom-width: 5px;
                background: linear-gradient(155deg, #201cfe 2.02%, #100e35 55.1%, #100e35 131.47%);
            }
            @media only screen and (max-width: 1199px) {
                .wpvr-banner .rex-notification-counter__time {
                    font-size: 30px;
                }
            }
            @media only screen and (max-width: 991px) {
                .wpvr-banner .rex-notification-counter__time {
                    font-size: 24px;
                }
            }
            .wpvr-banner .rex-notification-counter__btn-area {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
            }
            .wpvr-banner .rex-notification-counter__btn {
                position: relative;
                background-color: #3F05FD;
                font-family: "Inter";
                font-size: 20px;
                font-weight: 500;
                line-height: normal;
                color: #fff;
                text-align: center;
                filter: drop-shadow(0px 30px 60px rgba(21, 19, 119, 0.20));
                padding: 12px 22px;
                display: inline-block;
                border-radius: 10px;
                cursor: pointer;
                text-transform: uppercase;
                transition: all 0.3s ease;
                text-decoration: none;
                box-shadow: none;
            }
            .wpvr-banner .rex-notification-counter__btn:hover {
                background-color: #fff;
                color: #3F05FD;
            }
            .wpvr-banner .rex-notification-counter__stroke-font {
                font-size: 26px;
                font-family: "Inter";
                font-weight: 600;
            }

            .rex-feed-tb__notification.wpvr-banner .rex-feed-tb__cross-top {
                position: absolute;
                top: -10px;
                right: -9px;
                background: #fff;
                border: none;
                padding: 0;
                border-radius: 50%;
                cursor: pointer;
                z-index: 9999;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            

            @media only screen and (max-width: 1599px) {
                .wpvr-banner .rex-notification-counter__container {
                    max-width: 1030px;
                }
                .wpvr-banner .rex-notification-counter__figure-logo {
                    max-width: 200px;
                }
                .wpvr-banner .rex-notification-counter__figure-percentage {
                    max-width: 190px;
                }

                .wpvr-banner .rex-notification-counter__btn {
                    font-size: 16px;
                }
                .wpvr-banner .rex-notification-counter__stroke-font {
                    font-size: 22px;
                }

            }

            @media only screen and (max-width: 1399px) {
                .wpvr-banner .rex-feed-tb__notification {
                    background-position: left center;
                }

            }

            @media only screen and (max-width: 1199px) {
                .wpvr-banner .rex-notification-counter__container {
                    max-width: 740px;
                }
                .wpvr-banner .rex-notification-counter__figure-logo {
                    max-width: 140px;
                }
                .wpvr-banner .rex-notification-counter__figure-percentage {
                    max-width: 140px;
                }
                .wpvr-banner .rex-notification-counter__time {
                    font-size: 22px;
                    padding: 2px 0;
                    font-weight: 500;
                }
                .wpvr-banner .rex-notification-counter__btn {
                    font-size: 13px;
                    padding: 8px 16px;
                    transform: translateY(-2px);
                }
                .wpvr-banner .rex-notification-counter__stroke-font {
                    font-size: 20px;
                }

            }

            @media only screen and (max-width: 991px) {
                .wpvr-banner .rex-notification-counter__item {
                    font-size: 12px;
                }

            }
        </style>
        <?php

    }
}
