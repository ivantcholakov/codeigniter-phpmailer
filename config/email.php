<?php defined('BASEPATH') OR exit('No direct script access allowed.');

$config['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         = 'mail';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         = '/usr/sbin/sendmail';
$config['smtp_host']        = 'localhost';
$config['smtp_auth']        = null;                     // Whether to use SMTP authentication, boolean TRUE/FALSE. If this option is omited or if it is NULL, then SMTP authentication is used when both $config['smtp_user'] and $config['smtp_pass'] are non-empty strings.
$config['smtp_user']        = '';
$config['smtp_pass']        = '';
$config['smtp_port']        = 25;
$config['smtp_timeout']     = 30;                       // (in seconds)
$config['smtp_crypto']      = '';                       // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['debug_output']     = '';                       // PHPMailer's SMTP debug output: 'html', 'echo', 'error_log' or user defined function with parameter $str and $level. NULL or '' means 'echo' on CLI, 'html' otherwise.
$config['smtp_auto_tls']    = true;                     // Whether to enable TLS encryption automatically if a server supports it, even if `smtp_crypto` is not set to 'tls'.
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
$config['oauth_type']          = '';                    // XOAUTH2 authentication mechanism:
                                                        // ''                  - disabled;
                                                        // 'xoauth2'           - custom implementation;
                                                        // 'xoauth2_google'    - Google provider;
                                                        // 'xoauth2_yahoo'     - Yahoo provider;
                                                        // 'xoauth2_microsoft' - Microsoft provider.
$config['oauth_instance']      = null;                  // Initialized instance of \PHPMailer\PHPMailer\OAuth (OAuthTokenProvider interface) that contains a custom token provider. Needed for 'xoauth2' custom implementation only. 
$config['oauth_user_email']    = '';                    // If this option is an empty string or null, $config['smtp_user'] will be used.
$config['oauth_client_id']     = '';
$config['oauth_client_secret'] = '';
$config['oauth_refresh_token'] = '';

// DKIM Signing
// See https://yomotherboard.com/how-to-setup-email-server-dkim-keys/
// See http://stackoverflow.com/questions/24463425/send-mail-in-phpmailer-using-dkim-keys
// See https://github.com/PHPMailer/PHPMailer/blob/v5.2.14/test/phpmailerTest.php#L1708
$config['dkim_domain']      = '';                       // DKIM signing domain name, for exmple 'example.com'.
$config['dkim_private']     = '';                       // DKIM private key, set as a file path.
$config['dkim_private_string'] = '';                    // DKIM private key, set directly from a string.
$config['dkim_selector']    = '';                       // DKIM selector.
$config['dkim_passphrase']  = '';                       // DKIM passphrase, used if your key is encrypted.
$config['dkim_identity']    = '';                       // DKIM Identity, usually the email address used as the source of the email.
