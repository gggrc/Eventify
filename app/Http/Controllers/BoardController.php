<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\TaskList;
use App\Models\Card;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function show(Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            abort(403);
        }

        return view('boards.show', [
            'board' => $board,
            'lists' => $board->taskLists()->with(['cards' => function($query) {
                $query->orderBy('position');
            }])->get()
        ]);
    }

    public function storeList(Request $request, Board $board)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $lastPosition = $board->taskLists()->max('position') ?? 0;

        $board->taskLists()->create([
            'title' => $validated['title'],
            'position' => $lastPosition + 1,
        ]);

        return back();
    }

    public function storeCard(Request $request, TaskList $list)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $lastPosition = $list->cards()->max('position') ?? 0;

        $list->cards()->create([
            'title' => $validated['title'],
            'position' => $lastPosition + 1,
        ]);

        return back();
    }

    public function updatePositions(Request $request)
    {
        $lists = $request->input('lists');

        foreach ($lists as $listIndex => $listData) {
            TaskList::where('id', $listData['id'])->update([
                'position' => $listIndex
            ]);
        
            if (isset($listData['cards'])) {
                foreach ($listData['cards'] as $cardIndex => $cardData) {
                    Card::where('id', $cardData['id'])->update([
                        'position' => $cardIndex,
                        'task_list_id' => $listData['id'] 
                    ]);
                }
            }
        }

        return back();
    }

    public function destroyList(TaskList $list)
    {
        $list->delete();
        return back();
    }

    public function destroyCard(Card $card)
    {
        $card->delete();
        return back();
    }
}