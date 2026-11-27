@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-2">{{ $memo->title }}</h1>
    <p class="text-sm text-gray-500 mb-6">
        作成日時: {{ $memo->created_at->format('Y-m-d H:i') }} /
        更新日時: {{ $memo->updated_at->format('Y-m-d H:i') }}
    </p>

    <p class="whitespace-pre-wrap mb-8">{{ $memo->body }}</p>

    <div class="flex gap-2">
        <a href="{{ route('memos.edit', $memo) }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
            編集
        </a>

        <form method="POST" action="{{ route('memos.destroy', $memo) }}" onsubmit="return confirm('このメモを削除しますか?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded border border-red-300 text-red-600 hover:bg-red-50">
                削除
            </button>
        </form>

        <a href="{{ route('memos.index') }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
            一覧へ戻る
        </a>
    </div>
@endsection
