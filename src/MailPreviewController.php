<?php

namespace Spatie\MailPreview;

class MailPreviewController
{
    /**
     * @return string
     */
    public function preview()
    {
        return file_get_contents(
            request('path')
                ? config('mailpreview.path').'/'.request('path').'.html'
                : last(glob(config('mailpreview.path').'/*.html'))
        );
    }
}