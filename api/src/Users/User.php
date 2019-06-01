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
namespace FriendsWall\Users;

use FriendsWall\Configs\DB;
use PDO;

/**
 * This class contains various methods for handling user accounts.
 * 
 * All the activities related to user account can be done with this class like
 * creating user account, updating and deleting.
 * @author Aditya Nathwani <adityanathwani@gmail.com>
 */
class User
{

    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $gender;
    private $dob;
    private $password;
    private $media = "";
    private $dp = "";
    private $db;

    /**
     * The constructor accepts attributes associated with user accounts.
     * 
     * To create new user, provide all of the user information.
     * To get existing user, leave all the arguments blank. after that
     * you can initialize the object using {@see User::setId()} or
     * {@see User::setEmail} and set argument $initObject to true.
     * 
     * @param string $email email of the user.
     * @param string $firstName first name of the user.
     * @param string $lastName last name of the user.
     * @param string $gender gender of the user (male/female).
     * @param string $dob date of birth of the user (YYYY-MM-DD).
     * @param string $password password of the user.
     */
    public function __construct(
        string $email = null,
        string $firstName = null,
        string $lastName = null,
        string $gender = null,
        string $dob = null,
        string $password = null
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
        if ($email === null && $firstName === null && $lastName === null && $gender === null && $dob === null && $password === null) {
            return;
        }
        $this->setEmail($email);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setGender($gender);
        $this->setDOB($dob);
        $this->setPassword($password);
    }

    /**
     * Sets id of the user.
     * 
     * You can also initialize the object using this method by setting
     * $initObject to true. This will change other attributes
     * (email, password) etc. corresponding to given $id.
     * 
     * @param int $id ID of the user.
     * @param bool $initObject set this to true to initialize object
     * according to given $id.
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setId(int $id, bool $initObject = false): User
    {
        if ($initObject) {
            $stmt = $this->db->prepare('SELECT * FROM users where id = ?');
            $flagUserExists = false;
            if ($stmt->execute(array($id))) {
                while ($row = $stmt->fetch()) {
                    $this->id = $row['id'];
                    $this->email = $row['email'];
                    $this->firstName = $row['first_name'];
                    $this->lastName = $row['last_name'];
                    $this->gender = $row['gender'];
                    $this->dob = $row['dob'];
                    $this->password = $row['password'];
                    $this->media = $row['media'];
                    $this->dp = $row['dp'];
                    $flagUserExists = true;
                }
                if (!$flagUserExists) {
                    throw new InvalidUserAttributeException("User with given id doesnot exist");
                }
            }
        } else {
            $this->id = $id;
        }
        return $this;
    }

    /**
     * Returns the id of the user or null if not set in the object.
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets Email of the user.
     * 
     * You can also initialize the object using this method by setting
     * $initObject to true. This will change other attributes
     * (id, password) etc. corresponding to given $email.
     * 
     * @param int $email email of the user.
     * @param bool $initObject set this to true to initialize object
     * according to given $email.
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setEmail(string $email, bool $initObject = false): User
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidUserAttributeException('Invalid email');
        }
        if ($initObject) {
            $stmt = $this->db->prepare('SELECT * FROM users where email = ?');
            $flagUserExists = false;
            if ($stmt->execute(array($email))) {
                while ($row = $stmt->fetch()) {
                    $this->id = $row['id'];
                    $this->email = $row['email'];
                    $this->firstName = $row['first_name'];
                    $this->lastName = $row['last_name'];
                    $this->gender = $row['gender'];
                    $this->dob = $row['dob'];
                    $this->password = $row['password'];
                    $this->media = $row['media'];
                    $this->dp = $row['dp'];
                    $flagUserExists = true;
                }
                if (!$flagUserExists) {
                    throw new InvalidUserAttributeException('User with given email doesnot exist');
                }
            }
        } else {
            $this->email = $email;
        }
        return $this;
    }

    /**
     * Returns the email address of the user or null if not set in the object.
     * 
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets first name of the user
     * 
     * @param string $firstName first name of the user
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setFirstName(string $firstName): User
    {
        if (!preg_match("/^[a-zA-Z0-9'-]{5,255}$/", $firstName)) {
            throw new InvalidUserAttributeException('First Name invalid');
        }
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Returns first name of the user or null if not set in the object.
     * 
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Sets last name of the user
     * 
     * @param string $lastName last name of the user
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setLastName(string $lastName): User
    {
        if (!preg_match("/^[a-zA-Z0-9'-]{5,255}$/", $lastName)) {
            throw new InvalidUserAttributeException('Last Name invalid');
        }
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Returns last name of the user or null if not set in the object.
     * 
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Sets gender name of the user (male/female)
     * 
     * @param string $gender gender name of the user (male/female)
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setGender(string $gender): User
    {
        if ($gender !== 'male' && $gender !== 'female') {
            throw new InvalidUserAttributeException('Gender invalid');
        }
        $this->gender = $gender;
        return $this;
    }

    /**
     * Returns gender of the user or null if not set in the object.
     * 
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * Sets date of birth of the user
     * 
     * @param string $dob date of birth of the user (YYYY-MM-DD)
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setDOB(string $dob): User
    {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
            throw new InvalidUserAttributeException("Invalid Birth date");
        }
        $date = explode("-", $dob);
        if (mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0])) >= mktime(0, 0, 0, date('m'), date('d'), date('Y') - 13)) {
            throw new InvalidUserAttributeException('User must be atleast 13 years old to create an account');
        }
        $this->dob = $dob;
        return $this;
    }

    /**
     * Returns date of birth of the user or null if not set in the object.
     * 
     * @return string|null
     */
    public function getDOB(): ?string
    {
        return $this->dob;
    }

