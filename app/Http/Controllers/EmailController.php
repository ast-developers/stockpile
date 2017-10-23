<?php

/**
 * Email Controller
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use Config;

class EmailController extends Controller
{
    

    public function __construct()
    {
       
    }

    public function sendEmail($to,$subject,$messageBody)
    {
        $mail = new \App\libraries\MailService;
        $dataMail = [];
        $dataMail = array(
            'to' => array($to),
            'subject' => $subject,
            'content' => $messageBody,
        );
        //d($dataMail,1);
        $mail->send($dataMail,'emails.paymentReceipt');

    }
}
