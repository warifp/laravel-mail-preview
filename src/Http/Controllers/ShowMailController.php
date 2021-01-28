<?php

namespace Spatie\MailPreview\Http\Controllers;

use Illuminate\Http\Request;

class ShowMailController
{
    public function __invoke(Request $request)
    {
        $storedMailFileName = $request->get('mail_preview_file_name');

        $storedMailPath = $storedMailFileName
            ? config('mail-preview.path').'/'.$storedMailFileName.'.html'
            : last(glob(config('mail-preview.path').'/*.html'));

        return file_get_contents($storedMailPath);
    }
}
