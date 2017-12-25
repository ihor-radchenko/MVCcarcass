<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:40
 */

namespace IhorRadchenko\App\Models;

use IhorRadchenko\App\Components\Session;
use IhorRadchenko\App\Components\Traits\GetDate;
use IhorRadchenko\App\DataBase;
use IhorRadchenko\App\Exceptions\DbException;
use IhorRadchenko\App\Model;

/**
 * Class User
 * @package App\Models
 * Реализует модель таблицы users
 * @property string $group
 */
class User extends Model
{
    use GetDate;

    const TABLE = 'users';

    public $email;
    private $password;
    public $f_name;
    public $l_name;
    public $city;
    public $phone_number;
    private $group_id;
    private $verified;

    protected $fields = [
        'email' => '',
        'password' => '',
        'f_name' => '',
        'l_name' => '',
        'phone_number' => '',
        'city' => '',
        'group_id' => '',
        'token' => '',
        'verified' => ''
    ];

    public function __isset($name)
    {
        switch ($name) {
            case 'group':
                return isset($this->group_id);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $name
     * @return bool|string
     * @throws DbException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'group':
                return DataBase::getInstance()->get('user_group', 'id', $this->group_id)->name;
                break;
            default:
                return false;
        }
    }

    /**
     * @param string $email
     * @return null|User
     * @throws DbException
     */
    public static function findByEmail(string $email)
    {
        $sql = 'SELECT * FROM ' . User::TABLE . ' WHERE email = :email LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['email' => $email]);
        return (! empty($result)) ? $result[0] : null;
    }

    public function passwordVerify(string $password) {
        if (password_verify($password, $this->password)) {
            return true;
        }
        return false;
    }

    public function passwordHash()
    {
        $this->fields['password'] = password_hash($this->fields['password'], PASSWORD_DEFAULT);
    }

    public function getFullName(): string
    {
        return trim($this->f_name . ' ' . $this->l_name);
    }

    /**
     * @param string $hash
     * @return bool
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public function recordUserSessionInDB(string $hash)
    {
        if (DataBase::getInstance()->insert(
            'user_sessions',
            ['user_id' => $this->id, 'hash_user' => $hash]
        )) {
            return true;
        }
        return false;
    }

    /**
     * @param $value
     * @param string $field
     * @return mixed
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public static function getUserSessionFromDB($value, string $field = 'user_id')
    {
        return DataBase::getInstance()->get('user_sessions', $field, $value);
    }

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    public function deleteUserSessionFromDB()
    {
        DataBase::getInstance()->delete('user_sessions', 'user_id', $this->id);
    }

    public static function isAdmin(): bool
    {
        return (Session::has('user') && Session::get('user')->group === 'admin');
    }

    public static function isVerified(): bool
    {
        return (Session::has('user') && Session::get('user')->verified === '1');
    }

    /**
     * @return mixed
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * @return array
     * @throws DbException
     */
    public function getUserGroups()
    {
        return DataBase::getInstance()->getAll('user_group');
    }

    /**
     * @return bool
     * @throws DbException
     */
    public function delete(): bool
    {
        $reviews = Review::findByAuthorId($this->id);
        if ($reviews) {
            foreach ($reviews as $review) {
                $review->delete();
            }
        }
        $comments = Comment::findByAuthorId($this->id);
        if ($comments) {
            foreach ($comments as $comment) {
                $comment->delete();
            }
        }
        return parent::delete(); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     * @throws DbException
     */
    protected function insert(): bool
    {
        $this->fields['group_id'] = 1;
        $this->fields['verified'] = 0;
        return parent::insert(); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $token
     * @return self|null
     * @throws DbException
     */
    public static function findUserByToken(string $token)
    {
        $sql = 'SELECT * FROM users WHERE token = :token LIMIT 1';
        $result = DataBase::getInstance()->query($sql, self::class, ['token' => $token]);
        return ! empty($result) ? $result[0] : null;
    }
}