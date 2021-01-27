<?php

namespace Spatie\MailPreview;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\Middleware\StartSession;
use Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MailPreviewServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mail-preview')
            ->hasConfigFile();
    }

    public function boot()
    {
        app('mail.manager')->extend('preview', function () {
            return new PreviewTransport(
                app(Filesystem::class),
                config('mail-preview.path'),
                config('mail-preview.maximum_lifetime')
            );
        });

        if (config('mail-preview.show_link_to_preview')) {
            Route::get('spatie/mail-preview')->middleware($this->middleware())->name('mail.preview');

            if (config('mail-preview.middleware_groups')) {
                foreach (config('mail-preview.middleware_groups') as $groupName) {
                    app('router')->pushMiddlewareToGroup(
                        $groupName,
                        MailPreviewMiddleware::class
                    );
                }
            }
        }
    }

    protected function middleware(): array
    {
        return array_merge(
            $this->app['config']['mail-preview.middleware'],
            [StartSession::class]
        );
    }
}
