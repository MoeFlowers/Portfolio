// src/app/projects/clinic/dashboard/mockData.ts
import { Patient } from "../components_clinic/dashboard/types";

export const mockData = {
  user: {
    name: "Dr. Juan Pérez",
    role: "Odontólogo",
  },
  stats: {
    totalPatients: 1245,
    totalEmployees: 15, // Nuevo campo requerido
    todayAppointments: 8, // Nuevo campo requerido
    clinicalRecords: 342, // Nuevo campo requerido
    monthlyRevenue: 12500, // Campo opcional
  },
  appointments: [
    {
      id: 1,
      dateTime: "2023-11-15T09:30:00",
      patientName: "María González",
      procedure: "Limpieza dental",
      status: "Programada",
    },
  ],
  recentPatients: [
    {
      id: 1,
      primer_nombre: "Carlos",
      primer_apellido: "Rodríguez",
      cedula: "V-12345678",
      fecha_registro: "2023-11-10",
    },
    {
      id: 2,
      primer_nombre: "María",
      primer_apellido: "González",
      cedula: "V-87654321",
      fecha_registro: "2023-11-08",
    },
  ] as Patient[],
  monthlyAppointments: {
    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],
    data: [12, 19, 15, 8, 12, 17],
  },
  commonProcedures: [
    { name: "Limpieza dental", count: 45 },
    { name: "Extracción", count: 32 },
  ],
};
