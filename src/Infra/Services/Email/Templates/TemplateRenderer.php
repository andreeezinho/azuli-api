<?php

namespace App\Infra\Services\Email\Templates;

class TemplateRenderer {

    private string $viewPath;

    public function __construct(?string $viewPath) {
        $this->viewPath = $viewPath ?? __DIR__ . '/../views/';
    }

    public function renderResetPasswordTemplate(array $data) : string {
        return $this->renderTemplate('reset-password', $data);
    }

    private function renderTemplate(string $template, array $data = []) : string {
        $templatePath = $this->getPath($template);

        if(!file_exists($templatePath)){
            throw new \RuntimeException("Template não encontrado: {$template}");
        }

        $content = file_get_contents($templatePath);

        return $this->replaceData($content, $data);
    }

    private function getPath(string $template) : string {
        if(!str_ends_with($template, '.html')){
            $template .= '.html';
        }

        return $this->viewPath . $template;
    }

    private function replaceData(string $content, array $data) : string {
        $data = array_merge([
            'app_name' => $_ENV['SITE_NAME'],
            'contact_number' => $_ENV['CONTACT_NUMBER'],
            'contact_mail' => $_ENV['CONTACT_MAIL'],
            'year' => date('Y')
        ], $data);

        foreach($data as $key => $value){
            $content = str_replace("{{ {$key} }}", $value, $content);
        }

        return $content;
    }

}