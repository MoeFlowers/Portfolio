// src/app/projects/clinic/dashboard/components/WelcomeBanner.tsx
import { ToothIcon } from '@heroicons/react/24/solid';

export default function WelcomeBanner({ user }: { user: { name: string } }) {
  return (
    <div className="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white mb-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">Welcome, {user.name}</h1>
          <p className="mt-2">Here's a summary of your recent activity</p>
        </div>
        <div className="hidden md:block">
          <ToothIcon className="h-16 w-16 text-white opacity-20" />
        </div>
      </div>
    </div>
  );
}