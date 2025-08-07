// src/app/projects/clinic/dashboard/components/StatsCards.tsx
export default function StatsCards({ stats }: {
  stats: {
    totalPatients: number;
    totalEmployees: number;
    todayAppointments: number;
    clinicalRecords: number;
  }
}) {
  const cards = [
    { title: "Total pacientes", value: stats.totalPatients },
    { title: "Total empleados", value: stats.totalEmployees },
    { title: "Citas hoy", value: stats.todayAppointments },
    { title: "Historias clínicas", value: stats.clinicalRecords }
  ];

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      {cards.map((card) => (
        <div key={card.title} className="bg-white overflow-hidden shadow rounded-lg">
          <div className="px-4 py-5 sm:p-6">
            <dt className="text-sm font-medium text-gray-500 truncate">{card.title}</dt>
            <dd className="mt-1 text-3xl font-semibold text-gray-900">
              {card.value}
            </dd>
          </div>
        </div>
      ))}
    </div>
  );
}