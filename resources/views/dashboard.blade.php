<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Dashboard') }}
            </h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 transition">
                    Account Settings
                </a>
                <a href="{{ route('tasks.student.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-sm">
                    Task Center
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="toast" class="fixed top-5 right-5 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-2xl z-50 flex items-center border-l-4 border-indigo-500 transition-all duration-500">
                    <span class="mr-2 text-indigo-400">✓</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => { 
                        const toast = document.getElementById('toast');
                        if(toast) {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 500);
                        }
                    }, 3000);
                </script>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-purple-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-[10px] font-black text-purple-600 uppercase tracking-widest">Current Grade</div>
                            <div class="text-4xl font-black {{ $color ?? 'text-gray-800' }}">{{ $grade ?? 'N/A' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Score</div>
                            <div class="text-lg font-black text-gray-700">{{ round($percentage ?? 0) }}%</div>
                            <p class="text-[9px] text-gray-400 font-bold italic">
                                ({{ $attendances->where('attendance_date', '>=', now()->startOfMonth()->toDateString())->count() }} / {{ $workdaysCount ?? 0 }})
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-blue-500">
                    <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Total Attendance</div>
                    <div class="text-3xl font-black text-gray-800">{{ $attendances->count() }} <span class="text-sm text-gray-400">Days</span></div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-green-500">
                    <div class="text-[10px] font-black text-green-600 uppercase tracking-widest">Approved Leaves</div>
                    <div class="text-3xl font-black text-gray-800">
                        @php
                            $approvedLeaveDays = $leaveRequests->where('status', 'approved')->reduce(function ($carry, $leave) {
                                $start = \Carbon\Carbon::parse($leave->start_date);
                                $end = \Carbon\Carbon::parse($leave->end_date);
                                return $carry + ($start->diffInDays($end) + 1);
                            }, 0);
                        @endphp
                        {{ (int)$approvedLeaveDays }} <span class="text-sm text-gray-400">Days</span>
                    </div>
                </div>

                <div class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl p-6 border-l-4 border-orange-500 relative group">
                    <div class="relative z-10">
                        <div class="text-[10px] font-black text-orange-500 uppercase tracking-widest">Task Console</div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="text-2xl font-black text-white uppercase tracking-tighter">Live</div>
                            <a href="{{ route('tasks.student.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-2 rounded-lg transition transform group-hover:scale-110">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-all"></div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100 p-8">
                <div class="mb-10 p-8 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight flex items-center">
                                <span class="w-3 h-3 bg-blue-600 rounded-full mr-3 animate-pulse"></span> Daily Attendance
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 font-medium">Verify your presence for the current session.</p>
                        </div>
                        
                        @if($hasMarkedToday)
                            <div class="px-6 py-3 bg-white border border-yellow-200 text-yellow-700 font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-sm">
                                ✅ Recorded for Today ({{ date('M d') }})
                            </div>
                        @else
                            <form action="{{ route('attendance.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-gray-900 hover:bg-blue-600 text-white font-black text-xs uppercase tracking-[0.2em] py-4 px-10 rounded-2xl shadow-xl transition transform hover:-translate-y-1 active:scale-95">
                                    Mark Presence
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="p-8 bg-gray-50/50 rounded-[2rem] border border-gray-100 mb-12">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.3em] mb-8">Request Leave</h3>
                    <form action="{{ route('leave.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="relative">
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Start Date</label>
                                <input type="date" name="start_date" id="start_date" required class="w-full border-gray-100 bg-white rounded-2xl py-3 px-5 focus:ring-orange-500 focus:border-orange-500 shadow-sm font-bold text-gray-700">
                            </div>
                            <div class="relative">
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">End Date</label>
                                <input type="date" name="end_date" id="end_date" required class="w-full border-gray-100 bg-white rounded-2xl py-3 px-5 focus:ring-orange-500 focus:border-orange-500 shadow-sm font-bold text-gray-700">
                            </div>
                            <div class="relative">
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-1">Justification</label>
                                <textarea name="reason" required class="w-full border-gray-100 bg-white rounded-2xl py-3 px-5 focus:ring-orange-500 focus:border-orange-500 shadow-sm font-bold text-gray-700" rows="1" placeholder="Reason..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="mt-8 bg-orange-500 hover:bg-orange-600 text-white font-black text-[10px] uppercase tracking-widest py-3 px-8 rounded-2xl shadow-lg shadow-orange-100 transition transform active:scale-95">
                            Send Request
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 border-b pb-4">Attendance Log</h3>
                        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50/50 text-gray-400 uppercase text-[9px] font-black tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Timestamp</th>
                                        <th class="px-6 py-4 text-left">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($attendances as $attendance)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="px-6 py-4 font-bold text-gray-700">{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-gray-400 font-mono text-[10px] uppercase">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="px-6 py-10 text-center text-gray-300 font-bold italic">No records initialized.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 border-b pb-4">Leave History</h3>
                        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50/50 text-gray-400 uppercase text-[9px] font-black tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Period</th>
                                        <th class="px-6 py-4 text-center">Qty</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($leaveRequests as $leave)
                                        @php
                                            $start = \Carbon\Carbon::parse($leave->start_date);
                                            $end = \Carbon\Carbon::parse($leave->end_date);
                                            $totalDays = $start->diffInDays($end) + 1;
                                            $isSingleDay = $leave->start_date === $leave->end_date;
                                        @endphp
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-700">
                                                    @if($isSingleDay) {{ $start->format('M d, Y') }}
                                                    @else {{ $start->format('M d') }} - {{ $end->format('M d, Y') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center font-black text-gray-600 text-[10px]">
                                                {{ (int)$totalDays }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border
                                                    {{ $leave->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($leave->status == 'rejected' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-amber-50 text-amber-600 border-amber-100') }}">
                                                    {{ $leave->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-6 py-10 text-center text-gray-300 font-bold italic">No requests logged.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
    </script>
</x-app-layout>