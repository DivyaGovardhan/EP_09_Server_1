<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class Book extends Model
{
    protected $table = 'books';

    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'publication_year',
        'price',
        'edition_number',
        'annotation'
    ];

    //Выборка книги по первичному ключу
    public function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->ID;
    }
}