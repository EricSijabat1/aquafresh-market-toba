@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-medium mb-2">Kategori</label>
                <select name="category_id" id="category_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-medium mb-2">Harga (Rp)</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-gray-700 font-medium mb-2">Stok</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="weight" class="block text-gray-700 font-medium mb-2">Berat (kg)</label>
                <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-medium mb-2">Gambar Produk</label>
            <input type="file" name="image" id="image" class="w-full border rounded-lg px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-checkbox" checked>
                <span class="ml-2 text-gray-700">Aktifkan Produk</span>
            </label>
        </div>
        <div class="flex justify-end">
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Simpan Produk</button>
        </div>
    </form>
</div>
@endsection
