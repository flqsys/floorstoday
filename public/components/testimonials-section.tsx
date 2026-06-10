"use client"

import { Star, Quote } from "lucide-react"
import { Card, CardContent } from "@/components/ui/card"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function TestimonialsSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-14 sm:py-16 lg:py-20"
      style={{
        background: `linear-gradient(${settings.testimonials_bg_location}, ${settings.testimonials_bg_color_1}, ${settings.testimonials_bg_color_2})`,
      }}
      aria-labelledby="testimonials-heading"
    >
      <div className="mx-auto max-w-[1280px] px-4">
        <div className="mb-8 text-center sm:mb-12">
          <h2 id="testimonials-heading" className="font-serif text-3xl font-bold text-foreground sm:text-4xl">
            {settings.testimonials_title}
          </h2>
          <p className="mx-auto mt-4 max-w-2xl text-base text-muted-foreground sm:text-lg">
            {settings.testimonials_text}
          </p>
        </div>

        <div className="grid gap-5 md:grid-cols-3 lg:gap-8">
          {settings.testimonials.map((testimonial, index) => (
            <Card key={`${testimonial.name}-${index}`} className="relative rounded-xl shadow-sm">
              <CardContent className="p-5 pt-8 sm:p-6 sm:pt-8">
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
