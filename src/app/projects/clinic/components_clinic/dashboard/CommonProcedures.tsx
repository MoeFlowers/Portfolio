// src/app/projects/clinic/dashboard/components/CommonProcedures.tsx
export default function CommonProcedures({ procedures }: {
    procedures: Array<{
        name: string;
        count: number;
    }>
}) {
    return (
        <div className="bg-white shadow overflow-hidden sm:rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
            <h3 className="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                </svg>
                Procedimientos más solicitados
            </h3>
            <ul className="space-y-3">
                {procedures.length > 0 ? (
                    procedures.map((procedure, index) => (
                        <li key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-100">
                            <span className="font-medium text-gray-800">{procedure.name}</span>
                            <span className="text-sm text-gray-500">{procedure.count} citas</span>
                        </li>
                    ))
                ) : (
                    <p className="text-sm text-gray-500">No hay datos disponibles.</p>
                )}
            </ul>
        </div>
    );
}