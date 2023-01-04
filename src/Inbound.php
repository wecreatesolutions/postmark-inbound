<?php

namespace WeCreateSolutions\Postmark;

use DateTimeImmutable;
use Exception;

/**
 * @phpstan-type InboundMessage array{
 *                                       Date: string,
 *                                       MessageStream: string,
 *                                       FromFull: InboundMessageContact,
 *                                       ToFull: array<int, InboundMessageContact>,
 *                                       CcFull: array<int, InboundMessageContact>|null,
 *                                       BccFull: array<int, InboundMessageContact>|null,
 *                                       Subject: string,
 *                                       OriginalRecipient: string,
 *                                       MailboxHash: string,
 *                                       MessageID: string,
 *                                       Headers: array<int, InboundMessageHeader>,
 *                                       Attachments: array<int, InboundMessageAttachment>,
 *                                       ReplyTo: string,
 *                                       TextBody: string,
 *                                       HtmlBody: string,
 *                                       StrippedTextReply: string|null,
 *                                       Tag: string
 *                                     }
 *
 * @phpstan-type InboundMessageContact array{
 *                                          Email: string,
 *                                          Name: string,
 *                                          MailboxHash: string|null
 *                                       }
 *
 * @phpstan-type InboundMessageHeader array{
 *                                          Name: string,
 *                                          Value: string
 *                                       }
 *
 * @phpstan-type InboundMessageAttachment array{
 *                                          Name: string,
 *                                          Content: string,
 *                                          ContentType: string,
 *                                          ContentLength: string
 *                                       }
 */
class Inbound
{
    /**
     * @phpstan-param InboundMessage $json
     */
    public static function fromPostmarkArray(array $json): Message
    {
        $jsonToContact = static fn (array $contactData): Contact => new Contact(
            $contactData['Email'] ?? '',
            $contactData['Name'] ?? '',
            $contactData['MailboxHash'] ?? ''
        );

        $jsonToAttachment = static fn (array $attachmentData): Attachment => new Attachment(
            $attachmentData['Name'] ?? '',
            $attachmentData['Content'] ?? '',
            $attachmentData['ContentType'] ?? '',
            $attachmentData['ContentLength'] ?? ''
        );

        $jsonToHeader = static fn (array $headerData): Header => new Header(
            $headerData['Name'] ?? '',
            $headerData['Value'] ?? ''
        );

        try {
            $dateTimeImmutable = new DateTimeImmutable($json['Date']);
        } catch (Exception) {
            throw new InboundParseException('Invalid date format ');
        }

        $requiredKeys = ['FromFull', 'ToFull', 'CcFull', 'BccFull', 'Subject', 'OriginalRecipient', 'MailboxHash', 'MessageID', 'Headers', 'Attachments', 'ReplyTo', 'TextBody', 'HtmlBody', 'StrippedTextReply', 'Tag'];
        $missingKeys = [];
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $json)) {
                $missingKeys[] = $requiredKey;
            }
        }

        if (count($missingKeys) !== 0) {
            throw new InboundParseException(sprintf('Missing required key(s): %1$s', implode(', ', $missingKeys)));
        }

        return (new Message())
            ->setFrom($jsonToContact($json['FromFull']))
            ->setTo(array_map($jsonToContact, $json['ToFull']))
            ->setCc(array_map($jsonToContact, $json['CcFull'] ?? []))
            ->setBcc(array_map($jsonToContact, $json['BccFull'] ?? []))
            ->setOriginalRecipient($json['OriginalRecipient'])
            ->setSubject($json['Subject'])
            ->setMessageStream($json['MessageStream'])
            ->setMessageId($json['MessageID'])
            ->setHeaders(array_map($jsonToHeader, $json['Headers']))
            ->setAttachments(array_map($jsonToAttachment, $json['Attachments']))
            ->setReplyTo($json['ReplyTo'])
            ->setMailboxHash($json['MailboxHash'])
            ->setDate($dateTimeImmutable)
            ->setTextBody($json['TextBody'])
            ->setHtmlBody($json['HtmlBody'])
            ->setStrippedTextReply($json['StrippedTextReply'] ?? '')
            ->setTag($json['Tag']);
    }

    public static function fromPostmarkJson(string $json): Message
    {
        $jsonData = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        /** @phpstan-var InboundMessage $jsonData */

        return self::fromPostmarkArray($jsonData);
    }
}
