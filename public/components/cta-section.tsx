"use client"

import { Button } from "@/components/ui/button"
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
        <Button 
          asChild 
          size="lg" 
          className="mt-8 bg-secondary hover:bg-secondary/90 text-secondary-foreground"
        >
          <Link href="#estimate">
            {settings.cta_button}
            <ArrowRight className="ml-2 h-4 w-4" />
          </Link>
        </Button>
      </div>
    </section>
  )
}
