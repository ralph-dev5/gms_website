<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('partials.user-sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">

        <div class="mb-4">
            <a href="{{ route('documents.index') }}" class="text-green-600 hover:underline text-sm">&larr; Back to My Requests</a>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Request a Barangay Document</h1>
        <p class="text-gray-500 mb-8">Fill out the form below and the admin will process your request.</p>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">{{ $errors->first() }}</div>
        @endif

        <div class="bg-white shadow rounded-lg p-6 max-w-xl">
            <form method="POST" action="{{ route('documents.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Document Type</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($documentTypes as $type)
                        <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:border-green-400 hover:bg-green-50 transition
                            {{ old('document_type') === $type ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" name="document_type" value="{{ $type }}"
                                class="accent-green-600"
                                {{ old('document_type') === $type ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $type }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('document_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Purpose <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="purpose" rows="3" maxlength="500"
                        placeholder="e.g. For employment requirements..."
                        class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('purpose') }}</textarea>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Submit Request
                </button>
            </form>
        </div>

    </main>
</div>
</x-layout>
