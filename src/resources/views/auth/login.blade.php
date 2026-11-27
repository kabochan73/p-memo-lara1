@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-bold mb-6">ログイン</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium mb-1">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1">パスワード</label>
            <input type="password" id="password" name="password"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ログイン
        </button>
    </form>

    <p class="mt-4 text-sm text-gray-600">
        アカウントをお持ちでない方は <a href="{{ route('register') }}" class="text-blue-600 underline">会員登録</a>
    </p>
@endsection
