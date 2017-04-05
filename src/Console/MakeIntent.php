<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen\Console;

use FondBot\Conversation\ConversationCreator;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class MakeIntent extends Command
{
    use DetectsApplicationNamespace;

    protected $signature = 'fondbot:make:intent {name}';
    protected $description = 'Create a new intent class';

    public function handle(ConversationCreator $creator)
    {
        $creator->createIntent(app('path'), $this->getAppNamespace(), $this->argument('name'));

        $this->info('Intent has been created.');
    }
}