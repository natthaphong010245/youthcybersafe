<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'role_user',
        'school',
        'name',
        'lastname',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role_user' => 'boolean',
        ];
    }

    /**
     * Automatically hash password when setting
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Check if user is approved (role_user = 1)
     */
    public function isApproved(): bool
    {
        return $this->role_user == 1;
    }

    /**
     * Check if user can access dashboard (approved researcher)
     */
    public function canAccessDashboard(): bool
    {
        return $this->isApproved() && $this->role === 'researcher';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is researcher
     */
    public function isResearcher(): bool
    {
        return $this->role === 'researcher';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->lastname;
    }

    /**
     * Get role in Thai
     */
    public function getRoleThaiAttribute(): string
    {
        return match($this->role) {
            'teacher' => 'คุณครู',
            'researcher' => 'นักวิจัย',
            'admin' => 'ผู้ดูแลระบบ',
            default => 'ไม่ระบุ'
        };
    }

    /**
     * Get status in Thai
     */
    public function getStatusThaiAttribute(): string
    {
        if ($this->isApproved()) {
            return 'อนุมัติแล้ว';
        } else {
            return 'รออนุมัติ';
        }
    }

    /**
     * Scope for approved users
     */
    public function scopeApproved($query)
    {
        return $query->where('role_user', 1);
    }

    /**
     * Scope for pending users
     */
    public function scopePending($query)
    {
        return $query->where('role_user', 0);
    }

    /**
     * Scope for teachers
     */
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    /**
     * Scope for researchers
     */
    public function scopeResearchers($query)
    {
        return $query->where('role', 'researcher');
    }
}