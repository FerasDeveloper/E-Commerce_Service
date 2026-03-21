<?php

namespace App\Http\Controllers;

use App\Domains\E_Commerce\DTOs\Offers\CreateOfferDTO;
use App\Domains\E_Commerce\DTOs\Offers\DeactiveOfferDTO;
use App\Domains\E_Commerce\DTOs\Offers\OfferItemsDTO;
use App\Domains\E_Commerce\DTOs\Offers\UpdateOfferDTO;
use App\Domains\E_Commerce\Requests\CreateOfferRequest;
use App\Domains\E_Commerce\Requests\DeactivateOfferRequest;
use App\Domains\E_Commerce\Requests\InsertOfferItemsRequest;
use App\Domains\E_Commerce\Requests\RemoveOfferItemsRequest;
use App\Domains\E_Commerce\Requests\UpdateOfferRequest;
use App\Domains\E_Commerce\Services\OfferService;

class OfferController extends Controller
{
  public function __construct(protected OfferService $service) {}

  public function store(CreateOfferRequest $request)
  {
    $dto = CreateOfferDTO::fromRequest($request);
    $this->service->create($dto);
    return response()->json(['message' => 'Offer created successfully'], 201);
  }

  public function update(string $collectionSlug, UpdateOfferRequest $request)
  {
    $dto = UpdateOfferDTO::fromRequest($collectionSlug, $request);
    $data = $this->service->update($dto);

    return response()->json([
      'message' => 'Offer updated successfully',
      'data' => $data
    ]);
  }

  public function show(string $collectionSlug)
  {
    $data = $this->service->show($collectionSlug);

    if (!$data) {
      return response()->json([
        'message' => 'Offer not found',
      ], 404);
    }

    return response()->json([
      'data' => $data,
    ]);
    return response()->json(['data' => $offer]);
  }

  public function index()
  {
    $data = $this->service->index(app('currentProject')->id);
    return response()->json(['data' => $data]);
  }

  public function destroy(string $collectionSlug)
  {
    $this->service->delete($collectionSlug);

    return response()->json(['message' => 'Offer deleted successfully']);
  }

  public function addItems($collectionSlug, InsertOfferItemsRequest $request)
  {
    $dto = OfferItemsDTO::fromInsertRequest($collectionSlug, $request);
    $this->service->addItems($dto);

    return response()->json([
      'message' => 'Items added successfully',
    ]);
  }

  public function removeItems($collectionSlug, RemoveOfferItemsRequest $request)
  {
    $dto = OfferItemsDTO::fromRemoveRequest($collectionSlug, $request);
    $this->service->removeItems($dto);

    return response()->json([
      'message' => 'Items removed successfully'
    ]);
  }

  public function deactivate(string $collectionSlug, DeactivateOfferRequest $request)
  {
    $dto = DeactiveOfferDTO::fromRequest($collectionSlug, $request);
    $this->service->deactivate($dto);

    return response()->json([
      'message' => 'Items removed successfully'
    ]);
  }
}
