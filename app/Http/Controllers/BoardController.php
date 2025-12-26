<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::where('user_id', Auth::id())->latest()->get();
        return view('dashboard', compact('boards'));
    }

    public function show(Board $board)
    {
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        $board->load(['taskLists' => function($query) {
            $query->orderBy('position', 'asc')->with(['cards' => function($q) {
                $q->orderBy('position', 'asc');
            }]);
        }]);        
        
        return view('boards.show', compact('board'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        Board::create(['title' => $request->title, 'user_id' => Auth::id()]);
        return redirect()->route('dashboard')->with('success', 'Project created!');
    }

    public function storeList(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id'
        ]);

        TaskList::create([
            'title' => $request->title,
            'board_id' => $request->board_id,
            'position' => TaskList::where('board_id', $request->board_id)->count()
        ]);

        return back();
    }

    public function reorderLists(Request $request)
    {
        foreach ($request->order as $item) {
            TaskList::where('id', $item['id'])->update(['position' => $item['position']]);
        }
        return response()->json(['success' => true]);
    }
}