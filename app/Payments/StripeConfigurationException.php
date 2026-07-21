<?php

namespace App\Payments;

use RuntimeException;

class StripeConfigurationException extends RuntimeException
{
    public function __construct(public readonly string $reason)
    {
        parent::__construct('Stripe is not configured correctly.');
    }
}
