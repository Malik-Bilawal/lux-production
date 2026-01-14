@extends("admin.layouts.master-layouts.plain")

<title>Sales Edit | Luxorix Admin</title>

@section('content')

        <section class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-8 text-center">Edit Sale</h2>
            @include("admin.components.dark-mode.dark-toggle")


            <form action="{{ route('admin.sales.update', $sale->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <div>
                    <label for="title" class="block text-gray-700 font-medium mb-2">Sale Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $sale->title) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" value="{{ old('description', $sale->decsription) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $sale->description) }}</textarea>
                </div>

                <div>
                    <label for="discount" class="block text-gray-700 font-medium mb-2">Discount %</label>
                    <input type="number" id="discount" name="discount" value="{{ old('discount', $sale->discount) }}" min="0" max="100"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-medium text-gray-700">Offer Timer</label>
                    <div class="flex gap-2">
                        @php
                            $start = \Carbon\Carbon::parse($sale->start_time ?? now());
                            $end = \Carbon\Carbon::parse($sale->end_time ?? now());
                            $diff = $end->diff($start);
                        @endphp
                        <input type="number" name="days" min="0" max="30" placeholder="Days" 
                            class="border rounded p-2 w-20" value="{{ old('days', $diff->d) }}">
                        <input type="number" name="hours" min="0" max="23" placeholder="Hours" 
                            class="border rounded p-2 w-20" value="{{ old('hours', $diff->h) }}">
                        <input type="number" name="minutes" min="0" max="59" placeholder="Minutes" 
                            class="border rounded p-2 w-20" value="{{ old('minutes', $diff->i) }}">
                        <input type="number" name="seconds" min="0" max="59" placeholder="Seconds" 
                            class="border rounded p-2 w-20" value="{{ old('seconds', $diff->s) }}">
                    </div>
                </div>

                <!-- Status Radio Buttons -->
                <fieldset class="mb-6">
                    <legend class="block text-gray-700 font-medium mb-2">Status</legend>
                    <div class="flex space-x-8 items-center">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="active" 
                                {{ old('status', $sale->status) === 'active' ? 'checked' : '' }} 
                                class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-800">Active</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="inactive" 
                                {{ old('status', $sale->status) === 'inactive' ? 'checked' : '' }} 
                                class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-800">Inactive</span>
                        </label>
                    </div>
                </fieldset>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-lg transition-colors">
                    Update Sale
                </button>
            </form>
        </section>
    </main>
</div>
@endsection
