<?php

namespace Spatie\MailPreview;

class PreviewMailController
{
    public function __invoke()
    {
        return file_get_contents(
            request('path')
                ? config('mail-preview.path').'/'.request('path').'.html'
                : last(glob(config('mail-preview.path').'/*.html'))
        );
    }
}
