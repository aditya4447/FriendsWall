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
namespace FriendsWall\Posts;

use FriendsWall\Configs\DB;
use PDO;

class Post {
    
    private $db;
    private $id;
    private $uid;
    private $text;
    private $media;
    private $time;
    private $isdeleted;
    
    public function __construct(
        string $uid = null,
        string $text = null,
        string $media = null
    )
    {
        $this->db = new PDO(
            DB::PDO_CONNECTION_STRING,
            DB::USERNAME, DB::PASSWORD,
            array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        );
        if ($uid === null && $text === null && $media === null) {
            return;
        }
        $this->setUid($uid);
        $this->setText($text);
        $this->setMedia($media);
    }
    
    public function setId($id): Post
    {
        $stmt = $this->db->prepare('SELECT * FROM posts where id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $flagUserExists = false;
        while ($row = $stmt->fetch()) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->text = $row['text'];
            $this->media = $row['media'];
            $this->time = $row['time'];
            $this->isdeleted = $row['isdeleted'];
            $flagUserExists = true;
        }
        if (!$flagUserExists) {
            throw new InvalidPostException("Post with given id doesnot exist");
        }
        return $this;
    }
    
    public function setUid($id): Post
    {
        $this->checkID($id);
        $this->uid = $id;
        return $this;
    }
    
    public function setText($text): Post
    {
        if ($text !== null && strlen($text) > 2000) {
            throw new InvalidPostException('Text should be less than 2000 characters');
        }
        $this->text = $text;
        return $this;
    }
    
    public function setMedia($media): Post
    {
        if ($media !== null) {
            if (strlen($media) !== 12 || (substr($media, -4) !== '.jpg' && substr($media, -4) !== '.mp4')) {
                throw new InvalidPostException('Invalid media name');
            }
        }
        $this->media = $media;
        return $this;
    }
    
    public function add(): void
    {
        $stmt = $this->db->prepare('INSERT INTO posts (uid, text, media) VALUES (:uid, :text, :media)');
        $stmt->bindParam(':uid', $this->uid);
        $stmt->bindParam(':text', $this->text);
        $stmt->bindParam(':media', $this->media);
        $stmt->execute();
        $this->id = $this->db->lastInsertId();
    }
    
    public function delete(): void
    {
        
        $stmt = $this->db->prepare('UPDATE posts SET isdeleted = 1 WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $this->isdeleted = 1;
    }


    private function checkID(int $id): void
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            throw new InvalidPostException("User with given id does not exist");
        }
    }
}