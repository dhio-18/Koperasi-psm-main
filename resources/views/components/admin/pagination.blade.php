@props(['data'])

<div
    x-data="pagination(() => {{ $data }})"
    class="bg-gray-50 px-4 sm:px-6 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

    <!-- Select Page Size -->
    <div class="order-1 flex items-center justify-center sm:justify-start gap-2">
        <label for="page-size" class="sr-only">Item per halaman</label>

        <div class="relative">
            <select
                id="page-size"
                x-model.number="itemsPerPage"
                @change="changePageSize"
                class="w-20 sm:w-24 appearance-none bg-white border border-gray-300 rounded-lg py-2 pl-3 pr-8
                       focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-xs sm:text-sm"
            >
                <option value="3">3</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Pagination Buttons -->
    <div class="order-2 flex items-center justify-center sm:justify-end gap-2">

        <!-- Prev -->
        <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm shadow-sm
                   disabled:text-gray-300 disabled:cursor-not-allowed
                   hover:bg-green-600 hover:text-white transition-colors"
        >
            Sebelumnya
        </button>

        <!-- Page Numbers -->
        <div class="flex items-center gap-1 sm:gap-2">

            <!-- First Page -->
            <template x-if="shouldShowFirstPage">
                <button
                    @click="goToPage(1)"
                    x-text="1"
                    :class="buttonClass(1)"
                    class="appearance-none border border-gray-300 rounded-lg px-2.5 sm:px-3 py-1 text-xs sm:text-sm shadow-sm"
                ></button>
            </template>

            <!-- Left Ellipsis -->
            <template x-if="shouldShowLeftEllipsis">
                <span class="px-1 text-gray-500 text-xs sm:text-sm">...</span>
            </template>

            <!-- Visible Pages -->
            <template x-for="page in visiblePages" :key="page">
                <button
                    @click="goToPage(page)"
                    x-text="page"
                    :class="buttonClass(page)"
                    class="appearance-none border border-gray-300 rounded-lg px-2.5 sm:px-3 py-1 text-xs sm:text-sm shadow-sm"
                ></button>
            </template>

            <!-- Right Ellipsis -->
            <template x-if="shouldShowRightEllipsis">
                <span class="px-1 text-gray-500 text-xs sm:text-sm">...</span>
            </template>

            <!-- Last Page -->
            <template x-if="shouldShowLastPage">
                <button
                    @click="goToPage(totalPages)"
                    x-text="totalPages"
                    :class="buttonClass(totalPages)"
                    class="appearance-none border border-gray-300 rounded-lg px-2.5 sm:px-3 py-1 text-xs sm:text-sm shadow-sm"
                ></button>
            </template>

        </div>

        <!-- Next -->
        <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm shadow-sm
                   disabled:text-gray-300 disabled:cursor-not-allowed
                   hover:bg-green-600 hover:text-white transition-colors"
        >
            Selanjutnya
        </button>

    </div>
</div>

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
    Alpine.data('pagination', (itemsFn) => ({

        /* States */
        currentPage: 1,
        itemsPerPage: 5,
        maxVisiblePages: window.innerWidth < 640 ? 3 : 5,

        init() {
            // Responsive page count
            window.addEventListener('resize', () => {
                this.maxVisiblePages = window.innerWidth < 640 ? 3 : 5;
            });
        },

        /* Reactive total items */
        get totalItems() {
            return itemsFn().length;
        },

        /* Total pages */
        get totalPages() {
            return Math.ceil(this.totalItems / this.itemsPerPage) || 1;
        },

        /* Visible pages */
        get visiblePages() {
            const total = this.totalPages;
            const current = this.currentPage;
            const max = this.maxVisiblePages;

            if (total <= max) {
                return Array.from({ length: total }, (_, i) => i + 1);
            }

            if (current <= Math.ceil(max / 2)) {
                return Array.from({ length: max - 1 }, (_, i) => i + 2);
            }

            if (current >= total - Math.floor(max / 2)) {
                return Array.from({ length: max - 1 }, (_, i) => total - (max - 2) + i);
            }

            const start = current - Math.floor((max - 2) / 2);
            return Array.from({ length: max - 2 }, (_, i) => start + i);
        },

        /* Helpers */
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
            const last = this.visiblePages[this.visiblePages.length - 1];
            return this.shouldShowLastPage && last < this.totalPages - 1;
        },

        /* Pagination Methods */
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
        },

        /* Dynamic button class */
        buttonClass(page) {
            return page === this.currentPage
                ? 'bg-green-600 text-white'
                : 'hover:bg-green-600 hover:text-white text-gray-700';
        }

    }));
});
</script>
