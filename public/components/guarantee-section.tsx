"use client"

import Link from "next/link"
import { Shield, ArrowRight } from "lucide-react"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function GuaranteeSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-20"
      style={{
        background: `linear-gradient(${settings.guarantee_bg_location}, ${settings.guarantee_bg_color_1}, ${settings.guarantee_bg_color_2})`,
      }}
      aria-labelledby="guarantee-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="grid gap-12 lg:grid-cols-2 items-center">
          <div className="relative">
            <img
              src={settings.guarantee_image}
              alt={settings.guarantee_title}
              width={600}
              height={400}
              loading="lazy"
              decoding="async"
              className="rounded-2xl shadow-lg"
            />
            <div className="absolute -bottom-6 -right-6 bg-secondary text-secondary-foreground p-4 rounded-xl shadow-lg hidden md:block">
              <Shield className="h-8 w-8" />
            </div>
          </div>

          <div>
            <h2 id="guarantee-heading" className="font-serif text-3xl font-bold text-foreground sm:text-4xl">
              {settings.guarantee_title}
            </h2>
            <p className="mt-2 text-xl font-semibold text-secondary">
              {settings.guarantee_subtitle}
            </p>
            <p className="mt-6 text-lg text-muted-foreground leading-relaxed">
              {settings.guarantee_text}
            </p>
            <Link
              href="#guarantee-details"
              className="inline-flex items-center gap-2 mt-6 text-primary font-semibold hover:underline"
            >
              {settings.guarantee_link}
              <ArrowRight className="h-4 w-4" />
            </Link>
          </div>
        </div>
      </div>
    </section>
  )
}
