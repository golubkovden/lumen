<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Lumen\Http\Controllers;

use FondBot\BotFactory;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    public function handle(
        Container $container,
        BotFactory $factory,
        Request $request,
        ChannelManager $channelManager,
        string $channel
    ) {
        $channel = $channelManager->create($channel);

        dd($channelManager);

        $bot = $factory->create(
            $container,
            $channel,
            $request->isJson() ? $request->json()->all() : $request->all(),
            $request->headers->all()
        );

        return $bot->process();
    }
}