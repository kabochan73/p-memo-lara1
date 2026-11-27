@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold">メモ一覧</h1>
        <a href="{{ route('memos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            新規作成
        </a>
    </div>

    <form method="GET" action="{{ route('memos.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="キーワードで検索"
               class="flex-1 border border-gray-300 rounded px-3 py-2">
        <button type="submit" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
            検索
        </button>
    </form>

    @if ($memos->isEmpty())
        <p class="text-gray-500">
            {{ $keyword ? '該当するメモが見つかりません' : 'メモがありません' }}
        </p>
    @else
        <ul class="divide-y divide-gray-200">
            @foreach ($memos as $memo)
                <li class="py-3">
                    <a href="{{ route('memos.show', $memo) }}" class="block hover:bg-gray-50 -mx-2 px-2 py-1 rounded">
                        <p class="font-medium">{{ $memo->title }}</p>
                        <p class="text-sm text-gray-500">更新日時: {{ $memo->updated_at->format('Y-m-d H:i') }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
