@extends('layouts.app')

@section('content')
    <!-- Bagian Hero -->
    <section class="relative w-full h-[700px] bg-brand-teal flex items-center bg-cover bg-center" style="background-image: url('{{ asset('storage/images/hero-model.jpg') }}');">
        <!-- Overlay Gradasi -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/90 via-brand-teal/60 to-transparent"></div>
        
        <div class="relative z-10 px-12 md:px-24 max-w-3xl text-white">
            <!-- <span class="text-sm tracking-[0.2em] uppercase opacity-90 mb-4 block">Kualitas</span> -->
            <h2 class="w-full text-6xl font-serif mb-6">
                mulai sadar pentingnya asupan nutrisi tambahan untuk tumbuh kembang optimal.    
            </h2>
            <p class="text-sm opacity-90 mb-10 max-w-md leading-relaxed">
                imbangi asupan makan dengan memberikan nutrisi tambahan yang tepat. untuk bantu maksimalkan tumbuh kembang.
            </p>
            
            <div class="flex items-center space-x-6">
                <a href="#produk-unggulan" class="bg-brand-dark hover:bg-brand-teal border border-brand-teal transition-colors text-white px-8 py-3 rounded-full text-sm font-bold tracking-wider flex items-center">
                    Produk Unggulan<span class="ml-2">&rarr;</span>
                </a>
                <!-- <button class="flex items-center space-x-2 hover:opacity-80 transition-opacity text-sm font-medium">
                    <span class="w-8 h-8 rounded-full border border-white flex items-center justify-center">
                        <svg class="w-3 h-3 fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    <span>WATCH VIDEO</span>
                </button> -->
            </div>
        </div>
    </section>

    <!-- Value Proposition Bar -->
    <div class="max-w-6xl mx-auto px-8 relative -mt-12 z-20">
        <div class="bg-white rounded-xl shadow-lg p-6 grid grid-cols-1 md:grid-cols-4 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <!-- Nilai 1-4 (seperti kode sebelumnya) -->
            <div class="flex items-center space-x-4 px-4">
                <div class="text-brand-accent"><svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="7.635 23.527 386.758 353.266"><g fill="#006BB6"><path d="M326.561 175.348c2.648 3.957 2.471 9.134 3.006 13.775-.18 9.833.685 21.54-1.071 31.536-.894 4.149-3.688 8.45-8.093 10.012-7.051 3.095-16.571 2.231-23.086-1.383-5.476-3.794-6.159-10.16-6.873-16.2-.535-11.9-1.428-24.47 1.935-35.344 2.647-6.709 9.877-8.435 16.394-8.955 6.87-.001 13.563 1.04 17.788 6.559"/><path d="M230.494 326.127l-1.221 1.041c-18.355 3.957-38.848 4.121-57.211-.357l1.384-1.547c16.133-10.517 41.281-10.517 57.048.863m-62.076-3.957l-3.474 2.752c-14.578-3.793-28.1-10.339-40.595-19.294v-.521c4.172-2.589 8.338-5.341 12.845-7.229 9.885 9.803 20.119 17.226 31.224 24.292m109.945-17.063c-9.698 7.75-20.975 12.927-32.43 17.227l-8.15 2.588c-1.369-.699-2.59-1.711-3.809-2.752l.327-.521c11.126-6.547 21.183-14.475 30.703-23.95 4.702 1.905 9.194 4.478 13.359 7.408m-18.535-9.818c-5.562 6.367-12.674 11.887-19.278 16.542-3.985 2.41-7.974 4.999-12.139 7.066l-7.468-3.272.535-1.205c6.248-8.449 11.812-17.062 16.662-26.195l.504-.356c7.469 1.723 14.581 4.149 21.184 7.42m-95.396-7.244c5.021 9.477 11.268 18.432 17.516 27.387-2.439 1.398-5.377 2.262-7.817 3.629-11.439-5.682-22.373-13.61-31.559-23.086v-.521c6.761-3.271 13.872-5.684 21.153-7.586l.707.177zm68.502-1.547l.148.521c-4.818 9.297-10.918 18.252-17.493 26.701-2.944.342-5.712-.862-8.688-1.041l-2.916-.328c-.355-10.175-.534-19.145 0-29.305 9.878.181 19.755 1.206 28.949 3.452m-34.177 25.676c-3.63.862-7.624.862-11.097 1.726-7.118-8.449-13.009-17.583-18.037-27.06l.87-.699c9.015-1.709 18.386-3.094 28.1-3.094l.164 29.127zm-36.416-39.629c-1.74 0-3.645-.357-4.857.164l4.336 10.159-.513.521c-7.817 2.232-15.448 5-22.894 8.107l-.707-.355c-4.857-5.326-9.536-11.188-13.358-17.062-.848-2.916-4.321-1.205-6.225-1.532 4.157 7.571 9.698 14.295 15.262 21.183-4.693 2.768-9.371 5.877-14.229 8.107-10.405-7.929-20.126-18.268-27.4-29.126-2.09-.343-3.474-.164-5.549 0 12.473 19.649 31.916 36.876 52.206 47.036l.164-.163.186.52c13.693 6.71 28.256 11.545 43.697 13.611 53.239 7.066 104.056-15.856 133.538-61.004-1.547-.164-3.809-.164-5.562 0-7.261 10.517-16.453 20.69-26.509 28.605l-1.727-.164c-4.345-2.752-9.044-5.013-13.209-7.751 5.563-6.737 10.948-13.446 15.112-20.854h-5.385c-4.344 6.367-8.865 13.076-14.578 18.772-7.646-3.094-14.578-5.86-22.373-7.93l-.861-.684c1.22-3.451 3.451-6.725 4.164-10.16h-4.67l-4.701 9.297c-10.234-2.246-21.51-3.451-31.924-4.135-.506-1.547.209-3.794-.506-5.162h-4.515c-.342 2.053.521 3.615-.506 5.325-10.763.521-21.168 1.726-31.231 3.794l-4.676-9.119zM52.381 140.182l-10.755.179a826.025 826.025 0 0 0 0 29.111c5.905.193 11.454 0 17.181.521v58.61l-.186 2.395-17.166.357a384.93 384.93 0 0 0 0 28.427h76.482v-28.427c-5.727-.357-12.138.162-17.687-.521l.171-60.484.35-.521 17.166-.164c.187-9.833.35-20.335-.163-29.305l-65.393-.178zm294.678 10.353c-22.551-14.474-56.914-15.515-80.119-2.425-12.674 6.545-21.004 19.487-23.592 32.741-4.701 21.896-3.481 47.915 13.148 64.293 19.458 17.226 47.722 21.019 72.861 13.953 1.188-.238 4.998-1.205 7.586-2.604l.744-.327c7.795-3.271 15.263-8.628 20.795-15.857 12.854-18.951 13.717-47.394 5.892-68.754-3.095-8.272-9.52-15.68-17.315-21.02m-147.082-5.518c-19.085-8.107-45.787-8.955-63.132 3.614-11.276 8.271-14.749 23.087-11.796 36.192 2.261 12.406 14.05 21.54 25.318 25.839 15.449 5.52 31.581 9.477 47.171 14.831 1.577.685 3.651 2.231 3.325 4.136 0 1.547-1.584 2.41-2.804 3.094-16.296 4.479-33.983 1.041-45.772-11.365-1.383-1.725-2.253-3.793-3.651-5.519l-23.415.179c-.334 13.953-.521 29.976 0 43.765l26.018-.163.35-8.286c2.953 1.397 5.898 3.108 8.851 4.671 18.036 7.572 41.287 9.282 59.138 0 13.001-7.259 19.963-21.212 18.029-36.191-1.904-14.652-15.77-27.921-29.812-31.551l-41.979-12.228-3.124-1.562c-1.383-1.205-2.253-2.752-1.919-4.641 3.302-5.683 10.941-3.957 16.668-3.793 12.138 1.725 24.611 5.861 31.722 17.062 7.646 0 16.482.164 24.812-.163.328-14.147-.179-26.553-.356-40.863-7.795-.164-17.345-.164-25.14.193-.357 2.067.326 4.983-.537 7.065l-7.965-4.316zm14.035-58.596c6.783 8.613 13.358 17.404 17.881 26.88-9.371 2.246-18.893 3.436-28.621 3.436-.356-9.119-.179-19.293 0-28.77 3.48-.519 7.289-1.04 10.74-1.546m-16.282 1.384c.521 8.955.164 19.294.164 28.77-4.864.685-9.186-.164-14.042-.521-4.693-.685-10.592-1.205-15.263-2.752 5.028-9.476 10.919-18.609 18.029-27.059l11.112 1.562zm-16.838-3.273l-.514 1.368c-6.597 8.271-11.789 17.241-17.003 26.032l-.684.164c-7.11-1.89-14.057-4.136-20.64-7.066l.35-1.042c9.536-9.119 19.592-16.705 30.696-22.566 2.603 1.027 5.191 1.905 7.795 3.11m78.044 19.651l-.326.506c-6.782 2.931-13.715 5.683-21.184 7.244-5.355-9.148-11.246-18.267-17.851-27.059 2.438-1.383 5.385-2.425 7.974-3.793 11.275 5.683 22.194 13.79 31.387 23.102M167.883 78.15c-10.748 6.724-21.324 14.652-31.038 24.292-4.679-1.726-9.022-4.299-13.18-7.244v-.328c10.926-8.107 23.064-14.132 35.902-18.282l1.554-1.012v.327c1.383.357 2.44-1.205 3.645-.164l3.117 2.411zm109.262 16.364l-.328.685-11.959 6.738h-.894c-9.163-9.312-19.933-17.404-30.882-23.786l3.332-2.931c14.549 3.793 28.264 10.339 40.731 19.294m-47.692-21.02v.343c-15.084 11.037-39.54 12.063-55.486 1.904l-2.774-2.247c18.386-4.313 40.052-4.656 58.26 0m-17.851-7.75c-4.699-.521-10.412-.684-15.448-.179-1.904-.163-4.157 0-6.411.179-41.629 3.272-79.956 24.812-103.884 61.525 1.733-.178 3.809.506 5.541-.178 4.166-5.846 8.852-11.543 14.05-17.062 4.507-4.314 8.68-8.434 13.537-11.543 4.515 2.931 9.535 5.34 13.864 8.092-5.378 6.724-10.919 13.448-14.913 20.692 1.22.506 3.474.342 5.207 0 4.328-6.381 9.021-12.763 14.563-18.445 7.467 2.752 14.749 5.682 22.38 7.75l.684.685-4.329 10.011c1.904.342 3.124.164 5.028 0 1.569-2.945 2.774-6.218 4.507-9.134 10.056 2.231 20.64 3.437 31.395 3.794 1.383 1.189.357 3.793.877 5.34h4.663c.536-1.74-.147-3.629.536-5.177 10.056-.521 20.617-1.562 30.347-3.957l.862.164 4.671 8.97c1.577 0 3.659.342 4.879-.357l-4.344-9.981.356-.357c7.975-2.068 15.441-5.162 23.057-8.271 5.387 5.683 10.414 12.421 14.937 18.966 1.904 0 3.81.164 5.177-.357a67.945 67.945 0 0 0-7.438-10.859c-2.262-3.273-4.701-6.188-7.646-9.297v-.863l12.14-7.066 1.755-.684c10.562 8.092 19.934 17.91 27.371 28.769 2.083 0 3.838.699 5.564-.164-23.237-35.152-61.556-57.733-103.03-61.006l-.178.342-.327-.342zM7.635 23.527h386.758v353.266H7.635V23.527z"/></g></svg></div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Standar mutu tinggi</h4>
                    <p class="text-xs text-gray-500 mt-1">menerapkan standar jaminan mutu HACCP dan ISO 22000</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 px-4">
                <div class="text-brand-accent"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAAD9UlEQVR4nO2aWYgURxjHf7urrkeMZ0RMUBMvPPEiKmrUBzUaguCBoOJ9gKIPCgYfdCOo64PmwSMgLIgiiGKIiKImEPDBFUUEEY8H8RaVxQuDxnPlg2+gabpnq3tqamZC/aEepr+a/lf/urpu8PLy8vLy8vLySq0yoCcwEPiS/Ksx0BvoThFoLnAXqNf0FtgHtMuDVzOgGngT8LsCjKJAWhcoSDhdB9pYrmWHY7zeAaNxrF7A+ywAJO2w6DelAa/bQDkOtbGBAkl6ZrFQRw38nH4KBwwKJOkrS43eKwOvLTjUToMCySdSacFrpCHs8zjUjwYFOmnJ6zdDAB+BzjhSGXAqS2GkqxpkwUfakIeGACT9gkO1immcHgPjLXm0BT4kAFBDAfS9jgk2AbOBLyzfX/r4pcAafcObga2aNui1lcAiBeZUHbWPXgGsBuYBg4GKPPl1A5YrBHn4n4GmOFYZMAs4l6Uq1gHbFJANyX2OAJ9iPrn5OFJnoDbBN/kSWJCjZxfgnoGXfBJ51XdKuz5FkjYibS9wMYHPNPJY7WtTPny9Vt3hKXxnJvS5oWW1rh45PHwm/Z7C948UPrIuYV0jLACQ6WxSXU3hI91hyQD4AVgMDI3xvZ/Cp7oUAFQAf4biuywBOFwKABbG5BlvAcClUgBQE5OnygKAl6UAoComzwILAGwtxOQVQIeIQdV1Xfm1ASDNmMN5L9BJF05Pa8vdOsI3LQCZlRY9gEp9UxOAIUAjiwCqihlAJbAeeBqKPwBWhVaS0wLYbxvAMAsADgFNgL8SFD4tgLO2AbQD/ssRgLz16YZ5h+UIQBpY65oHPE9RGFmtPQG01KUtk/9MzhFAvfpZVzmwO8LsTJapa7BrcwkgL7NC0fYIs2PAuJiCUCAA01wCOKirw8UEYK1LADVAvyIDsMclgB3At0UG4LRLANU6vm8IwBjDTdWeFgAcdAlgPdDCAIBoYiD2WrvXYP6+gbxpAcj/+rgEsFq7yKiNi7C+CcSe6NA4Ln8UgEnawIWv/61zin4x84q8AlimsdcJAdzSa+8TAJCzRz+5WgozBTBHY3UJAcgpL3QVxxSA1JixxQZgqsbuJASQOdXxyBCAbJUTM+YoKICJGruWEMA/eu2mIYDMel/fQgLYFmGeOad3IXRdGsVsAI7rtcsxAMKbolJTRF1jpttOtCRkLA1Ye43tDcVkZyesryPeWm0MsPDagfxGW/kXodivOFITfXOZo7FyYCG4fZ75DOp04BNWhYL5pPsDwVVi6cqCGhCoBXIkt38gNgP4V2O1emTHqTrFzLnLtYrK+b44VWpNCJ8DiOq/G+n9omLNXZ4M8/Ly8vLy8vLi/6TPWNqhgAHO73gAAAAASUVORK5CYII=" alt="family"></div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Lengkapi nutrisi keluarga</h4>
                    <p class="text-xs text-gray-500 mt-1">lengkapi nutrisi sesuai tingkatan usia, mulai dari ibu hamil hingga lansia.</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 px-4">
                <div class="text-brand-accent">ðŸŒ¿</div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Formula khusus</h4>
                    <p class="text-xs text-gray-500 mt-1"> Formula khusus yang disesuaikan dengan kebutuhan nutrisi, memberikan keamanan dan kenyamanan konsumen</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 px-4">
                <div class="text-brand-accent">ðŸŒ¿</div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Mementingkan Kualitas Produk</h4>
                    <p class="text-xs text-gray-500 mt-1">Produk susu berkualitas tinggi, dengan harga yang tetap terjangkau. </p>
                </div>
            </div>
            <!-- ... -->
        </div>
    </div>

    {{-- @php
    <!-- Kategori -->
    <section class="max-w-6xl mx-auto px-8 py-20 text-center">
        <div class="flex items-center justify-center mb-10">
            <div class="h-px bg-gray-200 w-16"></div>
            <h3 class="text-xs font-bold tracking-[0.2em] text-gray-500 mx-4 uppercase">Shop by Category</h3>
            <div class="h-px bg-gray-200 w-16"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
            <!-- Kategori 1: Serums -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <!-- Ganti warna kotak dengan gambar produk kategori sesungguhnya -->
                    <div class="w-16 h-20 bg-brand-dark/20 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Serums</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 2: Moisturizers -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-20 h-16 bg-brand-dark/30 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Moisturizers</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
             <!-- Kategori 3: Cleansers -->
             <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-12 h-24 bg-brand-dark/25 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cleansers</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 4: Toners -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-14 h-22 bg-brand-dark/40 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Toners</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 5: Eye Care -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-brand-light flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-16 h-12 bg-brand-dark/15 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Eye Care</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
            <!-- Kategori 6: Sun Care -->
            <div class="flex flex-col items-center cursor-pointer group">
                <div class="w-28 h-28 rounded-full bg-orange-50 flex items-center justify-center mb-4 overflow-hidden relative group-hover:shadow-md transition-shadow">
                    <div class="w-12 h-20 bg-orange-200 rounded"></div>
                </div>
                <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Sun Care</h4>
                <p class="text-xs text-brand-teal mt-1 group-hover:underline">Explore</p>
            </div>
        </div>
    </section>
    @endphp --}}

    <!-- Featured Products -->
    <section id="produk-unggulan" class="max-w-6xl mx-auto px-8 py-20">
        <div class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4">
            <h3 class="text-xl font-serif font-bold text-gray-800 uppercase tracking-wider">Featured Products</h3>
            <a href="/produk-kami" class="text-xs font-bold text-gray-500 hover:text-brand-teal tracking-wider border border-gray-300 px-4 py-1.5 rounded uppercase">View All</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Kartu Produk 1 -->
            <div class="group">
                <div class="bg-brand-light relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <!-- Label Status -->
                    <span class="absolute top-4 left-4 bg-brand-dark text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Best Seller</span>
                    <!-- Gambar Produk Dummy -->
                    <img src="{{ asset('produk/Lactona Skim.png') }}" alt="lactona skim">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Skim</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">â˜…â˜…â˜…â˜…â˜…</span>
                    <span class="text-xs text-gray-400">(245)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 34.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Beli Sekarang &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 2 -->
             <div class="group">
                <div class="bg-gray-100 relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-brand-teal text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">New</span>
                    <img src="{{ asset('produk/Lactona Ibu Cokelat.png') }}" alt="lactona ibu">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Ibu</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">â˜…â˜…â˜…â˜…â˜†</span>
                    <span class="text-xs text-gray-400">(189)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 42.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Beli Sekarang &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 3 -->
            <div class="group">
                <div class="bg-brand-light relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-brand-accent text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Limited</span>
                    <img src="{{ asset('produk/Lactona Gold 3.png') }}" alt="lactona gold 3+">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona Gold 3+</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">â˜…â˜…â˜…â˜…â˜…</span>
                    <span class="text-xs text-gray-400">(312)</span>
                </div> -->
                <p class="text-lg font-bold text-gray-900 mb-4">Rp. 38.00</p>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Beli Sekarang &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>

            <!-- Kartu Produk 4 (Dengan Harga Diskon) -->
            <div class="group">
                <div class="bg-gray-100 relative aspect-[4/5] flex items-center justify-center p-6 mb-4 overflow-hidden">
                    <span class="absolute top-4 left-4 bg-teal-600 text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider z-10">Sale</span>
                    <img src="{{ asset('produk/Lactona Gold 3.png') }}" alt="lactona gold 3+">
                </div>
                <h4 class="text-sm font-medium text-gray-800 mb-1 leading-snug">Lactona 1+</h4>
                <!-- <div class="flex items-center space-x-1 mb-2">
                    <span class="text-yellow-400 text-xs">â˜…â˜…â˜…â˜…â˜†</span>
                    <span class="text-xs text-gray-400">(156)</span>
                </div> -->
                <div class="flex items-end space-x-2 mb-4">
                    <p class="text-lg font-bold text-gray-900">Rp. 24.00</p>
                    <p class="text-xs text-gray-400 line-through pb-1">Rp. 30.00</p>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-brand-dark hover:bg-brand-teal text-white text-xs font-bold py-2.5 rounded transition-colors uppercase tracking-wide">Beli Sekarang &gt;</button>
                    <!-- <button class="w-10 h-10 border border-gray-200 rounded flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Bagian Promosi (Newsletter) -->
    <section class="max-w-6xl mx-auto px-8 pb-12">
        <div class="bg-brand-dark rounded-2xl flex flex-col md:flex-row items-center overflow-hidden">
            <!-- Gambar Tambahan (Kiri) -->
            <div class="w-full md:w-5/12 h-64 bg-brand-teal/80 flex items-center justify-center p-8">
                    <img class="w-50" src="mirota-care.png" alt="mirota care">
            </div>
            <!-- Teks Penawaran (Tengah) -->
            <div class="w-full md:w-4/12 p-8 md:pl-12 text-white text-center md:text-left">
                <span class="text-xs tracking-[0.2em] font-medium opacity-80 uppercase block mb-2">Special Offer</span>
                <h3 class="text-3xl font-serif leading-tight mb-3">Get 20% Off<br>Your First Order</h3>
                <p class="text-sm opacity-80 leading-relaxed">Join our glow club & unlock exclusive offers and skincare tips.</p>
            </div>
            <!-- Tombol (Kanan) -->
            <div class="w-full md:w-3/12 p-8 flex flex-col items-center md:items-start justify-center border-t md:border-t-0 md:border-l border-white/20">
                <div class="mb-4 opacity-80">
                    <svg class="w-8 h-8 mx-auto md:mx-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <button class="bg-transparent border border-white hover:bg-white hover:text-brand-dark text-white text-sm font-bold uppercase tracking-wider py-2 px-8 transition-colors">Join Now</button>
                <p class="text-[10px] text-white/60 mt-3 text-center md:text-left leading-tight">Be the first to know about new arrivals & exclusive deals.</p>
            </div>
        </div>
    </section>
@endsection