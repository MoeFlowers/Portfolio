import LoginForm from '../components_clinic/login/LoginForm';
import BackgroundImage from '../components_clinic/login/BackgroundImage';
import type { Metadata } from 'next';

export const metadata: Metadata = {
    title: 'MF - Login',
};

export default function LoginPage() {
    return (
        <div className="min-h-screen flex items-center justify-center p-4 relative">
            <BackgroundImage />

            <div className="w-full max-w-md p-8 bg-white/90 rounded-xl shadow-2xl backdrop-blur-sm z-10">
                <LoginForm />
            </div>
        </div>
    );
}