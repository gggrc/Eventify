<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'task_list_id' => 'required|exists:task_lists,id'
        ]);

        Card::create([
            'title' => $request->title,
            'task_list_id' => $request->task_list_id,
            'position' => Card::where('task_list_id', $request->task_list_id)->count()
        ]);

        return back();
    }

    public function move(Request $request, Card $card)
    {
        $card->update([
            'task_list_id' => $request->list_id,
            'position' => $request->position
        ]);

        return response()->json(['success' => true]);
    }
}