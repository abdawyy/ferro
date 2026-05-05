<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * Sends transactional mail using config('ferro.mail.queue'):
 * false = immediate send (no queue worker required)
 * true  = Mail::queue() (requires php artisan queue:work)
 */
final class FerroMail
{
    public static function useQueue(): bool
    {
        return (bool) config('ferro.mail.queue', false);
    }

    public static function to(string $email, Mailable $mailable, ?string $locale = null): void
    {
        $pending = Mail::to($email);
        if ($locale !== null && $locale !== '') {
            $pending = $pending->locale($locale);
        }
        if (self::useQueue()) {
            $pending->queue($mailable);
        } else {
            $pending->send($mailable);
        }
    }

    /**
     * @param  non-empty-string  $queue
     */
    public static function toQueuedOn(string $email, Mailable $mailable, string $queue, ?string $locale = null): void
    {
        if (! self::useQueue()) {
            self::to($email, $mailable, $locale);

            return;
        }

        $pending = Mail::to($email);
        if ($locale !== null && $locale !== '') {
            $pending = $pending->locale($locale);
        }
        $pending->queue($mailable->onQueue($queue));
    }
}
