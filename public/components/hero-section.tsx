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
  const settings = useHomepageSettings()
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    phone: "",
    postalCode: "",
    flooringType: "",
    propertyType: "",
    startTime: "Within 1 month",
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
    if (step < 3) setStep(step + 1)
  }

  const handlePrev = () => {
    if (step > 1) setStep(step - 1)
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    // Handle form submission
    console.log("Form submitted:", formData)
  }

  return (
    <section className="relative min-h-[90vh] flex items-center">
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

      <div className="relative z-10 mx-auto w-full max-w-[1280px] px-3 py-16 sm:px-4 lg:px-4">
        <div className="grid gap-8 lg:grid-cols-2 lg:gap-12 items-center">
          {/* Left Content */}
          <div className="text-background">
            {/* Trust Badge */}
            <div className="flex items-center gap-2 mb-6">
              <div className="flex -space-x-1">
                {[1, 2, 3, 4, 5].map((i) => (
                  <Star key={i} className="h-5 w-5 fill-secondary text-secondary" />
                ))}
              </div>
              <span className="text-sm text-background/80">Rated 4.9/5 by 2,000+ Ontario homeowners</span>
            </div>

            {/* Promotion Badge */}
            <Badge
              className="ft-hero-badge mb-6 border-0 px-4 py-2 font-bold"
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
            
            <h1 className="font-serif text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl text-balance">
              {settings.hero_title}{" "}
              <span className="text-secondary">{settings.hero_highlight}</span>
            </h1>

            <p className="mt-6 text-xl leading-relaxed text-background/90 max-w-lg text-pretty">
              {settings.hero_text}
            </p>

            {/* Value Props */}
            <div className="mt-8 grid grid-cols-2 gap-4">
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
            <div className="mt-10 flex flex-col sm:flex-row items-start sm:items-center gap-6">
              <a 
                href={`tel:${settings.phone.replace(/[^\d+]/g, "")}`} 
                className="group flex flex-none items-center gap-3"
              >
                <div className="flex h-14 w-14 flex-none items-center justify-center rounded-full bg-secondary transition-transform group-hover:scale-110">
                  <Phone className="h-6 w-6 text-secondary-foreground" />
                </div>
                <div className="min-w-max">
                  <p className="text-sm text-background/70">Call Us Now</p>
                  <p className="whitespace-nowrap text-2xl font-bold text-background">{settings.phone}</p>
                </div>
              </a>
              <div className="hidden sm:block w-px h-12 bg-background/20" />
              <div className="flex min-w-0 items-center gap-2">
                <MapPin className="h-5 w-5 flex-none text-secondary" />
                <span className="text-background/80">{settings.service_area}</span>
              </div>
            </div>
          </div>

          <Card className="w-full max-w-[520px] justify-self-center rounded-[20px] border-0 bg-white shadow-2xl lg:justify-self-end">
            <CardContent className="p-6 sm:p-9">
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
                  const isDone = item < step
                  const isCurrent = item === step

                  return (
                    <div key={item} className="flex items-center">
                      <div
                        className={`flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold transition-colors ${
                          isDone
                            ? "bg-secondary text-secondary-foreground"
                            : isCurrent
                              ? "bg-primary text-primary-foreground"
                              : "bg-stone-100 text-stone-500"
                        }`}
                      >
                        {isDone ? <Check className="h-4 w-4" /> : item}
                      </div>
                      {item < 3 && (
                        <div
                          className={`mx-3 h-0.5 w-14 rounded-full ${
                            item < step ? "bg-secondary" : "bg-stone-100"
                          }`}
                        />
                      )}
                    </div>
                  )
                })}
              </div>

              <form onSubmit={handleSubmit}>
                {step === 1 && (
                  <div className="space-y-5">
                    <h3 className="text-base font-semibold text-slate-950">What floors interest you?</h3>
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                      {flooringOptions.map((option) => (
                        <button
                          key={option}
                          type="button"
                          onClick={() => setFormData({ ...formData, flooringType: option })}
                          className={`min-h-14 rounded-lg border px-4 text-left text-base font-medium transition-colors ${
                            formData.flooringType === option
                              ? "border-primary bg-primary/5 text-primary"
                              : "border-stone-200 bg-white text-slate-900 hover:border-primary"
                          }`}
                        >
                          {option}
                        </button>
                      ))}
                    </div>
                    <div className="flex items-center justify-between pt-1">
                      <p className="text-sm text-slate-500">Takes under 60 seconds</p>
                      <Button
                        type="button"
                        onClick={handleNext}
                        className="rounded-full bg-primary px-5 font-bold text-primary-foreground hover:bg-primary/90"
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
                        placeholder="Jane Doe"
                        value={formData.fullName}
                        onChange={(e) => setFormData({ ...formData, fullName: e.target.value })}
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
                        placeholder="M5V 2T6"
                        value={formData.postalCode}
                        onChange={(e) => setFormData({ ...formData, postalCode: e.target.value })}
                        className="h-12 rounded-lg border-stone-200 bg-white text-base"
                        required
                      />
                    </div>
                    <h3 className="pt-2 text-base font-semibold text-slate-950">How can we reach you?</h3>
                    <div>
                      <label htmlFor="phone" className="mb-2 block text-sm font-medium text-slate-600">
                        Phone
                      </label>
                      <Input
                        id="phone"
                        type="tel"
                        placeholder="(416) 555-0199"
                        value={formData.phone}
                        onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
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
                        className="h-12 rounded-lg border-stone-200 bg-white text-base"
                        required
                      />
                    </div>
                  </div>
                )}

                {step > 1 && (
                  <div className="mt-7 flex items-center justify-between gap-4">
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
                      className="rounded-full bg-primary px-5 font-bold text-primary-foreground hover:bg-primary/90"
                    >
                      {step === 3 ? "Get My Free Estimate" : "Continue"}
                      <ArrowRight className="ml-2 h-4 w-4" />
                    </Button>
                  </div>
                )}
              </form>
            </CardContent>
          </Card>
        </div>
      </div>
    </section>
  )
}
