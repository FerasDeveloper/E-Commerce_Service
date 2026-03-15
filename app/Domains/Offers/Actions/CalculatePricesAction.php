<?php

namespace App\Domains\Offers\Actions;

use App\Domains\Offers\Benefits\BenefitStrategyFactory;
use App\Domains\Offers\Repositories\Interfaces\OfferPriceRepositoryInterface;
use App\Services\CMS\CMSApiClient;

class CalculatePricesAction
{
  public function __construct(
    protected CMSApiClient $cms,
    protected BenefitStrategyFactory $benefitFactory,
    protected OfferPriceRepositoryInterface $repository
  ) {}

  public function execute(array $data)
  {
    $strategy = $this->benefitFactory->make($data['offer']['benefit_type']);
    $entries = $this->cms->getDynamicEntries($data['collection']['slug']);

    $calculated = [];
    foreach ($entries as $entry) {
      $newPrice = $strategy->calculate(
        $entry['price'],
        1,
        $data['offer']['benefit_config']
      );

      $calculated[] = [
        'entry_id' => $entry['id'],
        'applied_offer_id' => $data['offer']['id'],
        'original_price' => $entry['price'],
        'final_price' => $newPrice,
      ];
    }

    foreach ($calculated as $entry) {

      if ($data['offer']['is_code_offer']) {
        $entry['is_applied'] = true;
        $entry['is_code_price'] = true;
        $this->repository->enterOfferItem($entry);
        continue;
      }

      $lowestEntry = $this->repository->getLowestPriceItem($entry['entry_id']);

      if (!$lowestEntry) {
        $entry['is_applied'] = true;
        $this->repository->enterOfferItem($entry);
        continue;
      }

      if ($entry['final_price'] < $lowestEntry->final_price) {
        $entry['is_applied'] = true;
        $this->repository->disableItemPrice($entry['entry_id']);
      } else {
        $entry['is_applied'] = false;
      }

      $this->repository->enterOfferItem($entry);
    }

    return $calculated;
  }
}
