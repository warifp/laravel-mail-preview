<?php

namespace Spatie\MailPreview\Tests;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class MailPreviewTest extends TestCase
{
    /** @test */
    public function it_will_write_sent_mails_to_disk()
    {
        $this->assertTrue(true);

        Mail::raw('laravel mail preview test', function(Message $message) {
            $message->to('john@example.com');
        });

        $this->assertLatestStoredMailContains("laravel mail preview test");
    }
}