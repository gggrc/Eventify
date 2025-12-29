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
        $activeBoards = Board::where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('position', 'asc')
            ->get();

        $inactiveBoards = Board::where('user_id', Auth::id())
            ->where('status', 'inactive')
            ->orderBy('position', 'asc')
            ->get();

        return view('dashboard', compact('activeBoards', 'inactiveBoards'));
    }

    public function reorderBoards(Request $request)
    {
        foreach ($request->order as $item) {
            Board::where('id', $item['id'])
                ->where('user_id', Auth::id())
                ->update(['position' => $item['position']]);
        }
        return response()->json(['success' => true]);
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
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        Board::create([
            'title' => $request->title,
            'user_id' => Auth::id(),
            'status' => 'active' 
        ]);

        return redirect()->route('dashboard')->with('success', 'Project created!');
    }

    public function update(Request $request, Board $board)
    {
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        $board->update([
            'title' => $request->title,
            'status' => $request->status
        ]);

        return redirect()->route('dashboard')->with('success', 'Project updated!');
    }

    public function destroy(Board $board)
    {
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        $board->delete();

        return redirect()->route('dashboard')->with('success', 'Project deleted!');
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

    public function destroyList(TaskList $list)
    {
        if (!$list->board || $list->board->user_id !== Auth::id()) {
            abort(403);
        }

        $list->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'List deleted!');
    }
}