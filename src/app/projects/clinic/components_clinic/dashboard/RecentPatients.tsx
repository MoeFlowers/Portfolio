// src/app/projects/clinic/dashboard/components/RecentPatients.tsx
import Link from 'next/link';

export default function RecentPatients({ patients }: {
    patients: Array<{
        id: number;
        firstName: string;
        lastName: string;
        idNumber: string;
        registrationDate: string;
    }>
}) {
    return (
        <div className="bg-white shadow overflow-hidden sm:rounded-lg">
            <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 className="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd" />
                    </svg>
                    Pacientes recientes
                </h3>
            </div>
            <div className="divide-y divide-gray-200">
                {patients.map((patient) => {
                    const registrationDate = new Date(patient.registrationDate);
                    const formattedDate = registrationDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'long' });

                    return (
                        <div key={patient.id} className="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div className="flex items-center">
                                <div className="ml-4">
                                    <div className="text-sm font-medium text-gray-900">
                                        {patient.firstName} {patient.lastName}
                                    </div>
                                    <div className="text-sm text-gray-500">{patient.idNumber}</div>
                                </div>
                                <div className="ml-auto">
                                    <span className="text-xs text-gray-500">Registrado el {formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
            <div className="px-4 py-4 sm:px-6 border-t border-gray-200 text-center">
                <Link href="/projects/clinic/dashboard/patients" className="text-sm text-blue-600 hover:text-blue-800">
                    Ver todos los pacientes
                </Link>
            </div>
        </div>
    );
}