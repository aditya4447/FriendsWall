<?php

/*
 * Copyright (C) 2019 Aditya Nathwani <adityanathwani@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace FriendsWall\Informers;

use FriendsWall\Users\User;
use Sse\Event;
use Sse\SSE;

/**
 * This class is used for SSE to pass user data when required.
 * 
 * To use this class, create new object and pass it in the second argument
 * of {@see \Sse\SSE} method addEventListener. after that, set the user
 * using setUser() method and use setError() if any error occures.
 * @author Aditya Nathwani <adityanathwani@gmail.com>
 */
class UserInformer implements Event
{

    private $user;
    private $error;
    private $sent = false;
    private $sse;

    public function check(): bool
    {
        if ($this->error === null && $this->user === null) {
            return false;
        }
        if ($this->sent) {
            $this->sse->removeEventListener('userInfo');
            return false;
        }
        return true;
    }

    public function update(): string
    {
        if ($this->error !== null) {
            $this->sent = true;
            return $this->error;
        } elseif ($this->user !== null) {
            $this->sent = true;
            return json_encode(array(
                'first_name' => $this->user->getFirstName(),
                'last_name' => $this->user->getLastName(),
                'email' => $this->user->getEmail(),
            ));
        }
    }
    
    /**
     * Sets user's object of which data is to be sent.
     * 
     * @param User $user Initialized user object
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Use this method if any error occurs.
     * 
     * @param string $error error message
     * @return void
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }
    
    /**
     * sets SSE object for future use.
     * 
     * @param SSE $sse SSE object in which the current class is bind
     * @return void
     */
    public function setSSE(SSE $sse) : void
    {
        $this->sse = $sse;
    }
}
