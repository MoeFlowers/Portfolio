// src/app/projects/clinic/dashboard/patients/page.tsx
'use client';
import { useState, useEffect } from 'react';
import PatientsTable from './PatientsTable';
import PatientModal from './PatientModal';
import { useSession } from 'next-auth/react';
import { redirect } from 'next/navigation';

export default function PatientsPage() {
  const { data: session, status } = useSession();
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [patients, setPatients] = useState([]);
  const [message, setMessage] = useState<{type: string, text: string} | null>(null);

  // Verificar autenticación
  if (status === 'unauthenticated') {
    redirect('/projects/clinic/login');
  }

  // Cargar pacientes (simulado)
  useEffect(() => {
    // En una aplicación real, harías una llamada a la API aquí
    const fetchPatients = async () => {
      try {
        // Simulando datos de pacientes
        const mockPatients = [
          {
            id: 1,
            primer_nombre: 'Juan',
            primer_apellido: 'Pérez',
            cedula: '12345678',
            telefono: '3101234567',
            fecha_nacimiento: '1985-05-15'
          },
          // ... más pacientes de ejemplo
        ];
        setPatients(mockPatients);
      } catch (error) {
        console.error('Error fetching patients:', error);
      }
    };

    fetchPatients();
  }, []);

  // Mostrar mensaje si existe
  useEffect(() => {
    if (message) {
      // Aquí implementarías SweetAlert2 o similar
      console.log(`Mostrando mensaje: ${message.type} - ${message.text}`);
      // Limpiar el mensaje después de mostrarlo
      const timer = setTimeout(() => setMessage(null), 5000);
      return () => clearTimeout(timer);
    }
  }, [message]);

  return (
    <div className="flex h-screen bg-gray-100">
      {/* Sidebar se incluye en el layout */}
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
          onSave={(newPatient) => {
            // Aquí manejarías el guardado del paciente
            console.log('Paciente a guardar:', newPatient);
            setMessage({type: 'success', text: 'Paciente registrado exitosamente'});
            setIsModalOpen(false);
          }}
        />
      )}
    </div>
  );
}