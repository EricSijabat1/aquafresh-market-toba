@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full border rounded px-3 py-2">{{ $category->description }}</textarea>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700">Gambar</label>
            <input type="file" name="image" id="image" class="w-full border rounded px-3 py-2">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-32 mt-2">
            @endif
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">
                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                Aktif
            </label>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update</button>
        </div>
    </form>
</div>
@endsection