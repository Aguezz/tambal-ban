<?php

require_once __DIR__ . "/../Core/Service.php";
require_once __DIR__ . "/../Models/User.php";

class UserService extends Service
{
    public static function findOneByEmail($email)
    {
        $stmt = static::getConnection()->prepare("SELECT * FROM `users` WHERE `email` = ? LIMIT 1");

        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result) {
            return User::serialize($result);
        }

        return null;
    }

    public static function insert($user)
    {
        $stmt = static::getConnection()->prepare("INSERT INTO `users` (`name`, `email`, `password`, `created`) VALUES (?, ?, ?, ?)");

        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $created = $user->getCreated();

        $stmt->bind_param("sssi", $name, $email, $password, $created);

        $process = $stmt->execute();
        $stmt->close();

        // Jika proses sukses.
        if ($process) {
            return true;
        }

        // Jika proses gagal.
        return false;
    }

    public static function findOneById($id)
    {
        $stmt = static::getConnection()->prepare("SELECT * FROM users WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result) {
            return User::serialize($result);
        }
        return null;
    }

    public static function delete($id)
    {
        $user = self::findOneById($id);

        if (!$user) return false;

        $stmt = self::getConnection()->prepare("DELETE users WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $process = $stmt->execute();
        $stmt->close();

        if ($process) {
            return true;
        }

        return false;
    }

    public static function changePassword($user)
    {
        $stmt = static::getConnection()->prepare("UPDATE `users` SET `password` = ? WHERE id = ?");

        $password = $user->getPassword();
        $id = $user->getId();

        $stmt->bind_param("si", $password, $id);

        $process = $stmt->execute();
        $stmt->close();

        // Jika proses sukses.
        if ($process) {
            return true;
        }
        // Jika proses gagal.
        return false;
    }

    public static function edit($user)
    {
        $stmt = self::getConnection()->prepare("UPDATE `users` SET `name` = ?, `email` = ? WHERE `id` = ?");

        $name = $user->getName();
        $email = $user->getEmail();
        $id = $user->getId();

        $stmt->bind_param("ssi", $name, $email, $id);

        $process = $stmt->execute();
        $stmt->close();

        // Jika proses sukses.
        if ($process) {
            return true;
        }
        // Jika proses gagal.
        return false;
    }
}
