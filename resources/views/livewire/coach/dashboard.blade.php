<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Coach Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-600">Total Sessions</h2>
            <p class="text-2xl font-bold">{{ $sessionCount }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-600">Total Bookings</h2>
            <p class="text-2xl font-bold">{{ $totalBookings }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-600">Approved Bookings</h2>
            <p class="text-2xl font-bold text-green-600">{{ $approved }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-600">Pending Bookings</h2>
            <p class="text-2xl font-bold text-yellow-500">{{ $pending }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-600">Rejected Bookings</h2>
            <p class="text-2xl font-bold text-red-500">{{ $rejected }}</p>
        </div>
    </div>
</div>

