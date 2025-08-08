import type { Metadata } from "next";
import { CleanDOM } from "./CleanDOM";
import { Inter, Roboto_Mono } from "next/font/google";
import { ThemeProvider } from "next-themes";
import "./globals.css";

// Configuración de fuentes
const inter = Inter({
  subsets: ["latin"],
  variable: "--font-inter",
  display: "swap",
});

const robotoMono = Roboto_Mono({
  subsets: ["latin"],
  variable: "--font-roboto-mono",
  display: "swap",
});

export const metadata: Metadata = {
  title: "Moises Flores",
  description: "Full-Stack Developer",
  other: {
    "bis_extension": "disable", // Bloquea extensiones de Microsoft/Bing
    "x-extension": "disable"   // Protección adicional
  }
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {

  return (
    <html lang="es" suppressHydrationWarning>
      <body className={`${inter.variable} ${robotoMono.variable} font-sans bg-white dark:bg-gray-900 transition-colors duration-200`}>
        <CleanDOM />
        <ThemeProvider
          attribute="class"
          defaultTheme="system"
          enableSystem
          disableTransitionOnChange
        >
          <main className="min-h-screen">
            {children}
          </main>
        </ThemeProvider>
        <script dangerouslySetInnerHTML={{
          __html: `
    document.addEventListener('DOMContentLoaded', () => {
      const elements = document.querySelectorAll('[bis_skin_checked]');
      elements.forEach(el => el.removeAttribute('bis_skin_checked'));
    });
  `
        }} />
      </body>
    </html>
  );
}