<?php

namespace App\Models;

use App\Models\Link;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_name'
    ];

    public function links()
    {
        return $this->hasMany(Link::class, 'domain_id');
    }

    public function linkCount()
    {
        return $this->hasMany(Link::class, 'domain_id')->count();
    }
}
