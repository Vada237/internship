<?php

namespace App\Http\Controllers\Api;

use App\Actions\Board\BoardCreateAction;
use App\Actions\Board\BoardDeleteAction;
use App\Actions\Board\BoardGetAllAction;
use App\Actions\Board\BoardGetByIdAction;
use App\Actions\Board\BoardGetByProjectIdAction;
use App\Actions\Board\BoardUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\BoardCreateRequest;
use App\Http\Requests\Board\BoardUpdateRequest;
use App\Http\Requests\LimitOffsetRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Project;
use Illuminate\Http\Request;
use Termwind\Html\InheritStyles;

class BoardController extends Controller
{
    public function index(BoardGetAllAction $action, LimitOffsetRequest $request)
    {
        $this->authorize('viewAny', Board::class);
        return BoardResource::collection($action->handle($request->validated()));
    }

    public function store(BoardCreateAction $action, BoardCreateRequest $request)
    {
        $this->authorize('create', [Board::class, Project::find($request->validated('project_id'))]);
        return new BoardResource($action->handle($request->validated()));
    }

    public function findByProject(BoardGetByProjectIdAction $action, Project $project)
    {
        $this->authorize('viewByProject', [Board::class, $project]);
        return BoardResource::collection($action->handle($project));
    }

    public function show(BoardGetByIdAction $action, Board $board)
    {
        $this->authorize('view', [Board::class, Project::find($board->project_id)]);
        return new BoardResource($action->handle($board));
    }

    public function update(BoardUpdateAction $action, BoardUpdateRequest $request, Board $board)
    {
        $this->authorize('update', [Board::class, $board]);
        return new BoardResource($action->handle($request->validated(), $board));
    }

    public function destroy(BoardDeleteAction $action, Board $board)
    {
        $this->authorize('delete', [Board::class, $board]);
        return $action->handle($board);
    }
}
