import { ReactElement } from 'react';

export default function FeatureSection(): ReactElement {
    return (
        <section className="py-16 bg-white">
            <div className="max-w-6xl mx-auto px-4">
                <h2 className="text-3xl font-bold text-center text-blue-900 mb-12">
                    Características Principales
                </h2>
                <div className="grid md:grid-cols-3 gap-8">
                    {/* Tarjeta 1 */}
                    <div className="bg-blue-50 p-6 rounded-xl">
                        <div className="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                strokeWidth="1.5"
                                stroke="currentColor"
                                className="w-6 h-6 text-blue-600"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"
                                />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-blue-800 mb-2">
                            Historiales Digitales
                        </h3>
                        <p className="text-gray-600">
                            Acceso rápido y seguro a los historiales clínicos dentales.
                        </p>
                    </div>

                    {/* Tarjeta 2 */}
                    <div className="bg-blue-50 p-6 rounded-xl">
                        <div className="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                strokeWidth="1.5"
                                stroke="currentColor"
                                className="w-6 h-6 text-blue-600"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-blue-800 mb-2">
                            Seguridad Garantizada
                        </h3>
                        <p className="text-gray-600">
                            Protección de datos con cifrado avanzado.
                        </p>
                    </div>

                    {/* Tarjeta 3 */}
                    <div className="bg-blue-50 p-6 rounded-xl">
                        <div className="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                strokeWidth="1.5"
                                stroke="currentColor"
                                className="w-6 h-6 text-blue-600"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                                />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-blue-800 mb-2">
                            Agenda Integrada
                        </h3>
                        <p className="text-gray-600">
                            Gestión de citas y recordatorios automáticos.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    );
}