<?php

namespace App\Http\Controllers;

use App\Domains\Offers\DTOs\CreateOfferDTO;
use App\Domains\Offers\DTOs\UpdateOfferDTO;
use App\Domains\Offers\Requests\CreateOfferRequest;
use App\Domains\Offers\Requests\UpdateOfferRequest;
use App\Domains\Offers\Services\OfferService;

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

  // public function index()
  // {
  //   $projectId = (int)app('currentProject')->id;

  //   $offers = Offer::query()
  //     ->where('project_id', $projectId)
  //     ->orderByDesc('priority')
  //     ->orderByDesc('id')
  //     ->get();

  //   return response()->json(['data' => $offers]);
  // }

  // public function show(Offer $offer)
  // {
  //   $projectId = (int)app('currentProject')->id;
  //   if ((int)$offer->project_id !== $projectId) {
  //     abort(404, 'Offer not found');
  //   }

  //   return response()->json(['data' => $offer]);
  // }

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
