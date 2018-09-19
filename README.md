A CodeIgniter compatible email-library powered by PHPMailer
===========================================================

Version: 1.2.30  
Author: Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2018.  
License: The MIT License (MIT), http://opensource.org/licenses/MIT

This library is intended to be compatible with CI 2.x and CI 3.x.

Tested on CodeIgniter 3.1.8 (March 22th, 2018) and PHPMailer Version 5.2.26 (November 4th, 2017).

Installation
------------

Download this package and uncompress it within `application/` directory of your CodeIgniter site.

PHPMailer Links
---------------

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
$config['smtp_auth']        = true;                     // Whether to use SMTP authentication, boolean TRUE/FALSE. If this option is omited or if it is NULL, then SMTP authentication is used when both $config['smtp_user'] and $config['smtp_pass'] are non-empty strings.
$config['smtp_user']        = 'yourusername@gmail.com';
$config['smtp_pass']        = 'yourpassword';
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

// DKIM Signing
$config['dkim_domain']      = '';                       // DKIM signing domain name, for exmple 'example.com'.
$config['dkim_private']     = '';                       // DKIM private key, set as a file path.
$config['dkim_private_string'] = '';                    // DKIM private key, set directly from a string.
$config['dkim_selector']    = '';                       // DKIM selector.
$config['dkim_passphrase']  = '';                       // DKIM passphrase, used if your key is encrypted.
$config['dkim_identity']    = '';                       // DKIM Identity, usually the email address used as the source of the email.
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

The API of this library is the same as the original Email API. Read the CodeIgniter's manual about [Email Class](https://www.codeigniter.com/user_guide/libraries/email.html).

Using Composer for Installing PHPMailer 5.2
-------------------------------------------

This library uses PHPMailer 5.2 which is in maintenance development mode, the last its released version is 5.2.26.
If you anyway want to avoid to update manually possible future releases of PHPMailer 5.2, you can use Composer for
doing this job.

First, you need to have Composer locally or globally installed on your development machine see the instructions for that: https://getcomposer.org/doc/00-intro.md .
Then you need to enable Composer to be used by CodeIgniter.

* For CodeIgniter 2 there is no officially suggested/supported way for that, you can use the hint from this Phil Sturgeon's article: https://philsturgeon.uk/php/2012/05/07/composer-with-codeigniter/ .
* For CodeIgniter 3 check this page from its documentation: https://www.codeigniter.com/user_guide/general/autoloader.html . You need to see or decide when your vendor/ directory is (to be) and within the
CodeIgniter's configuration file application/config/config.php you need to set the configuration option $config['composer_autoload'] accordingly.

Then, third, add to your composer.json uder the section "require" the following line:
```
"phpmailer/phpmailer": "^5.2.26"
```
It might need a comma at the end if the list "require" continues. Or, alternatively from command line interface run the following command:
```
composer require phpmailer/phpmailer:^5.2.26
```
The command
```
composer update
```
will install or update PHPMailer.

Forth, delete the not needed anymore directory application/third_party/phpmailer/ .

Lately, from your development machine you are to upload on the production server the whole CodeIgniter-based project, together with the files composer.json, composer.lock, and the directory vendor/ .
Avoid using Composer on your production server. Composer is a version management tool for developers, its alone is not a deployment tool.

Readings
--------

* http://bisakomputer.com/membuat-pengiriman-email-terjadwal-dengan-framework-codeigniter/ - "Membuat Pengiriman Email Terjadwal dengan Framework CodeIgniter" by Arif Rachman, in Indonesian language.
* https://www.youtube.com/watch?v=UGzZ0qyODDo - "Bibliotecas Codeigniter - PHPMailer", Wagner Faria dos Santos
