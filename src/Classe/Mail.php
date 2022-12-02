<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '9fb68c14c70ad8ff7314d4f7373933b3';
    private $secret_key = '88de1a7bfb9fbcdb11fb1f29084df78e';

    public function send($email, $name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->secret_key, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
            [
                'From' => [
                    'Email' => "ludovic.braine@gmail.com",
                    'Name' => "La boutique française"
                ],
                'To' => [
                [
                    'Email' => $email,
                    'Name' => $name
                ]
                ],
                "TemplateID" => 4402672,
				"TemplateLanguage"=> true,
				"Subject"=> $subject,
                'Variables' => [
                    'content' => $content,
                ]
            ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}