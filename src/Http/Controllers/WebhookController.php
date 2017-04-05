<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen\Http\Controllers;

use FondBot\BotFactory;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Container;
use Illuminate\Http\Request;

class WebhookController
{
    public function handle(
        Container $container,
        BotFactory $factory,
        Request $request,
        ChannelManager $channelManager,
        string $channel
    ) {
        $channel = $channelManager->create($channel);

        $bot = $factory->create(
            $container,
            $channel,
            $request->isJson() ? $request->json()->all() : $request->all(),
            $request->headers->all()
        );

        return $bot->process();
    }
}