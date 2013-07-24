<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * CodeIgniter compatible email-library powered by PHPMailer.
 * @author Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2013.
 * @license The MIT License (MIT), http://opensource.org/licenses/MIT
 *
 * Tested on production sites with CodeIgniter 3.0-dev (July 2013) and
 * PHPMailer Version 5.2.1 (January 16, 2012).
 */

class MY_Email extends CI_Email {

    protected $phpmailer;
    protected $CI;

    protected static $protocols = array('mail', 'sendmail', 'smtp');
    protected static $mailtypes = array('html', 'text');

    public function __construct($config = array()) {

        $this->CI = get_instance();
        $this->CI->load->helper('email');
        $this->CI->load->helper('html');

        // If your system uses class autoloading feature,
        // then the following require statement would not be needed.
        if (!class_exists('PHPMailer')) {
            require APPPATH.'third_party/phpmailer/class.phpmailer.php';
        }

        $this->phpmailer = new PHPMailer();

        $this->phpmailer->PluginDir = APPPATH.'third_party/phpmailer/';

        $this->charset = strtoupper(config_item('charset'));
        $this->copy_property_to_phpmailer('charset');

        if (!is_array($config)) {
            $config = array();
        }

        if (count($config) > 0) {
            $this->initialize($config);
        } else {
            $this->copy_property_to_phpmailer('_smtp_auth');
            $this->_smtp_auth = ($this->smtp_user == '' AND $this->smtp_pass == '') ? FALSE : TRUE;
            $this->copy_property_to_phpmailer('_smtp_auth');
            $this->_safe_mode = ((boolean)@ini_get('safe_mode') === FALSE) ? FALSE : TRUE;
        }

        log_message('debug', 'MY_Email Class Initialized');
    }

    /**
     * Define these options within the $config array or
     * within the configuration file email.php:
     * useragent
     * protocol
     * mailpath
     * smtp_host
     * smtp_user
     * smtp_pass
     * smtp_port
     * smtp_timeout
     * smtp_secure
     * set_wordwrap
     * wrapchars
     * mailtype
     * charset
     * validate
     * priority
     * crlf
     * newline
     * bcc_batch_mode
     * bcc_batch_size
     */
    public function initialize($config = array()) {

        if (!is_array($config)) {
            $config = array();
        }

        foreach ($config as $key => $val) {

            if (isset($this->$key)) {

                $method = 'set_'.$key;

                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->$key = $val;
                    $this->copy_property_to_phpmailer($key);
                }
            }
        }
        $this->clear();

        $this->_smtp_auth = ($this->smtp_user == '' AND $this->smtp_pass == '') ? FALSE : TRUE;
        $this->copy_property_to_phpmailer('_smtp_auth');
        $this->_safe_mode = ((boolean)@ini_get('safe_mode') === FALSE) ? FALSE : TRUE;

