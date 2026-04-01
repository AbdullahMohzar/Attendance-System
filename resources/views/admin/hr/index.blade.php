<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                Pending <span class="text-orange-600">HR Approvals</span>
            </h2>
            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                Verification Queue
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="toast" class="mb-6 p-4 bg-green-600 text-white rounded-2xl shadow-lg shadow-green-100 flex items-center animate-in slide-in-from-top-4 duration-300">
                    <span class="mr-3 bg-white/20 p-1 rounded-full text-xs">✓</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <script>setTimeout(() => document.getElementById('toast')?.remove(), 3000);</script>
            @endif

            <form action="{{ route('hr.bulkApprove') }}" method="POST" id="bulk-approval-form">
                @csrf
                
                <div id="bulk-action-bar" class="hidden sticky top-4 z-50 mb-8 p-4 bg-gray-900 rounded-[2rem] shadow-2xl flex justify-between items-center border border-gray-800 animate-in zoom-in-95 duration-200">
                    <div class="flex items-center ml-4">
                        <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse mr-3"></div>
                        <span class="text-white text-[10px] font-black uppercase tracking-widest">
                            <span id="selected-count" class="text-orange-500 text-sm mr-1">0</span> Students Selected for Verification
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="document.getElementById('bulk-approval-form').submit()" class="bg-orange-600 hover:bg-orange-500 text-white px-8 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition transform hover:scale-105 active:scale-95 shadow-lg shadow-orange-900/20">
                            Approve Selected
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-5 text-left w-10">
                                        <input type="checkbox" id="select-all" class="rounded-lg border-gray-300 text-orange-600 focus:ring-orange-500 w-5 h-5 transition cursor-pointer">
                                    </th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">User Details</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Role</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                                    <th class="px-6 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Single Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($pendingUsers as $user)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-checkbox rounded-lg border-gray-300 text-orange-600 focus:ring-orange-500 w-5 h-5 transition cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-gray-900 group-hover:text-orange-600 transition-colors">{{ $user->name }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-[9px] font-black rounded-xl bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-widest">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-[11px] font-mono font-bold text-gray-500 tracking-tighter">
                                            {{ $user->phone ?? 'NO PHONE' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button type="button" onclick="event.preventDefault(); document.getElementById('single-approve-{{ $user->id }}').submit();" 
                                                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition shadow-sm">
                                                    Verify
                                                </button>
                                                
                                                <button type="button" onclick="event.preventDefault(); if(confirm('Delete this registration?')) document.getElementById('single-delete-{{ $user->id }}').submit();" 
                                                        class="bg-white border border-rose-100 text-rose-500 hover:bg-rose-50 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition">
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pendingUsers->isEmpty())
                        <div class="text-center py-20 bg-white">
                            <div class="relative inline-block mb-4">
                                <div class="absolute inset-0 bg-orange-100 rounded-full blur-2xl opacity-50"></div>
                                <svg class="relative mx-auto h-16 w-16 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-[0.2em]">Queue Empty</h3>
                            <p class="mt-1 text-[10px] text-gray-400 font-bold uppercase tracking-widest">All student registrations have been processed.</p>
                        </div>
                    @endif
                </div>
            </form>

            @foreach($pendingUsers as $user)
                <form id="single-approve-{{ $user->id }}" action="{{ route('hr.approve', $user->id) }}" method="POST" class="hidden">@csrf</form>
                <form id="single-delete-{{ $user->id }}" action="{{ route('admin.students.destroy', $user->id) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>

    <script>
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const actionBar = document.getElementById('bulk-action-bar');
        const selectedCountDisp = document.getElementById('selected-count');

        function updateActionBar() {
            const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
            selectedCountDisp.textContent = checkedCount;
            
            if (checkedCount > 0) {
                actionBar.classList.remove('hidden');
                actionBar.classList.add('flex');
            } else {
                actionBar.classList.add('hidden');
                actionBar.classList.remove('flex');
            }
        }

        selectAll.addEventListener('change', (e) => {
            checkboxes.forEach(cb => cb.checked = e.target.checked);
            updateActionBar();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateActionBar);
        });
    </script>
</x-app-layout>