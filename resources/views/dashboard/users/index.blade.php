@extends('dashboard.layouts.app')

@section('container')
<div class="p-6 bg-white rounded-xl shadow-md">
    <h4 class="text-xl font-semibold mb-4">Daftar User</h4>
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $index + 1 }}</td>
                <td class="px-4 py-2">{{ $user->name }}</td>
                <td class="px-4 py-2">{{ $user->email }}</td>
                <td class="px-4 py-2">
                    {{ $user->roles->pluck('name')->implode(', ') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection