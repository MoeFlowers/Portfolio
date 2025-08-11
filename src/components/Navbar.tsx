"use client";
import Link from "next/link";
import { useState, useEffect } from "react";
import { usePathname } from "next/navigation";

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [cvLanguage, setCvLanguage] = useState<'ES' | 'EN'>('ES');
  const pathname = usePathname();

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 10);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  useEffect(() => {
    setIsOpen(false);
  }, [pathname]);

  const handleCvDownload = () => {
    const cvUrl = cvLanguage === 'ES'
      ? '/cv-moises-flores-es.pdf'
      : '/cv-moises-flores-en.pdf';

    const link = document.createElement('a');
    link.href = cvUrl;
    link.download = `CV-Moises-Flores-${cvLanguage}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const handleEmailClick = () => {
    window.location.href = "mailto:moeflowers2@gmail.com";
  };

  return (
    <header
      className={`sticky top-0 z-50 transition-all duration-300 ${scrolled
          ? "bg-white/90 dark:bg-gray-900/90 backdrop-blur-md shadow-md"
          : "bg-transparent backdrop-blur-sm"
        }`}
    >
      <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-20">

          {/* Logo */}
          <Link href="/" className="flex items-center space-x-1 group" aria-label="Inicio">
            <span className="text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#1DA1F2] transition-colors duration-200">
              MoisesFlores
            </span>
            <span className="text-gray-500 dark:text-gray-400 text-sm group-hover:text-[#1DA1F2] transition-colors duration-200">
              .dev
            </span>
          </Link>

          {/* Menú Desktop */}
          <div className="hidden md:flex items-center space-x-1">
            <NavLink href="/#about">Sobre mí</NavLink>
            <NavLink href="/#skills">Habilidades</NavLink>
            <NavLink href="/#projects">Proyectos</NavLink>
            <NavLink href="/#contact">Contacto</NavLink>

            {/* Redes Sociales */}
            <SocialIcon href="https://github.com/Moeflowers" label="GitHub">
              <GitHubIcon />
            </SocialIcon>
            <SocialIcon href="https://www.linkedin.com/in/moises-flores-09668b307" label="LinkedIn">
              <LinkedInIcon />
            </SocialIcon>
            <button
              onClick={handleEmailClick}
              className="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200 group"
              aria-label="Enviar email"
            >
              <EmailIcon />
            </button>

            {/* Botón de CV */}
            <div className="ml-4 relative group">
              <button
                onClick={handleCvDownload}
                className="border-2 border-[#1DA1F2] text-[#1DA1F2] px-4 py-2.5 rounded-full text-sm font-medium transition-all duration-200 flex items-center gap-2 hover:bg-[#1DA1F2] hover:text-white"
              >
                <DownloadIcon />
                Download CV
                <span className="text-xs opacity-80">{cvLanguage}</span>
              </button>

              {/* Selector de idioma */}
              <div className="absolute right-0 mt-1 w-32 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 transform z-10">
                <div className="py-1">
                  <CvLangOption lang="ES" active={cvLanguage} setLang={setCvLanguage} />
                  <CvLangOption lang="EN" active={cvLanguage} setLang={setCvLanguage} />
                </div>
              </div>
            </div>
          </div>

          {/* Botón Menú Móvil */}
          <div className="mb-4 md:hidden flex items-center space-x-2">
            <SocialIcon href="https://github.com/Moeflowers" label="GitHub">
              <GitHubIcon />
            </SocialIcon>
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#1DA1F2]"
              aria-label={isOpen ? "Cerrar menú" : "Abrir menú"}
              aria-expanded={isOpen}
            >
              {isOpen ? <XIcon className="h-6 w-6" /> : <MenuIcon className="h-6 w-6" />}
            </button>
          </div>
        </div>

        {/* Contenido Menú Móvil */}
        <div className={`md:hidden overflow-hidden transition-all duration-300 ease-in-out ${isOpen ? "max-h-[500px] py-4" : "max-h-0 py-0"}`}>
          <div className="flex flex-col space-y-3 px-2 pt-2">
            <MobileNavLink href="/#about">Sobre mí</MobileNavLink>
            <MobileNavLink href="/#skills">Habilidades</MobileNavLink>
            <MobileNavLink href="/#projects">Proyectos</MobileNavLink>
            <MobileNavLink href="/#contact">Contacto</MobileNavLink>
          </div>
        </div>
      </nav>
    </header>
  );
}

/* Subcomponentes */

function NavLink({ href, children }: { href: string; children: React.ReactNode }) {
  const pathname = usePathname();
  const isActive = pathname === href || (href !== '/' && pathname.startsWith(href));
  return (
    <Link
      href={href}
      className={`relative px-4 py-2.5 text-sm font-medium group ${isActive
          ? "text-[#1DA1F2]"
          : "text-gray-700 dark:text-gray-300"
        }`}
    >
      {children}
      <span className={`absolute left-0 bottom-0 h-[2px] bg-[#1DA1F2] transition-all duration-300 group-hover:w-full ${isActive ? "w-full" : "w-0"}`} />
    </Link>
  );
}

function MobileNavLink({ href, children }: { href: string; children: React.ReactNode }) {
  const pathname = usePathname();
  const isActive = pathname === href;
  return (
    <Link
      href={href}
      className={`px-4 py-3 rounded-lg text-sm transition-colors duration-200 ${isActive
          ? "bg-[#1DA1F2]/10 text-[#1DA1F2]"
          : "text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
        }`}
    >
      {children}
    </Link>
  );
}

function SocialIcon({ href, label, children }: { href: string; label: string; children: React.ReactNode }) {
  return (
    <a
      href={href}
      target="_blank"
      rel="noopener noreferrer"
      aria-label={label}
      className="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors duration-200 group"
    >
      {children}
    </a>
  );
}

function CvLangOption({ lang, active, setLang }: { lang: 'ES' | 'EN'; active: 'ES' | 'EN'; setLang: (lang: 'ES' | 'EN') => void }) {
  return (
    <div
      className={`flex justify-between items-center px-4 py-2 text-sm cursor-pointer ${active === lang ? 'bg-gray-100 dark:bg-gray-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700'
        } transition-colors duration-150`}
      onClick={() => setLang(lang)}
    >
      <span>{lang === 'ES' ? 'Español' : 'English'}</span>
      <span className="text-xs font-bold">{lang}</span>
    </div>
  );
}

/* Iconos */
function MenuIcon({ className }: { className?: string }) {
  return <svg className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>;
}

function XIcon({ className }: { className?: string }) {
  return <svg className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>;
}

function DownloadIcon({ className }: { className?: string }) {
  return <svg className={className || "h-4 w-4"} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>;
}

function GitHubIcon() {
  return <svg className="h-5 w-5 text-gray-700 dark:text-gray-300 group-hover:text-[#1DA1F2] transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor"><path fillRule="evenodd" clipRule="evenodd" d="M12 2C6.477..." /></svg>;
}

function LinkedInIcon() {
  return <svg className="h-5 w-5 text-gray-700 dark:text-gray-300 group-hover:text-[#1DA1F2] transition-colors duration-200" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447..." /></svg>;
}

function EmailIcon() {
  return <svg className="h-5 w-5 text-gray-700 dark:text-gray-300 group-hover:text-[#1DA1F2] transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89..." /></svg>;
}
