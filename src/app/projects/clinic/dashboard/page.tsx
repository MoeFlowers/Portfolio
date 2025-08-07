// src/app/projects/clinic/dashboard/page.tsx
'use client';
import WelcomeBanner from '../components_clinic/dashboard/WelcomeBanner';
import StatsCards from '../components_clinic/dashboard/StatsCards';
import UpcomingAppointments from '../components_clinic/dashboard/UpcomingAppointments';
import RecentPatients from '../components_clinic/dashboard/RecentPatients';
import MonthlyAppointmentsChart from '../components_clinic/dashboard/MonthlyAppointmentsChart';
import CommonProcedures from '../components_clinic/dashboard/CommonProcedures';
import { mockData } from './mockData';


export default function DashboardPage() {
  return (
    <main className="p-6">
      <div className="max-w-7xl mx-auto">
        {/* Welcome Banner */}
        <WelcomeBanner user={mockData.user} />
        {/* Stats Cards */}
        <StatsCards stats={mockData.stats} />
        
        {/* Two Column Layout */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
          {/* Upcoming Appointments - Takes 2 columns */}
          <div className="lg:col-span-2">
            <UpcomingAppointments appointments={mockData.upcomingAppointments} />
          </div>
          
          {/* Recent Patients */}
          <div>
            <RecentPatients patients={mockData.recentPatients} />
          </div>
          
          {/* Monthly Appointments Chart */}
          <div className="lg:col-span-2">
            <MonthlyAppointmentsChart data={mockData.monthlyAppointments} />
          </div>
          
          {/* Common Procedures */}
          <div>
            <CommonProcedures procedures={mockData.commonProcedures} />
          </div>
        </div>
      </div>
    </main>
  );
}