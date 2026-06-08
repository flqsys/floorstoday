"use client"

import { Star, Quote } from "lucide-react"
import { Card, CardContent } from "@/components/ui/card"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function TestimonialsSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-20"
      style={{
        background: `linear-gradient(${settings.testimonials_bg_location}, ${settings.testimonials_bg_color_1}, ${settings.testimonials_bg_color_2})`,
      }}
      aria-labelledby="testimonials-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="text-center mb-12">
          <h2 id="testimonials-heading" className="font-serif text-3xl font-bold text-foreground sm:text-4xl">
            {settings.testimonials_title}
          </h2>
          <p className="mt-4 text-lg text-muted-foreground max-w-2xl mx-auto">
            {settings.testimonials_text}
          </p>
        </div>

        <div className="grid gap-8 md:grid-cols-3">
          {settings.testimonials.map((testimonial, index) => (
            <Card key={`${testimonial.name}-${index}`} className="relative">
              <CardContent className="pt-8">
                <Quote className="absolute top-4 right-4 h-8 w-8 text-secondary/20" />

                <div className="flex gap-1 mb-4">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} className="h-5 w-5 fill-secondary text-secondary" />
                  ))}
                </div>

                <p className="text-muted-foreground leading-relaxed mb-6">
                  "{testimonial.text}"
                </p>

                <div className="border-t border-border pt-4">
                  <p className="font-semibold text-foreground">{testimonial.name}</p>
                  <p className="text-sm text-muted-foreground">{testimonial.location}</p>
                  <p className="text-sm text-secondary mt-1">{testimonial.floorType}</p>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  )
}
