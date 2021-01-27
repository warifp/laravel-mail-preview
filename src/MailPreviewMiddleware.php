<?php

namespace Spatie\MailPreview;

use Closure;
use Illuminate\Http\Response;

class MailPreviewMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldAttachPreviewLinkToResponse($request, $response)) {
            $this->attachPreviewLink(
                $response,
                $request->session()->get('mail_preview_path')
            );

            $request->session()->forget('mail_preview_path');
        }

        return $response;
    }

    /**
     * @param $request
     * @param $response
     * @return bool
     */
    private function shouldAttachPreviewLinkToResponse($request, $response)
    {
        return
            ! app()->runningInConsole() &&
            $response instanceof Response &&
            $request->hasSession() &&
            $request->session()->get('mail_preview_path');
    }

    /**
     * @param $response
     * @param $previewPath
     */
    private function attachPreviewLink($response, $previewPath)
    {
        $content = $response->getContent();

        $previewUrl = url('/themsaid/mail-preview?path='.$previewPath);

        $timeout = intval(config('mailpreview.popup_timeout', 8000));

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
}, $timeout);
</script>
HTML;

        $bodyPosition = strripos($content, '</body>');

        if (false !== $bodyPosition) {
            $content = substr($content, 0, $bodyPosition)
                .$linkContent
                .substr($content, $bodyPosition);
        }

        $response->setContent($content);
    }
}
