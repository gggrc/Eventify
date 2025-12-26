<?php

namespace App\Http\Controllers;

use App\Models\Board;
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

        $board->load('taskLists.cards');        
        return view('boards.show', compact('board'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Board::create([
            'title' => $request->title,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Project created!');
    }
}