<?php

namespace app\common\interfaces;

interface SmsNotification {
    /**
     * @return array
     */
    public function getPhoneNumbersForSms(): array;

    /**
     * @return string
     */
    public function getTextForSms(): string;
}