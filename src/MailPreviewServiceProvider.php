<?php

namespace Spatie\MailPreview;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\Middleware\StartSession;
use Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MailPreview\Http\Middleware\AddMailPreviewPopupToResponse;

class MailPreviewServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mail-preview')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted()
    {
        $this
            ->registerPreviewMailTransport()
            ->registerRouteMacro();
    }

    protected function registerPreviewMailTransport(): self
    {
        $previewTransport = new PreviewMailTransport(
            app(Filesystem::class),
            config('mail-preview.storage_path'),
            config('mail-preview.maximum_lifetime_in_seconds')
        );

        app('mail.manager')->extend('preview', fn () => $previewTransport);

        return $this;
    }

    protected function registerRouteMacro(): self
    {
        Route::macro('mailPreview', function(string $prefix = 'spatie-mail-preview') {
            Route::get($prefix)->middleware(StartSession::class)->name('mail.preview');
        });

        return $this;
    }
}
