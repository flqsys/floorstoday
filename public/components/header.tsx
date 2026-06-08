"use client"

import { useState } from "react"
import Link from "next/link"
import { Menu, X, MapPin, Phone } from "lucide-react"
import { useHomepageSettingsStatus } from "@/components/homepage-settings-provider"

export function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)
  const { settings, isLoaded } = useHomepageSettingsStatus()
  const logoSrc = settings.logo_image
  const phoneHref = `tel:${settings.phone.replace(/[^\d+]/g, "")}`

  return (
    <header className="sticky top-0 z-50 bg-card shadow-sm">
      <div className="bg-primary text-primary-foreground">
        <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
          <div className="flex h-10 items-center justify-between text-sm">
            <div className="flex items-center gap-2">
              <MapPin className="h-4 w-4" />
              <span>{settings.service_area}</span>
            </div>
            <div className="hidden sm:flex items-center gap-6">
              <Link href="#financing" className="hover:underline">
                Financing
              </Link>
              <Link href="#contact" className="hover:underline">
                Contact
              </Link>
              <Link href="#faq" className="hover:underline">
                FAQs
              </Link>
            </div>
          </div>
        </div>
      </div>

      <nav className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="flex items-center justify-between py-0">
          <a href="/" className="flex items-center text-2xl font-bold text-primary">
            {logoSrc ? (
              <img
                src={logoSrc}
                alt={settings.logo_text}
                className="block h-auto object-contain"
                style={{ width: settings.logo_size }}
                loading="eager"
                fetchPriority="high"
                decoding="sync"
              />
            ) : (
              <span className={isLoaded ? "" : "invisible"}>{settings.logo_text}</span>
            )}
          </a>

          <div className="hidden lg:flex lg:items-center lg:gap-6">
            {settings.nav_items.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className="text-base font-medium text-foreground hover:text-primary transition-colors"
              >
                {item.name}
              </Link>
            ))}
          </div>

          <div className="flex items-center justify-end gap-4">
            <a
              href={phoneHref}
              className="hidden sm:flex items-center gap-2 text-base font-semibold text-foreground whitespace-nowrap"
            >
              <Phone className="h-4 w-4 text-primary" />
              {settings.phone}
            </a>

            <button
              type="button"
              className="lg:hidden rounded-md p-[3px] text-foreground"
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            >
              <span className="sr-only">Open menu</span>
              {mobileMenuOpen ? (
                <X className="h-6 w-6" aria-hidden="true" />
              ) : (
                <Menu className="h-6 w-6" aria-hidden="true" />
              )}
            </button>
          </div>
        </div>

        {mobileMenuOpen && (
          <div className="lg:hidden border-t border-border py-4">
            <div className="flex flex-col gap-4">
              {settings.nav_items.map((item) => (
                <Link
                  key={item.name}
                  href={item.href}
                  className="text-base font-medium text-foreground hover:text-primary"
                  onClick={() => setMobileMenuOpen(false)}
                >
                  {item.name}
                </Link>
              ))}
              <div className="pt-4 border-t border-border flex flex-col gap-3">
                <a href={phoneHref} className="flex items-center gap-2 text-base font-semibold">
                  <Phone className="h-4 w-4 text-primary" />
                  {settings.phone}
                </a>
              </div>
            </div>
          </div>
        )}
      </nav>
    </header>
  )
}
