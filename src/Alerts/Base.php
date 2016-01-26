<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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

namespace Seat\Notifications\Alerts;

use Seat\Notifications\Containers\Message;
use Seat\Notifications\Exceptions\NoNotifiersException;
use Seat\Notifications\Exceptions\TypeException;
use Seat\Notifications\Models\Notification;
use Seat\Web\Models\User;

/**
 * Class Base
 * @package Seat\Notifications\Alerts
 */
abstract class Base
{

    /**
     * The notifier drivers
     *
     * @var
     */
    protected $notifiers = [];

    /**
     * The message container that will be sent
     *
     * @var
     */
    protected $message;

    /**
     * A required method to call a notifications class
     *
     * @return mixed
     */
    abstract function call();

    /**
     * Construct an instance by setting up the notifiers
     */
    public function __construct()
    {

        $this->setNotifiers();
    }

    /**
     * Load the notifications drivers.
     *
     * @return $this
     * @throws \Seat\Notifications\Exceptions\NoNotifiersException
     */
    private function setNotifiers()
    {

        if (empty(config('notifications.notifiers')))
            throw new NoNotifiersException;

        $this->notifiers = config('notifications.notifiers');

    }

    /**
     * Determine which users have a specified permission
     *
     * @param      $permission
     * @param null $character_id
     * @param null $corporation_id
     *
     * @return array
     * @throws \Seat\Notifications\Exceptions\TypeException
     */
    public function usersWithPermission(
        $permission, $character_id = null, $corporation_id = null)
    {

        if (!is_null($character_id) && !is_null($corporation_id))
            throw new TypeException(
                'Define either the character_id or corporation_id. Not both.');

        $users = [];

        foreach (User::all() as $user) {

            $user->setCorporationId($corporation_id);
            $user->setCharacterId($character_id);

            if ($user->has($permission))
                array_push($users, $user);
        }

        return $users;

    }

    /**
     * Instantiate a new Messge Container and
     * return it.
     *
     * @return \Seat\Notifications\Containers\Message
     */
    public function newMessage()
    {

        $this->message = new Message;

        return $this->message;
    }

    /**
     * Send the notification using the notification
     * drivers.
     *
     * @param \Seat\Notifications\Containers\Message $message
     */
    public function sendNotification(Message $message)
    {

        if ($this->isMessageOk($message) && $this->shouldSendMessage($message)) {

            foreach ($this->notifiers as $notifier) {

                (new $notifier)->notify($message);
                $this->markMessageAsSent($message);
            }
        }

        return;
    }

    /**
     * Check if a Message container has all of the
     * required fields populated.
     *
     * @param \Seat\Notifications\Containers\Message $message
     *
     * @return bool
     */
    protected function isMessageOk(Message $message)
    {

        return (!is_null($message->recipient) &&
            !is_null($message->subject) && !is_null($message->message));
    }

    /**
     * Check if a message should be sent based on
     * a poormans cache implementation lookup.
     *
     * @param \Seat\Notifications\Containers\Message $message
     *
     * @return bool
     */
    protected function shouldSendMessage(Message $message)
    {

        $sent = Notification::where('user_id', $message->recipient->id)
            ->where('hash', $this->hashMessage($message))
            ->first();

        if (!$sent)
            return true;

        return false;

    }

    /**
     * Create / mark a message as sent.
     *
     * @param \Seat\Notifications\Containers\Message $message
     */
    protected function markMessageAsSent(Message $message)
    {

        Notification::create([
            'user_id' => $message->recipient->id,
            'hash'    => $this->hashMessage($message),
            'subject' => $message->subject,
            'message' => $message->message
        ]);

        return;
    }

    /**
     * Create a unique hash based on parts of a
     * message container
     *
     * @param \Seat\Notifications\Containers\Message $message
     *
     * @return string
     */
    protected function hashMessage(Message $message)
    {

        return md5(implode(",", [
            $message->recipient->id, $message->subject, $message->message]));

    }
}
