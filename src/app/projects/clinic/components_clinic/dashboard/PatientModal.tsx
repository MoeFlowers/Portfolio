// src/app/projects/clinic/components_clinic/dashboard/PatientModal.tsx
'use client';
import { useState } from 'react';
import { Patient } from './types';

export default function PatientModal({ onClose, onSave }: {
    onClose: () => void;
    onSave: (patient: Omit<Patient, 'id'>) => void;
}) {
    const [formData, setFormData] = useState<Omit<Patient, 'id'>>({
        primer_nombre: '',
        segundo_nombre: '',
        primer_apellido: '',
        segundo_apellido: '',
        cedula: '',
        fecha_nacimiento: '',
        genero: '',
        telefono: '',
        correo: '',
        direccion: '',
        alergias: '',
        tipo_sangre: ''
    });

    const [errors, setErrors] = useState<Record<string, string>>({});

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
        // Limpiar error al cambiar
        if (errors[name]) {
            setErrors(prev => ({ ...prev, [name]: '' }));
        }
    };

    const validateForm = () => {
        const newErrors: Record<string, string> = {};
        const requiredFields: (keyof Omit<Patient, 'id'>)[] = [
            'primer_nombre', 'primer_apellido', 'cedula',
            'fecha_nacimiento', 'genero', 'telefono', 'tipo_sangre'
        ];

        requiredFields.forEach(field => {
            if (!formData[field]) {
                newErrors[field] = 'Este campo es requerido';
            }
        });

        if (formData.cedula && !/^\d{7,}$/.test(formData.cedula)) {
            newErrors.cedula = 'La cédula debe tener al menos 7 dígitos';
        }

        if (formData.telefono && !/^\d{10,}$/.test(formData.telefono)) {
            newErrors.telefono = 'El teléfono debe tener al menos 10 dígitos';
        }

        if (formData.correo && !/^\S+@\S+\.\S+$/.test(formData.correo)) {
            newErrors.correo = 'Correo electrónico inválido';
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (validateForm()) {
            onSave(formData);
        }
    };

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                {/* Encabezado del modal */}
                <div className="bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                    <div className="flex items-center">
                        <div className="bg-blue-700 p-2 rounded-lg mr-3">
                            <i className="fas fa-user-plus text-white text-xl"></i>
                        </div>
                        <h3 className="text-xl font-semibold">Registro de Nuevo Paciente</h3>
                    </div>
                    <button onClick={onClose} className="text-white text-2xl">&times;</button>
                </div>

                {/* Formulario */}
                <form onSubmit={handleSubmit} className="p-6">
                    {/* Secciones del formulario... */}
                    {/* ... (mantén tus secciones existentes) ... */}

                    {/* Botones del formulario */}
                    <div className="flex justify-between pt-4">
                        <button
                            type="button"
                            onClick={onClose}
                            className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                        <div className="flex gap-3">
                            <button
                                type="button"
                                onClick={() => setFormData({
                                    primer_nombre: '',
                                    segundo_nombre: '',
                                    primer_apellido: '',
                                    segundo_apellido: '',
                                    cedula: '',
                                    fecha_nacimiento: '',
                                    genero: '',
                                    telefono: '',
                                    correo: '',
                                    direccion: '',
                                    alergias: '',
                                    tipo_sangre: ''
                                })}
                                className="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200"
                            >
                                Limpiar
                            </button>
                            <button
                                type="submit"
                                className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                Guardar Paciente
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
}