<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Leave Requests') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm flex items-center rounded-r-xl animate-in fade-in slide-in-from-top-4 duration-300">
                    <span class="mr-2">✅</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Student</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Leave Period</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Days</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Action / Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($allLeaves as $leave)
                                    @php
                                        $start = \Carbon\Carbon::parse($leave->start_date);
                                        $end = \Carbon\Carbon::parse($leave->end_date);
                                        
                                        // ENSURE WHOLE NUMBER: diffInDays + 1 cast to int
                                        $totalDays = (int) ($start->diffInDays($end) + 1);
                                        
                                        $isSingleDay = $leave->start_date === $leave->end_date;
                                        $status = trim(strtolower($leave->status));
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-black text-gray-900">{{ $leave->user->name }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">{{ $leave->user->email }}</div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">
                                            @if($isSingleDay)
                                                {{ $start->format('M d, Y') }}
                                            @else
                                                <span class="text-indigo-600">{{ $start->format('M d') }}</span> 
                                                <span class="mx-1 text-gray-300">→</span> 
                                                <span class="text-indigo-600">{{ $end->format('M d, Y') }}</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="bg-gray-100 text-gray-700 text-[10px] font-black px-3 py-1 rounded-lg">
                                                {{ $totalDays }} {{ Str::plural('Day', $totalDays) }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-gray-500 italic">
                                            "{{ Str::limit($leave->reason, 40) }}"
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($status == 'pending')
                                                <form action="{{ route('admin.leaves.status', $leave->id) }}" method="POST" class="flex flex-col space-y-2">
                                                    @csrf
                                                    <input type="text" name="admin_comment" placeholder="Optional feedback..." 
                                                        class="text-[10px] border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50">
                                                    
                                                    <div class="flex justify-center space-x-2">
                                                        <button type="submit" name="status" value="approved"
                                                            class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-emerald-100 transition">
                                                            Approve
                                                        </button>

                                                        <button type="submit" name="status" value="rejected"
                                                            class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-rose-100 transition">
                                                            Reject
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="flex flex-col items-center">
                                                    <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border
                                                        {{ $status == 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                    @if($leave->admin_comment)
                                                        <p class="mt-2 text-[10px] text-gray-400 italic">"{{ $leave->admin_comment }}"</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center">
                                            <div class="text-gray-300 font-black uppercase tracking-[0.3em] text-xs">No Leave Requests Found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>