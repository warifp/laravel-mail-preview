<?php

namespace Spatie\MailPreview\Tests\TestClasses;

use Illuminate\Mail\Mailable;

class TestMailable extends Mailable
{
    public function build()
    {
        $this
            ->from('ringo@example.com')
            ->subject('this is the subject')
            ->html('this is the html');
    }
}
