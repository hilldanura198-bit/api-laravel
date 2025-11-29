<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->todos()->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'title' => 'required|string|max:100',
            'completed' => 'boolean',
        ]);

        $todo = $user->todos()->create([
            'title' => $data['title'],
            'completed' => $data['completed'] ?? false,
        ]);

        return response()->json($todo, 201);
    }

    public function show(Todo $todo)
    {
        $this->authorizeOwner($todo);
        return $todo;
    }

    public function update(Request $request, Todo $todo)
    {
        $this->authorizeOwner($todo);

        $data = $request->validate([
            'title' => 'sometimes|string|max:100',
            'completed' => 'sometimes|boolean',
        ]);

        $todo->update($data);
        return $todo;
    }

    public function destroy(Todo $todo)
    {
        $this->authorizeOwner($todo);

        $todo->delete();
        return response()->noContent();
    }

    /**
     * Pastikan todo milik user yang login
     *
     * @param \App\Models\Todo $todo
     */
    protected function authorizeOwner(Todo $todo)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($todo->user_id !== $user->id) {
            abort(403, 'Forbidden');
        }
    }
}