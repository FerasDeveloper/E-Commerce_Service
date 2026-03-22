<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Http;

class AuthApiClient
{
  protected string $baseUrl;

  public function __construct()
  {
    $this->baseUrl = rtrim(config('services.auth.url'), '/');
  }

  public function getUserFromToken(string $token): array
  {
    $response = Http::withToken($token)
      ->get("{$this->baseUrl}/api/my-profile");

    if (!$response->successful()) {
      dd($response->status(), $response->body());
    }

    $user = $response->json()['data'];

    $permissions = collect($user['roles'])
      ->flatMap(fn($role) => $role['permessions'])
      ->pluck('name')
      ->unique()
      ->values()
      ->toArray();

    $user['permissions'] = $permissions;

    return $user;
  }
}
