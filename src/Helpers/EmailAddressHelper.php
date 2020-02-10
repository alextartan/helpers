<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use InvalidArgumentException;
use Zend\Validator\EmailAddress;

final class EmailAddressHelper
{
    /** @var string */
    private $emailAddress;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $emailAddress)
    {
        $validator = new EmailAddress();
        if (!$validator->isValid($emailAddress)) {
            throw new InvalidArgumentException('The provided value is not a valid email address');
        }
        $this->emailAddress = $emailAddress;
    }

    public function getDomain(): string
    {
        return explode('@', $this->emailAddress)[1];
    }

    public function getUsername(): string
    {
        return explode('@', $this->emailAddress)[0];
    }
}
