<?php

namespace Spatie\MailPreview;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Swift_Mime_SimpleMessage;

class PreviewTransport extends Transport
{
    protected Filesystem $files;

    protected string $previewPath;

    protected int $lifeTime;

    public function __construct(Filesystem $files, string $previewPath, int $lifeTime = 60)
    {
        $this->files = $files;

        $this->previewPath = $previewPath;

        $this->lifeTime = $lifeTime;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->createEmailPreviewDirectory();

        $this->cleanOldPreviews();

        Session::put('mail_preview_path', basename($previewPath = $this->getPreviewFilePath($message)));

        $this->files->put(
            "{$previewPath}.html",
            $this->getHTMLPreviewContent($message)
        );

        $this->files->put(
            "{$previewPath}.eml",
            $this->getEMLPreviewContent($message)
        );
    }

    protected function getPreviewFilePath(Swift_Mime_SimpleMessage $message): string
    {
        $recipients = array_keys($message->getTo());

        $to = ! empty($recipients)
            ? str_replace(['@', '.'], ['_at_', '_'], $recipients[0]).'_'
            : '';

        $subject = $message->getSubject();

        return $this->previewPath.
            '/'.
            Str::slug(
                $message->getDate()->format('u').'_'.$to.$subject,
                '_'
            );
    }

    protected function getHTMLPreviewContent(Swift_Mime_SimpleMessage $message): string
    {
        $messageInfo = $this->getMessageInfo($message);

        return $messageInfo.$message->getBody();
    }

    protected function getEMLPreviewContent(Swift_Mime_SimpleMessage $message): string
    {
        return $message->toString();
    }

    protected function getMessageInfo(Swift_Mime_SimpleMessage $message): string
    {
        return sprintf(
            "<!--\nFrom:%s, \nto:%s, \nreply-to:%s, \ncc:%s, \nbcc:%s, \nsubject:%s\n-->\n",
            json_encode($message->getFrom()),
            json_encode($message->getTo()),
            json_encode($message->getReplyTo()),
            json_encode($message->getCc()),
            json_encode($message->getBcc()),
            $message->getSubject()
        );
    }

    protected function createEmailPreviewDirectory(): void
    {
        if (! $this->files->exists($this->previewPath)) {
            $this->files->makeDirectory($this->previewPath);

            $this->files->put("{$this->previewPath}/.gitignore", "*\n!.gitignore");
        }
    }

    protected function cleanOldPreviews(): void
    {
        $oldPreviews = array_filter($this->files->files($this->previewPath), function ($file) {
            return time() - $this->files->lastModified($file) > $this->lifeTime;
        });

        if ($oldPreviews) {
            $this->files->delete($oldPreviews);
        }
    }
}
