@if (session('success'))
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="successModal">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm mx-4">
            <!-- Success Icon -->
            <div class="flex justify-center mb-4">
                <div class="rounded-full bg-green-100 p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Berhasil!</h3>
                <p class="text-gray-600 mb-4">{{ session('success') }}</p>

                <!-- Close Button -->
                <button onclick="closeSuccessModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        // Auto close after 3 seconds
        setTimeout(function () {
            closeSuccessModal();
        }, 3000);

        function closeSuccessModal() {
            document.getElementById('successModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('successModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeSuccessModal();
            }
        });
    </script>
@endif
