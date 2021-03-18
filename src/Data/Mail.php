<?php 

namespace App\Data;

use Mailjet\Client;
use Mailjet\Resources;

class Mail {
  
    private $api_key = "fa22cbc18dad5a4d64a6738277cfe545";
    private $api_key_secret ="630a18b1c814af21dd08c40fa697a403";
    
    public function send($to_email, $to_name, $subject, $content)
    {
       
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
              [
                  'From' => [
                    'Email' => "yusufheri64@gmail.com",
                    'Name' => "La Boutique Française"
                  ],
                  'To' => [
                    [
                      'Email' => $to_email,
                      'Name' => $to_name
                    ]
                  ],
                  'TemplateID' => 2656942,
                  'TemplateLanguage' => true,
                  'Subject' => $subject,
                  'Variables' => [
                    'content' => $content
                  ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
  
}