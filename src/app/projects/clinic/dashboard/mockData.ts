// src/app/projects/clinic/dashboard/data/mockData.ts
export const mockData = {
  user: {
    name: "Dr. John Smith",
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
      patientName: "Maria Gonzalez",
      procedure: "Dental cleaning",
      date: "2023-11-15T10:00:00",
      status: "Scheduled"
    },
    {
      id: 2,
      patientName: "Robert Johnson",
      procedure: "Tooth extraction",
      date: "2023-11-16T14:30:00",
      status: "Confirmed"
    }
  ],
  monthlyAppointments: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
    values: [12, 19, 15, 20, 18, 24]
  }
};