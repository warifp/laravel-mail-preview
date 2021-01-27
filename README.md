# A mail driver that saves the rendered mails to disk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-mail-preview.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mail-preview)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-mail-preview/run-tests?label=tests)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-mail-preview.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mail-preview)

This package introduces a new `preview` mail driver for Laravel that when selected will render the content of the
sent email and save it as both `.html` and `.eml` files.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/package-skeleton-laravel.jpg?t=2" width="419px" />](https://spatie.be/github-ad-click/package-skeleton-laravel)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

Begin by installing the package through Composer. Run the following command in your terminal:

```bash
composer require themsaid/laravel-mail-preview
```

Then publish the config file:

```
php artisan vendor:publish --provider="Spatie\MailPreview\MailPreviewServiceProvider"
```

Finally, change the `mailers.smtp.transport` to `preview` in your `config/mail.php` file:

```
'mailers' => [
    'smtp' => [
        'transport' => 'preview',
        // ...
    ],
    // ...
]
```

## Usage

Everytime an email is sent, an `.html` and `.eml` file will be generated in `storage/email-previews` with a name that includes the first recipient and the subject:

```
1457904864_jack_at_gmail_com_invoice_000234.html
1457904864_jack_at_gmail_com_invoice_000234.eml
```

You can open the `.html` file in a web browser, or open the `.eml` file in your default email client to have a realistic look
at the final output.

### Preview in a web browser

When you open the `.html` file in a web browser you'll be able to see how your email will look, however there might be
some differences that varies from one email client to another.

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

### Package Configurations
From the `config/mailpreview.php` file you'll be able to change the output location of the preview files as well as the maximum lifetime for keeping previews, after this time old previews will get removed.

### Logged out after clicked on the preview link
You will always lose your current session if you click on the generated notification link. This is because Laravel stores the session in an encrypted cookie. To change this behavior, you have to adjust the `middleware` property in the `config/mailpreview.php` file to match the following snippet:

```php
    'middleware' => [
        \App\Http\Middleware\EncryptCookies::class,
    ],
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mohamed Said](https://github.com/themsaid)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

The initial version of this package was created by Mohamed Said, who graciously entrusted this package to us at Spatie.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
