@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Tambah Kategori</a>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-2">Nama</th>
                    <th class="text-left py-2">Produk</th>
                    <th class="text-left py-2">Aktif</th>
                    <th class="text-right py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-t">
                    <td class="py-2">{{ $category->name }}</td>
                    <td class="py-2">{{ $category->products_count }}</td>
                    <td class="py-2">{{ $category->is_active ? 'Ya' : 'Tidak' }}</td>
                    <td class="py-2 text-right">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection