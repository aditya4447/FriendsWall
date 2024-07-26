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
header("Content-Type: application/json");
require '../../vendor/autoload.php';

use FriendsWall\Feedback;

$output = ['success' => true, 'error' => ''];
$success = &$output['success'];
$error = &$output['error'];


if (
    empty($_POST['email']) ||
    empty($_POST['name']) ||
    empty($_POST['description'])
) {
    $success = false;
    $error = 'Data incomplete.';
    goto output;
}

try {
    Feedback::insert($_POST['name'], $_POST['email'], $_POST['description']);
} catch (Exception $exc) {
    $success = false;
    $error = FriendsWall\Configs\Strings::UNKNOWN_ERROR;
}

output:
echo json_encode($output);