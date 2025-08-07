// src/app/projects/clinic/dashboard/data/mockData.ts
export const mockData = {
  user: {
    name: "Dr. Juan Pérez",
    role: "Dentist"
  },
  stats: {
    totalPatients: 124,
    totalEmployees: 15,
    todayAppointments: 8,
    clinicalRecords: 230
  },
  upcomingAppointments: [
    {
      id: 1,
      patientName: "María González",
      procedure: "Limpieza dental",
      date: "2023-11-15T10:00:00",
      status: "Scheduled"
    },
    // ... más datos simulados
  ],
  monthlyAppointments: {
    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],
    values: [12, 19, 15, 20, 18, 24]
  }
};