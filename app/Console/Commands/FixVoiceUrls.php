<?php
// app/Console/Commands/FixVoiceUrls.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class FixVoiceUrls extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fix:voice-urls';

    /**
     * The console command description.
     */
    protected $description = 'Fix voice URLs in behavioral reports to use full URLs instead of filenames';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix voice URLs...');
        
        // ดึงรายงานทั้งหมดที่มี voice และยังไม่ใช่ URL เต็ม
        $reports = BehavioralReportReportConsultation::whereNotNull('voice')
            ->where('voice', '!=', '')
            ->where('voice', 'not like', 'http%')
            ->get();
            
        $this->info("Found {$reports->count()} reports with voice files to fix.");
        
        $fixed = 0;
        $errors = 0;
        
        foreach ($reports as $report) {
            try {
                $oldVoice = $report->voice;
                
                // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
                $voiceFilePath = public_path("uploads/behavioral_report/voice/{$report->id}/{$oldVoice}");
                
                if (file_exists($voiceFilePath)) {
                    // สร้าง URL เต็ม
                    $newVoiceUrl = asset("uploads/behavioral_report/voice/{$report->id}/{$oldVoice}");
                    
                    // อัปเดต database
                    $report->voice = $newVoiceUrl;
                    $report->save();
                    
                    $this->line("Fixed Report #{$report->id}: {$oldVoice} → {$newVoiceUrl}");
                    $fixed++;
                } else {
                    $this->warn("Report #{$report->id}: Voice file not found at {$voiceFilePath}");
                    $errors++;
                }
                
            } catch (\Exception $e) {
                $this->error("Error fixing Report #{$report->id}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->info("\n=== Summary ===");
        $this->info("Fixed: {$fixed} records");
        if ($errors > 0) {
            $this->warn("Errors: {$errors} records");
        }
        $this->info("Voice URLs have been updated successfully!");
        
        return Command::SUCCESS;
    }
}