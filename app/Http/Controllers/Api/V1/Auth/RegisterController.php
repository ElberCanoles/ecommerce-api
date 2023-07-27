<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Domain\Users\Actions\StoreUserAction;
use App\Domain\Users\DataTransferObjects\StoreUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(StoreRequest $request, StoreUserAction $storeUserAction): JsonResponse
    {
        $storeUserAction->execute(StoreUserData::fromRequest($request));
        return response()->json(data: ['message' => trans(key: 'responses.record_created')], status: Response::HTTP_CREATED);
    }
}
