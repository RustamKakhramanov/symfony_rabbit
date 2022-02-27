<?php

namespace App\Enums;

use App\Notificators\Email;
use App\Notificators\SMS;
use App\Service\EmailService;
use App\Service\SmsService;

class NotificatorEnum extends BaseEnum
{
    const EMAIL =  EmailService::class;
    const SMS =  SmsService::class;

    const EMAIL_NOTIFY = Email::class;
    const SMS_NOTIFY = SMS::class;
}