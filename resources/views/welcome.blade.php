<x-layout>
<div class="min-h-screen bg-white font-sans" x-data="{ mobileMenuOpen: false }">

    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-8 h-8 rounded-full object-cover">
                <span class="text-lg font-bold text-gray-900 tracking-tight">GMS</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm text-gray-600 hover:text-green-600 transition">Features</a>
                <a href="#how-it-works" class="text-sm text-gray-600 hover:text-green-600 transition">How it Works</a>
                <a href="#about" class="text-sm text-gray-600 hover:text-green-600 transition">About</a>
                <a href="/login" class="text-sm font-semibold text-green-600">Login</a>
            </div>

            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-b border-gray-100 px-4 py-4 space-y-3">
            <a href="#features" @click="mobileMenuOpen = false" class="block text-base text-gray-600">Features</a>
            <a href="#how-it-works" @click="mobileMenuOpen = false" class="block text-base text-gray-600">How it Works</a>
            <a href="#about" @click="mobileMenuOpen = false" class="block text-base text-gray-600">About</a>
            <a href="/login" class="block text-base font-bold text-green-600">Login</a>
        </div>
    </nav>

    <section class="pt-24 md:pt-32 pb-16 md:pb-24 px-4 md:px-6 bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block text-[10px] md:text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full mb-6 tracking-wide uppercase">
                Community Waste Management
            </span>
            <h1 class="text-3xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6 px-2">
                Keep Your Community<br class="hidden md:block"> <span class="text-green-600"> Clean & Organized</span>
            </h1>
            <p class="text-gray-500 text-sm md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed px-4">
                Report garbage issues, track collection status, and help your local government respond faster — all from one simple platform.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center px-6">
                <a href="/login" class="px-8 py-4 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition shadow-lg shadow-green-200 text-center">
                    Start Reporting Free
                </a>
                <a href="#how-it-works" class="px-8 py-4 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition text-center">
                    See How It Works
                </a>
            </div>
        </div>

        <div class="max-w-5xl mx-auto mt-12 md:mt-16 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mx-2">
            <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b">
                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                <span class="ml-2 text-[10px] text-gray-400 truncate">gms-app.com/dashboard</span>
            </div>
            <div class="p-4 md:p-8 grid grid-cols-3 gap-2 md:gap-4">
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-2 md:p-5 text-center">
                    <p class="text-xl md:text-3xl font-bold text-yellow-600">12</p>
                    <p class="text-[10px] md:text-sm text-gray-500 mt-1">Pending</p>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-2 md:p-5 text-center">
                    <p class="text-xl md:text-3xl font-bold text-blue-600">5</p>
                    <p class="text-[10px] md:text-sm text-gray-500 mt-1">In Progress</p>
                </div>
                <div class="bg-green-50 border border-green-100 rounded-xl p-2 md:p-5 text-center">
                    <p class="text-xl md:text-3xl font-bold text-green-600">38</p>
                    <p class="text-[10px] md:text-sm text-gray-500 mt-1">Completed</p>
                </div>
                <div class="col-span-3 bg-gray-50 rounded-xl p-3 md:p-4 flex items-center gap-3">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm font-semibold text-gray-700 truncate">Garbage pile near Barangay Hall</p>
                        <p class="text-[10px] text-gray-400">Reported 2 hours ago</p>
                    </div>
                    <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-medium">Pending</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 border-y border-gray-100 bg-white px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <p class="text-3xl md:text-4xl font-extrabold text-gray-900">100+</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1 uppercase tracking-wider">Registered Residents</p>
            </div>
            <div class="border-y md:border-y-0 md:border-x border-gray-100 py-6 md:py-0">
                <p class="text-xl md:text-2xl font-extrabold text-gray-900 uppercase">Pinontingan</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1 uppercase tracking-wider">Areas Covered</p>
            </div>
            <div>
                <p class="text-3xl md:text-4xl font-extrabold text-gray-900">75+</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1 uppercase tracking-wider">Reports Submitted</p>
            </div>
        </div>
    </section>

    <section id="features" class="py-16 md:py-24 px-4 md:px-6 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Everything you need</h2>
                <p class="text-gray-500 mt-3 text-sm md:text-base max-w-xl mx-auto">A complete toolkit for residents and administrators to manage waste efficiently.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div class="p-6 md:p-7 rounded-2xl border border-gray-100 hover:border-green-200 transition bg-white">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Location Pinning</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pin the exact location of garbage on an interactive map so collectors know exactly where to go.</p>
                </div>
                <div class="p-6 md:p-7 rounded-2xl border border-gray-100 hover:border-green-200 transition bg-white">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Real-time Tracking</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Monitor the status of your reports from pending to in-progress to completed in real time.</p>
                </div>
                <div class="p-6 md:p-7 rounded-2xl border border-gray-100 hover:border-green-200 transition bg-white">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Admin Dashboard</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Administrators get a full overview of all reports, users, and analytics to act quickly.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-16 md:py-24 px-4 md:px-6 bg-gray-50">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">How it works</h2>
            <p class="text-gray-500 mb-12 md:mb-16 text-sm md:text-base">Three simple steps to report and resolve garbage issues.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8">
                <div class="relative">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-green-600 text-white rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-5 shadow-lg shadow-green-100">1</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Create Account</h3>
                    <p class="text-sm text-gray-500 px-4">Sign up with your email or Google account in seconds.</p>
                </div>
                <div class="relative">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-green-600 text-white rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-5 shadow-lg shadow-green-100">2</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Submit Report</h3>
                    <p class="text-sm text-gray-500 px-4">Pin location, add a photo, and submit your report.</p>
                </div>
                <div class="relative">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-green-600 text-white rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-5 shadow-lg shadow-green-100">3</div>
                    <h3 class="font-semibold text-gray-900 mb-2">Track Progress</h3>
                    <p class="text-sm text-gray-500 px-4">Watch your report status update as the team responds.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-500 py-12 px-6 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-center items-center gap-2 mb-6 opacity-50">
                <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-6 h-6 rounded-full grayscale">
                <span class="text-white font-bold tracking-tight">GMS</span>
            </div>
            <p class="text-xs md:text-sm">© {{ date('Y') }} Garbage Management System.<br class="md:hidden"> All rights reserved.</p>
        </div>
    </footer>

</div>
</x-layout>