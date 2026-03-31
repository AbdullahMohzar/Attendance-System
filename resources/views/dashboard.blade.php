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
                <a href="{{ route('tasks.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-1 rounded text-xs font-bold transition shadow-sm">
                    View My Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="toast" class="fixed top-5 right-5 bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl z-50 flex items-center border-b-4 border-green-800 transition-all duration-500">
                    <span class="mr-2">✓</span> {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => { 
                        const toast = document.getElementById('toast');
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 500);
                    }, 3000);
                </script>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-600">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-sm font-bold text-purple-600 uppercase tracking-tight">Current Grade</div>
                            <div class="text-4xl font-black {{ $color ?? 'text-gray-800' }}">{{ $grade ?? 'N/A' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Score</div>
                            <div class="text-lg font-bold text-gray-700">{{ round($percentage ?? 0) }}%</div>
                            <p class="text-[9px] text-gray-400 font-bold italic">
                                ({{ $attendances->where('attendance_date', '>=', now()->startOfMonth()->toDateString())->count() }} / {{ $workdaysCount ?? 0 }} Workdays)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-bold text-blue-600 uppercase">Total Attendance</div>
                    <div class="text-3xl font-black text-gray-800">{{ $attendances->count() }} Days</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-bold text-green-600 uppercase">Approved Leaves</div>
                    <div class="text-3xl font-black text-gray-800">{{ $leaveRequests->where('status', 'approved')->count() }} Days</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="text-sm font-bold text-orange-600 uppercase">Active Tasks</div>
                    <div class="text-3xl font-black text-gray-800">Check Console</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-10 p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                        <span class="mr-2 text-blue-600">●</span> Daily Attendance
                    </h3>
                    @if($hasMarkedToday)
                        <div class="p-4 bg-white border-l-4 border-yellow-400 text-yellow-700 shadow-sm rounded-r-lg">
                            <strong>Note:</strong> Your attendance for today ({{ date('M d, Y') }}) is already recorded.
                        </div>
                    @else
                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                                Mark Attendance for Today
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mb-10 p-6 bg-purple-50 rounded-2xl border border-purple-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-purple-900">Task Submission Center</h3>
                        <p class="text-sm text-purple-600">View assignments and submit your work for review.</p>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="bg-purple-600 text-white px-6 py-2 rounded-xl font-bold shadow-md hover:bg-purple-700 transition">
                        Enter Task Console
                    </a>
                </div>

                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 mb-12">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Request Leave</h3>
                    <form action="{{ route('leave.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-1">Leave Date</label>
                                <input type="date" name="leave_date" required class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 mb-1">Reason</label>
                                <textarea name="reason" required class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500" rows="1"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-xl shadow transition">
                            Submit Request
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-bold mb-4 text-gray-900 border-b pb-2">Attendance History</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white text-sm">
                                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                                    <tr>
                                        <th class="px-4 py-2 border-b text-left">Date</th>
                                        <th class="px-4 py-2 border-b text-left">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                        <tr class="hover:bg-gray-50 border-b last:border-0">
                                            <td class="px-4 py-2">{{ $attendance->attendance_date }}</td>
                                            <td class="px-4 py-2 text-gray-600 font-mono text-xs">{{ $attendance->check_in_time }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400 italic">No attendance records yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold mb-4 text-gray-900 border-b pb-2">Leave Status</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white text-sm">
                                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                                    <tr>
                                        <th class="px-4 py-2 border-b text-left">Date</th>
                                        <th class="px-4 py-2 border-b text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leaveRequests as $leave)
                                        <tr class="hover:bg-gray-50 border-b last:border-0">
                                            <td class="px-4 py-2">{{ $leave->leave_date }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                    {{ $leave->status == 'approved' ? 'bg-green-100 text-green-700' : ($leave->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                                    {{ $leave->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400 italic">No leave requests found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>