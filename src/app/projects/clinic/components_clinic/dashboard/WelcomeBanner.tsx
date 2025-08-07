// src/app/projects/clinic/dashboard/components/WelcomeBanner.tsx
export default function WelcomeBanner({ user }: { user: { name: string } }) {
  return (
    <div className="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white mb-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">Bienvenido, {user.name}</h1>
          <p className="mt-2">Aquí tienes un resumen de tu actividad reciente</p>
        </div>
        <div className="hidden md:block">
          <span className="text-white opacity-20 text-6xl">🦷</span>
        </div>
      </div>
    </div>
  );
}