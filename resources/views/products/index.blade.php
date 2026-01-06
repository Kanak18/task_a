<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Products List --}}
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        All Products
                    </h3>

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
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->sku }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('products.images', $product) }}" class="text-indigo-600 hover:text-indigo-900">View Images</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No products found.</p>
                    @endif
                </div>
            </div>

            {{-- Import Card --}}
            <div class="bg-white shadow rounded-lg p-6">
                {{-- Title --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Bulk Product CSV Import
                </h3>

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
                    <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Upload Form --}}
                <form method="POST"
                      action="{{ route('products.import') }}"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Select CSV File
                        </label>
                        <input type="file"
                               name="csv"
                               required
                               class="mt-1 block w-full text-sm text-gray-700
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-gray-50 file:text-gray-700
                                      hover:file:bg-gray-100">
                    </div>

                    <div class="pt-4">
                        <x-primary-button>
                            Upload & Import
                        </x-primary-button>
                    </div>
                </form>

                {{-- Progress --}}
                @if(!session('import_id'))
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">
                            Import Progress
                        </h4>

                        <div class="grid grid-cols-5 gap-3 text-center">

                            <div class="bg-gray-50 p-3 rounded border">
                                <p class="text-xs text-gray-500">Total</p>
                                <p id="total" class="text-lg font-bold text-gray-800">0</p>
                            </div>

                            <div class="bg-green-50 p-3 rounded border">
                                <p class="text-xs text-gray-500">Inserted</p>
                                <p id="inserted" class="text-lg font-bold text-green-700">0</p>
                            </div>

                            <div class="bg-blue-50 p-3 rounded border">
                                <p class="text-xs text-gray-500">Updated</p>
                                <p id="updated" class="text-lg font-bold text-blue-700">0</p>
                            </div>

                            <div class="bg-red-50 p-3 rounded border">
                                <p class="text-xs text-gray-500">Invalid</p>
                                <p id="invalid" class="text-lg font-bold text-red-700">0</p>
                            </div>

                            <div class="bg-yellow-50 p-3 rounded border">
                                <p class="text-xs text-gray-500">Duplicates</p>
                                <p id="duplicates" class="text-lg font-bold text-yellow-700">0</p>
                            </div>

                        </div>
                    </div>

                    {{-- Live Progress Script --}}
                    <script>
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
                    </script>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
