<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-100 to-green-50">

        <!-- Navbar -->
        <nav class="flex justify-between items-center px-10 py-5 bg-white shadow">
            <h1 class="text-2xl font-bold text-green-700">GMS</h1>

            
        </nav>

        <!-- Hero Section -->
        <section class="flex flex-col items-center justify-center text-center px-6 py-20">
            <h2 class="text-5xl font-bold text-green-800 mb-6">
                Garbage Management System
            </h2>

            <p class="text-gray-600 text-lg max-w-2xl mb-8">
                A smart web-based platform designed to help communities manage
                garbage collection schedules, monitor waste disposal, and improve
                environmental cleanliness.
            </p>

            <div class="flex gap-4">
                <a href="/login"
                   class="px-8 py-3 bg-green-600 text-white rounded-lg text-lg hover:bg-green-700 transition">
                   Get Started
                </a>

                <a href="#features"
                   class="px-8 py-3 border border-green-600 text-green-700 rounded-lg text-lg hover:bg-green-100 transition">
                   Learn More
                </a>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="px-10 py-16 bg-white">
            <h3 class="text-3xl font-bold text-center text-green-800 mb-12">
                System Features
            </h3>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                <div class="p-6 bg-green-50 rounded-xl shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold text-green-700 mb-2">
                        Collection Scheduling
                    </h4>
                    <p class="text-gray-600">
                        View and manage garbage collection schedules to ensure
                        timely waste pickup in every area.
                    </p>
                </div>

                <div class="p-6 bg-green-50 rounded-xl shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold text-green-700 mb-2">
                        Waste Monitoring
                    </h4>
                    <p class="text-gray-600">
                        Track waste collection data and monitor garbage levels
                        for better waste management planning.
                    </p>
                </div>

                <div class="p-6 bg-green-50 rounded-xl shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold text-green-700 mb-2">
                        Community Management
                    </h4>
                    <p class="text-gray-600">
                        Help communities stay organized and informed about waste
                        disposal and environmental cleanliness.
                    </p>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center py-6 bg-green-700 text-white">
            <p>© {{ date('Y') }} Garbage Management System | All Rights Reserved</p>
        </footer>

    </div>
</x-layout>