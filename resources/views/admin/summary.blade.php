<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight uppercase tracking-tighter">
                     <span class="text-indigo-600">Global System Monitor</span>
                </h2>
                
            </div>
            
            <div class="flex items-center px-4 py-2 bg-white rounded-2xl shadow-sm border border-gray-100">
                <span class="flex h-3 w-3 relative mr-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $todayStats['whatsapp_online'] ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $todayStats['whatsapp_online'] ? 'bg-green-500' : 'bg-red-500' }}"></span>
                </span>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-400 uppercase leading-none">WhatsApp API</span>
                    <span class="text-xs font-bold {{ $todayStats['whatsapp_online'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $todayStats['whatsapp_online'] ? 'CONNECTED' : 'OFFLINE' }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                @php
                    $cards = [
                        ['label' => 'Total Population', 'value' => $stats['total_users'], 'color' => 'indigo'],
                        ['label' => 'Pending Access', 'value' => $stats['pending_approvals'], 'color' => 'red'],
                        ['label' => 'Faculty', 'value' => $stats['total_teachers'], 'color' => 'emerald'],
                        ['label' => 'Students', 'value' => $stats['total_students'], 'color' => 'blue'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-lg hover:-translate-y-1">
                    <p class="text-[10px] font-black text-{{ $card['color'] }}-500 uppercase tracking-widest mb-2">{{ $card['label'] }}</p>
                    <p class="text-4xl font-black text-gray-800">{{ $card['value'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white">
                        <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest flex items-center">
                            <span class="p-2 bg-amber-100 text-amber-600 rounded-lg mr-3">🏆</span>
                            Student Performance
                        </h3>
                    </div>
                    
                    <div class="p-4">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <th class="px-6 pb-2">Student Information</th>
                                    <th class="px-6 pb-2 text-right">Attendance Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topStudents as $student)
                                <tr class="group hover:bg-indigo-50/50 transition-colors">
                                    <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white rounded-l-2xl transition-colors">
                                        <div class="flex items-center">
                                            <img class="h-10 w-10 rounded-full border-2 border-white shadow-sm mr-4" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=EEF2FF&color=4F46E5&bold=true" 
                                                 alt="{{ $student->name }}">
                                            <div>
                                                <div class="text-sm font-black text-gray-800">{{ $student->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white rounded-r-2xl transition-colors text-right">
                                        <div class="flex items-center justify-end">
                                            <span class="text-sm font-black text-indigo-600 mr-3">{{ $student->attendances_count }}</span>
                                            <div class="w-24 bg-gray-200 rounded-full h-1.5 overflow-hidden hidden md:block">
                                                <div class="bg-indigo-500 h-full rounded-full" style="width: {{ min(100, ($student->attendances_count / 22) * 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100 p-8 border border-indigo-50 overflow-hidden">
                        <h3 class="text-sm font-black text-indigo-900 mb-6 uppercase tracking-widest">Gatekeeper</h3>
                        <a href="{{ route('hr.pending') }}" class="flex items-center justify-between w-full bg-indigo-600 p-5 rounded-2xl shadow-lg shadow-indigo-300 hover:bg-indigo-700 transition-all active:scale-95 group">
                            <div class="flex items-center text-white">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                <span class="font-black text-md">Approvals</span>
                            </div>
                            @if($stats['pending_approvals'] > 0)
                                <div class="bg-red-500 text-white text-[10px] h-6 w-6 flex items-center justify-center rounded-lg font-black animate-bounce">
                                    {{ $stats['pending_approvals'] }}
                                </div>
                            @endif
                        </a>
                    </div>

                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 space-y-4">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Management</h3>
                        
                        <a href="{{ route('admin.users.index') }}" class="flex items-center group p-4 rounded-2xl hover:bg-gray-50 transition-colors">
                            <div class="p-2 bg-indigo-50 text-indigo-500 rounded-lg mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Global Directory</span>
                        </a>

                        <a href="{{ route('admin.reports') }}" class="flex items-center group p-4 rounded-2xl hover:bg-gray-50 transition-colors">
                            <div class="p-2 bg-purple-50 text-purple-500 rounded-lg mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Audit Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>