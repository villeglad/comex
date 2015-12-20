<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Company;

class UpdateCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCompanies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the companies data from YTJ';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newCompanies = Company::fetchAllDataFromPrh(214000, 220000);
        $this->comment('Companies fetched from PRH open data');
    }
}
