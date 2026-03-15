<?php

namespace App\Http\Controllers;

use App\Domains\E_Commerce\DTOs\Offers\CreateOfferDTO;
use App\Domains\E_Commerce\DTOs\Offers\UpdateOfferDTO;
use App\Domains\E_Commerce\Requests\CreateOfferRequest;
use App\Domains\E_Commerce\Requests\UpdateOfferRequest;
use App\Domains\E_Commerce\Services\OfferService;
use Illuminate\Http\Request;

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

  public function show($collectionSlug)
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

  // public function destroy(Offer $offer)
  // {
  //   $projectId = (int)app('currentProject')->id;
  //   $this->service->delete($offer, $projectId);

  //   return response()->json(['message' => 'Offer deleted successfully']);
  // }

  // public function targets(Offer $offer)
  // {
  //   $projectId = (int)app('currentProject')->id;
  //   $targets = $this->service->listTargets($offer, $projectId);

  //   return response()->json(['data' => $targets]);
  // }

  // public function appliedPrice(int $entryId)
  // {
  //   $projectId = (int)app('currentProject')->id;

  //   $price = OfferPrice::query()
  //     ->where('project_id', $projectId)
  //     ->where('entry_id', $entryId)
  //     ->first();

  //   return response()->json(['data' => $price]);
  // }
}
