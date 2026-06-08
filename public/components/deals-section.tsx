"use client"

import { useEffect, useState } from "react"
import Link from "next/link"
import { ArrowRight, Check, Gift } from "lucide-react"
import { Badge } from "@/components/ui/badge"
import { Card, CardContent } from "@/components/ui/card"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function DealsSection() {
  const settings = useHomepageSettings()
  const [isOfferOpen, setIsOfferOpen] = useState(false)
  const includedItems = settings.deals_includes.split(/\r?\n/).map((item) => item.trim()).filter(Boolean)
  const popupSteps = settings.deals_popup_steps.split(/\r?\n/).map((line) => {
    const [title, ...descriptionParts] = line.split("|")
    return {
      title: title.trim(),
      description: descriptionParts.join("|").trim().replaceAll("{phone}", settings.phone),
    }
  }).filter((item) => item.title)

  useEffect(() => {
    if (!isOfferOpen) return

    const previousOverflow = document.body.style.overflow
    const closeOnEscape = (event: KeyboardEvent) => {
      if (event.key === "Escape") setIsOfferOpen(false)
    }

    document.body.style.overflow = "hidden"
    window.addEventListener("keydown", closeOnEscape)

    return () => {
      document.body.style.overflow = previousOverflow
      window.removeEventListener("keydown", closeOnEscape)
    }
  }, [isOfferOpen])

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
            <Card className="overflow-hidden rounded-lg border border-primary/20 bg-primary text-primary-foreground shadow-xl">
              <CardContent className="p-0">
                <div className="flex items-center gap-3 border-b border-white/15 px-6 py-3">
                  <span className="flex h-10 w-10 items-center justify-center rounded-md bg-white/10">
                    <Gift className="h-5 w-5 text-secondary" />
                  </span>
                  <div className="text-left">
                    <p className="text-xs font-bold uppercase text-primary-foreground/70">
                      {settings.deals_popup_eyebrow}
                    </p>
                    <p className="text-sm font-semibold text-primary-foreground">
                      {settings.deals_badge}
                    </p>
                  </div>
                </div>

                <div className="grid gap-6 px-6 py-5 sm:px-8 sm:py-5 md:grid-cols-[minmax(0,1fr)_minmax(230px,0.9fr)] md:items-stretch">
                  <div>
                    <div className="mb-6">
                      <div className="flex flex-nowrap items-center gap-3 whitespace-nowrap">
                        <h3 className="text-4xl font-extrabold leading-none text-white sm:text-5xl">
                          {settings.deals_card_title}
                        </h3>
                        <span className="ft-sale-badge inline-flex flex-none rounded bg-red-600 px-2 py-0.5 text-[11px] font-extrabold uppercase text-white shadow-sm">
                          {settings.deals_card_subtitle}
                        </span>
                      </div>
                    </div>

                    <div className="flex flex-wrap items-center gap-x-5 gap-y-3">
                      <Link
                        href="#estimate"
                        className="inline-flex min-h-11 flex-none items-center justify-center gap-2 whitespace-nowrap rounded-md bg-secondary px-5 py-2.5 font-bold leading-none text-secondary-foreground shadow-md transition-colors hover:bg-secondary/90"
                      >
                        <span className="whitespace-nowrap">{settings.deals_button}</span>
                        <ArrowRight className="h-4 w-4 flex-none" aria-hidden="true" />
                      </Link>

                      <button
                        type="button"
                        onClick={() => setIsOfferOpen(true)}
                        className="inline-flex items-center text-sm font-semibold text-white underline decoration-white/40 underline-offset-4 transition-colors hover:text-secondary"
                      >
                        {settings.deals_details_label}
                      </button>
                    </div>
                  </div>

                  <div className="hidden border-l border-white/20 pl-7 md:flex md:flex-col md:justify-center">
                    <p className="mb-3 text-xs font-bold uppercase text-white/60">
                      {settings.deals_includes_title}
                    </p>
                    <div className="space-y-2.5">
                      {includedItems.map((item) => (
                        <div key={item} className="flex items-center gap-2.5 text-sm font-semibold text-white">
                          <span className="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-white/10 text-secondary">
                            <Check className="h-3.5 w-3.5" />
                          </span>
                          <span>{item}</span>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>

      {isOfferOpen ? (
        <div
          className="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/70 p-3 backdrop-blur-sm sm:p-5"
          role="presentation"
          onMouseDown={(event) => {
            if (event.target === event.currentTarget) setIsOfferOpen(false)
          }}
        >
          <section
            role="dialog"
            aria-modal="true"
            aria-labelledby="offer-details-title"
            className="relative w-full max-w-6xl overflow-hidden rounded-lg bg-white shadow-2xl"
          >
            <button
              type="button"
              onClick={() => setIsOfferOpen(false)}
              className="absolute right-3 top-3 z-20 flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-2xl font-normal leading-none text-slate-950 shadow-md transition-colors hover:bg-slate-100"
              aria-label="Close offer details"
            >
              <span aria-hidden="true">&times;</span>
            </button>

            <div className="flex items-center justify-between border-b border-slate-200 bg-white px-5 py-3 sm:px-7">
              <div className="flex items-center gap-3">
                <span className="flex h-10 w-10 items-center justify-center rounded-md bg-primary text-white">
                  <Gift className="h-5 w-5" />
                </span>
                <div>
                  <p className="text-xs font-bold uppercase text-primary">{settings.logo_text}</p>
                  <p className="text-sm text-slate-500">{settings.deals_popup_eyebrow}</p>
                </div>
              </div>
              <span className="h-10 w-10" aria-hidden="true" />
            </div>

            <div className="grid lg:grid-cols-[0.82fr_1.18fr]">
              <div className="flex flex-col justify-between bg-primary px-6 py-7 text-white sm:px-8">
                <div>
                  <p className="text-sm font-bold uppercase text-secondary">
                    {settings.deals_card_title} {settings.deals_card_subtitle}
                  </p>
                  <h2 id="offer-details-title" className="mt-3 font-serif text-3xl font-bold leading-tight sm:text-4xl">
                    {settings.deals_popup_title}
                  </h2>
                  <p className="mt-4 max-w-md text-sm leading-relaxed text-white/75">
                    {settings.deals_popup_intro}
                  </p>
                </div>

                <Link
                  href="#estimate"
                  onClick={() => setIsOfferOpen(false)}
                  className="mt-7 inline-flex min-h-11 w-fit items-center justify-center gap-2 rounded-md bg-secondary px-5 py-2.5 font-bold text-secondary-foreground transition-colors hover:bg-secondary/90"
                >
                  {settings.deals_popup_button}
                  <ArrowRight className="h-4 w-4" />
                </Link>
              </div>

              <div className="px-6 py-6 sm:px-8">
                <h3 className="text-lg font-bold text-slate-950">{settings.deals_popup_steps_title}</h3>
                <div className="mt-4 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                  {popupSteps.map((step) => (
                    <div key={step.title} className="flex gap-3">
                      <span className="mt-0.5 flex h-7 w-7 flex-none items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <Check className="h-4 w-4" />
                      </span>
                      <div>
                        <h4 className="text-sm font-bold text-slate-950">{step.title}</h4>
                        <p className="mt-1 text-xs leading-relaxed text-slate-600">{step.description}</p>
                      </div>
                    </div>
                  ))}
                </div>

                <div className="mt-5 border-t border-slate-200 pt-4 text-[11px] leading-relaxed text-slate-500">
                  <p>
                    {settings.deals_popup_terms}
                  </p>
                  <p className="mt-2">
                    {settings.deals_popup_terms_extra}
                  </p>
                </div>
              </div>
            </div>
          </section>
        </div>
      ) : null}
    </section>
  )
}
