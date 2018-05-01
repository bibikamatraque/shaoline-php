<?php

require_once "swiftMailer/swift_required.php";


/**
 * Mail lib
 *
 * PHP version 5.3
 *
 * @category Core
 * @package  ShaUtils
 * @author   Bastien DUHOT <bastien.duhot@free.fr>
 * @license  mon-referendum.com copyright
 * @link     No link
 *
 */
class ShaUtilsMail
{

    /**
     * Send mail to admin using param 'MAIL_ADMINS'
     *
     * @param string $sBody    Mail body
     * @param string $sSubject Mail subject
     *
     * @return void
     */
    public static function sendMailToAdmin($sBody, $sSubject)
    {

        $aMails = explode(";", ShaParameter::get('MAIL_ADMINS'));
        foreach ($aMails as $sMail) {
            self::sendMail($sBody, $sSubject, $sMail);
        }
    }

    /**
     * Send mail
     *
     * @param string $sBody    Mail body
     * @param string $sSubject receiver mail
     * @param string $sMail    Mail subject
     *
     * @return void
     */
    public static function sendMail($sBody, $sSubject, $sMail)
    {

        if (!ShaParameter::get('MAIL_ALLOWED')) {
            return;
        }
        //try {

            $msg
                = '
			<div style="width:100%;text-align:center;">
				<img alt="" src="' . ShaContext::getSiteFullUrl().ShaParameter::get("MAIL_LOGO_URL") . '"/>
			</div>
			<div style="width:100%;">
				' . $sBody . '
			</div>
			';

            if (ShaParameter::get('SMTP_NEED_AUTH')) {
                //Create the Transport the call setUsername() and setPassword()
                $transport = Swift_SmtpTransport::newInstance(
                    ShaParameter::get('SMTP_HOST'), ShaParameter::get('SMTP_PORT')
                )
                    ->setUsername(ShaParameter::get('SMTP_USER'))
                    ->setPassword(ShaParameter::get('SMTP_PASSWD'));
            } else {
                //Create the Transport the call setUsername() and setPassword()
                $transport = Swift_SmtpTransport::newInstance(
                    ShaParameter::get('SMTP_HOST'), ShaParameter::get('SMTP_PORT')
                );
            }


            //Create the Mailer using your created Transport
            $mailer = Swift_Mailer::newInstance($transport);

            //Create a message
            $message = Swift_Message::newInstance($sSubject)
                ->setContentType("text/html")
                ->setCharset("utf-8")
                ->setFrom(array(ShaParameter::get('MAIL_FROM_ADDRESS') => ShaParameter::get('MAIL_FROM_NAME')))
                ->setTo(array($sMail))
                ->setBody($msg);

            //Send the message
            $mailer->send($message);


        /*} catch (Swift_BadShaResponseException $e) {
            //throw new Exception($e->getMessage());
            throw new Exception("Erreur during mail sending !");
        }*/

    }


}

?>