<!-- Add Item Modal -->
<div id="addItemModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-purple-600 dark:text-purple-400">Add New Inventory Item</h3>
                <button onclick="closeAddItemModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('inventory.store') }}" method="POST" class="mt-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Item Name -->
                    <div>
                        <label for="modal_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Name *</label>
                        <input type="text" id="modal_name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Enter item name">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="modal_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <input type="text" id="modal_category" name="category"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                               placeholder="e.g., Medications, Supplies, Equipment">
                    </div>

                    <!-- Initial Quantity -->
                    <div>
                        <label for="modal_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Initial Quantity *</label>
                        <input type="number" id="modal_quantity" name="quantity" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                               placeholder="0">
                    </div>

                    <!-- Unit of Measure -->
                    <div>
                        <label for="modal_uom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit of Measure *</label>
                        <select id="modal_uom" name="uom" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Unit</option>
                            <option value="pieces">Pieces</option>
                            <option value="boxes">Boxes</option>
                            <option value="bottles">Bottles</option>
                            <option value="vials">Vials</option>
                            <option value="tablets">Tablets</option>
                            <option value="capsules">Capsules</option>
                            <option value="ml">Milliliters (ml)</option>
                            <option value="liters">Liters</option>
                            <option value="grams">Grams</option>
                            <option value="kg">Kilograms</option>
                        </select>
                    </div>

                    <!-- Unit Cost -->
                    <div>
                        <label for="modal_unit_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit Cost (TZS) *</label>
                        <input type="number" id="modal_unit_cost" name="unit_cost" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                               placeholder="0.00">
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label for="modal_reorder_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reorder Level *</label>
                        <input type="number" id="modal_reorder_level" name="reorder_level" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Minimum stock level">
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="modal_expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                        <input type="date" id="modal_expiry_date" name="expiry_date"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Supplier -->
                    <div>
                        <label for="modal_supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                        <select id="modal_supplier_id" name="supplier_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Supplier (Optional)</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <button type="button" onclick="openAddSupplierModal()" class="text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                                + Add new supplier
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="modal_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea id="modal_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Item description or notes"></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700 mt-6 gap-3">
                    <button type="button" onclick="closeAddItemModal()"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
