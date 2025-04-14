@if ($paginator->hasPages())
    <div class="flex justify-center mt-8">
        <nav role="navigation" aria-label="Pagination Navigation" class="flex space-x-2">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed shadow-md">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md transition-all duration-300 transform hover:scale-105">&laquo;</a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg shadow">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-blue-100 transition-all shadow-sm">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md transition-all duration-300 transform hover:scale-105">&raquo;</a>
            @else
                <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed shadow-md">&raquo;</span>
            @endif
        </nav>
    </div>
@endif
