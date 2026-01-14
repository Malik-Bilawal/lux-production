



@extends("admin.layouts.master-layouts.plain")

@section('title', 'Admin Login | Luxorix | ')

@push("script")
@endpush


@push("style")
@endpush



@section("content")
@include("admin.components.dark-mode.dark-toggle")

<body class="bg-gray-100 flex items-center justify-center h-screen">


    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

        @if(session('error'))
            <div class="bg-red-200 text-red-700 p-2 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif
        @if(session('error'))
    <div class="bg-red-200 text-red-700 p-2 mb-4 rounded">
        {{ session('error') }}
    </div>
@endif


        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>
    </div>
</div>
@endsection



@push("script")
@endpush
