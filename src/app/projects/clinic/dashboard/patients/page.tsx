// src/app/projects/clinic/dashboard/patients/page.tsx
'use client';
import { useState, useEffect } from 'react';
import PatientsTable from '../../components_clinic/dashboard/PatientsTable';
import PatientModal from '../../components_clinic/dashboard/PatientModal';

type Patient = {
  id: number;
  primer_nombre: string;
  primer_apellido: string;
  cedula: string;
  telefono: string;
  fecha_nacimiento: string;
  genero: string;
  tipo_sangre: string;
};

type MessageType = {
  type: 'success' | 'error';
  text: string;
};

export default function PatientsPage() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [patients, setPatients] = useState<Patient[]>([]);
  const [message, setMessage] = useState<MessageType | null>(null);

  // Cargar pacientes desde localStorage
  useEffect(() => {
    const savedPatients = localStorage.getItem('clinic-patients');
    if (savedPatients) {
      setPatients(JSON.parse(savedPatients));
    } else {
      // Datos iniciales de ejemplo
      const initialPatients: Patient[] = [
        {
          id: 1,
          primer_nombre: 'Juan',
          primer_apellido: 'Pérez',
          cedula: '12345678',
          telefono: '3101234567',
          fecha_nacimiento: '1985-05-15',
          genero: 'Masculino',
          tipo_sangre: 'O+'
        }
      ];
      setPatients(initialPatients);
      localStorage.setItem('clinic-patients', JSON.stringify(initialPatients));
    }
  }, []);

  const handleSavePatient = (newPatient: Omit<Patient, 'id'>) => {
    const updatedPatients = [
      ...patients,
      {
        ...newPatient,
        id: patients.length > 0 ? Math.max(...patients.map(p => p.id)) + 1 : 1
      }
    ];
    setPatients(updatedPatients);
    localStorage.setItem('clinic-patients', JSON.stringify(updatedPatients));
    setMessage({ type: 'success', text: 'Paciente registrado exitosamente' });
    setIsModalOpen(false);
  };

  return (
    <div className="flex h-screen bg-gray-100">
      <div className="flex-1 overflow-auto">
        <main className="p-6">
          <div className="max-w-7xl mx-auto">
            {/* Header */}
            <div className="flex justify-between items-center mb-6">
              <h1 className="text-2xl font-bold text-gray-900">
                <i className="fas fa-users mr-2 text-blue-600"></i>
                Gestión de Pacientes
              </h1>
              <button
                onClick={() => setIsModalOpen(true)}
                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
              >
                <i className="fas fa-plus mr-2"></i>Nuevo Paciente
              </button>
            </div>

            {/* Tabla de pacientes */}
            <PatientsTable patients={patients} />
          </div>
        </main>
      </div>

      {/* Modal para agregar paciente */}
      {isModalOpen && (
        <PatientModal
          onClose={() => setIsModalOpen(false)}
          onSave={handleSavePatient}
        />
      )}

      {/* Mensaje de notificación */}
      {message && (
        <div className={`fixed top-4 right-4 p-4 rounded-md ${message.type === 'success' ? 'bg-green-500' : 'bg-red-500'
          } text-white`}>
          {message.text}
          <button
            onClick={() => setMessage(null)}
            className="ml-4"
          >
            ×
          </button>
        </div>
      )}
    </div>
  );
}