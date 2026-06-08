"use client"

import Link from "next/link"
import { ArrowRight, Gift } from "lucide-react"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function DealsSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-20"
      style={{
        background: `linear-gradient(${settings.deals_bg_location}, ${settings.deals_bg_color_1}, ${settings.deals_bg_color_2})`,
      }}
      aria-labelledby="deals-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="text-center max-w-3xl mx-auto mb-12">
          <Badge className="mb-4 bg-secondary/10 text-secondary border-secondary/20 hover:bg-secondary/10">
            {settings.deals_badge}
          </Badge>

          <h2 id="deals-heading" className="font-serif text-3xl font-bold text-foreground sm:text-4xl text-balance">
            {settings.deals_title}
          </h2>

          <p className="mt-4 text-lg text-muted-foreground text-pretty">
            {settings.deals_text}
          </p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
          {settings.offers.map((offer, index) => (
            <Card key={`${offer.title}-${index}`} className="border-2 border-primary/10 hover:border-primary/30 transition-colors bg-card">
              <CardContent className="p-6 text-center">
                <div className="mx-auto w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                  <Gift className="w-6 h-6 text-primary" />
                </div>
                <h3 className="font-bold text-lg text-foreground mb-2">{offer.title}</h3>
                <p className="text-sm text-muted-foreground">{offer.description}</p>
              </CardContent>
            </Card>
          ))}
        </div>

        <div className="grid lg:grid-cols-2 gap-12 items-start">
          <div className="space-y-8">
            <p className="text-lg leading-relaxed text-muted-foreground">
              {settings.deals_body}
            </p>
          </div>

          <div className="lg:sticky lg:top-24">
            <Card className="bg-primary text-primary-foreground overflow-hidden">
              <CardContent className="p-8">
                <div className="text-center mb-6">
                  <p className="text-primary-foreground/80 text-sm uppercase tracking-wider mb-2">
                    {settings.deals_badge}
                  </p>
                  <h3 className="text-4xl font-bold mb-2">{settings.deals_card_title}</h3>
                  <p className="text-xl font-semibold">{settings.deals_card_subtitle}</p>
                </div>

                <Button
                  size="lg"
                  className="w-full bg-secondary hover:bg-secondary/90 text-secondary-foreground font-bold"
                  asChild
                >
                  <Link href="#estimate">
                    {settings.deals_button}
                    <ArrowRight className="ml-2 h-4 w-4" />
                  </Link>
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </section>
  )
}
