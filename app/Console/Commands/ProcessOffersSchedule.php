<?php

namespace App\Console\Commands;

use App\Domains\E_Commerce\Services\OfferService;
use Illuminate\Console\Command;

class ProcessOffersSchedule extends Command
{
  protected $signature = 'offers:process-schedule';
  protected $description = 'Activate or deactivate offers based on schedule';

  public function handle(OfferService $service)
  {
    $result = $service->run();

    $this->info("Activated: {$result['activated']}, Deactivated: {$result['deactivated']}");

    return Command::SUCCESS;
  }
}
