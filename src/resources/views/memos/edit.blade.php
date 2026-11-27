@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-6">メモ編集</h1>

    <form method="POST" action="{{ route('memos.update', $memo) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium mb-1">タイトル</label>
            <input type="text" id="title" name="title" value="{{ old('title', $memo->title) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="body" class="block text-sm font-medium mb-1">本文</label>
            <textarea id="body" name="body" rows="8"
                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old('body', $memo->body) }}</textarea>
            @error('body')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                更新
            </button>
            <a href="{{ route('memos.show', $memo) }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
                キャンセル
            </a>
        </div>
    </form>
@endsection
