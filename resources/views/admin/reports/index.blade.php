<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Generate Attendance Report</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow rounded-lg">
                <form action="{{ route('admin.reports.generate') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">From Date</label>
                            <input type="date" name="start_date" required class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">To Date</label>
                            <input type="date" name="end_date" required class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700">Student Filter</label>
                        <select name="student_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">All Students (System-wide)</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" style="background-color: #2563eb !important; color: white !important;" 
                            class="w-full py-3 rounded font-bold shadow hover:bg-blue-700 transition">
                        Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>