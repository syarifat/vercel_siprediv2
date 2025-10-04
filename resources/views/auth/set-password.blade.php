<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Set Password Guru</title>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
	<div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
		<h2 class="text-2xl font-bold text-center mb-6 text-orange-600">Set Password Guru</h2>
		@if(session('error'))
			<div class="mb-4 text-red-600 text-center">{{ session('error') }}</div>
		@endif
		<form method="POST" action="{{ route('set-password.store') }}">
			@csrf
			<div class="mb-4">
				<label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
				<input type="email" name="email" id="email" value="{{ old('email', $email ?? '') }}" required autofocus
					class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
				@error('email')
					<span class="text-red-600 text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="mb-4">
				<label for="password" class="block text-gray-700 font-semibold mb-2">Password Baru</label>
				<div class="relative">
					<input type="password" name="password" id="password" required
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
					<button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400">
						<svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
					</button>
				</div>
				@error('password')
					<span class="text-red-600 text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="mb-6">
				<label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
				<div class="relative">
					<input type="password" name="password_confirmation" id="password_confirmation" required
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
					<button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400">
						<svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
					</button>
				</div>
			</div>
			<button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition mb-3">Simpan Password</button>
			<a href="{{ route('login') }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">Kembali ke Login</a>
		</form>
	</div>
</body>
</html>
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const eye = document.getElementById('eye-' + id);
    if (input.type === 'password') {
        input.type = 'text';
        eye.style.opacity = 0.5;
    } else {
        input.type = 'password';
        eye.style.opacity = 1;
    }
}
</script>
