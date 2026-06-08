"use client"

import { useState } from "react"
import Link from "next/link"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { MapPin, Phone, Mail } from "lucide-react"
import { useHomepageSettingsStatus } from "@/components/homepage-settings-provider"

const footerLinks = {
  aboutUs: [
    { name: "About Us", href: "#about" },
    { name: "Contact Us", href: "#contact" },
  ],
  helpArea: [
    { name: "How Shop at Home Works", href: "#how-it-works" },
    { name: "Product Care", href: "#care" },
    { name: "Contact", href: "#contact" },
  ],
  policies: [
    { name: "Terms Of Use", href: "#terms" },
    { name: "FAQs", href: "#faq" },
    { name: "Privacy Policy", href: "#privacy" },
    { name: "Warranty Information", href: "#warranty" },
  ],
}

export function Footer() {
  const [email, setEmail] = useState("")
  const { settings, isLoaded } = useHomepageSettingsStatus()
  const logoSrc = settings.logo_image
  const phoneHref = `tel:${settings.phone.replace(/[^\d+]/g, "")}`

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setEmail("")
  }

  return (
    <footer
      className="text-background"
      style={{
        background: `linear-gradient(${settings.footer_bg_location}, ${settings.footer_bg_color_1}, ${settings.footer_bg_color_2})`,
      }}
      role="contentinfo"
    >
      <div className="border-b border-background/10">
        <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4 py-8">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h3 className="text-lg font-semibold">{settings.newsletter_title}</h3>
              <p className="text-sm text-background/70">{settings.newsletter_text}</p>
            </div>
            <form onSubmit={handleSubmit} className="flex gap-2 w-full md:w-auto">
              <Input
                type="email"
                placeholder="Your Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="bg-background/10 border-background/20 text-background placeholder:text-background/50 w-full md:w-64"
                required
              />
              <Button type="submit" className="bg-secondary hover:bg-secondary/90 text-secondary-foreground">
                {settings.newsletter_button}
              </Button>
            </form>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4 py-12">
        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-6">
          <div className="lg:col-span-2">
            <Link href="/" className="inline-flex items-center text-2xl font-bold">
              {logoSrc ? (
                <img
                  src={logoSrc}
                  alt={settings.logo_text}
                  className="h-auto object-contain"
                  style={{ width: settings.logo_size, maxWidth: "100%" }}
                  loading="eager"
                  fetchPriority="high"
                />
              ) : (
                <span className={isLoaded ? "" : "invisible"}>{settings.logo_text}</span>
              )}
            </Link>
            <p className="mt-4 text-sm text-background/70 leading-relaxed">
              {settings.footer_about}
            </p>
            <div className="mt-6 flex items-center gap-4">
              <a href="#" className="text-background/70 hover:text-background" aria-label="Facebook">
                Facebook
              </a>
              <a href="#" className="text-background/70 hover:text-background" aria-label="Instagram">
                Instagram
              </a>
              <a href="#" className="text-background/70 hover:text-background" aria-label="LinkedIn">
                LinkedIn
              </a>
            </div>
          </div>

          <div>
            <h4 className="font-semibold mb-4">About Us</h4>
            <ul className="space-y-3">
              {footerLinks.aboutUs.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-background/70 hover:text-background">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Categories</h4>
            <ul className="space-y-3">
              {settings.nav_items.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-background/70 hover:text-background">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Help Area</h4>
            <ul className="space-y-3">
              {footerLinks.helpArea.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-background/70 hover:text-background">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Our Policies</h4>
            <ul className="space-y-3">
              {footerLinks.policies.map((link) => (
                <li key={link.name}>
                  <Link href={link.href} className="text-sm text-background/70 hover:text-background">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="mt-12 pt-8 border-t border-background/10">
          <div className="flex flex-wrap items-center gap-6 text-sm text-background/70">
            <div className="flex items-center gap-2">
              <MapPin className="h-4 w-4" />
              <span>{settings.service_area}</span>
            </div>
            <a href={phoneHref} className="flex items-center gap-2 hover:text-background">
              <Phone className="h-4 w-4" />
              <span>{settings.phone}</span>
            </a>
            <a href={`mailto:${settings.email}`} className="flex items-center gap-2 hover:text-background">
              <Mail className="h-4 w-4" />
              <span>{settings.email}</span>
            </a>
          </div>
        </div>

        <div className="mt-8 pt-8 border-t border-background/10">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-sm text-background/70">
            <p>Floors Today Copyright {new Date().getFullYear()} All Rights Reserved</p>
            <div className="flex items-center gap-6">
              <Link href="#careers" className="hover:text-background">Careers</Link>
              <Link href="#privacy" className="hover:text-background">Privacy Policy</Link>
              <Link href="#sitemap" className="hover:text-background">Sitemap</Link>
              <Link href="#terms" className="hover:text-background">Terms Of Use</Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}
