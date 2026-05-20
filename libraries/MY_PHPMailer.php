<?php

/**
 * Added by Ivan Tcholakov, 20-MAY-2026.
 * We need to make some properties publicly accessible.
 */
class MY_PHPMailer extends \PHPMailer\PHPMailer\PHPMailer
{
    public $oauth;
    public static $LE = self::CRLF;
}
