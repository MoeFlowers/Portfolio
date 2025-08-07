// src/app/projects/clinic/components_clinic/dashboard/MonthlyAppointmentsChart.tsx
'use client';
import { useEffect, useRef } from 'react';
import { Chart, registerables } from 'chart.js';

// Registrar todos los componentes de Chart.js
Chart.register(...registerables);

type ChartData = {
    labels: string[];
    data: number[];
};

export default function MonthlyAppointmentsChart({ data }: { data: ChartData }) {
    const chartRef = useRef<HTMLCanvasElement>(null);
    const chartInstance = useRef<Chart | null>(null);

    useEffect(() => {
        if (chartRef.current) {
            const ctx = chartRef.current.getContext('2d');
            if (ctx) {
                // Destruir la instancia anterior si existe
                if (chartInstance.current) {
                    chartInstance.current.destroy();
                }

                chartInstance.current = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Citas',
                            data: data.data,
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        }

        // Limpieza al desmontar el componente
        return () => {
            if (chartInstance.current) {
                chartInstance.current.destroy();
            }
        };
    }, [data]);

    return (
        <div className="bg-white shadow overflow-hidden sm:rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
            <div className="flex justify-between items-center mb-4">
                <h3 className="text-lg font-medium text-gray-900 flex items-center">
                    {/* Icono SVG aquí */}
                    Citas mensuales
                </h3>
                <select
                    className="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    onChange={(e) => {
                        console.log('Cambiar a:', e.target.value, 'meses');
                    }}
                >
                    <option value="6">Últimos 6 meses</option>
                    <option value="12">Este año</option>
                    <option value="24">Último año</option>
                </select>
            </div>
            <div className="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
                <canvas ref={chartRef} width="400" height="300"></canvas>
            </div>
        </div>
    );
}