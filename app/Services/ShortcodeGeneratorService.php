<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Form;

class ShortcodeGeneratorService
{
    public function generate(Form $form): string
    {
        return "[bitra_form id=\"{$form->shortcode}\"]";
    }

    public function generatePublicUrl(Form $form): string
    {
        return route('public.form', ['token' => $form->public_token]);
    }

    public function generateEmbedCode(Form $form): string
    {
        $url = $this->generatePublicUrl($form);

        return <<<HTML
<div id="bitra-form-{$form->id}"></div>
<script src="{$this->getScriptUrl()}" data-form-token="{$form->public_token}"></script>
HTML;
    }

    public function generateIframeCode(Form $form): string
    {
        $url = $this->generatePublicUrl($form);

        return <<<HTML
<iframe src="{$url}" width="100%" height="800" frameborder="0" style="border:none;"></iframe>
HTML;
    }

    private function getScriptUrl(): string
    {
        return asset('wordpress-shortcode.js');
    }

    public function parseShortcode(string $content): string
    {
        return preg_replace_callback(
            '/\[bitra_form id="([^"]+)"\]/',
            function ($matches) {
                $shortcode = $matches[1];
                $form = Form::where('shortcode', $shortcode)->where('status', 'active')->first();

                if (!$form) {
                    return '<p>Form not found</p>';
                }

                return $this->renderForm($form);
            },
            $content
        );
    }

    private function renderForm(Form $form): string
    {
        $url = $this->generatePublicUrl($form);
        return "<iframe src=\"{$url}\" width=\"100%\" height=\"800\" frameborder=\"0\" style=\"border:none;\"></iframe>";
    }
}

