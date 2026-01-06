<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products Management') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('products.sample.download') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Sample CSV
                </a>&nbsp;
                <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Import Products
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Products List --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Products List
                        </h3>
                        <span class="text-sm text-gray-500">{{ $products->total() }} products</span>
                    </div>

                    @if($products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->sku }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($product->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">${{ number_format($product->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('products.images', $product) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-medium hover:bg-indigo-100 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Gallery
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-12">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by importing your first products.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="import-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full {{ ($errors->any() || session('success') || session('import_id')) ? '' : 'hidden' }} z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Import Products CSV</h3>
                    <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Success --}}
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data" class="space-y-4" id="import-form">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                        <input type="file" name="csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                        <button type="submit" id="import-submit-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 hidden" id="loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span id="button-text">Upload & Import</span>
                        </button>
                    </div>
                </form>

                {{-- Progress --}}
                @if(session('import_id'))
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Import Progress
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Total Processed</span>
                                </div>
                                <span id="total" class="text-xl font-bold text-gray-900">0</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium text-green-800">Inserted</span>
                                        </div>
                                        <span id="inserted" class="text-lg font-bold text-green-700">0</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium text-blue-800">Updated</span>
                                        </div>
                                        <span id="updated" class="text-lg font-bold text-blue-700">0</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-2">
                                                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium text-red-800">Invalid</span>
                                        </div>
                                        <span id="invalid" class="text-lg font-bold text-red-700">0</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-2">
                                                <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium text-yellow-800">Duplicates</span>
                                        </div>
                                        <span id="duplicates" class="text-lg font-bold text-yellow-700">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Handle form submission loading state
                        document.getElementById('import-form').addEventListener('submit', function() {
                            const button = document.getElementById('import-submit-btn');
                            const spinner = document.getElementById('loading-spinner');
                            const text = document.getElementById('button-text');

                            button.disabled = true;
                            spinner.classList.remove('hidden');
                            text.textContent = 'Importing...';
                        });

                        @if(session('import_id'))
                            const importId = {{ session('import_id') }};
                            function fetchProgress() {
                                fetch(`/products/import/${importId}/progress`)
                                    .then(res => res.json())
                                    .then(data => {
                                        document.getElementById('total').innerText = data.total;
                                        document.getElementById('inserted').innerText = data.inserted;
                                        document.getElementById('updated').innerText = data.updated;
                                        document.getElementById('invalid').innerText = data.invalid;
                                        document.getElementById('duplicates').innerText = data.duplicates;
                                        if (data.total < data.expected_total) {
                                            setTimeout(fetchProgress, 1000);
                                        }
                                    })
                                    .catch(err => console.error(err));
                            }
                            fetchProgress();
                        @endif
                    </script>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
