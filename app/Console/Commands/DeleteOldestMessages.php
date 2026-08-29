<?php

namespace App\Console\Commands;

use App\Services\DiscordService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldestMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:delete_messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = app(DiscordService::class)->getMessages();

        foreach ($messages as $message) {
            if (Carbon::parse($message->timestamp) <= Carbon::now()) {
                app(DiscordService::class)->deleteMessage(882668631891247118, $message->id);

                echo "Message $message->id deleted \n";
            }
        }
    }
}
