<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Images for ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success --}}
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Upload Section --}}
                <div>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Upload New Images
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Drag and drop images here or click to select</p>
                        </div>
                        <div class="p-6">
                            <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200 cursor-pointer">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">Drop images here</p>
                                <p class="text-sm text-gray-500">or click to browse files</p>
                                <input type="file" id="fileInput" multiple class="hidden" accept="image/*">
                            </div>
                            <div id="uploadProgress" class="mt-6 hidden">
                                <div class="bg-gray-200 rounded-full h-2 mb-2">
                                    <div id="progressBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p id="progressText" class="text-sm text-gray-600 text-center">Uploading...</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Existing Images Section --}}
                <div>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Existing Images
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Select a primary image from the options below</p>
                        </div>
                        <div class="p-6">
                            @if($product->images->count() > 0)
                                <form method="POST" action="{{ route('product.setPrimary', $product) }}" id="primaryForm">
                                    @csrf
                                    <div class="mb-6">
                                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Select Primary Image</h4>
                                        @php
                                            $allImages = [];
                                            foreach($product->images as $img) {
                                                if($img->path_256) $allImages[] = ['path' => $img->path_256, 'size' => 256, 'img_id' => $img->id, 'is_primary' => $img->is_primary && $product->primary_size == 256];
                                                if($img->path_512) $allImages[] = ['path' => $img->path_512, 'size' => 512, 'img_id' => $img->id, 'is_primary' => $img->is_primary && $product->primary_size == 512];
                                                if($img->path_1024) $allImages[] = ['path' => $img->path_1024, 'size' => 1024, 'img_id' => $img->id, 'is_primary' => $img->is_primary && $product->primary_size == 1024];
                                            }
                                        @endphp
                                        <div class="grid grid-cols-6 gap-2">
                                            @foreach($allImages as $image)
                                                <div class="bg-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 h-20 w-20 relative">
                                                    <img src="/storage/{{ $image['path'] }}" alt="Product image" class="w-full h-full object-cover">
                                                    @if($image['is_primary'])
                                                        <div class="absolute top-1 right-1 bg-green-500 text-white px-1 py-0.5 rounded text-xs font-semibold">Primary</div>
                                                    @endif
                                                    <div class="absolute bottom-1 left-1">
                                                        <input type="radio" name="primary" value="{{ $image['img_id'] }}-{{ $image['size'] }}" {{ $image['is_primary'] ? 'checked' : '' }} onchange="document.getElementById('primaryForm').submit()" class="w-3 h-3 text-white focus:ring-white">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-12">
                                    
                                    <p class="text-gray-500 mt-4">No images uploaded yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const size = 1024 * 1024;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');

        dropzone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-indigo-400');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-indigo-400');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-400');
            handleFiles(e.dataTransfer.files);
        });

        async function handleFiles(files) {
            for (let file of files) {
                if (!file.type.startsWith('image/')) continue;
                await uploadFile(file);
            }
        }

        async function uploadFile(file) {
            uploadProgress.classList.remove('hidden');
            progressText.textContent = `Uploading ${file.name}...`;

            let uuid = crypto.randomUUID();
            let total = Math.ceil(file.size / size);

            for (let i = 0; i < total; i++) {
                let form = new FormData();
                form.append('_token', csrfToken);
                form.append('uuid', uuid);
                form.append('chunk_index', i);
                form.append('total_chunks', total);
                form.append('file', file.slice(i * size, (i + 1) * size));

                await fetch('/upload/chunk', { method: 'POST', body: form });

                progressBar.style.width = `${((i + 1) / total) * 100}%`;
                progressText.textContent = `Uploading ${file.name}... ${Math.round(((i + 1) / total) * 100)}%`;
            }

            let buf = await file.arrayBuffer();
            let hash = await crypto.subtle.digest('SHA-256', buf);
            let checksum = [...new Uint8Array(hash)]
                .map(b => b.toString(16).padStart(2, '0')).join('');

            await fetch('/upload/{{ $product->id }}/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ uuid, checksum })
            });

            progressText.textContent = 'Processing...';
            setTimeout(() => location.reload(), 1000);
        }
    </script>
</x-app-layout>
