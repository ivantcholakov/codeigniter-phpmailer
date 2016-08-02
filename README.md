A CodeIgniter compatible email-library powered by PHPMailer
===========================================================

Version: 1.2.8  
Author: Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2016.  
License: The MIT License (MIT), http://opensource.org/licenses/MIT

This library is intended to be compatible with CI 2.x and CI 3.x.

Tested on CodeIgniter 3.1.0 (July 26, 2016) and with PHPMailer Version 5.2.16+ (July 26, 2016).

Installation
------------

Download this package and uncompress it within `application/` directory of your CodeIgniter site.

PHPMailer Links
---------------

http://phpmailer.worxware.com/  
https://github.com/PHPMailer/PHPMailer

Setting It Up (An Example)
--------------------------

* Edit the file `application/config/email.php` which contains the default settings for the email engine. For a Gmail account, the setting might be something like this:

```php
<?php defined('BASEPATH') OR exit('No direct script access allowed.');

$config['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         = '/usr/sbin/sendmail';
$config['smtp_host']        = 'smtp.gmail.com';
$config['smtp_user']        = 'yourusername@gmail.com';
$config['smtp_pass']        = 'yourpassword';
$config['smtp_port']        = 465;
$config['smtp_timeout']     = 30;                       // (in seconds)
$config['smtp_crypto']      = 'ssl';                    // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
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
```

* Notes:

PHP openssl module should be enabled if encrypted SMTP access is required;  
Set $config['useragent'] as 'PHPMailer' if the original 'CodeIgniter' engine fails for some reason.

* Within a controller paste the following code for testing purposes:

```php
$this->load->library('email');

$subject = 'This is a test';
$message = '<p>This message has been sent for testing purposes.</p>';

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

Load the corresponding page, executte this code. Check whether an email has been sent. Read the error message, if any, and make corrections in your settings.

Note, that most of the SMTP servers require "from" address of the message to be the same as the address within $config['smtp_user'] setting.

At the end remove this test.

The API of this library is the same as the original Email API. Read the CodeIgniter's manual about Email class.

Readings
--------

* http://bisakomputer.com/membuat-pengiriman-email-terjadwal-dengan-framework-codeigniter/ - "Membuat Pengiriman Email Terjadwal dengan Framework CodeIgniter" by Arif Rachman, in Indonesian language.
