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
include '../vendor/autoload.php';

use FriendsWall\Informers\UserInformer;
use FriendsWall\Users\InvalidUserAttributeException;
use FriendsWall\Users\User;
use Sse\SSE;

$userInformer = new UserInformer();
$sse = new SSE();

if (isset($_POST['sid'])) {
    session_id($_POST['sid']);
}
session_start();
if (!isset($_SESSION['id'])) {
    $userInformer->setError('No user logged in. Please login to continue');
} else {
    try {
        $user = new User();
        $user->setId($_SESSION['id'], true);
        $userInformer->setUser($user);
        $userInformer->setSSE($sse);
        session_write_close();
    } catch (InvalidUserAttributeException $exc) {
        $userInformer->setError($exc->getMessage());
    } catch (Exception $exc) {
        $userInformer->setError(FriendsWall\Configs\Strings::UNKNOWN_ERROR);
    }
}

$sse->addEventListener('userInfo', $userInformer);
$sse->start();