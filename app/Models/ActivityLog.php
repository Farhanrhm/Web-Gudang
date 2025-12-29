<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    /**
     * Simpan log aktivitas
     */
    public static function record(string $action, string $description): self
    {
        return self::create([
            'user_id'     => auth()->id() ?? 1,
            'action'      => $action,
            'description' => $description,
        ]);
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
