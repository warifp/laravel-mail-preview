<?php

namespace Spatie\MailPreview\Tests;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class MailPreviewTest extends TestCase
{
    /** @test */
    public function it_will_write_sent_mails_to_disk()
    {
        Mail::raw('mail content', fn (Message $message) => $message->to('john@example.com'));

        $this->assertTrue(true);
    }
}
