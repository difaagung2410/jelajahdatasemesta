<?php

namespace App\Listeners;

use App\Events\NewsHistory;
use App\Models\NewsLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreNewsHistory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsHistory $event)
    {
        // Menambahkan data ke log berita
        $news_log = NewsLog::create([
            'news_title' => $event->news->title,
            'news_content' => $event->news->content,
            'action' => $event->action,
            'action_by' => auth()->user()->name
        ]);

        return $news_log;
    }
}
