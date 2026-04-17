<?php

namespace App\Console\Commands;

use App\Models\Share;
use Illuminate\Console\Command;

class BackfillShareSalaryPercentage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shares:backfill-salary-percent {--only-approved : Only backfill shares that are already approved}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill share_salary_percentage on existing shares using config(shares.default_salary_percent)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $default = config('shares.default_salary_percent', 0);
        $this->info("Starting backfill with default percent: {$default}%");

        $query = Share::whereNull('share_salary_percentage');
        if ($this->option('only-approved')) {
            $query->where('status', 'approved');
        }

        $count = $query->count();
        if ($count === 0) {
            $this->info('No shares require backfill.');
            return 0;
        }

        $this->info("Backfilling {$count} shares...");

        $query->chunkById(200, function ($rows) use ($default) {
            foreach ($rows as $share) {
                $share->share_salary_percentage = $default;
                $share->save();
            }
        });

        $this->info('Backfill completed.');
        return 0;
    }
}

