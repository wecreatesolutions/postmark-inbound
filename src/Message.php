<?php

namespace WeCreateSolutions\Postmark;

use DateTimeImmutable;

class Message
{
    private string $subject;

    private string $originalRecipient;

    private string $messageId;

    private string $replyTo;

    private string $mailboxHash;

    private DateTimeImmutable $date;

    private string $textBody;

    private string $htmlBody;

    private string $strippedTextReply;

    private string $tag;

    private string $messageStream;

    private Contact $from;

    /**
     * @var array<Contact>
     */
    private array $to = [];

    /**
     * @var array<Contact>
     */
    private array $cc = [];

    /**
     * @var array<Contact>
     */
    private array $bcc = [];

    /**
     * @var array<Attachment>
     */
    private array $attachments = [];

    /**
     * @var array<Header>
     */
    private array $headers = [];

    public function setSubject(string $subject): Message
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setOriginalRecipient(string $originalRecipient): Message
    {
        $this->originalRecipient = $originalRecipient;
        return $this;
    }

    public function getOriginalRecipient(): string
    {
        return $this->originalRecipient;
    }

    public function setMessageId(string $messageId): Message
    {
        $this->messageId = $messageId;
        return $this;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    public function setReplyTo(string $replyTo): Message
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function getMailboxHash(): string
    {
        return $this->mailboxHash;
    }

    public function setMailboxHash(string $mailboxHash): Message
    {
        $this->mailboxHash = $mailboxHash;
        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): Message
    {
        $this->date = $date;
        return $this;
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function setTextBody(string $textBody): Message
    {
        $this->textBody = $textBody;
        return $this;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function setHtmlBody(string $htmlBody): Message
    {
        $this->htmlBody = $htmlBody;
        return $this;
    }

    public function getStrippedTextReply(): string
    {
        return $this->strippedTextReply;
    }

    public function setStrippedTextReply(string $strippedTextReply): Message
    {
        $this->strippedTextReply = $strippedTextReply;
        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): Message
    {
        $this->tag = $tag;
        return $this;
    }

    public function getMessageStream(): string
    {
        return $this->messageStream;
    }

    public function setMessageStream(string $messageStream): Message
    {
        $this->messageStream = $messageStream;
        return $this;
    }

    public function setFrom(Contact $from): Message
    {
        $this->from = $from;
        return $this;
    }

    public function getFrom(): Contact
    {
        return $this->from;
    }

    /**
     * @param Contact[] $cc
     */
    public function setCc(array $cc): Message
    {
        $this->cc = [];
        array_walk_recursive($cc, function (Contact $contact) {
            $this->cc[] = $contact;
        });
        return $this;
    }

    /**
     * @return Contact[]
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @param Contact[] $bcc
     * @return $this
     */
    public function setBcc(array $bcc): Message
    {
        $this->bcc = [];
        array_walk_recursive($bcc, function (Contact $contact) {
            $this->bcc[] = $contact;
        });
        return $this;
    }

    /**
     * @param Contact[] $to
     * @return $this
     */
    public function setTo(array $to): Message
    {
        $this->to = [];
        array_walk_recursive($to, function (Contact $contact) {
            $this->to[] = $contact;
        });
        return $this;
    }

    /**
     * @return Contact[]
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @return Contact[]
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param Attachment[] $attachments
     * @return $this
     */
    public function setAttachments(array $attachments): Message
    {
        $this->attachments = [];
        array_walk_recursive($attachments, function (Attachment $attachment) {
            $this->attachments[] = $attachment;
        });
        return $this;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param Header[] $headers
     * @return $this
     */
    public function setHeaders(array $headers): Message
    {
        $this->headers = [];
        array_walk_recursive($headers, function (Header $header) {
            $this->headers[] = $header;
        });
        return $this;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeaderByName(string $name): ?Header
    {
        foreach ($this->headers as $header) {
            if ($header->name === $name) {
                return $header;
            }
        }
        return null;
    }
}
