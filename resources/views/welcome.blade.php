<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
       
    @endif
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    <div class="bg-white dark:bg-[#0a0a0a]  rounded-lg shadow-lg p-6  mx-auto my-10">
        @include('sweetalert::alert')
        <h3 class="text-3xl font-bold dark:text-white text-center"> Formulir Buku Tamu</h3>
        <p class="text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400 text-center">Kami menghargai setiap
            kunjungan Anda. Silakan isi formulir di bawah ini.</p>


        <form action="{{ route('form-buku.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <!-- Name Field -->
                <div class="col-span-1">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama<span
                            class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Phone Field -->
                <div class="col-span-1">
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor
                        Telepon<span class="text-red-600">*</span></label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email Field -->
                <div class="col-span-1">
                    <label for="email"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Gender Field -->
                <div class="col-span-1">
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                        Kelamin<span class="text-red-600">*</span></label>
                    <select name="gender" id="gender" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori Tamu Field -->
                <div class="col-span-1">
                    <label for="kategori_tamu_id"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori Tamu<span
                            class="text-red-600">*</span></label>
                    <select name="kategori_tamu_id" id="kategori_tamu_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        onchange="toggleKategoriTamuLainnya()">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoriTamus as $kategoriTamu)
                            <option value="{{ $kategoriTamu->id }}"
                                {{ old('kategori_tamu_id') == $kategoriTamu->id ? 'selected' : '' }}>
                                {{ $kategoriTamu->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_tamu_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Tamu Lainnya Field -->
                <div id="kategori_tamu_lainnya_container" class="col-span-1 hidden">
                    <label for="kategori_tamu_lainnya"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori Tamu Lainnya<span
                            class="text-red-600">*</span></label>
                    <input type="text" name="kategori_tamu_lainnya" id="kategori_tamu_lainnya"
                        value="{{ old('kategori_tamu_lainnya') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('kategori_tamu_lainnya')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <label for="jurusan_id"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jurusan<span
                            class="text-red-600">*</span></label>
                    <select name="jurusan_id" id="jurusan_id" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        onchange="toggleJurusanLainnya()">
                        <option value="">Pilih Jurusan</option>
                        @foreach ($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}"
                                {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                {{ $jurusan->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('jurusan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jurusan Lainnya Field -->
                <div id="jurusan_lainnya_container" class="col-span-1 hidden">
                    <label for="jurusan_lainnya"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jurusan Lainnya<span
                            class="text-red-600">*</span></label>
                    <input type="text" name="jurusan_lainnya" id="jurusan_lainnya"
                        value="{{ old('jurusan_lainnya') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('jurusan_lainnya')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <!-- Address Field -->
                <div class="col-span-1">
                    <label for="address"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat<span
                            class="text-red-600">*</span></label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <!-- Description Field -->
            <div>
                <label for="description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi<span
                        class="text-red-600">*</span></label>
                <textarea name="description" id="description" rows="4" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full">
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out w-full">
                    Simpan
                </button>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
                    <a href="/admin/login" class="text-sm text-gray-600 underline hover:text-gray-900">
                        {{ __('Login Admin? Klik disini') }}
                    </a>
                </p>
            </div>
        </form>
    </div>






    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
    <!-- Tombol Dark Mode -->
<button id="darkModeToggle" class="fixed bottom-4 right-4 p-4  dark:bg-gray-600 bg-gray-800 text-white rounded-full shadow-lg">
    <span id="darkIcon" class="hidden w-4">🌙</span>
    <span id="lightIcon" class="hidden w-4">🌞</span>
</button>
</body>
<script>
    // Periksa apakah tema sebelumnya sudah ada di localStorage
    const currentTheme = localStorage.getItem('theme');

    // Jika ada tema yang tersimpan, terapkan tema tersebut
    if (currentTheme) {
        document.documentElement.classList.add(currentTheme);
    }

    // Fungsi untuk toggle dark mode
    const toggleDarkMode = () => {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
           updateIcon('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            updateIcon('light');
            localStorage.setItem('theme', 'dark');
        }
    };

    const updateIcon = (theme) => {
            if (theme === 'dark') {
                document.getElementById('darkIcon').classList.remove('hidden');
                document.getElementById('lightIcon').classList.add('hidden');
            } else {
                document.getElementById('darkIcon').classList.add('hidden');
                document.getElementById('lightIcon').classList.remove('hidden');
            }
        };
    // Event listener untuk tombol dark mode
    document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);

    document.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                updateIcon('light');
            }else{
                updateIcon('dark');
            }
    })
</script>

<script>
    function toggleKategoriTamuLainnya() {
        const kategoriTamuSelect = document.getElementById('kategori_tamu_id');
        const kategoriTamuLainnyaContainer = document.getElementById('kategori_tamu_lainnya_container');
        const kategoriTamuLainnyaInput = document.getElementById('kategori_tamu_lainnya');

        // Find the selected option text
        const selectedOption = kategoriTamuSelect.options[kategoriTamuSelect.selectedIndex].text.toLowerCase();

        if (selectedOption === 'lainnya') {
            kategoriTamuLainnyaContainer.classList.remove('hidden');
            kategoriTamuLainnyaInput.setAttribute('required', 'required');
        } else {
            kategoriTamuLainnyaContainer.classList.add('hidden');
            kategoriTamuLainnyaInput.removeAttribute('required');
            kategoriTamuLainnyaInput.value = '';
        }
    }

    function toggleJurusanLainnya() {
        const jurusanSelect = document.getElementById('jurusan_id');
        const jurusanLainnyaContainer = document.getElementById('jurusan_lainnya_container');
        const jurusanLainnyaInput = document.getElementById('jurusan_lainnya');

        // Find the selected option text
        const selectedOption = jurusanSelect.options[jurusanSelect.selectedIndex].text.toLowerCase();

        if (selectedOption === 'lainnya') {
            jurusanLainnyaContainer.classList.remove('hidden');
            jurusanLainnyaInput.setAttribute('required', 'required');
        } else {
            jurusanLainnyaContainer.classList.add('hidden');
            jurusanLainnyaInput.removeAttribute('required');
            jurusanLainnyaInput.value = '';
        }
    }

    // Initialize the form state
    document.addEventListener('DOMContentLoaded', function() {
        toggleKategoriTamuLainnya();
        toggleJurusanLainnya();
    });
</script>

</html>
