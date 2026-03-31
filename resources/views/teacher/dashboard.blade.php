<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏫 {{ __('Teacher Command Center') }}
            </h2>
            <div class="text-sm font-medium text-gray-500">
                {{ now()->format('l, d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100 transition hover:shadow-md">
                    <div class="text-xs font-black text-blue-500 uppercase tracking-widest mb-1">Total Students</div>
                    <div class="text-3xl font-black text-gray-800">{{ $totalStudents }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100 transition hover:shadow-md">
                    <div class="text-xs font-black text-yellow-500 uppercase tracking-widest mb-1">Pending Leaves</div>
                    <div class="text-3xl font-black text-gray-800">{{ $pendingLeaves }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100 transition hover:shadow-md">
                    <div class="text-xs font-black text-green-500 uppercase tracking-widest mb-1">Present Today</div>
                    <div class="text-3xl font-black text-gray-800">{{ $presentToday }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-gray-100 transition hover:shadow-md">
                    <div class="text-xs font-black text-purple-500 uppercase tracking-widest mb-1">Active Tasks</div>
                    <div class="text-3xl font-black text-gray-800">{{ $activeTasks }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 bg-white shadow-sm rounded-2xl p-8 border border-gray-100">
                    <h3 class="text-sm font-black text-gray-400 mb-6 uppercase tracking-widest">Recent Student Activity</h3>
                    <div class="space-y-4">
                        @forelse($recentSubmissions as $sub)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-purple-200 transition">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold mr-4">
                                        {{ substr($sub->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">
                                            {{ $sub->user->name }} 
                                            <span class="font-normal text-gray-500 underline decoration-purple-200">submitted task</span>
                                        </p>
                                        <p class="text-xs text-gray-400 font-medium">{{ $sub->task->title }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase bg-white px-2 py-1 rounded border">{{ $sub->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-gray-400 italic">No recent task submissions found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-2xl p-8 border border-gray-100">
                    <h3 class="text-sm font-black text-gray-400 mb-6 uppercase tracking-widest text-center">Teacher Launch Pad</h3>
                    <div class="flex flex-col space-y-4">
                        <a href="{{ route('admin.tasks') }}" class="group flex items-center justify-between w-full bg-purple-600 text-white px-4 py-3 rounded-xl font-bold shadow-lg shadow-purple-200 hover:bg-purple-700 transition transform hover:-translate-y-1">
                            <span>Assign New Task</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </a>

                        <a href="{{ route('admin.tasks.submissions') }}" class="flex items-center justify-between w-full bg-pink-50 text-pink-700 px-4 py-3 rounded-xl font-bold border border-pink-100 hover:bg-pink-100 transition">
                            <span>Check Submissions</span>
                            
                        </a>

                        <a href="{{ route('admin.leaves') }}" class="flex items-center justify-between w-full bg-yellow-50 text-yellow-700 px-4 py-3 rounded-xl font-bold border border-yellow-100 hover:bg-yellow-100 transition">
                            <span>Review Leaves</span>
                            @if($pendingLeaves > 0)
                                <span class="bg-yellow-200 text-yellow-800 text-[10px] px-2 py-0.5 rounded-full">{{ $pendingLeaves }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.reports') }}" class="flex items-center justify-between w-full bg-green-50 text-green-700 px-4 py-3 rounded-xl font-bold border border-green-100 hover:bg-green-100 transition">
                            <span>Monthly Reports</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </a>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-[10px] text-center font-black text-gray-300 uppercase">System Status: Active</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>