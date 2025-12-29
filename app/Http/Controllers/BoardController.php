<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids; 
use Illuminate\Http\JsonResponse;

class BoardController extends Controller
{
    private $hashids;

    public function __construct()
    {
        // Menginisialisasi Hashids untuk enkripsi ID Board pada URL
        $this->hashids = new Hashids('eventify-secret-salt', 10);
    }

    /**
     * Menampilkan daftar Board di Dashboard.
     */
    public function index()
    {
        $activeBoards = Board::where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('position', 'asc')
            ->get()
            ->map(function ($board) {
                $board->hashid = $this->hashids->encode($board->id);
                return $board;
            });

        $inactiveBoards = Board::where('user_id', Auth::id())
            ->where('status', 'inactive')
            ->orderBy('position', 'asc')
            ->get()
            ->map(function ($board) {
                $board->hashid = $this->hashids->encode($board->id);
                return $board;
            });

        return view('dashboard', compact('activeBoards', 'inactiveBoards'));
    }

    /**
     * Menampilkan detail Board beserta Task Lists dan Cards di dalamnya.
     */
    public function show(Request $request)
    {
        $hashid = $request->query('id');
        $decoded = $this->hashids->decode($hashid);

        if (empty($decoded)) {
            abort(404);
        }

        $board = Board::where('id', $decoded[0])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Memuat relasi Task Lists dan Cards secara berurutan
        $board->load(['taskLists' => function($query) {
            $query->orderBy('position', 'asc')->with(['cards' => function($q) {
                $q->orderBy('position', 'asc');
            }]);
        }]);        
        
        return view('boards.show', compact('board'));
    }

    /**
     * Menyimpan Board baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board = Board::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'status' => 'active', 
            'position' => Board::where('user_id', auth()->id())->count()
        ]);

        $hashedId = $this->hashids->encode($board->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('boards.show', ['id' => $hashedId])
            ]);
        }

        return redirect()->route('boards.show', ['id' => $hashedId]);
    }

    /**
     * Memperbarui data Board.
     */
    public function update(Request $request, Board $board)
    {
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive', 
        ]);

        $board->update([
            'title' => $request->title,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'board' => $board
            ]);
        }

        return back();
    }

    /**
     * Menghapus Board.
     */
    public function destroy(Board $board)
    {
        if ($board->user_id !== Auth::id()) {
            abort(403);
        }

        $board->delete();

        return redirect()->route('dashboard')->with('success', 'Project deleted!');
    }

    /**
     * Mengatur ulang urutan Board pada Dashboard.
     */
    public function reorderBoards(Request $request)
    {
        foreach ($request->order as $item) {
            Board::where('id', $item['id'])
                ->where('user_id', Auth::id())
                ->update(['position' => $item['position']]);
        }
        return response()->json(['success' => true]);
    }

    // ==========================================
    // FUNGSI GABUNGAN UNTUK TASK LIST
    // ==========================================

    /**
     * Menyimpan Task List baru (AJAX).
     */
    public function storeList(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id'
        ]);

        $list = TaskList::create([
            'title' => $request->title,
            'board_id' => $request->board_id,
            'position' => TaskList::where('board_id', $request->board_id)->count()
        ]);

        return response()->json([
            'success' => true,
            'list' => [
                'id' => $list->id,
                'title' => $list->title,
            ]
        ]);
    }

    /**
     * Memperbarui judul Task List (Fitur Edit Nama List).
     */
    public function updateList(Request $request, TaskList $list): JsonResponse
    {
        if (!$list->board || $list->board->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $list->update([
            'title' => $validated['title']
        ]);

        return response()->json([
            'success' => true,
            'list' => $list
        ]);
    }

    /**
     * Mengatur ulang urutan Task List (Drag and Drop).
     */
    public function reorderLists(Request $request): JsonResponse
    {
        foreach ($request->order as $item) {
            TaskList::where('id', $item['id'])->update(['position' => $item['position']]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Menghapus Task List.
     */
    public function destroyList(TaskList $list): JsonResponse
    {
        if (!$list->board || $list->board->user_id !== Auth::id()) {
            abort(403);
        }

        $list->delete();
        
        return response()->json(['success' => true]);
    }
}