@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Produk
        </a>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
        <table class="w-full min-w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Gambar</th>
                    <th class="py-3 px-6 text-left">Nama Produk</th>
                    <th class="py-3 px-6 text-left">Kategori</th>
                    <th class="py-3 px-6 text-center">Harga</th>
                    <th class="py-3 px-6 text-center">Stok</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse($products as $product)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg">
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="font-medium">{{ $product->name }}</span>
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $product->category->name }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        {{ $product->price_formatted }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        {{ $product->stock }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        @if($product->is_active)
                            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Aktif</span>
                        @else
                            <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-right">
                        <div class="flex item-center justify-end">
                            <a href="{{ route('admin.products.show', $product) }}" class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2 transform hover:scale-110">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-2 transform hover:scale-110">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center transform hover:scale-110">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
