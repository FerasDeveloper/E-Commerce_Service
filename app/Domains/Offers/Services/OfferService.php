<?php

namespace App\Domains\Offers\Services;

use App\Domains\Offers\DTOs\CreateOfferDTO;
use App\Domains\Offers\Actions\CalculatePricesAction;
use App\Domains\Offers\Actions\CreateOfferAction;
use App\Domains\Offers\Actions\DeleteOfferPricesAction;
use App\Domains\Offers\Actions\EnterOfferItemsAction;
use App\Domains\Offers\Actions\ReEvaluateEntryPricesAction;
use App\Domains\Offers\Actions\UpdateCollectionAction;
use App\Domains\Offers\Actions\UpdateOfferAction;
use App\Domains\Offers\DTOs\UpdateOfferDTO;
use App\Domains\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Services\CMS\CMSApiClient;

use function PHPUnit\Framework\isEmpty;

class OfferService
{
  public function __construct(
    protected CMSApiClient $cms,
    protected CreateOfferAction $createAction,
    protected CalculatePricesAction $calculateAction,
    protected EnterOfferItemsAction $enterItemsAction,
    protected UpdateCollectionAction $updateCollection,
    protected UpdateOfferAction $updateOffer,
    protected OfferRepositoryInterface $repository,
    protected DeleteOfferPricesAction $deleteOfferPricesAction,
    protected ReEvaluateEntryPricesAction $reEvaluateAction
  ) {}

  public function create(CreateOfferDTO $dto): void
  {
    $data = $this->createAction->execute($dto);

    if ($dto->type === 'dynamic' && in_array($dto->benefit_type, ['percentage', 'fixed_amount'])) {
      $this->calculateAction->execute($data);
    }
  }

  public function update(UpdateOfferDTO $dto)
  {
    $updated = [];
    if (!empty($dto->collectionData)) {
      $updated['collection'] = $this->updateCollection->execute($dto)['data'];
    }

    $collection = $updated['collection'] ?? $this->cms->getCollectionBySlug($dto->collectionSlug);
    if (!empty($dto->offerData)) {
      $updated['offer'] = $this->updateOffer->execute($collection['id'], $dto);
    }
    $offer = $updated['offer'] ?? $this->repository->findByCollectionId($collection['id']);

    $shouldRecalculate =
      (isset($dto->offerData['benefit_type']) || isset($dto->offerData['benefit_config']) || isset($dto->collectionData['conditions']))
      &&
      ($collection['type'] === 'dynamic' && in_array($offer['benefit_type'], ['percentage', 'fixed_amount']));

    if ($shouldRecalculate) {
      $data = [
        'collection' => $collection,
        'offer' => $offer
      ];
      $this->deleteOfferPricesAction->execute($offer['id']);
      $entries = $this->calculateAction->execute($data);
      $this->reEvaluateAction->execute($entries);
    } elseif (in_array($offer['benefit_type'], ['quantity', 'total_price'])) {
      $this->deleteOfferPricesAction->execute($offer['id']);
    }

    return $updated;
  }
}
