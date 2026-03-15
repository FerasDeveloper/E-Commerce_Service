<?php

namespace App\Services\CMS;

use Illuminate\Support\Facades\Http;
use App\Domains\Core\Traits\HasProjectHeaders;

class CMSApiClient
{
  use HasProjectHeaders;

  protected string $baseUrl;

  public function __construct()
  {
    $this->baseUrl = rtrim(config('services.cms.url'), '/');
  }

  public function createCollection(array $data): array
  {
    $response = Http::withHeaders(
      $this->projectHeaders()
    )->post("{$this->baseUrl}/api/cms/collections", $data);

    if ($response->failed()) {
      $error = $response->json('message')
        ?? substr($response->body(), 0, 200);

      throw new \Exception("Failed to create collection in CMS: " . $error);
    }

    return $response->json();
  }

  public function getCollectionBySlug(string $collectionSlug): array
  {
    $response = Http::withHeaders(
      $this->projectHeaders()
    )->get("{$this->baseUrl}/api/cms/collections/{$collectionSlug}");

    if ($response->failed()) {
      $error = $response->json('message')
        ?? substr($response->body(), 0, 200);

      throw new \Exception("Failed to fetch collection in CMS: " . $error);
    }

    return $response->json()['data'];
  }

  public function getCollectionById(int $collectionId): array
  {
    $response = Http::withHeaders(
      $this->projectHeaders()
    )->get("{$this->baseUrl}/api/cms/collections/id/{$collectionId}");

    if ($response->failed()) {
      $error = $response->json('message')
        ?? substr($response->body(), 0, 200);

      throw new \Exception("Failed to fetch collection in CMS: " . $error);
    }

    return $response->json('data');
  }

  public function getDynamicEntries(string $collectionSlug): array
  {
    $response = Http::withHeaders(
      $this->projectHeaders()
    )->get("{$this->baseUrl}/api/cms/collections/{$collectionSlug}/entries");

    if ($response->failed()) {
      $error = $response->json('message')
        ?? substr($response->body(), 0, 200);

      throw new \Exception("Failed to fetch dynamic entries in CMS: " . $error);
    }

    return $response->json();
  }

  public function updateCollection(string $collectionSlug, array $data)
  {
    $response = Http::withHeaders(
      $this->projectHeaders()
    )->patch("{$this->baseUrl}/api/cms/collections/{$collectionSlug}", $data);

    if ($response->failed()) {
      $error = $response->json('message')
        ?? substr($response->body(), 0, 200);

      throw new \Exception("Failed to update collection in CMS: " . $error);
    }

    return $response->json();
  }
}
