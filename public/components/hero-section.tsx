"use client"

import { useState } from "react"
import Image from "next/image"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Badge } from "@/components/ui/badge"
import { Card, CardContent } from "@/components/ui/card"
import { CheckCircle, Phone, Calendar, Shield, Star, ArrowRight, MapPin, ChevronLeft, Home, Building2, BriefcaseBusiness, Check } from "lucide-react"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function HeroSection() {
  const [step, setStep] = useState(1)
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [isSubmitted, setIsSubmitted] = useState(false)
  const [submitError, setSubmitError] = useState("")
  const settings = useHomepageSettings()
  const inboxEndpoint =
    process.env.NEXT_PUBLIC_WORDPRESS_INBOX_ENDPOINT ||
    "/wp-json/floors-today/v1/inbox-leads"
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    phone: "+1 ",
    postalCode: "",
    flooringType: "",
    propertyType: "",
    startTime: "Within 1 month",
    ftInboxTrap: "",
  })

  const flooringOptions = ["Solid Hardwood", "Engineered Hardwood", "Laminate", "Vinyl", "Carpet", "Not sure yet"]
  const propertyOptions = [
    { label: "Residential", icon: Home },
    { label: "Office Space", icon: Building2 },
    { label: "Business", icon: BriefcaseBusiness },
  ]
  const showBackground = settings.hero_show_background === true || settings.hero_show_background === "1" || settings.hero_show_background === "true"
  const showOverlay = settings.hero_show_overlay === true || settings.hero_show_overlay === "1" || settings.hero_show_overlay === "true"
  const overlayOpacity = Math.max(0, Math.min(1, Number.parseFloat(settings.hero_overlay_opacity) || 0))

  const handleNext = () => {
    setSubmitError("")

    if (step === 1 && !formData.flooringType) {
      setSubmitError("Please choose a flooring option.")
      return
    }

    if (step === 2 && !formData.propertyType) {
      setSubmitError("Please choose a property type.")
      return
    }

    if (step < 3) setStep(step + 1)
  }

  const handlePrev = () => {
    if (step > 1) setStep(step - 1)
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSubmitError("")

    const nameParts = formData.fullName.trim().split(/\s+/).filter(Boolean)

    if (nameParts.length < 2) {
      setSubmitError("Please enter your first and last name.")
      return
    }

    setIsSubmitting(true)

    try {
      const url = new URL(window.location.href)
      const referrer = document.referrer
      const referrerHost = referrer ? new URL(referrer).hostname.replace(/^www\./, "") : ""
      const utmSource = url.searchParams.get("utm_source") || ""
      const trafficSource = utmSource || referrerHost || "Direct"
      const userAgent = navigator.userAgent
      const devicePlatform = /Mobi|Android|iPhone|iPad/i.test(userAgent)
        ? "Mobile / Tablet"
        : "Desktop"

      const response = await fetch(inboxEndpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          ...formData,
          source: "Homepage hero estimate form",
          pageUrl: window.location.href,
          trafficSource,
          referrerUrl: referrer,
          utmSource,
          utmMedium: url.searchParams.get("utm_medium") || "",
          utmCampaign: url.searchParams.get("utm_campaign") || "",
          utmContent: url.searchParams.get("utm_content") || "",
          utmTerm: url.searchParams.get("utm_term") || "",
          devicePlatform,
        }),
      })

      const result = await response.json().catch(() => null)

      if (!response.ok) {
        throw new Error(result?.message || "We could not send your request.")
      }

      setIsSubmitted(true)
    } catch (error) {
      setSubmitError(
        error instanceof Error
          ? error.message
          : "We could not send your request. Please try again.",
      )
    } finally {
      setIsSubmitting(false)
    }
  }

  return (
    <section className="relative flex min-h-0 items-center lg:min-h-[90vh]">
      {/* Background Image */}
      <div className="absolute inset-0 z-0">
        {showBackground ? (
          <Image
            src={settings.hero_image}
            alt="Beautiful modern living room with hardwood flooring"
            fill
            className="object-cover"
            priority
            sizes="100vw"
          />
        ) : null}
        {showOverlay ? (
          <div
            className="absolute inset-0 bg-foreground"
            style={{ opacity: overlayOpacity }}
          />
        ) : null}
      </div>

      <div className="relative z-10 mx-auto w-full max-w-[1280px] px-3 py-10 sm:px-4 sm:py-14 lg:px-4 lg:py-16">
        <div className="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(540px,560px)] lg:gap-10 items-center">
          {/* Left Content */}
          <div className="text-background">
            {/* Trust Badge */}
            <div className="mb-5 flex flex-wrap items-center gap-2 sm:mb-6">
              <div className="flex -space-x-1">
                {[1, 2, 3, 4, 5].map((i) => (
                  <Star key={i} className="h-5 w-5 fill-secondary text-secondary" />
                ))}
              </div>
              <span className="text-xs text-background/80 sm:text-sm">Rated 4.9/5 by 2,000+ Ontario homeowners</span>
            </div>

            {/* Promotion Badge */}
            <Badge
              className="ft-hero-badge mb-5 max-w-full whitespace-normal border-0 px-4 py-2 text-center font-bold sm:mb-6"
              style={{
                backgroundColor: "var(--ft-hero-badge-bg)",
                color: "var(--ft-hero-badge-text)",
                fontSize: "var(--ft-hero-badge-font-size)",
                paddingInline: "var(--ft-hero-badge-padding-x)",
                paddingBlock: "var(--ft-hero-badge-padding-y)",
              }}
            >
              {settings.hero_badge}
            </Badge>
            
            <h1 className="font-serif text-3xl font-bold tracking-tight text-balance sm:text-5xl lg:text-6xl">
              {settings.hero_title}{" "}
              <span className="text-secondary">{settings.hero_highlight}</span>
            </h1>

            <p className="mt-5 max-w-lg text-base leading-relaxed text-background/90 text-pretty sm:mt-6 sm:text-xl">
              {settings.hero_text}
            </p>

            {/* Value Props */}
            <div className="mt-7 grid grid-cols-1 gap-3 min-[420px]:grid-cols-2 sm:mt-8 sm:gap-4">
              {[
                { icon: CheckCircle, text: "No Hidden Fees" },
                { icon: Calendar, text: "Free In-Home Estimate" },
                { icon: Shield, text: "Price Match Guarantee" },
                { icon: Star, text: "Professional Installation" },
              ].map((item, i) => (
                <div key={i} className="flex items-center gap-3">
                  <div className="flex-shrink-0 w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center">
                    <item.icon className="h-5 w-5 text-secondary" />
                  </div>
                  <span className="text-sm font-medium text-background">{item.text}</span>
                </div>
              ))}
            </div>

            {/* Phone CTA */}
            <div className="mt-8 flex flex-col items-start gap-5 sm:mt-10 sm:flex-row sm:items-center sm:gap-6">
              <a 
                href={`tel:${settings.phone.replace(/[^\d+]/g, "")}`} 
                className="group flex flex-none items-center gap-3"
              >
                <div className="flex h-14 w-14 flex-none items-center justify-center rounded-full bg-secondary transition-transform group-hover:scale-110">
                  <Phone className="h-6 w-6 text-secondary-foreground" />
                </div>
                <div className="min-w-0">
                  <p className="text-sm text-background/70">Call Us Now</p>
                  <p className="text-xl font-bold text-background sm:whitespace-nowrap sm:text-2xl">{settings.phone}</p>
                </div>
              </a>
              <div className="hidden sm:block w-px h-12 bg-background/20" />
              <div className="flex min-w-0 items-start gap-2">
                <MapPin className="h-5 w-5 flex-none text-secondary" />
                <span className="text-background/80">{settings.service_area}</span>
              </div>
            </div>
          </div>

          <Card
            id="estimate"
            className="scroll-mt-28 w-full max-w-[560px] justify-self-center rounded-[20px] border-0 bg-white shadow-2xl lg:justify-self-end"
          >
            <CardContent className="p-4 min-[380px]:p-5 sm:p-9">
              <div className="text-center">
                <h2 className="font-serif text-2xl font-bold leading-tight text-slate-950 sm:text-3xl">
                  {settings.form_title}
                </h2>
                <p className="mt-2 text-sm text-slate-600">
                  {settings.form_subtitle}
                </p>
              </div>

              <div className="my-7 flex items-center justify-center">
                {[1, 2, 3].map((item) => {
                  const isDone = isSubmitted || item < step
                  const isCurrent = !isSubmitted && item === step

                  return (
                    <div key={item} className="flex items-center">
                      <div
                        className={`flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold transition-colors ${
                          isDone
                            ? "bg-emerald-600 text-white"
                            : isCurrent
                              ? "bg-primary text-primary-foreground"
                              : "bg-stone-100 text-stone-500"
                        }`}
                      >
                        {isDone ? <Check className="h-4 w-4" /> : item}
                      </div>
                      {item < 3 && (
                        <div
                          className={`mx-2 h-0.5 w-8 rounded-full min-[380px]:w-10 sm:mx-3 sm:w-14 ${
                            isSubmitted || item < step ? "bg-emerald-600" : "bg-stone-100"
                          }`}
                        />
                      )}
                    </div>
                  )
                })}
              </div>

              {isSubmitted ? (
                <div className="flex min-h-[360px] flex-col items-center justify-center text-center">
                  <div className="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                    <CheckCircle className="h-8 w-8" />
                  </div>
                  <h3 className="mt-5 text-2xl font-bold text-slate-950">Request received</h3>
                  <p className="mt-3 max-w-sm text-base leading-relaxed text-slate-600">
                    Thank you, {formData.fullName}. A Floors Today specialist will contact you shortly.
                  </p>
                </div>
              ) : (
              <form onSubmit={handleSubmit}>
                <div className="absolute left-[-10000px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                  <label htmlFor="ftInboxTrap">Leave this field empty</label>
                  <input
                    id="ftInboxTrap"
                    name="ftInboxTrap"
                    type="text"
                    tabIndex={-1}
                    autoComplete="new-password"
                    value={formData.ftInboxTrap}
                    onChange={(e) => setFormData({ ...formData, ftInboxTrap: e.target.value })}
                  />
                </div>
                {step === 1 && (
                  <div className="space-y-5">
                    <h3 className="text-base font-semibold text-slate-950">What floors interest you?</h3>
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                      {flooringOptions.map((option) => (
                        <button
                          key={option}
                          type="button"
                          onClick={() => setFormData({ ...formData, flooringType: option })}
                          className={`min-h-14 rounded-lg border px-4 text-left text-base font-medium transition-colors sm:whitespace-nowrap ${
                            formData.flooringType === option
                              ? "border-primary bg-primary/5 text-primary"
                              : "border-stone-200 bg-white text-slate-900 hover:border-primary"
                          }`}
                        >
                          {option}
                        </button>
                      ))}
                    </div>
                    <div className="flex flex-col items-stretch gap-3 pt-1 min-[420px]:flex-row min-[420px]:items-center min-[420px]:justify-between">
                      <p className="text-sm text-slate-500">Takes under 60 seconds</p>
                      <Button
                        type="button"
                        onClick={handleNext}
                        className="w-full rounded-full bg-primary px-5 font-bold text-primary-foreground hover:bg-primary/90 min-[420px]:w-auto"
                      >
                        Continue
                        <ArrowRight className="ml-2 h-4 w-4" />
                      </Button>
                    </div>
                  </div>
                )}

                {step === 2 && (
                  <div className="space-y-6">
                    <div>
                      <h3 className="mb-4 text-base font-semibold text-slate-950">Property type</h3>
                      <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        {propertyOptions.map((option) => (
                          <button
                            key={option.label}
                            type="button"
                            onClick={() => setFormData({ ...formData, propertyType: option.label })}
                            className={`flex min-h-24 flex-col items-center justify-center gap-2 rounded-lg border px-3 text-sm font-medium transition-colors ${
                              formData.propertyType === option.label
                                ? "border-secondary bg-secondary/10 text-slate-950"
                                : "border-stone-200 bg-white text-slate-600 hover:border-primary"
                            }`}
                          >
                            <option.icon className="h-6 w-6" />
                            {option.label}
                          </button>
                        ))}
                      </div>
                    </div>

                    <div>
                      <label htmlFor="startTime" className="mb-3 block text-base font-semibold text-slate-950">
                        When are you looking to start?
                      </label>
                      <select
                        id="startTime"
                        value={formData.startTime}
                        onChange={(e) => setFormData({ ...formData, startTime: e.target.value })}
                        className="h-12 w-full rounded-lg border border-stone-200 bg-white px-4 text-base text-slate-900 outline-none focus:border-primary"
                      >
                        <option>Within 1 month</option>
                        <option>1-3 months</option>
                        <option>3+ months</option>
                        <option>Just researching</option>
                      </select>
                    </div>
                  </div>
                )}

                {step === 3 && (
                  <div className="space-y-4">
                    <h3 className="text-base font-semibold text-slate-950">Where should we visit?</h3>
                    <div>
                      <label htmlFor="fullName" className="mb-2 block text-sm font-medium text-slate-600">
                        Full name
                      </label>
                      <Input
                        id="fullName"
                        name="fullName"
                        placeholder="Jane Doe"
                        value={formData.fullName}
                        onChange={(e) => setFormData({ ...formData, fullName: e.target.value })}
                        autoComplete="name"
                        pattern="\S+\s+\S+.*"
                        title="Please enter your first and last name."
                        className="h-12 rounded-lg border-stone-200 bg-white text-base"
                        required
                      />
                    </div>
                    <div>
                      <label htmlFor="email" className="mb-2 block text-sm font-medium text-slate-600">
                        Email
                      </label>
                      <Input
                        id="email"
                        type="email"
                        placeholder="jane@email.com"
                        value={formData.email}
                        onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                        autoComplete="email"
                        className="h-12 rounded-lg border-stone-200 bg-white text-base"
                        required
                      />
                    </div>
                    <div className="grid gap-4 sm:grid-cols-2">
                      <div>
                        <label htmlFor="phone" className="mb-2 block text-sm font-medium text-slate-600">
                          Phone
                        </label>
                        <Input
                          id="phone"
                          name="phone"
                          type="tel"
                          placeholder="+1 (416) 555-0199"
                          value={formData.phone}
                          onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                          autoComplete="tel"
                          className="h-12 rounded-lg border-stone-200 bg-white text-base"
                          required
                        />
                      </div>
                      <div>
                        <label htmlFor="postalCode" className="mb-2 block text-sm font-medium text-slate-600">
                          Postal code
                        </label>
                        <Input
                          id="postalCode"
                          name="postalCode"
                          placeholder="M5V 2T6"
                          value={formData.postalCode}
                          onChange={(e) => setFormData({ ...formData, postalCode: e.target.value })}
                          autoComplete="postal-code"
                          className="h-12 rounded-lg border-stone-200 bg-white text-base"
                          required
                        />
                      </div>
                    </div>
                  </div>
                )}

                {step > 1 && (
                  <div className="mt-7 flex items-center justify-between gap-3">
                    <button
                      type="button"
                      onClick={handlePrev}
                      className="inline-flex items-center gap-2 rounded-full px-2 py-2 text-base font-medium text-slate-500 hover:text-slate-950"
                    >
                      <ChevronLeft className="h-4 w-4" />
                      Back
                    </button>
                    <Button
                      type={step === 3 ? "submit" : "button"}
                      onClick={step < 3 ? handleNext : undefined}
                      disabled={isSubmitting}
                      className="min-w-0 rounded-full bg-primary px-4 font-bold text-primary-foreground hover:bg-primary/90 sm:px-5"
                    >
                      {step === 3
                        ? isSubmitting
                          ? "Sending..."
                          : "Get My Free Estimate"
                        : "Continue"}
                      <ArrowRight className="ml-2 h-4 w-4" />
                    </Button>
                  </div>
                )}
                {submitError ? (
                  <p className="mt-4 text-sm font-medium text-red-600" role="alert">
                    {submitError}
                  </p>
                ) : null}
              </form>
              )}
            </CardContent>
          </Card>
        </div>
      </div>
    </section>
  )
}
