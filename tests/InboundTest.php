<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WeCreateSolutions\Postmark\Attachment;
use WeCreateSolutions\Postmark\Contact;
use WeCreateSolutions\Postmark\Header;
use WeCreateSolutions\Postmark\Inbound;
use WeCreateSolutions\Postmark\InboundParseException;
use WeCreateSolutions\Postmark\Message;

/**
 * Class InboundTest
 */
class InboundTest extends TestCase
{
    public function testJsonParseFailure(): void
    {
        self::expectException(InboundParseException::class);

        $content = (string)file_get_contents(__DIR__ . '/../fixtures/incomplete.json');
        Inbound::fromPostmarkJson($content);
    }

    public function testJsonParseSuccessful(): void
    {
        $content = (string)file_get_contents(__DIR__ . '/../fixtures/inbound.json');
        $message = Inbound::fromPostmarkJson($content);

        self::assertEquals('This is an inbound message', $message->getSubject());
        self::assertEquals('myUser@theirDomain.com', $message->getFrom()->email);
        self::assertEquals('John Doe', $message->getFrom()->name);
        self::assertEquals('', $message->getFrom()->mailboxHash);
        self::assertEquals('ahoy', $message->getMailboxHash());
        self::assertEquals('22c74902-a0c1-4511-804f2-341342852c90', $message->getMessageId());
        self::assertEquals('inbound', $message->getMessageStream());
        self::assertEquals('451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', $message->getOriginalRecipient());
        self::assertEquals('[ASCII]', $message->getTextBody());
        self::assertEquals('[HTML]', $message->getHtmlBody());
        self::assertEquals('Ok, thanks for letting me know!', $message->getStrippedTextReply());
        self::assertEquals('tag', $message->getTag());
        self::assertEquals('myUsersReplyAddress@theirDomain.com', $message->getReplyTo());
        self::assertEquals('TEST', $message->getRawEmail());

        self::assertEquals(new DateTime('Thu, 5 Apr 2012 16:59:01 +0200'), $message->getDate());

        self::assertEquals([
            new Contact(
                "451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com",
                "",
                "ahoy"
            )
        ], $message->getTo());

        self::assertEquals([
            new Contact(
                "sample.cc@emailDomain.com",
                "Full name",
                ""
            ),
            new Contact(
                "another.cc@emailDomain.com",
                "Another Cc",
                ""
            ),
        ], $message->getCc());

        self::assertEquals([
            new Contact(
                "451d9b70cf9364d23ff6f9d51d870251569e@inbound.postmarkapp.com",
                "Full name",
                ""
            ),
        ], $message->getBcc());

        self::assertEquals([
            new Attachment(
                "myimage.png",
                "[BASE64-ENCODED CONTENT]",
                "image/png",
                4096
            ),
            new Attachment(
                "mypaper.doc",
                "[BASE64-ENCODED CONTENT]",
                "application/msword",
                16384
            ),
        ], $message->getAttachments());

        self::assertEquals([
            new Header(
                "X-Spam-Checker-Version",
                "SpamAssassin 3.3.1 (2010-03-16) onrs-ord-pm-inbound1.wildbit.com"
            ),
            new Header(
                "X-Spam-Status",
                "No"
            ),
            new Header(
                "X-Spam-Score",
                "-0.1"
            ),
            new Header(
                "X-Spam-Tests",
                "DKIM_SIGNED,DKIM_VALID,DKIM_VALID_AU,SPF_PASS"
            ),
            new Header(
                "Received-SPF",
                "Pass (sender SPF authorized) identity=mailfrom; client-ip=209.85.160.180; helo=mail-gy0-f180.google.com; envelope-from=myUser@theirDomain.com; receiver=451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com"
            ),
            new Header(
                "DKIM-Signature",
                "v=1; a=rsa-sha256; c=relaxed/relaxed;        d=wildbit.com; s=google;        h=mime-version:reply-to:message-id:subject:from:to:cc         :content-type;        bh=cYr/+oQiklaYbBJOQU3CdAnyhCTuvemrU36WT7cPNt0=;        b=QsegXXbTbC4CMirl7A3VjDHyXbEsbCUTPL5vEHa7hNkkUTxXOK+dQA0JwgBHq5C+1u         iuAJMz+SNBoTqEDqte2ckDvG2SeFR+Edip10p80TFGLp5RucaYvkwJTyuwsA7xd78NKT         Q9ou6L1hgy/MbKChnp2kxHOtYNOrrszY3JfQM="
            ),
            new Header(
                "MIME-Version",
                "1.0"
            ),
            new Header(
                "Message-ID",
                "<CAGXpo2WKfxHWZ5UFYCR3H_J9SNMG+5AXUovfEFL6DjWBJSyZaA@mail.gmail.com>"
            ),
        ], $message->getHeaders());

        self::assertEquals(
            new Header(
                "MIME-Version",
                "1.0"
            ),
            $message->getHeaderByName('MIME-Version')
        );
    }

    public function testSetters(): void
    {
        $message = new Message();
        $message->setSubject('This is an inbound message 12');
        self::assertEquals('This is an inbound message 12', $message->getSubject());

        $message->setMessageId('123');
        self::assertEquals('123', $message->getMessageId());

        $message->setMessageStream('inbound');
        self::assertEquals('inbound', $message->getMessageStream());

        $message->setTag('tag');
        self::assertEquals('tag', $message->getTag());

        $message->setStrippedTextReply('text');
        self::assertEquals('text', $message->getStrippedTextReply());

        $message->setRawEmail('raw');
        self::assertEquals('raw', $message->getRawEmail());

        $message->setHtmlBody('html');
        self::assertEquals('html', $message->getHtmlBody());

        $message->setTextBody('text');
        self::assertEquals('text', $message->getTextBody());

        $date = new DateTimeImmutable('Thu, 5 Apr 2012 16:59:01 +0200');
        $message->setDate($date);
        self::assertEquals($date, $message->getDate());

        $message->setMailboxHash('hash');
        self::assertEquals('hash', $message->getMailboxHash());

        $message->setReplyTo('test@test.com');
        self::assertEquals('test@test.com', $message->getReplyTo());

        $message->setOriginalRecipient('test@test.com');
        self::assertEquals('test@test.com', $message->getOriginalRecipient());

        $fromContact = new Contact('test@test.com', 'Fullname', 'Hash');
        $message->setFrom($fromContact);
        self::assertEquals($fromContact, $message->getFrom());

        $contact = new Contact('test@test.com', 'Fullname', 'Hash');
        $message->setTo([$contact, $contact]);
        self::assertEquals([$contact, $contact], $message->getTo());

        $message->setCc([$contact, $contact]);
        self::assertEquals([$contact, $contact], $message->getCc());

        $message->setBcc([$contact, $contact]);
        self::assertEquals([$contact, $contact], $message->getBcc());

        $header = new Header('X-Test', 'test');
        $message->setHeaders([$header]);
        self::assertEquals([$header], $message->getHeaders());

        $attachment = new Attachment('test.txt', 'content', 'text/plain', 123);
        $message->setAttachments([$attachment]);
        self::assertEquals([$attachment], $message->getAttachments());
    }
}
