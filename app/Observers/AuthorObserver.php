<?php

namespace App\Observers;

use App\Models\Author;

class AuthorObserver
{
    /**
     * Handle the author "created" event.
     *
     * @param  \App\Models\Author  $author
     * @return void
     */
    public function created(Author $author)
    {
        //
    }

    /**
     * Handle the author "updated" event.
     *
     * @param  \App\Models\Author  $author
     * @return void
     */
    public function updated(Author $author)
    {
        //
    }

    /**
     * Handle the author "deleted" event.
     *
     * @param  \App\Models\Author  $author
     * @return void
     */
    public function deleted(Author $author)
    {
        //
    }

    /**
     * Handle the author "restored" event.
     *
     * @param  \App\Models\Author  $author
     * @return void
     */
    public function restored(Author $author)
    {
        //
    }

    /**
     * Handle the author "force deleted" event.
     *
     * @param  \App\Models\Author  $author
     * @return void
     */
    public function forceDeleted(Author $author)
    {
        //
    }
}
