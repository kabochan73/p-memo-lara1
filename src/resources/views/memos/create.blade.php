@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-6">メモ作成</h1>

    <form method="POST" action="{{ route('memos.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium mb-1">タイトル</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="body" class="block text-sm font-medium mb-1">本文</label>
            <textarea id="body" name="body" rows="8"
                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                保存
            </button>
            <a href="{{ route('memos.index') }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
                一覧へ戻る
            </a>
        </div>
    </form>
@endsection
