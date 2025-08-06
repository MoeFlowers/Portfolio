import Navbar from './components_clinic/ladingpage/Navbar'
import HeroSection from './components_clinic/ladingpage/HeroSection';
import FeatureSection from './components_clinic/ladingpage/FeatureSection';
import Footer from './components_clinic/ladingpage/Footer';
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'MF - Clínica Odontológica', 
};

export default function ClinicProject() {
  return (
    <div className="bg-gradient-to-br from-blue-50 to-cyan-50 min-h-screen">
      <Navbar />
      <HeroSection />
      <FeatureSection />
      <Footer />
    </div>
  );
}