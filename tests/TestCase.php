<?php

namespace Themsaid\MailPreview\Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Themsaid\MailPreview\MailPreviewServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('mail.mailers.smtp.transport', 'preview');

        $this->clearSentMails();
    }

    protected function getPackageProviders($app)
    {
        return [
            MailPreviewServiceProvider::class,
        ];
    }

    protected function assertLatestStoredMailContains(string $substring): void
    {
        $this->assertDirectoryExists(config('mailpreview.path'));

        $latestMailPath = collect(File::allFiles(config('mailpreview.path')))
            ->sortByDesc->getMTime()
            ->first()->getPathName();

        $latestMailContent = file_get_contents($latestMailPath);

        $latestMailContent = str_replace("=\r\n", '', $latestMailContent);

        $this->assertStringContainsString($substring, $latestMailContent);
    }

    protected function clearSentMails(): void
    {
        File::cleanDirectory(config('mailpreview.path'));
    }
}