<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeArea extends Model
{
    use HasFactory;

    
    protected $table = 'safe_area';

    
    protected $fillable = [
        'voice_message',
    ];

    
    protected $casts = [
        'voice_message' => 'array',
    ];

   
    const TYPE_VOICE = [[1], [0]];
    const TYPE_MESSAGE = [[0], [1]];

    
    public function isVoiceType()
    {
        return $this->voice_message === self::TYPE_VOICE;
    }

   
    public function isMessageType()
    {
        return $this->voice_message === self::TYPE_MESSAGE;
    }

    
    public function getTypeAttribute()
    {
        if ($this->isVoiceType()) {
            return 'voice';
        } elseif ($this->isMessageType()) {
            return 'message';
        }
        return 'unknown';
    }

    
    public function getTypeThaiAttribute()
    {
        $types = [
            'voice' => 'เสียง',
            'message' => 'ข้อความ',
            'unknown' => 'ไม่ทราบ'
        ];

        return $types[$this->type] ?? 'ไม่ทราบ';
    }

    
    public static function createVoice()
    {
        return self::create([
            'voice_message' => self::TYPE_VOICE
        ]);
    }

    public static function createMessage()
    {
        return self::create([
            'voice_message' => self::TYPE_MESSAGE
        ]);
    }

  
    public function scopeVoiceType($query)
    {
        return $query->whereJsonContains('voice_message', self::TYPE_VOICE);
    }

  
    public function scopeMessageType($query)
    {
        return $query->whereJsonContains('voice_message', self::TYPE_MESSAGE);
    }

    public static function getVoiceCount()
    {
        return self::voiceType()->count();
    }


    public static function getMessageCount()
    {
        return self::messageType()->count();
    }

    public static function getTotalCount()
    {
        return self::count();
    }

    
   
     
    public static function getStatistics()
    {
        return [
            'total' => self::getTotalCount(),
            'voice' => self::getVoiceCount(),
            'message' => self::getMessageCount(),
            'voice_percentage' => self::getTotalCount() > 0 ? round((self::getVoiceCount() / self::getTotalCount()) * 100, 2) : 0,
            'message_percentage' => self::getTotalCount() > 0 ? round((self::getMessageCount() / self::getTotalCount()) * 100, 2) : 0,
        ];
    }
}