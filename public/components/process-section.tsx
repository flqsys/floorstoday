"use client"

import { Button } from "@/components/ui/button"
import Link from "next/link"
import { Calendar, FileText, Wrench } from "lucide-react"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function ProcessSection() {
  const settings = useHomepageSettings()
  const icons = [Calendar, FileText, Wrench]

  return (
    <section
      className="py-20"
      style={{
        background: `linear-gradient(${settings.process_bg_location}, ${settings.process_bg_color_1}, ${settings.process_bg_color_2})`,
      }}
      aria-labelledby="process-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="text-center mb-16">
          <h2 id="process-heading" className="font-serif text-3xl font-bold text-foreground sm:text-4xl">
            {settings.process_title}
          </h2>
          <p className="mt-4 text-lg text-muted-foreground max-w-2xl mx-auto">
            {settings.process_text}
          </p>
        </div>

        <div className="grid gap-8 md:grid-cols-3">
          {settings.process_steps.map((step, index) => {
            const StepIcon = icons[index] || Calendar

            return (
            <article
              key={`${step.title}-${index}`}
              className="group relative bg-card rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow border border-border"
            >
              <div className="aspect-[4/3] overflow-hidden">
                <img
                  src={step.image}
                  alt={step.title}
                  className="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                <div className="absolute top-4 left-4">
                  <div className="flex items-center justify-center h-10 w-10 rounded-full bg-primary text-primary-foreground font-bold text-lg">
                    {index + 1}
                  </div>
                </div>
              </div>
              <div className="p-6">
                <div className="flex items-center gap-3 mb-3">
                  <StepIcon className="h-5 w-5 text-secondary" />
                  <h3 className="font-semibold text-foreground text-lg">{step.title}</h3>
                </div>
                <p className="text-muted-foreground leading-relaxed">{step.description}</p>
                {step.button && (
                  <Button asChild className="mt-4 bg-primary hover:bg-primary/90 text-primary-foreground">
                    <Link href="#estimate">{step.button}</Link>
                  </Button>
                )}
              </div>
            </article>
          )})}
        </div>
      </div>
    </section>
  )
}
