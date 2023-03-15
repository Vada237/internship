<?php

namespace app\Http\Controllers\Api;

use App\Actions\Board\Template\BoardTemplateCreateAction;
use App\Actions\Board\Template\BoardTemplateDeleteAction;
use App\Actions\Board\Template\BoardTemplateGetAllAction;
use App\Actions\Board\Template\BoardTemplateGetByIdAction;
use App\Actions\Board\Template\BoardTemplateUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\Template\BoardTemplateRequest;
use App\Http\Requests\LimitOffsetRequest;
use App\Http\Resources\BoardTemplateResource;
use App\Models\BoardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardTemplateController extends Controller
{
    public function index(BoardTemplateGetAllAction $action, LimitOffsetRequest $request)
    {
        return BoardTemplateResource::collection($action->handle($request->validated()));
    }

    public function store(BoardTemplateCreateAction $action, BoardTemplateRequest $request)
    {
        return new BoardTemplateResource($action->handle($request->validated(), Auth::id()));
    }

    public function show(BoardTemplateGetByIdAction $action, BoardTemplate $boardTemplate)
    {
        return new BoardTemplateResource($action->handle($boardTemplate));
    }

    public function update(BoardTemplateUpdateAction $action, BoardTemplateRequest $request, BoardTemplate $boardTemplate)
    {
        $this->authorize('update', [BoardTemplate::class, $boardTemplate]);
        return new BoardTemplateResource($action->handle($boardTemplate, $request->validated()));
    }

    public function destroy(BoardTemplateDeleteAction $action, BoardTemplate $boardTemplate)
    {
        $this->authorize('delete', [BoardTemplate::class, $boardTemplate]);
        return $action->handle($boardTemplate);
    }
}