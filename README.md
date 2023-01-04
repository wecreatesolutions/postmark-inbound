# We Create Solutions Postmark Inbound Package 

Goal of this package is to ease the use of a Postmark Inbound Webhook. 


### Definitions

#### Inbound

This class allows you to convert the Postmark json string into a Message class

#### Contact

Represents a mail contact (cc, bcc, from, to)

#### Header 

Represents a mail message header containing a name and value. 

#### Attachment

Represents any attachment that was sent along with the mail message. 


### Examples 

Let's say we want to convert the Postmark inbound json string into a message 
class that we can use in our application. 

```php

$message = \WeCreateSolutions\PostmarkInbound\Message::fromPostmarkJson($postmarkJson);

```

The message class will contain convenient getters to all values that represent the mail message.
