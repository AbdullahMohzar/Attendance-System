<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Performance & Grading') }}
            </h2>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-bold shadow-md no-print transition transform hover:-translate-y-1">
                Download Official Report
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 no-print">
                <a href="{{ route('admin.reports') }}" 
                   class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Adjust Report Filters
                </a>
                <div class="px-4 py-2 bg-white border shadow-sm rounded-lg text-gray-600 text-sm">
                    Analysis Period: <span class="font-bold text-gray-900">{{ $startDate }} to {{ $endDate }}</span> 
                    <span class="mx-2 text-gray-300">|</span> 
                    Range: <span class="font-bold text-indigo-600">{{ $totalDays }} Working Days (Mon-Fri)</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($students as $student)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        
                        <div class="p-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-xl font-black text-gray-900 truncate">{{ $student->name }}</h4>
                                    <p class="text-xs text-gray-400 font-medium italic">{{ $student->email }}</p>
                                </div>
                                <div class="flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-sm border border-gray-100 ring-4 ring-gray-50">
                                    <span class="text-3xl font-black {{ $student->grade_color }}">{{ $student->grade }}</span>
                                    <span class="text-[8px] uppercase tracking-tighter text-gray-400 font-bold">Grade</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="flex justify-between items-end mb-3">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Attendance Score</span>
                                <span class="text-2xl font-black text-indigo-700">{{ $student->attendance_percentage }}%</span>
                            </div>
                            
                            <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden shadow-inner p-1">
                                <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r from-indigo-500 to-purple-600" 
                                     style="width: {{ $student->attendance_percentage }}%"></div>
                            </div>

                            <div class="grid grid-cols-3 gap-3 mt-8">
                                <div class="text-center p-3 bg-blue-50 rounded-2xl border border-blue-100">
                                    <span class="block text-[10px] font-bold text-blue-400 uppercase">Present</span>
                                    <span class="text-lg font-black text-blue-700">{{ $student->present_count }}</span>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-2xl border border-green-100">
                                    <span class="block text-[10px] font-bold text-green-400 uppercase">Leave</span>
                                    <span class="text-lg font-black text-green-700">{{ $student->leave_count }}</span>
                                </div>
                                <div class="text-center p-3 bg-red-50 rounded-2xl border border-red-100">
                                    <span class="block text-[10px] font-bold text-red-400 uppercase">Absent</span>
                                    <span class="text-lg font-black text-red-700">{{ $student->absent_count }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-between items-center border-t border-gray-100">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Performance Status</span>
                            <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white shadow-sm border border-gray-100 {{ $student->grade_color }}">
                                {{ $student->attendance_percentage >= 75 ? 'Excellent' : ($student->attendance_percentage >= 60 ? 'Satisfactory' : 'Critical - Review Required') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 pt-6 border-t border-gray-200 flex flex-col md:flex-row justify-between text-[10px] text-gray-400 uppercase font-bold tracking-widest">
                <p>Generated by Attendance Management System v1.0</p>
                <p>Scale: A (90%+), B (75%+), C (60%+), D (Below 60%) | Calculation based on Weekdays Only</p>
            </div>
        </div>
    </div>
    
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .shadow-sm, .shadow-xl { shadow: none !important; }
            .bg-gray-50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</x-app-layout>