        return $this;
    }

    public function clear($clear_attachments = false) {

        parent::clear();

        $this->phpmailer->ClearAllRecipients();
        $this->phpmailer->ClearReplyTos();
        if ($clear_attachments) {
            $this->phpmailer->ClearAttachments();
        }

        $this->phpmailer->ClearCustomHeaders();

        $this->phpmailer->Subject = '';
        $this->phpmailer->Body = '';
        $this->phpmailer->AltBody = '';
        return $this;
    }

    public function set_protocol($protocol = 'mail') {
        $protocol = strtolower($protocol);
        $this->protocol = in_array($protocol, self::$protocols) ? $protocol : 'mail';
        switch ($this->protocol) {
            case 'mail':
                $this->phpmailer->IsMail();
                break;
            case 'sendmail':
                $this->phpmailer->IsSendmail();
                break;
            case 'smtp':
                $this->phpmailer->IsSMTP();
                break;
        }
        return $this;
    }

    public function set_smtp_secure($smtp_secure = '') {
        $smtp_secure = (string) $smtp_secure;
        if ($smtp_secure == 'tls' || $smtp_secure == 'ssl') {
            $this->phpmailer->set('SMTPSecure', $smtp_secure);
        }
        return $this;
    }

    public function set_wordwrap($wordwrap = TRUE) {
        $this->wordwrap = (bool) $wordwrap;
        if (!$this->wordwrap) {
            $this->phpmailer->set('WordWrap', 0);
        }
        return $this;
    }

    public function set_mailtype($type = 'text') {
        $this->mailtype = in_array($type, self::$mailtypes) ? $type : 'text';
        $this->phpmailer->IsHTML($this->mailtype == 'html');
        return $this;
    }

    public function set_priority($n = 3) {
        if (!is_numeric($n)) {
            $this->priority = 3;
            $this->phpmailer->set('Priority', $this->priority);
            return $this;
        }
        if ($n < 1 || $n > 5) {
            $this->priority = 3;
            $this->phpmailer->set('Priority', $this->priority);
            return $this;
        }
        $this->priority = $n;
        $this->phpmailer->set('Priority', $this->priority);
        return $this;
    }

    public function valid_email($email) {
        return valid_email($email);
    }

    public function from($from, $name = '', $return_path = NULL) {

        $from = (string) $from;
        $name = (string) $name;
        $return_path = (string) $return_path;

        if (preg_match( '/\<(.*)\>/', $from, $match)) {
            $from = $match['1'];
        }

        if ($this->validate) {
            $this->validate_email($this->_str_to_array($from));
            if ($return_path) {
                $this->validate_email($this->_str_to_array($return_path));
            }
        }

        $this->phpmailer->SetFrom($from, $name, 0);
        if (!$return_path) {
            $return_path = $from;
        }
        $this->phpmailer->set('Sender', $return_path);

        return $this;
    }

    public function reply_to($replyto, $name = '') {

        $replyto = (string) $replyto;
        $name = (string) $name;

        if (preg_match( '/\<(.*)\>/', $replyto, $match)) {
            $replyto = $match['1'];
        }

        if ($this->validate) {
            $this->validate_email($this->_str_to_array($replyto));
        }

        if ($name == '') {
            $name = $replyto;
        }

        $this->phpmailer->AddReplyTo($replyto, $name);

        $this->_replyto_flag = TRUE;

        return $this;
    }

    public function to($to) {

        $to = $this->_str_to_array($to);
        $names = $this->extract_name($to);
        $to = $this->clean_email($to);

        if ($this->validate) {
            $this->validate_email($to);
        }

        reset($names);
        foreach ($to as $address) {
            list($key, $name) = each($names);
            $this->phpmailer->AddAddress($address, $name);
        }

        return $this;
    }

    public function cc($cc) {

        $cc = $this->_str_to_array($cc);
        $names = $this->extract_name($cc);
        $cc = $this->clean_email($cc);

        if ($this->validate) {
            $this->validate_email($cc);
        }

        reset($names);
        foreach ($cc as $address) {
            list($key, $name) = each($names);
            $this->phpmailer->AddCC($address, $name);
        }

        return $this;
    }

    public function bcc($bcc, $limit = '') {

        $bcc = $this->_str_to_array($bcc);
        $names = $this->extract_name($bcc);
        $bcc = $this->clean_email($bcc);

        if ($this->validate) {
            $this->validate_email($bcc);
        }

        reset($names);
        foreach ($bcc as $address) {
            list($key, $name) = each($names);
            $this->phpmailer->AddBCC($address, $name);
        }

        return $this;
    }

    public function subject($subject) {
        $this->phpmailer->Subject = (string) $subject;
        return $this;
    }

    public function message($body) {
        $this->phpmailer->Body = (string) $body;
        if ($this->mailtype == 'html') {
            $this->set_alt_message($this->plain_text($body));
        }
        return $this;
    }

    public function set_alt_message($str = '') {
        $this->phpmailer->AltBody = (string) $str;
        return $this;
    }

    public function attach($filename, $disposition = 'attachment') {
        $this->phpmailer->AddAttachment($filename, '', 'base64',
            $this->_mime_types(pathinfo($filename, PATHINFO_EXTENSION)));
        return $this;
    }

    public function send($auto_clear = true) {
        $result = (bool) $this->phpmailer->Send();
        if ($result) {
            $this->_set_error_message('lang:email_sent', $this->_get_protocol());
            if ($auto_clear) {
                $this->clear();
            }
        } else {
            $this->_set_error_message($this->phpmailer->ErrorInfo);
        }
        return $result;
    }


    // Custom methods ----------------------------------------------------------


    public function set_smtp_debug($level) {
        $level = (int) $level;
        if (empty($level)) {
            $level = false;
        }
        $this->phpmailer->SMTPDebug = $level;
    }

    public function full_html($subject, $message) {

        $full_html =
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>'.htmlspecialchars($subject, ENT_QUOTES, $this->charset).'</title>

    <style type="text/css">

        /* See http://htmlemailboilerplate.com/ */

        /* Based on The MailChimp Reset INLINE: Yes. */  
        /* Client-specific Styles */
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        body {
            width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:40px;
            font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 16px;
        } 
        /* End reset */

        /* Some sensible defaults for images
        Bring inline: Yes. */
        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;} 
        a img {border:none;} 

        /* Yahoo paragraph fix
        Bring inline: Yes. */
        p {margin: 1em 0;}

        /* Hotmail header color reset
        Bring inline: Yes. */
        h1, h2, h3, h4, h5, h6 {color: black !important;}

        h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

        h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
        color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }

        h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
        color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }

        /* Outlook 07, 10 Padding issue fix
        Bring inline: No.*/
        table td {border-collapse: collapse;}

        /* Remove spacing around Outlook 07, 10 tables
        Bring inline: Yes */
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

        /* Styling your links has become much simpler with the new Yahoo.  In fact, it falls in line with the main credo of styling in email and make sure to bring your styles inline.  Your link colors will be uniform across clients when brought inline.
        Bring inline: Yes. */
        a {color: blue;}

    </style>

