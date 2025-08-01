<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role',
        'school',
        'name',
        'lastname',
        'username',
        'password',
        'role_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role_user' => 'boolean', 
        ];
    }

 
    protected $attributes = [
        'role_user' => false, 
    ];

  
    public function isApproved(): bool
    {
        return $this->role_user === true;
    }

  
    public function isResearcher(): bool
    {
        return $this->role === 'researcher';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function canAccessDashboard(): bool
    {
        return $this->isApproved() && $this->isResearcher();
    }

    public function approve(): bool
    {
        $this->role_user = true;
        return $this->save();
    }

    public function disapprove(): bool
    {
        $this->role_user = false;
        return $this->save();
    }

    public function getRoleThaiAttribute(): string
    {
        return match($this->role) {
            'researcher' => 'นักวิจัย',
            'teacher' => 'คุณครู',
            default => 'ผู้ใช้'
        };
    }

    public function getApprovalStatusThaiAttribute(): string
    {
        return $this->role_user ? 'อนุมัติแล้ว' : 'รอการอนุมัติ';
    }
}