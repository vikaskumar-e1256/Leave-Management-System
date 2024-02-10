<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Leave') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Success</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-green-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('leave.apply') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="leave_type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Leave Type') }}</label>
                            <select id="leave_type" class="form-select rounded-md shadow-sm mt-1 block w-full" name="leave_type_id" required>
                                <option value="" selected disabled>Select Leave Type</option>
                                @foreach ($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Start Date') }}</label>
                            <input id="start_date" type="date" class="form-input rounded-md shadow-sm mt-1 block w-full" name="start_date" required>
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('End Date') }}</label>
                            <input id="end_date" type="date" class="form-input rounded-md shadow-sm mt-1 block w-full" name="end_date" required>
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Reason') }}</label>
                            <textarea id="reason" class="form-textarea rounded-md shadow-sm mt-1 block w-full" name="reason" rows="4" required></textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
