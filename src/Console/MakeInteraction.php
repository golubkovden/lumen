<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen\Console;

use FondBot\Conversation\ConversationCreator;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class MakeInteraction extends Command
{
    use DetectsApplicationNamespace;

    protected $signature = 'fondbot:make:interaction {name}';
    protected $description = 'Create a new interaction class';

    public function handle(ConversationCreator $creator)
    {
        $creator->createInteraction(app('path'), $this->getAppNamespace(), $this->argument('name'));

        $this->info('Interaction has been created.');
    }
}