</head>

<body>

'.$message.'

</body>
</html>';

        return $full_html;
    }


    // Protected methods -------------------------------------------------------


    protected function plain_text($html) {

        if (!function_exists('html_to_text')) {

            // This is a very basic html to plain text converter.
            $result = html_entity_decode($html, ENT_QUOTES, $this->charset);
            $result = str_ireplace(array('<br/>', '<br />', '<br>', '<hr/>', '<hr />', '<hr>'), "\n", $result);
            $result = str_ireplace(
                array('</p>',   '</h1>',   '</h2>',   '</h3>',   '</h4>',   '</h5>',   '</h6>',   '</tr>'),
                array("</p>\n", "</h1>\n", "</h2>\n", "</h3>\n", "</h4>\n", "</h5>\n", "</h6>\n", "</tr>\n"),
                $result
            );
            $result = strip_tags($result);
            $result = str_replace("\r", "\n", $result);
            $result = preg_replace('/\n{2,}/', "\n", $result);

            return $result;
        }

        // Also, a special helper function based on Markdown or Textile libraries may be used.
        //
        // An example of Markdown-based implementation, see http://milianw.de/projects/markdownify/
        //
        // Make sure the class Markdownify_Extra is autoloaded (or simply loaded somehow).
        // Place in MY_html_helper.php the following function.
        //
        // function html_to_text($html) {
        //     static $parser;
        //     if (!isset($parser)) {
        //         $parser = new Markdownify_Extra();
        //         $parser->keepHTML = false;
        //     }
        //     return @ $parser->parseString($html);
        // }
        //
        return html_to_text($html);
    }

    protected function copy_property_to_phpmailer($key) {
        static $properties = array(
            '_smtp_auth' => 'SMTPAuth',
            'mailpath' => 'Sendmail',
            'smtp_host' => 'Host',
            'smtp_user' => 'Username',
            'smtp_pass' => 'Password',
            'smtp_port' => 'Port',
            'smtp_timeout' => 'Timeout',
            'wrapchars' => 'WordWrap',
            'charset' => 'CharSet',
        );
        if (isset($properties[$key])) {
            $this->phpmailer->set($properties[$key], $this->$key);
        }
        if ($key == 'wrapchars') {
            if (!$this->wordwrap) {
                $this->phpmailer->set('WordWrap', 0);
            }
        }
    }

    protected function extract_name($address) {

        if (!is_array($address)) {
            $address = trim($address);
            if (preg_match('/(.*)\<(.*)\>/', $address, $match)) {
                return trim($match['1']);
            } else {
                return '';
            }
        }

        $result = array();
        foreach ($address as $addr) {
            $addr = trim($addr);
            if (preg_match('/(.*)\<(.*)\>/', $addr, $match)) {
                $result[] = trim($match['1']);
            } else {
                $result[] = '';
            }
        }
        return $result;
    }

}
