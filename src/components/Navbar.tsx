"use client";
import Link from "next/link";
import { useState } from "react";

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <header className="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
      <nav className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          {/* Logo */}
          <Link 
            href="/" 
            className="flex items-center space-x-1 group"
            onClick={() => setIsOpen(false)}
          >
            <span className="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:opacity-80 transition-opacity duration-150">
              MoisesFlores
            </span>
            <span className="text-gray-500 dark:text-gray-400 text-sm group-hover:opacity-80 transition-opacity duration-150">
              .dev
            </span>
          </Link>

          {/* Menú Desktop */}
          <div className="hidden md:flex items-center space-x-2">
            <NavLink href="#projects" onClick={() => setIsOpen(false)}>
              Proyects
            </NavLink>
            <NavLink href="#about" onClick={() => setIsOpen(false)}>
              About me
            </NavLink>
            <NavLink href="#contact" onClick={() => setIsOpen(false)}>
              Contact
            </NavLink>
            <button className="ml-4 bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-600 transition-all duration-200 active:scale-95">
              Download CV
            </button>
          </div>

          {/* Menú Mobile - Hamburguesa */}
          <div className="md:hidden flex items-center">
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200"
              aria-label="Menu"
            >
              {isOpen ? (
                <XIcon />
              ) : (
                <MenuIcon />
              )}
            </button>
          </div>
        </div>

        {/* Menú Mobile - Contenido */}
        {isOpen && (
          <div className="md:hidden pb-4">
            <div className="flex flex-col space-y-2 px-2 pt-2">
              <MobileNavLink href="#projects" onClick={() => setIsOpen(false)}>
                Proyects
              </MobileNavLink>
              <MobileNavLink href="#about" onClick={() => setIsOpen(false)}>
                About me
              </MobileNavLink>
              <MobileNavLink href="#contact" onClick={() => setIsOpen(false)}>
                Contact
              </MobileNavLink>
              <button className="mt-2 bg-gray-900 dark:bg-gray-700 text-white px-4 py-3 rounded-full text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-600 transition-all duration-200 active:scale-95">
                Download CV
              </button>
            </div>
          </div>
        )}
      </nav>
    </header>
  );
}

// Componente para enlaces de escritorio (estilo X/Twitter)
function NavLink({ href, onClick, children }: { href: string; onClick: () => void; children: React.ReactNode }) {
  return (
    <Link
      href={href}
      onClick={onClick}
      className="relative px-4 py-2 rounded-full group transition-all duration-200"
    >
      <span className="text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors duration-200">
        {children}
      </span>
      <span className="absolute inset-0 bg-gray-200 dark:bg-gray-800 rounded-full opacity-0 group-hover:opacity-100 -z-10 transition-opacity duration-200" />
    </Link>
  );
}

// Componente para enlaces móviles
function MobileNavLink({ href, onClick, children }: { href: string; onClick: () => void; children: React.ReactNode }) {
  return (
    <Link
      href={href}
      onClick={onClick}
      className="px-4 py-3 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
    >
      {children}
    </Link>
  );
}

// Icono de menú (hamburguesa)
function MenuIcon() {
  return (
    <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  );
}

// Icono de cerrar (X)
function XIcon() {
  return (
    <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M6 18L18 6M6 6l12 12" />
    </svg>
  );
}