<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class DocumentFile extends Model
{
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'file_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'path',
        'mime',
        'original_name',
        'size',
    ];
}
