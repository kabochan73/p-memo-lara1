@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-6">メモ編集</h1>

    <form method="POST" action="{{ route('memos.update', $memo) }}" class="space-y-4">
        @csrf
        @method('PUT')

        @include('memos._form_fields')

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
