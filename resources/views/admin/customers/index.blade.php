@extends('layouts.admin')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <table class="w-full">
        <thead>
            <tr>
                <th class="text-left py-2">Nama</th>
                <th class="text-left py-2">Email</th>
                <th class="text-left py-2">WhatsApp</th>
                <th class="text-right py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr class="border-t">
                <td class="py-2">{{ $customer->name }}</td>
                <td class="py-2">{{ $customer->email }}</td>
                <td class="py-2">{{ $customer->whatsapp }}</td>
                <td class="py-2 text-right">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-500">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection