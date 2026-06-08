"use client"

import { Check, HelpCircle } from "lucide-react"
import { Button } from "@/components/ui/button"
import Link from "next/link"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function ComparisonSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-20 text-primary-foreground"
      style={{
        background: `linear-gradient(${settings.comparison_bg_location}, ${settings.comparison_bg_color_1}, ${settings.comparison_bg_color_2})`,
      }}
      aria-labelledby="comparison-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="grid gap-12 lg:grid-cols-2 items-center">
          {/* Left Content */}
          <div>
            <h2 id="comparison-heading" className="font-serif text-3xl font-bold sm:text-4xl lg:text-5xl">
              {settings.comparison_title}
            </h2>
            <p className="mt-6 text-lg leading-relaxed opacity-90">
              {settings.comparison_text}
            </p>
          </div>

          {/* Right Comparison Table */}
          <div className="bg-card text-card-foreground rounded-2xl overflow-hidden shadow-xl">
            <div className="grid grid-cols-[1.55fr_1fr_0.85fr] text-center text-base font-bold border-b border-border">
              <div className="px-5 py-5" />
              <div className="whitespace-nowrap px-5 py-5 bg-secondary text-secondary-foreground">Floors Today</div>
              <div className="px-5 py-5">Others</div>
            </div>
            
            {settings.comparison_rows.map((feature, index) => (
              <div
                key={feature}
                className={`grid grid-cols-[1.55fr_1fr_0.85fr] items-stretch ${
                  index !== settings.comparison_rows.length - 1 ? "border-b border-border" : ""
                }`}
              >
                <div className="flex items-center px-5 py-4 text-base font-semibold leading-snug">{feature}</div>
                <div className="flex items-center justify-center bg-primary/5 px-5 py-4">
                  <Check className="h-6 w-6 text-secondary" />
                </div>
                <div className="flex items-center justify-center px-5 py-4">
                  <HelpCircle className="h-6 w-6 text-muted-foreground" />
                </div>
              </div>
            ))}
            
            <div className="p-6 text-center">
              <Button asChild size="lg" className="bg-secondary hover:bg-secondary/90 text-secondary-foreground">
                <Link href="#estimate">{settings.comparison_button}</Link>
              </Button>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