    /**
     * Sets password of the user.
     * 
     * If password is not already hashed, set $isHashed to false(default). When
     * $isHashed if false, the method will validate the password, throw
     * {@see InvalidUserAttributeException} if password is invalid and store
     * hashed password.
     * @param string $password password of the user either hashed or unhashed
     * @param bool $isHashed set true if password is already hashed. false
     * otherwise
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setPassword(string $password, bool $isHashed = false): User
    {
        if ($isHashed) {
            $this->password = $password;
        } else {
            if (strlen($password) < 8) {
                throw new InvalidUserAttributeException('Password must contain atleast 8 caracters');
            }
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
        return $this;
    }

    /**
     * Verifies a password and returns true if given password if correct. false
     * otherwise
     * 
     * @param string $password password to be verified.
     * @return bool
     */
    
    /**
     * Returns media directory of the user or empty string if not set in the object.
     * 
     * @return string
     */
    public function getMedia(): string
    {
        return $this->media;
    }
    
    public function isPassword($password): bool
    {
        return password_verify($password, $this->password);
    }
    /**
     * Sets DP name of the user
     * 
     * @param string $name name of DP without extension (8 characters)
     * @return \FriendsWall\Users\User
     * @throws InvalidUserAttributeException
     */
    public function setDP(string $name): User
    {
        if (!preg_match("/^[a-zA-Z0-9]{8}$/", $name)) {
            throw new InvalidUserAttributeException('DP Name invalid');
        }
        $this->dp = $name;
        return $this;
    }

    /**
     * Returns DP name of the user or empty string if not set in the object.
     * 
     * @return string
     */
    public function getDP(): string
    {
        return $this->dp;
    }

    /**
     * Creates a new user.
     * 
     * all user attributes (all the parameters of constructor)
     * must be set either using constructor or by using corresponding
     * methods.
     * 
     * @throws \FriendsWall\Users\InvalidUserAttributeException
     * @return \FriendsWall\Users\User
     */
    public function create(): User
    {
        $stmt = $this->db->prepare('INSERT INTO users (email, first_name, last_name, gender, dob, password, regtime, media) VALUES (:email, :first_name, :last_name, :gender, :dob, :password, :regtime, :media)');
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':first_name', $this->firstName);
        $stmt->bindParam(':last_name', $this->lastName);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':password', $this->password);
        $time = date('Y-m-d H:i:s');
        $stmt->bindParam(':regtime', $time);
        $media = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"),0,8);
        $stmt->bindParam(':media', $media);
        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            if ($ex->getCode() == 23000) {
                throw new InvalidUserAttributeException("email address already exists. Please login to your account.");
            } else {
                throw $ex;
            }
        }
        $this->id = $this->db->lastInsertId();
        return $this;
    }

    /**
     * Updates the user details.
     * 
     * all user attributes must be set to updated ones using
     * corresponding methods.
     * 
     * @return \FriendsWall\Users\User
     */
    public function update(): User
    {
        $stmt = $this->db->prepare("UPDATE users set email=:email, first_name=:first_name, last_name=:last_name, gender=:gender, dob=:dob, password=:password, dp=:dp WHERE id='" . $this->id . "'");
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':first_name', $this->firstName);
        $stmt->bindParam(':last_name', $this->lastName);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':dp', $this->dp);
        $stmt->execute();
        $stmt2 = $this->db->prepare('SELECT * FROM users where id = ?');
        if ($stmt2->execute(array($this->id))) {
            while ($row = $stmt2->fetch()) {
                $this->id = $row['id'];
                $this->email = $row['email'];
                $this->firstName = $row['first_name'];
                $this->lastName = $row['last_name'];
                $this->gender = $row['gender'];
                $this->dob = $row['dob'];
                $this->password = $row['password'];
                $this->media = $row['media'];
                $this->dp = $row['dp'];
            }
        }
        return $this;
    }
}
