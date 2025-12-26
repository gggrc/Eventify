<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    // Menampilkan daftar project di Dashboard
    public function index()
    {
        $boards = Board::where('user_id', Auth::id())->latest()->get();
        return view('dashboard', compact('boards'));
    }

    // Membuat project baru
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

    // Menampilkan tampilan Kanban untuk project tertentu
    public function show(Board $board)
    {
        // Pastikan hanya pemilik yang bisa melihat
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        // Load lists dan cards-nya
        $board->load('lists.cards');
        
        return view('boards.show', compact('board'));
    }
}