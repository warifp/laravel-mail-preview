<?php

namespace Themsaid\MailPreview;

use Illuminate\Support\ServiceProvider;

class MailPreviewServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/mailpreview.php' => config_path('mailpreview.php'),
        ]);

        $this->app['mail.manager']->extend('preview', function(){
            return new PreviewTransport(
                $this->app->make('Illuminate\Filesystem\Filesystem'),
                $this->app['config']['mailpreview.path'],
                $this->app['config']['mailpreview.maximum_lifetime']
            );
        });

        if ($this->app['config']['mailpreview.show_link_to_preview']) {
            $this->app['router']->group(['middleware' => $this->middleware()], function ($router) {
                $router->get('/themsaid/mail-preview')->uses(MailPreviewController::class.'@preview');
            });

            if ($this->app['config']['mailpreview.middleware_groups']) {
                foreach ($this->app['config']['mailpreview.middleware_groups'] as $groupName) {
                    $this->app['router']->pushMiddlewareToGroup(
                        $groupName,
                        MailPreviewMiddleware::class
                    );
                }
            }
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/mailpreview.php', 'mailpreview'
        );
    }

    /**
     * The array of middleware for the preview route.
     *
     * @return array
     */
    private function middleware()
    {
        return array_merge(
            (array) $this->app['config']['mailpreview.middleware'],
            [\Illuminate\Session\Middleware\StartSession::class]
        );
    }
}
