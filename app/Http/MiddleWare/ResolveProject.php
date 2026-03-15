<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;

class ResolveProject
{
  public function handle(Request $request, Closure $next)
  {
    $projectKey = $request->header('X-Project-Id');

    if (!$projectKey) {
      abort(400, 'X-Project-Id header is required');
    }

    $project = Project::where('public_id', $projectKey)->first();

    if (!$project) {
      abort(404, 'Project not found');
    }

    app()->instance('currentProject', $project);

    return $next($request);
  }
}
