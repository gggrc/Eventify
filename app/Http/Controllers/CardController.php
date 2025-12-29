<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Task; 
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show(Card $card)
    {
        return response()->json($card->load('tasks'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,Urgent',
        ]);

        $card->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        if ($request->has('tasks')) {
            $card->tasks()->delete();
            foreach ($request->tasks as $taskData) {
                if (!empty($taskData['title'])) {
                    $card->tasks()->create([
                        'title' => $taskData['title'],
                        'is_completed' => filter_var($taskData['is_completed'], FILTER_VALIDATE_BOOLEAN)
                    ]);
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'card' => [
                'id' => $card->id,
                'title' => $card->title,
                'priority' => $card->priority,
                'updated_at' => $card->updated_at->format('D H:i')
            ]
        ]);
    }

    public function move(Request $request, Card $card)
    {
        $card->update([
            'task_list_id' => $request->list_id,
            'position' => $request->position
        ]);

        if ($request->has('card_order')) {
            foreach ($request->card_order as $item) {
                Card::where('id', $item['id'])->update(['position' => $item['position']]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'task_list_id' => 'required|exists:task_lists,id'
        ]);

        $card = Card::create([
            'title' => $request->title,
            'task_list_id' => $request->task_list_id,
            'position' => Card::where('task_list_id', $request->task_list_id)->count()
        ]);

        return response()->json([
            'success' => true,
            'card' => [
                'id' => $card->id,
                'title' => $card->title,
                'created_at' => $card->created_at->format('D H:i'),
                'priority' => 'Low'
            ]
        ]);
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return response()->json(['success' => true]);
    }
}