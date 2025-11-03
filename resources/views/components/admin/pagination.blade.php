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
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar max-w-[60vw] sm:max-w-none px-1">
            <template x-for="page in totalPages" :key="page">
                <button @click="goToPage(page)" x-text="page"
                    :class="page === currentPage ?
                        'bg-green-600 text-white' :
                        'hover:bg-green-600 hover:text-white text-gray-700'"
                    class="whitespace-nowrap appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm shadow-sm transition-colors">
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

            get totalPages() {
                return Math.ceil(this.totalItems / this.itemsPerPage);
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
