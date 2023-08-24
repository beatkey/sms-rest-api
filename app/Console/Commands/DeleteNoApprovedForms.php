<?php

namespace App\Console\Commands;

use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteNoApprovedForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-no-approved-forms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Son 48 içinde onaylanmayan teklifleri otomatik olarak status=auto-rejected olarak günceller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = Form::where("status", "pending")
            ->where('created_at', '<', Carbon::now()->subDays(2))
            ->get();

        $this->info("Onaylanmamış " . count($data) . " teklif bulundu");

        foreach ($data as $key => $value) {
            $value->update([
                "status" => "auto-rejected"
            ]);
            $this->info($key + 1 . "/" . count($data) . " teklif güncellendi");
        }
    }
}
