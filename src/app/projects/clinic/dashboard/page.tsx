// src/app/projects/clinic/dashboard/page.tsx
'use client';
import Sidebar from '../components_clinic/dashboard/Sidebar';
import WelcomeBanner from '../components_clinic/dashboard/WelcomeBanner';
import StatsCards from '../components_clinic/dashboard//StatsCards';
import { mockData } from './mockData';

export default function ClinicDashboard() {
  return (
    <div className="flex h-screen bg-gray-100">
      <Sidebar user={mockData.user} />
      
      <div className="flex-1 overflow-auto">
        <div className="p-6">
          <div className="max-w-7xl mx-auto">
            <WelcomeBanner user={mockData.user} />
            <StatsCards stats={mockData.stats} />
            
            {/* Podrías agregar más componentes aquí */}
          </div>
        </div>
      </div>
    </div>
  );
}