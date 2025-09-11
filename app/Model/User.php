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
        static::creating(function ($user) {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        });

        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = password_hash($user->password, PASSWORD_DEFAULT);
            }
        });
    }

    //Выборка пользователя по первичному ключу
    public function findIdentity(int $ID)
    {
        return self::where('ID', $ID)->first();
    }

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->ID;
    }

    //Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials)
    {
        echo "AttemptIdentity called with login: " . $credentials['login'] . "<br>";

        $employee = self::where('login', $credentials['login'])->first();

        if (!$employee) {
            echo "User not found<br>";
            return null;
        }

        echo "User found: " . $employee->login . "<br>";
        echo "Input password: " . $credentials['password'] . "<br>";
        echo "Stored hash: " . $employee->password . "<br>";

        if (password_verify($credentials['password'], $employee->password)) {
            echo "Password verified successfully<br>";
            return $employee;
        } else {
            echo "Password verification failed<br>";
            // Для отладки: проверяем хэш напрямую
            $testHash = password_hash($credentials['password'], PASSWORD_DEFAULT);
            echo "New hash of input: " . $testHash . "<br>";
            echo "Verify with new hash: " . (password_verify($credentials['password'], $testHash) ? 'true' : 'false') . "<br>";
        }

        return null;
    }
}