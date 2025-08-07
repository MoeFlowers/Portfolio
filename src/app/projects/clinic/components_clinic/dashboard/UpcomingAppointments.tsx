// src/app/projects/clinic/dashboard/components/UpcomingAppointments.tsx
import Link from 'next/link';


export default function UpcomingAppointments({ appointments }: {
    appointments: Array<{
        id: number;
        dateTime: string;
        patientName: string;
        procedure: string;
        status: string;
    }>
}) {
    return (
        <div className="bg-white shadow overflow-hidden sm:rounded-lg">
            <div className="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <h3 className="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-blue-600 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                    </svg>
                    Citas próximas
                </h3>
                <Link href="/projects/clinic/dashboard/appointments" className="text-sm text-blue-600 hover:text-blue-800">
                    Ver todas
                </Link>
            </div>
            <div className="divide-y divide-gray-200">
                {appointments.map((appointment) => {
                    const date = new Date(appointment.dateTime);
                    const day = date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
                    const time = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });

                    return (
                        <div key={appointment.id} className="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center">
                                    <div className="ml-4">
                                        <div className="text-sm font-medium text-gray-900">
                                            {appointment.patientName}
                                        </div>
                                        <div className="text-sm text-gray-500">{appointment.procedure}</div>
                                    </div>
                                </div>
                                <div className="ml-4">
                                    <div className="text-sm font-medium text-gray-900">{time}</div>
                                    <div className="text-sm text-gray-500">{day}</div>
                                </div>
                                <div>
                                    <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {appointment.status}
                                    </span>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}