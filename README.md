A CodeIgniter compatible email-library powered by PHPMailer
===========================================================

Version: 1.1  
Author: Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2014.  
License: The MIT License (MIT), http://opensource.org/licenses/MIT

Tested on CodeIgniter 3.0-dev (January 16th, 2014) and with PHPMailer Version 5.2.7 (September 12th, 2013).

Installation
------------

Download this package and uncompress it within `application/` directory of your CodeIgniter site.

PHPMailer Links
---------------

http://phpmailer.worxware.com/  
https://github.com/Synchro/PHPMailer

Setting It Up (An Example)
--------------------------

1. Edit the file `application/config/email.php` which contains the default settings for the email engine. For a Gmail account, the setting might be something like this:

```php
<?php defined('BASEPATH') OR exit('No direct script access allowed.');

$config['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         = '/usr/sbin/sendmail';
$config['smtp_host']        = 'smtp.gmail.com';
$config['smtp_user']        = 'yourusername@gmail.com';
$config['smtp_pass']        = 'yourpassword';
$config['smtp_port']        = 465;
$config['smtp_timeout']     = 5;                        // (in seconds)
$config['smtp_crypto']      = 'ssl';                    // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data
$config['wordwrap']         = true;
$config['wrapchars']        = 76;
$config['mailtype']         = 'html';                   // 'text' or 'html'
$config['charset']          = 'utf-8';
$config['validate']         = true;
$config['priority']         = 3;                        // 1, 2, 3, 4, 5
$config['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
$config['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode']   = false;
$config['bcc_batch_size']   = 200;
```

Notes:
- PHP openssl module should be enabled if encrypted SMTP access is required;
- Set $config['useragent'] as 'PHPMailer' if the original 'CodeIgniter' engine fails for some reason.

2. Within a controller paste the following code for testing purposes:

```php
            $this->load->library('email');

            $subject = 'This is a test';
            $message = '<p>This message has been sent for tesing purposes.</p>';

            // Get full html:
            $body =
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>'.htmlspecialchars($subject, ENT_QUOTES, $this->email->charset).'</title>
    <style type="text/css">
        body {
            font-family: Arial, Verdana, Helvetica, sans-serif;
            font-size: 16px;
        }
    </style>
</head>
<body>
'.$message.'
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
