<?php

namespace App\Libraries;
use App\Models\Response;

class PKMail
{
    protected $jsonResponse;
    protected $fromEmail;
    protected $fromName;
    public $subject;
    public $body;
    public $toEmail;
    public $bccEmail;
    
    public function __construct() {
        $this->jsonResponse = new Response();
        $this->fromEmail = config('mail.from.address');
        $this->fromName = config('mail.from.name');
    }
    
    public function send()
    {
        try {
            if (empty($this->subject)) {
                $this->jsonResponse->message = 'Judul email kosong';
                return $this->jsonResponse->get();
            }
            
            if (empty($this->body)) {
                $this->jsonResponse->message = 'Isi email kosong';
                return $this->jsonResponse->get();
            }
            
            if (empty($this->toEmail)) {
                $this->jsonResponse->message = 'Email tujuan kosong';
                return $this->jsonResponse->get();
            }
            
            $obj = $this;
            \Mail::send([], [], function($message) use ($obj) {
                $message->from($obj->fromEmail, $obj->fromName)
                    ->to($obj->toEmail);
                
                if (!empty($obj->bccEmail)) {
                    $message->bcc($obj->bccEmail);
                }
                $message->subject($obj->subject)
                    ->setBody($obj->body, 'text/html');
                
            });
            
            $this->jsonResponse->status = true;
            $this->jsonResponse->message = 'Pending Mail Sent';
        } catch (\Exception $e) {
            $this->jsonResponse->message = getExceptionMessage($e);
        }
        return $this->jsonResponse->get();
    }
    
}