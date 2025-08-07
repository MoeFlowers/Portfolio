// src/app/projects/clinic/dashboard/data/mockData.ts
import type { Patient, Procedure } from "../components_clinic/dashboard/types";

export const mockData = {
  user: {
    name: "Dr. John Smith",
    role: "Dentist",
  },
  stats: {
    totalPatients: 124,
    totalEmployees: 15,
    todayAppointments: 8,
    clinicalRecords: 230,
  },
  upcomingAppointments: [
    {
      id: 1,
      patientName: "Maria Gonzalez",
      procedure: "Dental cleaning",
      dateTime: "2023-11-15T10:00:00",
      status: "Scheduled",
    },
    // ... other appointments
  ],
  recentPatients: [
    {
      id: 1,
      firstName: "Maria",
      lastName: "Gonzalez",
      idNumber: "ID123456",
      registrationDate: "2023-10-15",
    },
    // ... other recent patients
  ] as Patient[],
  commonProcedures: [
    { name: "Dental cleaning", count: 45 },
    { name: "Tooth extraction", count: 23 },
    // ... other procedures
  ] as Procedure[],
  monthlyAppointments: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
    data: [12, 19, 15, 20, 18, 24], // Changed from 'values' to 'data'
  },
};
