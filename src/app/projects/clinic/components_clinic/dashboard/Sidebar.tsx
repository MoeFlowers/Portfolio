// src/app/projects/clinic/dashboard/components/Sidebar.tsx
'use client';
import Image from 'next/image';
import Link from 'next/link';

export default function Sidebar({ user }: { user: { name: string; role: string } }) {
  const menuItems = [
    { name: "Inicio", icon: "🏠", href: "#" },
    { name: "Pacientes", icon: "👨‍⚕️", href: "#" },
    { name: "Citas", icon: "📅", href: "#" },
    { name: "Historiales", icon: "📋", href: "#" },
    { name: "Configuración", icon: "⚙️", href: "#" }
  ];

  return (
    <div className="hidden md:flex md:w-64 md:flex-col">
      <div className="flex flex-col h-0 flex-1 bg-blue-800">
        {/* Logo */}
        <div className="flex items-center justify-center h-16 px-4 bg-blue-900">
          <Image 
            src="/projects/clinic/assets/images/logo-removebg-preview.png"
            width={40}
            height={40}
            alt="Clinic Logo"
          />
          <span className="ml-2 text-white font-semibold">IPSFANB Dental</span>
        </div>

        {/* Menú */}
        <div className="flex-1 pt-5 pb-4 overflow-y-auto">
          <nav className="px-4 space-y-1">
            {menuItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className="text-blue-200 hover:bg-blue-700 hover:text-white group flex items-center px-4 py-3 text-sm font-medium rounded-md"
              >
                <span className="mr-3">{item.icon}</span>
                {item.name}
              </Link>
            ))}
          </nav>
        </div>

        {/* Usuario */}
        <div className="p-4 border-t border-blue-700">
          <div className="flex items-center">
            <div className="h-10 w-10 rounded-full bg-white flex items-center justify-center">
              👨‍⚕️
            </div>
            <div className="ml-3">
              <p className="text-sm font-medium text-white">{user.name}</p>
              <p className="text-xs font-medium text-blue-200">{user.role}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}