# A mail driver to quickly preview mail

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-mail-preview.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mail-preview)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-mail-preview/run-tests?label=tests)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-mail-preview.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mail-preview)

This package can display a small overlay whenever a mail is sent. The overlay contains a link to the mail that was just sent.

<img alt="screenshot" src="http://spatie.github.io/laravel-mail-preview/images/overlay.png" width="400" />

This can be handy when testing out emails in a local environment. 

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/package-skeleton-laravel.jpg?t=2" width="419px" />](https://spatie.be/github-ad-click/package-skeleton-laravel)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-mail-preview
```

### Configuring the mail transport

This package contains a mail transport called `preview`. We recommend to only use this transport in non-production environments. To use the `preview` transport, change the `mailers.smtp.transport` to `preview` in your `config/mail.php` file:

```php
// in config/mail.php

'mailers' => [
    'smtp' => [
        'transport' => 'preview',
        // ...
    ],
    // ...
],
```

### Registering the preview middleware route

The package can display a links to sent mails whenever they are sent. To use this feature, you must add the `Spatie\MailPreview\Http\Middleware\AddMailPreviewPopupToResponse` middleware to the `web` middleware group in your kernel.

```php
// in app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        // other middleware
        
        \Spatie\MailPreview\Http\Middleware\AddMailPreviewOverlayToResponse::class,
    ],
    
    // ...
];
```

You must also add the `mailPreview` to your routes file. Typically, the routes file will be located at `routes/web.php`.

```php
// in routes/web.php

Route::mailPreview();
```

This will register a route to display sent mails at `/spatie-mail-preview`. To customize the URL, pass the URL you want to the macro.

```php
Route::mailPreview('custom-url-where-sent-mails-will-be-shown');
```

### Publishing the config file

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --provider="Spatie\MailPreview\MailPreviewServiceProvider" --tag="laravel-mail-preview-config"
```

This is the content of the config file that will be published at `config/mail-preview.php`:

```php
return [
    /*
     * All mails will be stored in the given directory.
     */
    'storage_path' => storage_path('email-previews'),

    /*
     * This option determines how long generated preview files will be kept.
     */
    'maximum_lifetime_in_seconds' => 60,

    /*
     * When enabled, a link to mail will be added to the response
     * every time a mail is sent.
     */
    'show_link_to_preview' => true,

    /*
     * Determines how long the preview pop up should remain visible.
     *
     * Set this to `false` if the popup should stay visible.
     */
    'popup_timeout_in_seconds' => 8,
];
```

### Publishing the views

Optionally, you can publish the views that render the preview overlay and the mail itself.

```bash
php artisan vendor:publish --provider="Spatie\MailPreview\MailPreviewServiceProvider" --tag="laravel-mail-preview-views"
```

You can modify the views that will be published at `resources/views/vendor/mail-preview` to your liking.

## Usage

Everytime an email is sent, an `.html` and `.eml` file will be savind in the directory specified in the `storage_path` of the `mail-preview` config file.  The name includes the first recipient and the subject:

```
1457904864_john_at_example_com_invoice_000234.html
1457904864_john_at_example_com_invoice_000234.eml
```

You can open the `.html` file in a web browser.  The `.eml` file in your default email client to have a realistic look
of the final output.

### Preview in a web browser

When you open the `.html` file in a web browser you'll be able to see how your email will look.

At the beginning of the generated file you'll find an HTML comment with all the message info:

```html
<!--
From:{"info@acme.com":"Acme HQ"},
to:{"jack@gmail.com":"Jack Black"},
reply-to:{"info@acme.com"},
cc:[{"finance@acme.com":"Acme Finance"}, {"management@acme.com":"Acme Management"}],
bcc:null,
subject:Invoice #000234
-->
```

### Events

Whenever a mail is stored on disk, the `Spatie\MailPreview\Events\MailStoredEvent` will be fired. It has three public properties:

- `message`: an instance of `Swift_Mime_SimpleMessage`
- `pathToHtmlVersion`: the path to the html version of the sent mail
- `pathToEmlVersion`: the path to the email version of the sent mail

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Mohamed Said](https://github.com/themsaid)
- [All Contributors](../../contributors)

The initial version of this package was created by Mohamed Said, who graciously entrusted this package to us at Spatie.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
