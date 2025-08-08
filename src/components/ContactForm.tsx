"use client";
import { useState } from "react";
import { FiSend, FiUser, FiMail, FiMessageSquare, FiCheckCircle } from "react-icons/fi";
import { motion } from "framer-motion";

export default function ContactForm() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    message: "",
  });
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSuccess, setIsSuccess] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    // Simulación de envío
    await new Promise(resolve => setTimeout(resolve, 1500));
    console.log("Form submitted:", formData);

    setIsSubmitting(false);
    setIsSuccess(true);
    setFormData({ name: "", email: "", message: "" });

    // Resetear estado de éxito después de 3 segundos
    setTimeout(() => setIsSuccess(false), 3000);
  };

  return (
    <section id="contact" className="py-20 px-4 bg-white dark:bg-gray-900">
      <div className="max-w-4xl mx-auto">
        {/* Encabezado */}
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            <span className="text-[#1DA1F2]">Contáctame</span>
          </h2>
          <p className="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
            ¿Tienes un proyecto en mente? Envíame un mensaje y hablemos sobre cómo puedo ayudarte.
          </p>
        </div>

        {/* Formulario */}
        <div className="bg-gray-50 dark:bg-gray-800/50 p-8 md:p-10 rounded-xl border border-gray-200 dark:border-gray-700">
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Campo Nombre */}
            <div className="group">
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nombre completo
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <FiUser className="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-[#1DA1F2]" />
                </div>
                <input
                  type="text"
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="block w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-[#1DA1F2] focus:border-transparent transition-all"
                  placeholder="Ej: Juan Pérez"
                  required
                />
              </div>
            </div>

            {/* Campo Email */}
            <div className="group">
              <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Correo electrónico
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <FiMail className="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-[#1DA1F2]" />
                </div>
                <input
                  type="email"
                  id="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="block w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-[#1DA1F2] focus:border-transparent transition-all"
                  placeholder="Ej: juan@ejemplo.com"
                  required
                />
              </div>
            </div>

            {/* Campo Mensaje */}
            <div className="group">
              <label htmlFor="message" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Mensaje
              </label>
              <div className="relative">
                <div className="absolute top-3 left-3">
                  <FiMessageSquare className="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-[#1DA1F2]" />
                </div>
                <textarea
                  id="message"
                  rows={5}
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className="block w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-[#1DA1F2] focus:border-transparent transition-all"
                  placeholder="Cuéntame sobre tu proyecto..."
                  required
                />
              </div>
            </div>

            {/* Botón de envío */}
            <div className="pt-2">
              <motion.button
                type="submit"
                disabled={isSubmitting || isSuccess}
                className={`flex items-center justify-center gap-2 w-full px-6 py-4 rounded-lg font-medium transition-all ${isSubmitting || isSuccess
                  ? "bg-[#1DA1F2]/80 cursor-not-allowed"
                  : "bg-[#1DA1F2] hover:bg-[#1a8cd8] shadow-lg hover:shadow-[#1DA1F2]/30"
                  }`}
                whileHover={!(isSubmitting || isSuccess) ? { scale: 1.02 } : {}}
                whileTap={!(isSubmitting || isSuccess) ? { scale: 0.98 } : {}}
              >
                {isSuccess ? (
                  <>
                    <FiCheckCircle className="w-5 h-5 text-white" />
                    <span className="text-white">¡Mensaje enviado!</span>
                  </>
                ) : isSubmitting ? (
                  <span className="text-white">Enviando...</span>
                ) : (
                  <>
                    <FiSend className="w-5 h-5 text-white" />
                    <span className="text-white">Enviar mensaje</span>
                  </>
                )}
              </motion.button>
            </div>
          </form>

          {/* Mensaje alternativo */}
          <div className="mt-8 text-center">
            <p className="text-gray-600 dark:text-gray-400">
              También puedes contactarme directamente a{' '}
              <a href="mailto:moeflowers2@gmail.com" className="text-[#1DA1F2] hover:underline">
                moeflowers2@gmail.com
              </a>
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}