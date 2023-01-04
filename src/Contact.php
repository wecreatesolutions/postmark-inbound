<?php

namespace WeCreateSolutions\Postmark;

class Contact
{
    public function __construct(
        public readonly string $email,
        public readonly string $name = '',
        public readonly string $mailboxHash = ''
    ) {
    }
}
