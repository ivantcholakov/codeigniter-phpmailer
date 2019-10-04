<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * CodeIgniter compatible email-library powered by PHPMailer.
 * Version: 1.3.2
 * @author Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2019.
 * @license The MIT License (MIT), http://opensource.org/licenses/MIT
 * @link https://github.com/ivantcholakov/codeigniter-phpmailer
 *
 * This library is intended to be compatible with CI 2.x and CI 3.x.
 *
 * Tested on CodeIgniter 3.1.11 (September 19th, 2019) and
 * PHPMailer Version 6.1.1 (September 28th, 2019).
 */

if (version_compare(CI_VERSION, '3.1.0') >= 0) {

    if (is_php('5.5') && class_exists('\\PHPMailer\\PHPMailer\\PHPMailer', true)) {

        require_once dirname(__FILE__).'/MY_Email_3_1_x_phpmailer_6_0_x.php';

    } else {

        require_once dirname(__FILE__).'/MY_Email_3_1_x.php';
    }

} elseif (version_compare(CI_VERSION, '3.0') >= 0) {

    require_once dirname(__FILE__).'/MY_Email_3_0_x.php';

} else {

    require_once dirname(__FILE__).'/MY_Email_2_x.php';
}
