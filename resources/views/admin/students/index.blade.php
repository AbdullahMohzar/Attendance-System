<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Management Console</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4 text-left">Student</th>
                            <th class="px-6 py-4 text-center">Attendance</th>
                            <th class="px-6 py-4 text-center">Leaves</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $student->name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $student->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600">{{ $student->attendances_count }} Days</td>
                            <td class="px-6 py-4 text-center font-bold text-green-600">{{ $student->approved_leaves_count }} Approved</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('admin.students.attendance', $student->id) }}" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[10px] font-bold uppercase hover:bg-blue-600 hover:text-white transition">Logs</a>
                                
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Permanently delete this student?')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-[10px] font-bold uppercase hover:bg-red-600 hover:text-white transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>