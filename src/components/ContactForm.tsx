"use client";
import { useState } from "react";
import { motion, Variants } from "framer-motion";
import { FiSend, FiUser, FiMail, FiMessageSquare } from "react-icons/fi";

export default function ContactForm() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    message: "",
  });
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    // Simulación de envío (reemplazar con tu API)
    await new Promise(resolve => setTimeout(resolve, 1500));
    console.log("Form submitted:", formData);

    setIsSubmitting(false);
    setFormData({ name: "", email: "", message: "" });
  };

  const containerVariants: Variants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1,
        delayChildren: 0.2
      }
    }
  };

  const itemVariants: Variants = {
    hidden: { y: 20, opacity: 0 },
    visible: {
      y: 0,
      opacity: 1,
      transition: {
        type: "spring",
        stiffness: 100
      }
    }
  };

  return (
    <motion.form
      onSubmit={handleSubmit}
      className="max-w-lg mx-auto space-y-6"
      initial="hidden"
      animate="visible"
      variants={containerVariants}
      viewport={{ once: true, margin: "-100px" }}
    >
      <motion.div variants={itemVariants}>
        <div className="relative">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <FiUser className="text-gray-400 dark:text-gray-500" />
          </div>
          <input
            type="text"
            id="name"
            value={formData.name}
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
            placeholder="Tu nombre completo"
            required
          />
        </div>
      </motion.div>

      <motion.div variants={itemVariants}>
        <div className="relative">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <FiMail className="text-gray-400 dark:text-gray-500" />
          </div>
          <input
            type="email"
            id="email"
            value={formData.email}
            onChange={(e) => setFormData({ ...formData, email: e.target.value })}
            className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
            placeholder="tucorreo@ejemplo.com"
            required
          />
        </div>
      </motion.div>

      <motion.div variants={itemVariants}>
        <div className="relative">
          <div className="absolute top-3 left-3">
            <FiMessageSquare className="text-gray-400 dark:text-gray-500" />
          </div>
          <textarea
            id="message"
            rows={5}
            value={formData.message}
            onChange={(e) => setFormData({ ...formData, message: e.target.value })}
            className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
            placeholder="Escribe tu mensaje aquí..."
            required
          />
        </div>
      </motion.div>

      <motion.div variants={itemVariants}>
        <motion.button
          type="submit"
          disabled={isSubmitting}
          className={`flex items-center justify-center gap-2 w-full px-6 py-3 rounded-full transition-all ${isSubmitting
              ? "bg-gray-400 dark:bg-gray-600 cursor-not-allowed"
              : "bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 hover:shadow-lg"
            }`}
          whileHover={!isSubmitting ? { scale: 1.02 } : {}}
          whileTap={!isSubmitting ? { scale: 0.98 } : {}}
        >
          <span className="text-white font-medium">
            {isSubmitting ? "Enviando..." : "Enviar Mensaje"}
          </span>
          {!isSubmitting && (
            <FiSend className="w-4 h-4 text-white animate-pulse" />
          )}
        </motion.button>
      </motion.div>

      {isSubmitting && (
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          className="text-center text-green-500 text-sm mt-2"
        >
          Gracias por tu mensaje. ¡Te responderé pronto!
        </motion.div>
      )}
    </motion.form>
  );
}