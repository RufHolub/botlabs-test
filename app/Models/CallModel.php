<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallModel extends Model
{
	protected $table = 'calls';
	
	protected $fillable = [
        'lead_id',
		'duration',
		'result'
    ];
	
	public const RESULT_NO_ANSWER = 'no_answer';
	public const RESULT_CALLBACK_LATER = 'callback_later';
	public const RESULT_SUCCESS = 'success';
	
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
	
	public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
