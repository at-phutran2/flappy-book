<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    const TYPE_VND = 0;
    const TYPE_DOLAR = 1;
    const TYPE_EURO =  2;
    const TYPE_YEN = 3;
    
    /**
     * Declare table
     *
     * @var string $tabel table name
     */
    protected $table = 'books';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qrcode',
        'title',
        'category_id',
        'description',
        'year',
        'author',
        'price',
        'unit',
        'picture',
        'from_person',
        'total_rating',
        'rating',
        'is_printed'
    ];
    
    /**
     * Relationship belongsTo with Category
     *
     * @return array
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship belongsTo with User
     *
     * @return array
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship hasMany with Borrow
     *
     * @return array
     */
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Relationship morphMany with Comment
     *
     * @return array
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}