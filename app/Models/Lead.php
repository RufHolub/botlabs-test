<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
	public $timestamps = false;
	public const STATUS_NEW = 'new';
	public const STATUS_WON = 'won';
	public const STATUS_LOST = 'lost';
	public const STATUS_IN_PROGRESS = 'in_progress';
	
	protected $fillable = [
        'name',
        'phone',
        'status',
        'manager_id',
    ];
	
	public function calls(): HasMany
    {
        return $this->hasMany(CallModel::class);
    }
	
	public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
