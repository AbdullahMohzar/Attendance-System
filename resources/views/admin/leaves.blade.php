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
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm flex items-center">
                    <span class="mr-2">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Leave Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status/Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($allLeaves as $leave)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $leave->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $leave->user->email }}</div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                            {{ \Carbon\Carbon::parse($leave->leave_date)->format('M d, Y') }}
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-gray-600 italic">
                                            "{{ $leave->reason }}"
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $status = trim(strtolower($leave->status));
                                            @endphp

                                            @if($status == 'pending')
                                                <form action="{{ route('admin.leaves.status', $leave->id) }}" method="POST" class="space-y-2">
                                                    @csrf
                                                    <textarea name="admin_comment" placeholder="Add a comment (optional)..." 
                                                        class="w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                        rows="1"></textarea>
                                                    
                                                    <div class="flex justify-center space-x-3">
                                                        <button type="submit" name="status" value="approved"
                                                                style="background-color: #16a34a !important; color: white !important;" 
                                                                class="inline-flex items-center px-4 py-1.5 border border-transparent text-xs font-bold rounded-md shadow-sm transition hover:opacity-90">
                                                            Approve
                                                        </button>

                                                        <button type="submit" name="status" value="rejected"
                                                                style="background-color: #dc2626 !important; color: white !important;" 
                                                                class="inline-flex items-center px-4 py-1.5 border border-transparent text-xs font-bold rounded-md shadow-sm transition hover:opacity-90">
                                                            Reject
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="flex flex-col items-center">
                                                    @if($status == 'approved')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200 uppercase">
                                                            Approved
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 uppercase">
                                                            Rejected
                                                        </span>
                                                    @endif
                                                    
                                                    @if($leave->admin_comment)
                                                        <p class="mt-1 text-xs text-gray-500 italic">"{{ $leave->admin_comment }}"</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                            No leave requests found in the system.
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