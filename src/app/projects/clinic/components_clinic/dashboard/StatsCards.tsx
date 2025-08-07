// src/app/projects/clinic/dashboard/components/StatsCards.tsx
import {
    UserGroupIcon,
    UsersIcon,
    CalendarIcon,
    DocumentTextIcon,
    CurrencyDollarIcon
} from '@heroicons/react/24/outline';

type StatsCardsProps = {
    stats: {
        totalPatients: number;
        totalEmployees: number;
        todayAppointments: number;
        clinicalRecords: number;
        monthlyRevenue?: number; // Opcional si quieres mantenerlo
    };
};

export default function StatsCards({ stats }: StatsCardsProps) {
    const cards = [
        {
            title: "Total Patients",
            value: stats.totalPatients,
            icon: UserGroupIcon,
            color: "blue"
        },
        {
            title: "Total Staff",
            value: stats.totalEmployees,
            icon: UsersIcon,
            color: "green"
        },
        {
            title: "Today's Appointments",
            value: stats.todayAppointments,
            icon: CalendarIcon,
            color: "purple"
        },
        {
            title: "Clinical Records",
            value: stats.clinicalRecords,
            icon: DocumentTextIcon,
            color: "yellow"
        },
        // Opcional: agregar tarjeta de ingresos si monthlyRevenue existe
        ...(stats.monthlyRevenue ? [{
            title: "Monthly Revenue",
            value: `$${stats.monthlyRevenue.toLocaleString()}`,
            icon: CurrencyDollarIcon,
            color: "emerald"
        }] : [])
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {cards.map((card) => (
                <div key={card.title} className="bg-white overflow-hidden shadow rounded-lg">
                    <div className="px-4 py-5 sm:p-6">
                        <div className="flex items-center">
                            <div className={`flex-shrink-0 bg-${card.color}-100 rounded-md p-3`}>
                                <card.icon className={`h-6 w-6 text-${card.color}-600`} />
                            </div>
                            <div className="ml-4">
                                <dt className="text-sm font-medium text-gray-500 truncate">
                                    {card.title}
                                </dt>
                                <dd className="mt-1 text-3xl font-semibold text-gray-900">
                                    {card.value}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}