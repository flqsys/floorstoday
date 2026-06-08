"use client"

import Link from "next/link"
import { ArrowRight } from "lucide-react"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function CTASection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-20 text-primary-foreground"
      style={{
        background: `linear-gradient(${settings.cta_bg_location}, ${settings.cta_bg_color_1}, ${settings.cta_bg_color_2})`,
      }}
      aria-labelledby="cta-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4 text-center">
        <h2 id="cta-heading" className="font-serif text-3xl font-bold sm:text-4xl lg:text-5xl">
          {settings.cta_title}
        </h2>
        <p className="mt-4 text-xl opacity-90">
          {settings.cta_subtitle}
        </p>
        <p className="mt-2 text-lg opacity-80 max-w-2xl mx-auto">
          {settings.cta_text}
        </p>
        <Link
          href="#estimate"
          className="mt-8 inline-flex min-h-12 items-center justify-center gap-2 rounded-md bg-secondary px-6 py-3 text-base font-bold text-secondary-foreground shadow-md transition-colors hover:bg-secondary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
        >
          <span>{settings.cta_button}</span>
          <ArrowRight className="h-4 w-4" aria-hidden="true" />
        </Link>
      </div>
    </section>
  )
}
