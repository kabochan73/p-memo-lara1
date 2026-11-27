@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-6">メモ作成</h1>

    <form method="POST" action="{{ route('memos.store') }}" class="space-y-4">
        @csrf

        @include('memos._form_fields')

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
