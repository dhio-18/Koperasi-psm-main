@props(['data'])

<div x-data="pagination(@json($data))"
    class="bg-gray-50 px-4 sm:px-6 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <!-- Select page size -->
    <div class="order-1 sm:order-1 flex items-center justify-center sm:justify-start gap-2">
        <label for="page-size" class="sr-only">Item per halaman</label>
        <div class="relative">
            <select id="page-size" x-model.number="itemsPerPage" @change="changePageSize"
                class="w-20 sm:w-24 appearance-none bg-white border border-gray-300 rounded-lg py-2 pl-3 pr-8
                     focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-xs sm:text-sm">
                <option value="3">3</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Navigasi halaman -->
    <div class="order-2 sm:order-2 flex items-center justify-center sm:justify-end gap-2">
        <!-- Tombol Sebelumnya -->
        <button @click="previousPage" :disabled="currentPage === 1"
            class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm
             shadow-sm transition-colors
             disabled:text-gray-300 disabled:cursor-not-allowed
             hover:bg-green-600 hover:text-white disabled:hover:bg-transparent disabled:hover:text-gray-300">
            Sebelumnya
        </button>

        <!-- Nomor Halaman -->
        <div class="flex items-center gap-1 sm:gap-2">
            <!-- Halaman Pertama -->
            <template x-if="shouldShowFirstPage">
                <button @click="goToPage(1)" x-text="1"
                    :class="1 === currentPage ?
                        'bg-green-600 text-white' :
                        'hover:bg-green-600 hover:text-white text-gray-700'"
                    class="appearance-none border border-gray-300 rounded-lg px-2.5 sm:px-3 py-1 text-xs sm:text-sm shadow-sm transition-colors">
                </button>
            </template>

            <!-- Ellipsis Kiri -->
            <template x-if="shouldShowLeftEllipsis">
                <span class="px-1 text-gray-500 text-xs sm:text-sm">...</span>
            </template>

            <!-- Halaman Tengah -->
            <template x-for="page in visiblePages" :key="page">
                <button @click="goToPage(page)" x-text="page"
                    :class="page === currentPage ?
                        'bg-green-600 text-white' :
                        'hover:bg-green-600 hover:text-white text-gray-700'"
                    class="appearance-none border border-gray-300 rounded-lg px-2.5 sm:px-3 py-1 text-xs sm:text-sm shadow-sm transition-colors">
                </button>
            </template>

            <!-- Ellipsis Kanan -->
            <template x-if="shouldShowRightEllipsis">
                <span class="px-1 text-gray-500 text-xs sm:text-sm">...</span>
            </template>

            <!-- Halaman Terakhir -->
            <template x-if="shouldShowLastPage">
                <button @click="goToPage(totalPages)" x-text="totalPages"
                    :class="totalPages === currentPage ?
                        'bg-green-600 text-white' :
                        'hover:bg-green-600 hover:text-white text-gray-700'"
                    class="appearance-none border border-gray-300 rounded-lg px-2.5 sm:px-3 py-1 text-xs sm:text-sm shadow-sm transition-colors">
                </button>
            </template>
        </div>

        <!-- Tombol Selanjutnya -->
        <button @click="nextPage" :disabled="currentPage === totalPages"
            class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm
             shadow-sm transition-colors
             disabled:text-gray-300 disabled:cursor-not-allowed
             hover:bg-green-600 hover:text-white disabled:hover:bg-transparent disabled:hover:text-gray-300">
            Selanjutnya
        </button>
    </div>
</div>

{{-- Hilangkan scrollbar di mobile --}}
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pagination', (items) => ({
            totalItems: items.length,
            itemsPerPage: 3,
            currentPage: 1,
            maxVisiblePages: window.innerWidth < 640 ? 3 : 5, // 3 untuk mobile, 5 untuk desktop

            init() {
                // Update maxVisiblePages saat resize window
                window.addEventListener('resize', () => {
                    this.maxVisiblePages = window.innerWidth < 640 ? 3 : 5;
                });
            },

            get totalPages() {
                return Math.ceil(this.totalItems / this.itemsPerPage);
            },

            get visiblePages() {
                const total = this.totalPages;
                const current = this.currentPage;
                const max = this.maxVisiblePages;

                // Jika total halaman <= max, tampilkan semua
                if (total <= max) {
                    return Array.from({
                        length: total
                    }, (_, i) => i + 1);
                }

                // Jika halaman saat ini di awal
                if (current <= Math.ceil(max / 2)) {
                    return Array.from({
                        length: max - 1
                    }, (_, i) => i + 1);
                }

                // Jika halaman saat ini di akhir
                if (current >= total - Math.floor(max / 2)) {
                    return Array.from({
                        length: max - 1
                    }, (_, i) => total - (max - 2) + i);
                }

                // Jika halaman saat ini di tengah
                const start = current - Math.floor((max - 2) / 2);
                return Array.from({
                    length: max - 2
                }, (_, i) => start + i);
            },

            get shouldShowFirstPage() {
                return this.totalPages > this.maxVisiblePages && !this.visiblePages.includes(1);
            },

            get shouldShowLastPage() {
                return this.totalPages > this.maxVisiblePages && !this.visiblePages.includes(this.totalPages);
            },

            get shouldShowLeftEllipsis() {
                return this.shouldShowFirstPage && this.visiblePages[0] > 2;
            },

            get shouldShowRightEllipsis() {
                return this.shouldShowLastPage && this.visiblePages[this.visiblePages.length - 1] < this.totalPages -
                    1;
            },

            previousPage() {
                if (this.currentPage > 1) this.currentPage--;
            },

            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },

            goToPage(page) {
                this.currentPage = page;
            },

            changePageSize() {
                this.currentPage = 1;
            }
        }));
    });
</script>
