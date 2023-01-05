<?php

namespace WeCreateSolutions\Postmark;

class Attachment
{
    public function __construct(
        public readonly string $name,
        public readonly string $content,
        public readonly string $contentType,
        public readonly int $contentLength
    ) {
    }
}
