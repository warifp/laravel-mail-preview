<?php

namespace Spatie\MailPreview;

use Closure;
use Illuminate\Http\Response;

class MailPreviewMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! $this->shouldAttachPreviewLinkToResponse($request, $response)) {
            return $request;
        }

        $this->attachPreviewLink(
            $response,
            $request->session()->get('mail_preview_path')
        );

        $request->session()->forget('mail_preview_path');

        return $response;
    }

    protected function shouldAttachPreviewLinkToResponse($request, $response): bool
    {
        if (app()->runningInConsole()) {
            return false;
        }

        if (! $response instanceof Response) {
            return false;
        }

        if (! $request->hasSession()) {
            return false;
        }

        if (! $request->session()->get('mail_preview_path')) {
            return false;
        }

        return true;
    }

    protected function attachPreviewLink($response, $previewPath)
    {
        $content = $response->getContent();

        $previewUrl = route('mail.preview', ['storage_path' => $previewPath]);

        $timeout = config('mail-preview.popup_timeout_in_seconds');

        $linkContent = <<<HTML
<div id="MailPreviewDriverBox" style="
    position:absolute;
    top:0;
    z-index:99999;
    background:#fff;
    border:solid 1px #ccc;
    padding: 15px;
    ">
An email was just sent: <a href="$previewUrl">Preview Sent Email</a>
</div>
<script type="text/javascript">
setTimeout(function(){
    document.body.removeChild(document.getElementById('MailPreviewDriverBox'));
}, $timeout * 1000);
</script>
HTML;

        $bodyPosition = strripos($content, '</body>');

        if (false !== $bodyPosition) {
            $content = substr($content, 0, $bodyPosition)
                . $linkContent
                . substr($content, $bodyPosition);
        }

        $response->setContent($content);
    }
}
