<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Prototype.
 *
 * @package namespace App\Models;
 */
class Prototype extends Model implements Transformable
{
    use TransformableTrait,
        HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'category',
        'capacity',
        'date_added'
    ];

    protected $fieldSearchable = [
        'name',
        'code',
        'type',
        'category',
        'capacity',
        'date_added'
    ];
}
