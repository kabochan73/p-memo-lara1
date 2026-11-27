<div>
    <label for="title" class="block text-sm font-medium mb-1">タイトル</label>
    <input type="text" id="title" name="title" value="{{ old('title', $memo->title ?? '') }}"
           class="w-full border border-gray-300 rounded px-3 py-2">
    @error('title')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="body" class="block text-sm font-medium mb-1">本文</label>
    <textarea id="body" name="body" rows="8"
              class="w-full border border-gray-300 rounded px-3 py-2">{{ old('body', $memo->body ?? '') }}</textarea>
    @error('body')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
