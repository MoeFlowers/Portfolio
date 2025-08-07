'use client'; // ¡No olvides esta directiva!

import { useEffect } from "react";

export const CleanDOM = () => {
  useEffect(() => {
    const cleanDOM = () => {
      document.querySelectorAll(
        "[bis_skin_checked], [bis_register], [__processed__]"
      ).forEach(el => {
        el.removeAttribute("bis_skin_checked");
        el.removeAttribute("bis_register");
        el.removeAttribute("__processed__");
      });
    };

    // Limpiar inmediatamente
    cleanDOM();

    // Observar cambios futuros
    const observer = new MutationObserver(cleanDOM);
    observer.observe(document.body, {
      attributes: true,
      subtree: true,
      attributeFilter: ["bis_skin_checked", "bis_register", "__processed__"]
    });

    return () => observer.disconnect(); // ¡Importante limpiar el observer!
  }, []);

  return null; // Este componente no renderiza nada
};