[![Latest Stable Version](https://poser.pugx.org/ivantcholakov/codeigniter-phpmailer/v)](//packagist.org/packages/ivantcholakov/codeigniter-phpmailer)
[![Total Downloads](https://poser.pugx.org/ivantcholakov/codeigniter-phpmailer/downloads)](//packagist.org/packages/ivantcholakov/codeigniter-phpmailer)
[![Latest Unstable Version](https://poser.pugx.org/ivantcholakov/codeigniter-phpmailer/v/unstable)](//packagist.org/packages/ivantcholakov/codeigniter-phpmailer)
[![License](https://poser.pugx.org/ivantcholakov/codeigniter-phpmailer/license)](//packagist.org/packages/ivantcholakov/codeigniter-phpmailer)

A CodeIgniter compatible email-library powered by PHPMailer
===========================================================

Version: 1.5.0  
Author: Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2022.  
License: The MIT License (MIT), http://opensource.org/licenses/MIT

This library is compatible with CodeIgniter 3.1.x and PHP >= 7.3.0.

Tested on CodeIgniter 3.1.13 (March 3rd, 2022) and PHPMailer Version 6.6.4 (August 22nd, 2022).

Links
-----

Package: https://packagist.org/packages/ivantcholakov/codeigniter-phpmailer

PHPMailer: https://github.com/PHPMailer/PHPMailer

Installation
------------

Enable Composer to be used by CodeIgniter. Check this page from its documentation:
https://www.codeigniter.com/userguide3/general/autoloader.html .
You need to see or decide when your vendor/ directory is (to be) and within the
CodeIgniter's configuration file application/config/config.php you need to set the
configuration option $config['composer_autoload'] accordingly. For the typical location
application/vendor/ the configuration setting would look like this:

```php
$config['composer_autoload'] = APPPATH.'vendor/autoload.php';
```

Within application/config/constants.php add the following lines:

```php
// Path to Composer's vendor/ directory, it should end with a trailing slash.
defined('VENDORPATH') OR define('VENDORPATH', rtrim(str_replace('\\', '/', realpath(dirname(APPPATH.'vendor/autoload.php'))), '/').'/');
```

It is assumed that Composer's vendor/ directory is placed under CodeIgniter's
application/ directory. Otherwise correct the setting so VENDORPATH to point correctly.

If PHPMailer was previously installed through Composer, uninstall it temporarily:

```
composer remove PHPMailer/PHPMailer
```

Now install this library's package, it will install a correct version of PHPMailer too:

```
composer require ivantcholakov/codeigniter-phpmailer
```

Create a file application/helpers/MY_email_helper.php with the following content:

```php
<?php defined('BASEPATH') OR exit('No direct script access allowed.');

// A place where you can move your custom helper functions,
// that are to be loaded before the functions below.
// If it is needed, create the corresponding file, insert
// your source there and uncomment the following lines.
//if (is_file(dirname(__FILE__).'/MY_email_helper_0.php')) {
//    require_once dirname(__FILE__).'/MY_email_helper_0.php';
//}

// Instead of copying manually or through script in this directory,
// let us just load here the provided by Composer file.
if (is_file(VENDORPATH.'ivantcholakov/codeigniter-phpmailer/helpers/MY_email_helper.php')) {
    require_once VENDORPATH.'ivantcholakov/codeigniter-phpmailer/helpers/MY_email_helper.php';
}

// A place where you can move your custom helper functions,
// that are to be loaded after the functions above.
// If it is needed, create the corresponding file, insert
// your source there and uncomment the following lines.
//if (is_file(dirname(__FILE__).'/MY_email_helper_2.php')) {
//    require_once dirname(__FILE__).'/MY_email_helper_2.php';
//}
```

Create a file application/libraries/MY_Email.php with the following content:

```php
<?php defined('BASEPATH') OR exit('No direct script access allowed.');

// Instead of copying manually or through script in this directory,
// let us just load here the provided by Composer file.
require_once VENDORPATH.'ivantcholakov/codeigniter-phpmailer/libraries/MY_Email.php';
```

This is an installation that is to be done once. Updating to next versions of
this package and PHPMailer would be done later easily:

```
composer update
```

Configuration and Sending an E-mail (An Example)
------------------------------------------------

Create if necessary or edit the file `application/config/email.php` which contains
the default settings for the email engine. For a Gmail account, the setting might be something like this:

```php
<?php defined('BASEPATH') OR exit('No direct script access allowed.');

$config['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         = '/usr/sbin/sendmail';
$config['smtp_host']        = 'smtp.gmail.com';
$config['smtp_auth']        = true;                     // Whether to use SMTP authentication, boolean TRUE/FALSE. If this option is omited or if it is NULL, then SMTP authentication is used when both $config['smtp_user'] and $config['smtp_pass'] are non-empty strings.
$config['smtp_user']        = 'yourusername@gmail.com';
$config['smtp_pass']        = '';                       // Gmail disabled the so-called "Less Secured Applications", your Google password is not to be used directly, XOAUTH2 authentication will be used.
$config['smtp_port']        = 587;
$config['smtp_timeout']     = 30;                       // (in seconds)
$config['smtp_crypto']      = 'tls';                    // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['debug_output']     = '';                       // PHPMailer's SMTP debug output: 'html', 'echo', 'error_log' or user defined function with parameter $str and $level. NULL or '' means 'echo' on CLI, 'html' otherwise.
$config['smtp_auto_tls']    = false;                    // Whether to enable TLS encryption automatically if a server supports it, even if `smtp_crypto` is not set to 'tls'.
$config['smtp_conn_options'] = array();                 // SMTP connection options, an array passed to the function stream_context_create() when connecting via SMTP.
$config['wordwrap']         = true;
$config['wrapchars']        = 76;
$config['mailtype']         = 'html';                   // 'text' or 'html'
$config['charset']          = null;                     // 'UTF-8', 'ISO-8859-15', ...; NULL (preferable) means config_item('charset'), i.e. the character set of the site.
$config['validate']         = true;
$config['priority']         = 3;                        // 1, 2, 3, 4, 5; on PHPMailer useragent NULL is a possible option, it means that X-priority header is not set at all, see https://github.com/PHPMailer/PHPMailer/issues/449
$config['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
$config['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode']   = false;
$config['bcc_batch_size']   = 200;
$config['encoding']         = '8bit';                   // The body encoding. For CodeIgniter: '8bit' or '7bit'. For PHPMailer: '8bit', '7bit', 'binary', 'base64', or 'quoted-printable'.

// XOAUTH2 mechanism for authentication.
// See https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2
$config['oauth_type']          = 'xoauth2_google';      // XOAUTH2 authentication mechanism:
                                                        // ''                  - disabled;
                                                        // 'xoauth2'           - custom implementation;
                                                        // 'xoauth2_google'    - Google provider;
                                                        // 'xoauth2_yahoo'     - Yahoo provider;
                                                        // 'xoauth2_microsoft' - Microsoft provider.
$config['oauth_instance']      = null;                  // Initialized instance of \PHPMailer\PHPMailer\OAuth (OAuthTokenProvider interface) that contains a custom token provider. Needed for 'xoauth2' custom implementation only. 
$config['oauth_user_email']    = '';                    // If this option is an empty string or null, $config['smtp_user'] will be used.
$config['oauth_client_id']     = '237644427849-g8d0pnkd1jh3idcjdbopvkse2hvj0tdp.apps.googleusercontent.com';
$config['oauth_client_secret'] = 'mklHhrns6eF-qjwuiLpSB4DL';
$config['oauth_refresh_token'] = '1/7Jt8_RHX86Pk09VTfQd4O_ZqKbmuV7HpMNz-rqJ4KdQMEudVrK5jSpoR30zcRFq6';

// DKIM Signing
$config['dkim_domain']      = '';                       // DKIM signing domain name, for exmple 'example.com'.
$config['dkim_private']     = '';                       // DKIM private key, set as a file path.
$config['dkim_private_string'] = '';                    // DKIM private key, set directly from a string.
$config['dkim_selector']    = '';                       // DKIM selector.
$config['dkim_passphrase']  = '';                       // DKIM passphrase, used if your key is encrypted.
$config['dkim_identity']    = '';                       // DKIM Identity, usually the email address used as the source of the email.
```

Notes:
Set $config['useragent'] as 'PHPMailer' in order PHPMailer engine to be used.
PHP openssl module should be enabled if encrypted SMTP access is required.

Within a controller paste the following code for testing purposes:

```php
$this->load->library('email');

$subject = 'This is a test';
$message = '
    <p>This message has been sent for testing purposes.</p>

    <!-- Attaching an image example - an inline logo. -->
    <p><img src="cid:logo_src" /></p>
';

// Get full html:
$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
    <title>' . html_escape($subject) . '</title>
    <style type="text/css">
        body {
            font-family: Arial, Verdana, Helvetica, sans-serif;
            font-size: 16px;
        }
    </style>
</head>
<body>
' . $message . '
</body>
</html>';
// Also, for getting full html you may use the following internal method:
//$body = $this->email->full_html($subject, $message);

// Attaching the logo first.
$file_logo = FCPATH.'apple-touch-icon-precomposed.png';  // Change the path accordingly.
// The last additional parameter is set to true in order
// the image (logo) to appear inline, within the message text:
$this->email->attach($file_logo, 'inline', null, '', true);
$cid_logo = $this->email->get_attachment_cid($file_logo);
$body = str_replace('cid:logo_src', 'cid:'.$cid_logo, $body);
// End attaching the logo.

$result = $this->email
    ->from('yourusername@gmail.com')
    ->reply_to('yoursecondemail@somedomain.com')    // Optional, an account where a human being reads.
    ->to('therecipient@otherdomain.com')
    ->subject($subject)
    ->message($body)
    ->send();

var_dump($result);
echo '<br />';
echo $this->email->print_debugger();

exit;
```

Load the corresponding page, executte this code. Check whether an email has been sent. Read the error message,
if any, and make corrections in your settings.

Note, that most of the SMTP servers require "from" address of the message to be the same as the address within
$config['smtp_user'] setting.

At the end remove this test.

The API of this library is the same as the original Email API. Read the CodeIgniter's manual about
[Email Class](https://www.codeigniter.com/userguide3/libraries/email.html).

For supporting CodeIgniter 2.x and CodeIgniter 3.0.x a manual installation of an older version of this
library is needed, see https://github.com/ivantcholakov/codeigniter-phpmailer/tree/1.3-stable
