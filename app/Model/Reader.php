<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class Reader extends Model
{
    protected $table = 'readers';

    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'last_name',
        'patronym',
        'card_number',
        'address',
        'phone_number'
    ];

    protected static function booted()
    {
        static::created(function ($reader) {
            $reader->password = password_hash($reader->password, PASSWORD_DEFAULT);
            $reader->save();
        });
    }

    //Выборка читателя по первичному ключу
    public function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->ID;
    }

//    //Возврат аутентифицированного пользователя
//    public function attemptIdentity(array $credentials)
//    {
//        return self::where(['login' => $credentials['login'],
//            'password' => md5($credentials['password'])])->first();
//    }
}