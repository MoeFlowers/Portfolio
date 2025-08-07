// src/app/projects/clinic/dashboard/layout.tsx
import { ReactNode } from 'react';
import Sidebar from '../components_clinic/dashboard/Sidebar';

export default function DashboardLayout({
  children,
}: {
  children: ReactNode;
}) {
  return (
    <div className="flex h-screen bg-gray-100">
      <Sidebar user={{ name: "Admin User", role: "Administrador" }} />
      <div className="flex-1 overflow-auto">
        {children}
      </div>
    </div>
  );
}