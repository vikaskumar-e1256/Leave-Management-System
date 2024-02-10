<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Balances') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="leave-balances-container">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                <section>
                                    <header>
                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Leave Balances') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('View your leave balances for different months.') }}
                                        </p>
                                    </header>

                                    <div class="mt-6 space-y-6">
                                        @foreach ($leaveBalances as $year => $yearData)
                                            @foreach ($yearData as $monthName => $monthLeaves)
                                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                                                    <h3 class="text-lg font-semibold mb-2">{{ $monthName }}</h3>
                                                    <ul>
                                                        @forelse ($monthLeaves as $leaveBalance)
                                                            <li class="flex justify-between mb-2">
                                                                <span>{{ $leaveBalance->leaveType->name }}</span>
                                                                <span>{{ round($leaveBalance->allocated_amount, 1) }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="text-gray-500">No leave balances found for the
                                                                selected month.</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @endforeach
                                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                                            <h2 class="text-lg font-semibold mb-2">Total Leave Balances {{ $currentFinancialYear }}</h2>
                                            <div class="flex justify-between mb-6">
                                                <div class="w-1/2 pr-4">
                                                    <h3 class="text-md font-semibold mb-2">Earned Leaves</h3>
                                                    <ul>
                                                        <li class="flex justify-between mb-2">
                                                            <span>PL</span>
                                                            <span>{{ round($totalEarnedPL, 2) }}</span>
                                                        </li>
                                                        <li class="flex justify-between mb-2">
                                                            <span>CL</span>
                                                            <span>{{ round($totalEarnedCL, 2) }}</span>
                                                        </li>
                                                        <li class="flex justify-between mb-2">
                                                            <span>SL</span>
                                                            <span>{{ round($totalEarnedSL, 2) }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="w-1/2 pl-4">
                                                    <h3 class="text-md font-semibold mb-2">Used Leaves</h3>
                                                    <ul>
                                                        <li class="flex justify-between mb-2">
                                                            <span>PL</span>
                                                            <span>{{ round($totalUsedPL, 2) }}</span>
                                                        </li>
                                                        <li class="flex justify-between mb-2">
                                                            <span>CL</span>
                                                            <span>{{ round($totalUsedCL, 2) }}</span>
                                                        </li>
                                                        <li class="flex justify-between mb-2">
                                                            <span>SL</span>
                                                            <span>{{ round($totalUsedSL, 2) }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
