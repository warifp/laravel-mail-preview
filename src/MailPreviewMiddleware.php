<?php

namespace Themsaid\MailPreview;

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
        $addLinktoResponse = false;

        if (
            $request->hasSession() &&
            $previewPath = $request->session()->get('mail_preview_path')
        ) {
            $addLinktoResponse = true;

            $request->session()->forget('mail_preview_path');
        }

        $response = $next($request);

        if (
            $response instanceOf Response &&
            $addLinktoResponse
        ) {
            $this->addLinkToResponse($response, $previewPath);
        }

        return $response;
    }

    /**
     * Modify the response to add link to the email preview.
     *
     * @param $response
     * @param $previewPath
     */
    private function addLinkToResponse($response, $previewPath)
    {
        if (app()->runningInConsole()) {
            return;
        }

        $content = $response->getContent();

        $linkHTML = "<div id='MailPreviewDriverBox' style='
            position:absolute;
            top:0;
            z-index:99999;
            background:#fff;
            border:solid 1px #ccc;
            padding: 15px;
            '>
        An email was just sent: <a href='".url('/themsaid/mail-preview?path='.$previewPath)."'>Preview Sent Email</a>
        </div>";

        $timeout = intval(config('mailpreview.popup_timeout', 8000));

        if ($timeout > 0) {
            $linkHTML .= "<script type=\"text/javascript\">";

            $linkHTML .= "setTimeout(function(){
            document.body.removeChild(document.getElementById('MailPreviewDriverBox'));
            }, " . $timeout . ");";

            $linkHTML .= "</script>";
        }


        $bodyPosition = strripos($content, '</body>');

        if (false !== $bodyPosition) {
            $content = substr($content, 0, $bodyPosition).$linkHTML.substr($content, $bodyPosition);
        }

        $response->setContent($content);
    }
}
