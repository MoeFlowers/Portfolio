"use client";
import Link from 'next/link';

export default function LoginForm() {
    return (
        <form action="" method="POST" className="space-y-6">
            <div>
                <div className="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                    </svg>
                    <label className="block text-sm font-medium text-gray-700">User</label>
                </div>
                <input
                    type="text"
                    name="usuario"
                    id="usuario"
                    className="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    required />
            </div>

            <div>
                <div className="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="w-5 h-5 text-gray-600 mr-2">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                    </svg>
                    <label className="block text-sm font-medium text-gray-700">Password</label>
                </div>
                <input type="password" name="contraseña" id="contraseña"
                    className="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                    required />
            </div>

            <div className="flex items-center justify-between">
                <div className="flex items-center">
                    <input type="checkbox" id="recordarme" name="recordarme"
                        className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label className="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                <div className="text-sm">
                    <a href="../views/recuperar/recuperar.php" className="font-medium text-blue-600 hover:text-blue-500">Forgot your password?</a>
                </div>
            </div>

            <div>
                <Link href="/projects/clinic/dashboard" passHref>
                    <button
                        type="button"
                        className="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300"
                    >
                        Go to Dashboard
                    </button>
                </Link>
            </div>
        </form>
    );
}