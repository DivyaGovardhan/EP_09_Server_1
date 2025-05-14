<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'last_name',
        'patronym',
        'login',
        'password',
        'is_admin'
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
            $user->save();
        });
    }

    //Выборка пользователя по первичному ключу
    public function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->ID;
    }

    //Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials)
    {
        $user = self::where('login', $credentials['login'])->first();
        if ($user && password_verify($credentials['password'], $user->password)) {
            return $user;
        }
        return null;
    }
}