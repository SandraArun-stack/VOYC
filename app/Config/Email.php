<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail;
    public string $fromName;
    public string $protocol;
    public string $SMTPHost;
    public string $SMTPUser;
    public string $SMTPPass;
    public int    $SMTPPort;
    public string $SMTPCrypto;
    public string $mailType;
    public string $charset;
    public bool   $wordWrap = true;

    public function __construct()
    {
        parent::__construct();

        $this->fromEmail = env('email.fromEmail');
        $this->fromName  = env('email.fromName');
        $this->protocol  = env('email.protocol');
        $this->SMTPHost  = env('email.SMTPHost');
        $this->SMTPUser  = env('email.SMTPUser');
        $this->SMTPPass  = env('email.SMTPPass');
        $this->SMTPPort  = env('email.SMTPPort');
        $this->SMTPCrypto = env('email.SMTPCrypto');
        $this->mailType  = env('email.mailType');
        $this->charset   = env('email.charset');
    }
}
