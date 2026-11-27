<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoRequest;
use App\Models\Memo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $keyword = $request->query('keyword');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $memos = $user->memos()
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhere('body', 'like', "%{$keyword}%");
                });
            })
            ->latest('updated_at')
            ->get();

        return view('memos.index', [
            'memos' => $memos,
            'keyword' => $keyword,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('memos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemoRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->memos()->create($request->validated());

        return redirect()->route('memos.index')->with('status', 'メモを作成しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Memo $memo): View
    {
        $this->authorize('view', $memo);

        return view('memos.show', ['memo' => $memo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Memo $memo): View
    {
        $this->authorize('update', $memo);

        return view('memos.edit', ['memo' => $memo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemoRequest $request, Memo $memo): RedirectResponse
    {
        $this->authorize('update', $memo);

        $memo->update($request->validated());

        return redirect()->route('memos.show', $memo)->with('status', 'メモを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Memo $memo): RedirectResponse
    {
        $this->authorize('delete', $memo);

        $memo->delete();

        return redirect()->route('memos.index')->with('status', 'メモを削除しました');
    }
}
