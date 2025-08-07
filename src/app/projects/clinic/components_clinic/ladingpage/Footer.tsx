import { ReactElement } from 'react';
import Image from 'next/image';

export default function Footer(): ReactElement {
  return (
    <footer className="bg-blue-900 text-white py-12" id="contact">
      <div className="max-w-6xl mx-auto px-4">
        <div className="grid md:grid-cols-4 gap-8">
          {/* Column 1: Logo */}
          <div>
            <Image
              src="/projects/clinic/assets/images/logo-removebg-preview.png"
              width={48}
              height={48}
              alt="IPSFANB Logo"
              className="h-12 mb-4"
            />
            <p className="text-blue-200 text-sm">
              Dental Clinic specialized in military personnel care.
            </p>
          </div>

          {/* Column 2: Services */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Services</h4>
            <ul className="space-y-2 text-blue-200 text-sm">
              <li>Dental Emergencies</li>
              <li>Pediatric Dentistry</li>
              <li>Orthodontics</li>
              <li>Oral Surgery</li>
              <li className="mt-2">
                <a href="#services" className="text-cyan-400 hover:underline">
                  See all →
                </a>
              </li>
            </ul>
          </div>

          {/* Column 3: Contact */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Contact</h4>
            <address className="text-blue-200 text-sm not-italic">
              <p className="font-medium text-white">IPSFA Barquisimeto</p>
              <p className="mt-1">
                Av. Venezuela between Av. Argimiro and Av. Los Leones
              </p>
              <p className="mt-2 flex items-center">
                <svg
                  className="w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                  />
                </svg>
                (0251) 254.03.38
              </p>
              <p className="mt-1 flex items-center">
                <svg
                  className="w-4 h-4 mr-2"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                clinicaodontologicaccs@gmail.com
              </p>
            </address>
          </div>

          {/* Column 4: Schedule */}
          <div>
            <h4 className="text-lg font-semibold mb-4">Schedule</h4>
            <ul className="text-blue-200 text-sm space-y-2">
              <li className="flex flex-col">
                <span className="font-medium">Morning:</span>
                <span>08:00 AM - 12:00 PM</span>
              </li>
              <li className="flex flex-col">
                <span className="font-medium">Afternoon:</span>
                <span>01:00 PM - 04:00 PM</span>
              </li>
              <li className="mt-4 text-cyan-400">
                <a href="#" className="hover:underline">
                  Terms and Conditions
                </a>
              </li>
            </ul>
          </div>
        </div>

        {/* Copyright */}
        <div className="border-t border-blue-800 mt-8 pt-8 text-center text-blue-300 text-sm">
          <p>
            © 2023 IPSFA Barquisimeto Dental Clinic. All rights reserved.
          </p>
          <p className="mt-1">
            Director: My. Janette Sofia Gandica Zambrano
          </p>
        </div>
      </div>
    </footer>
  );
}