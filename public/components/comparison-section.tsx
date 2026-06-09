"use client"

import { Check, HelpCircle } from "lucide-react"
import Link from "next/link"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function ComparisonSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-12 text-primary-foreground sm:py-16 lg:py-20"
      style={{
        background: `linear-gradient(${settings.comparison_bg_location}, ${settings.comparison_bg_color_1}, ${settings.comparison_bg_color_2})`,
      }}
      aria-labelledby="comparison-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="grid items-center gap-8 lg:grid-cols-2 lg:gap-12">
          {/* Left Content */}
          <div>
            <h2 id="comparison-heading" className="font-serif text-3xl font-bold sm:text-4xl lg:text-5xl">
              {settings.comparison_title}
            </h2>
            <p className="mt-4 text-base leading-relaxed opacity-90 sm:mt-6 sm:text-lg">
              {settings.comparison_text}
            </p>
          </div>

          {/* Right Comparison Table */}
          <div className="overflow-hidden rounded-lg bg-card text-card-foreground shadow-xl">
            <div className="grid grid-cols-[1.5fr_0.9fr_0.7fr] border-b border-border text-center text-xs font-bold sm:text-base">
              <div className="px-2 py-4 sm:px-5 sm:py-5" />
              <div className="bg-secondary px-2 py-4 text-secondary-foreground sm:whitespace-nowrap sm:px-5 sm:py-5">Floors Today</div>
              <div className="px-2 py-4 sm:px-5 sm:py-5">Others</div>
            </div>
            
            {settings.comparison_rows.map((feature, index) => (
              <div
                key={feature}
                className={`grid grid-cols-[1.5fr_0.9fr_0.7fr] items-stretch ${
                  index !== settings.comparison_rows.length - 1 ? "border-b border-border" : ""
                }`}
              >
                <div className="flex items-center px-2 py-3 text-xs font-semibold leading-snug sm:px-5 sm:py-4 sm:text-base">{feature}</div>
                <div className="flex items-center justify-center bg-primary/5 px-2 py-3 sm:px-5 sm:py-4">
                  <Check className="h-5 w-5 text-secondary sm:h-6 sm:w-6" />
                </div>
                <div className="flex items-center justify-center px-2 py-3 sm:px-5 sm:py-4">
                  <HelpCircle className="h-5 w-5 text-muted-foreground sm:h-6 sm:w-6" />
                </div>
              </div>
            ))}
            
            <div className="p-4 text-center sm:p-6">
              <Link
                href="#estimate"
                className="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-secondary px-5 py-2.5 font-bold text-secondary-foreground transition-colors hover:bg-secondary/90 sm:w-auto"
              >
                {settings.comparison_button}
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
