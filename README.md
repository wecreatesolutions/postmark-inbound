# Postmark Inbound Package 

Goal of this package is to ease the use of a Postmark Inbound Webhook. 

## Requirements
>= PHP 8.1

## Definitions

### Inbound

This object allows you to convert the Postmark json string into a Message object

### Contact

Represents a mail contact (cc, bcc, from, to)

### Header 

Represents a mail message header containing a name and value. 

### Attachment

Represents any attachment that was sent along with the mail message. 


## Examples 

Let's say we want to convert the Postmark inbound json string into a message 
object that we can use in our application. 

```php
$message = \WeCreateSolutions\PostmarkInbound\Message::fromPostmarkJson($postmarkJson);
```

The message object will contain convenient getters to all values that represent the mail message.

### Headers

All headers can be fetched with getHeaders(), an array of Header objects will be returned.

```php
$message = \WeCreateSolutions\PostmarkInbound\Message::fromPostmarkJson($postmarkJson);
$headers = $message->getHeaders();
foreach ($headers as $header) {
    echo $header->name . ': ' . $header->value . PHP_EOL;
}
```

A convenience method is also available to get a specific header by name.

```php
$message = \WeCreateSolutions\PostmarkInbound\Message::fromPostmarkJson($postmarkJson);
$header = $message->getHeaderByName('X-Spam-Status');
if (null !== $header) {
    echo $header->name . ': ' . $header->value . PHP_EOL;
}
```

### Contacts
The Contact object is used to store from, to, cc and bcc contacts. Via the getCc(), getBcc(), getFrom() and getTo() 
methods you are able to get an array of Contact objects.

```php
$message = \WeCreateSolutions\PostmarkInbound\Message::fromPostmarkJson($postmarkJson);
$cc = $message->getCc();
foreach ($cc as $contact) {
    echo $contact->name . ': ' . $contact->email . PHP_EOL;
}
```

## Change log

Please see [CHANGELOG][link-changelog] for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING][link-contribute] for details.

## Security Vulnerabilities

Please review [our security policy][link-policy] on how to report security vulnerabilities.

## Credits

- [Alex Buis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File][link-license] for more information.

[link-author]: https://github.com/alexbuis
[link-contributors]: ../../contributors
[link-policy]: ../../security/policy
[link-contribute]: .github/CONTRIBUTING.md
[link-changelog]: CHANGELOG.md
[link-license]: LICENSE.md
