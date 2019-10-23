<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivateCheatRequest;
use App\Http\Resources\ProjectKeyResource;
use App\VkPayOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailableCheatController extends Controller
{
    public function activateCheat(ActivateCheatRequest $request) {
        $user = Auth::user();

        /**
         * @var VkPayOrder
         */
        $vkPayOrder = $user->vkPayOrders()->where('id', $request->order_id)->first();

        if (!$vkPayOrder) {
            abort(422, 'Чит код не найден');
        }

        if (!$vkPayOrder->availableCheat) {
            abort(422, 'Чит код не найден');
        }

        if (!$vkPayOrder->isDone()) {
            abort(402, 'Не оплачен');
        }

        if ($vkPayOrder->availableCheat->is_fired) {
            abort(403, 'Чит код уже использован');
        }

        $projectKey = $vkPayOrder->availableCheat->activate();

        $project = $projectKey->project;
        $project->refresh();

        $projectKey->setAttribute('is_last', $project->is_finished);

        return new ProjectKeyResource($projectKey);
    }
}
