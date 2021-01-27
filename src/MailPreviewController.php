<?php

namespace Spatie\MailPreview;

class MailPreviewController
{
    public function preview()
    {
        return file_get_contents(
            request('path')
                ? config('mail-preview.path').'/'.request('path').'.html'
                : last(glob(config('mail-preview.path').'/*.html'))
        );
    }
}
