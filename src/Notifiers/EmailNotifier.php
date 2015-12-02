<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Notifications\Notifiers;

use Mail;
use Seat\Notifications\Containers\Message;
use Seat\Services\Settings\Profile;

/**
 * Class EmailNotifier
 * @package Seat\Notifications\Notifiers
 */
class EmailNotifier implements NotifierInterface
{

    /**
     * @param \Seat\Notifications\Containers\Message $message
     */
    public function notify(Message $message)
    {

        // Check if the recipient wants to receive
        // email notifications based on their
        // profile preferences
        if (Profile::get('email_notifications', $message->recipient->id) != 'yes')
            return;

        Mail::send('notifications::emails.notification',
            ['data' => $message], function ($email) use ($message) {

                $email->to($message->recipient->email);
                $email->subject($message->subject);
            });

    }
}
