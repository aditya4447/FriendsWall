<?php
/*
 * Copyright (C) 2019 Aditya
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
namespace FriendsWall;

use FriendsWall\Configs\DB;
use PDO;

/**
 * This class is used to insert feedback into database
 */
class Feedback
{
    
    public static function insert(string $name, string $email, string $description): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email');
        }
        $dbh = new PDO(
            DB::PDO_CONNECTION_STRING,
            DB::USERNAME, DB::PASSWORD,
            array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        );
        $q = $dbh->prepare("INSERT INTO feedback (name,email,description) VALUES (:name, :email, :description)");
        $q->bindValue(':name', $name);
        $q->bindValue(':email', $email);
        $q->bindValue(':description', $description);
        $q->execute();
    }
    
    public static function get(int $page = 1): array
    {
        $result = [];
        $dbh = new PDO(
            DB::PDO_CONNECTION_STRING,
            DB::USERNAME, DB::PASSWORD,
            array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        );
        if ($page < 1) {
            $page = 1;
        }
        $page--;
        $limit = $page * 20;
        $q = $dbh->prepare("SELECT id, name, email, description FROM feedback LIMIT $limit,20;");
        $q->execute();
        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
    public static function delete(int $id): void
    {
        $dbh = new PDO(
            DB::PDO_CONNECTION_STRING,
            DB::USERNAME, DB::PASSWORD,
            array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        );
        $q = $dbh->prepare("DELETE FROM feedback WHERE id=:id;");
        $q->bindValue(':id', $id);
        $q->execute();
    }
}