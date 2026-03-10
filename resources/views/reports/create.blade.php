<x-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 p-6">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Add Report</h1>

            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Report Title -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Report Title</label>
                    <input type="text" name="title" required
                        class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Add Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Upload Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full border px-4 py-2 rounded-lg">
                </div>

                <!-- Add Location -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Location</label>
                    <input type="text" name="location" required
                        placeholder="Enter location"
                        class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-semibold">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>