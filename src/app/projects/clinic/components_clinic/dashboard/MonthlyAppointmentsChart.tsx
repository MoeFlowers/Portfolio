// src/app/projects/clinic/dashboard/components/MonthlyAppointmentsChart.tsx
'use client';
import { useEffect, useRef } from 'react';
import Chart from 'chart.js/auto';

export default function MonthlyAppointmentsChart({ data }: {
    data: {
        labels: string[];
        data: number[];
    }
}) {
    const chartRef = useRef<HTMLCanvasElement>(null);
    const chartInstance = useRef<Chart>();

    useEffect(() => {
        if (chartRef.current) {
            const ctx = chartRef.current.getContext('2d');
            if (ctx) {
                // Destroy previous chart instance if it exists
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

        // Cleanup function to destroy chart instance
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
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                    </svg>
                    Citas mensuales
                </h3>
                <select
                    className="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    onChange={(e) => {
                        // Aquí implementarías la lógica para cambiar el rango de meses
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