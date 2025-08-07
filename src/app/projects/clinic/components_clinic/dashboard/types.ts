// src/app/projects/clinic/dashboard/types.ts
export type User = {
  name: string;
  role: string;
};

export type Appointment = {
  id: number;
  dateTime: string;
  patientName: string;
  procedure: string;
  status: string;
};

export type Patient = {
  id: number;
  primer_nombre: string;
  segundo_nombre?: string;
  primer_apellido: string;
  segundo_apellido?: string;
  cedula: string;
  fecha_nacimiento: string;
  genero: string;
  telefono: string;
  correo?: string;
  direccion?: string;
  alergias?: string;
  tipo_sangre: string;
  fecha_registro?: string;
};

export type Procedure = {
  name: string;
  count: number;
};
