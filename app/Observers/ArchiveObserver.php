<?php

namespace App\Observers;

use App\Models\Archive;

class ArchiveObserver
{
    /**
     * Handle the archive "created" event.
     *
     * @param  \App\Models\Archive  $archive
     * @return void
     */
    public function created(Archive $archive)
    {
        //
    }

    /**
     * Handle the archive "updated" event.
     *
     * @param  \App\Models\Archive  $archive
     * @return void
     */
    public function updated(Archive $archive)
    {
        //
    }

    /**
     * Handle the archive "deleted" event.
     *
     * @param  \App\Models\Archive  $archive
     * @return void
     */
    public function deleted(Archive $archive)
    {
        //
    }

    /**
     * Handle the archive "restored" event.
     *
     * @param  \App\Models\Archive  $archive
     * @return void
     */
    public function restored(Archive $archive)
    {
        //
    }

    /**
     * Handle the archive "force deleted" event.
     *
     * @param  \App\Models\Archive  $archive
     * @return void
     */
    public function forceDeleted(Archive $archive)
    {
        //
    }
}
