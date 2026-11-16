"use client";

import Link from "next/link";
import { trackPortfolioEvent, type PortfolioEvent, type PortfolioTarget } from "@/lib/analytics";
import type { ComponentProps } from "react";

type Props = ComponentProps<typeof Link> & {
  event: PortfolioEvent;
  targetName: PortfolioTarget;
};

export default function TrackedLink({ event, targetName, ...props }: Props) {
  return <Link {...props} onClick={() => trackPortfolioEvent(event, targetName)} />;
}
