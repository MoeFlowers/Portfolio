import { track } from "@vercel/analytics";

export type PortfolioEvent = "portfolio_route" | "portfolio_cv" | "portfolio_contact" | "portfolio_contact_sent";
export type PortfolioTarget = "career" | "project" | "general" | "cv-en" | "cv-es";

export function trackPortfolioEvent(event: PortfolioEvent, target: PortfolioTarget) {
  if (process.env.NEXT_PUBLIC_PORTFOLIO_CUSTOM_EVENTS === "true") track(event, { target });
}
