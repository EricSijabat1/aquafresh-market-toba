@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700">Gambar</label>
            <input type="file" name="image" id="image" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">
                <input type="checkbox" name="is_active" value="1" checked>
                Aktif
            </label>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection