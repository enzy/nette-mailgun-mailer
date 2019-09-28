<?php

namespace ondrs\MailgunMailer\DI;

use Nette\DI\CompilerExtension;
use ondrs\MailgunMailer\Mailer;

class Extension extends CompilerExtension
{

    /** @var array */
    private $defaults = [
		'apiKey' => NULL,
		'domain' => NULL,
		'servers' => 'https://api.mailgun.net',
    ];


    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('mailgun'))
            ->setFactory('Mailgun\Mailgun::create', [
				$config['apiKey'],
				$config['servers']
            ]);

        $builder->getDefinition('mail.mailer')
            ->setFactory(Mailer::class, [
                $config['domain'],
                $builder->getDefinition($this->prefix('mailgun'))
            ]);

        $builder->addAlias($this->prefix('mailer'), 'mail.mailer');

    }

}
