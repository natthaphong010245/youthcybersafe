<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class UpdateOldVoiceUrls extends Command
{
    protected $signature = 'behavioral-report:update-voice-urls';
    protected $description = 'Update old voice recordings to use full URLs instead of filenames';

    public function handle()
    {
        $this->info('Starting to update voice URLs...');
        
        // ค้นหารายการที่ voice ไม่ใช่ URL (ไม่มี http)
        $reports = BehavioralReportReportConsultation::whereNotNull('voice')
            ->where('voice', 'not like', 'http%')
            ->get();
            
        $this->info("Found {$reports->count()} reports with filename-only voice records");
        
        $updated = 0;
        $bar = $this->output->createProgressBar($reports->count());
        
        foreach ($reports as $report) {
            $oldVoice = $report->voice;
            
            // สร้าง URL เต็มจาก filename
            $newUrl = asset("uploads/behavioral_report/voice/{$report->id}/{$oldVoice}");
            
            // ตรวจสอบว่าไฟล์มีจริงหรือไม่
            $filePath = public_path("uploads/behavioral_report/voice/{$report->id}/{$oldVoice}");
            
            if (file_exists($filePath)) {
                $report->voice = $newUrl;
                $report->save();
                $updated++;
                $this->line("\nUpdated Report #{$report->id}: {$oldVoice} -> {$newUrl}");
            } else {
                $this->warn("\nSkipped Report #{$report->id}: File not found at {$filePath}");
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully updated {$updated} voice URL records!");
        
        return 0;
    }
}