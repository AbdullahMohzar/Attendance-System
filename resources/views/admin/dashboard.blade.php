<!-- <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Command Center') }}
            </h2>
            <div class="text-sm text-gray-500">
                Welcome back, <strong>{{ Auth::user()->name }}</strong>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b-4 border-blue-500 hover:shadow-md transition">
                    <div class="text-gray-500 font-bold uppercase text-xs">Total Students</div>
                    <div class="text-3xl font-extrabold text-gray-800">{{ $totalStudents }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b-4 border-orange-500 hover:shadow-md transition">
                    <div class="text-gray-500 font-bold uppercase text-xs">Pending Leave Requests</div>
                    <div class="text-3xl font-extrabold text-gray-800">{{ $pendingLeaves }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b-4 border-green-500 hover:shadow-md transition">
                    <div class="text-gray-500 font-bold uppercase text-xs">Attendance Today</div>
                    <div class="text-3xl font-extrabold text-gray-800">{{ $todayAttendance }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2 text-center md:text-left">Management Console</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <a href="{{ route('admin.students') }}" class="flex items-center p-5 bg-indigo-50 rounded-xl border border-indigo-200 hover:bg-indigo-100 hover:shadow-md transition group">
                        <div class="p-3 bg-indigo-200 rounded-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-indigo-900">Student Management</p>
                            <p class="text-xs text-indigo-600">View list & Edit records</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.leaves') }}" class="flex items-center p-5 bg-orange-50 rounded-xl border border-orange-200 hover:bg-orange-100 hover:shadow-md transition group">
                        <div class="p-3 bg-orange-200 rounded-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-orange-900">Approve Leaves</p>
                            <p class="text-xs text-orange-600">Review pending requests</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}" class="flex items-center p-5 bg-blue-50 rounded-xl border border-blue-200 hover:bg-blue-100 hover:shadow-md transition group">
                        <div class="p-3 bg-blue-200 rounded-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-blue-900">Attendance Reports</p>
                            <p class="text-xs text-blue-600">Generate Date filters</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.tasks') }}" class="flex items-center p-5 bg-purple-50 rounded-xl border border-purple-200 hover:bg-purple-100 hover:shadow-md transition group">
                        <div class="p-3 bg-purple-200 rounded-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-purple-900">Assign New Tasks</p>
                            <p class="text-xs text-purple-600">Use Rich Text Editor</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.tasks.submissions') }}" class="flex items-center p-5 bg-pink-50 rounded-xl border border-pink-200 hover:bg-pink-100 hover:shadow-md transition group">
                        <div class="p-3 bg-pink-200 rounded-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-pink-900">Review Submissions</p>
                            <p class="text-xs text-pink-600">Approve/Reject work</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}" class="flex items-center p-5 bg-green-50 rounded-xl border border-green-200 hover:bg-green-100 hover:shadow-md transition group">
                        <div class="p-3 bg-green-200 rounded-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-green-900">Grading System</p>
                            <p class="text-xs text-green-600">Automated performance</p>
                        </div>
                    </a>

                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:underline">
                        Securely Logout of Admin Panel
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout> -->