<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use mar3y\ImageUpload\Traits\HasImage;

class Expense extends Model
{
    use HasFactory, HasImage, SoftDeletes;

    protected $fillable = [
        'title',
        'workshop_id',
        'invoice_number',
        'image',
        'vendor',
        'amount',
        'notes',
        'is_including_tax',
    ];

    protected $casts = [
        'amount'           => 'double',
        'is_including_tax' => 'boolean',
    ];

    protected static $imageAttributes = ['image'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

}
