"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { MapPin, Phone, Mail } from "lucide-react"
import { useHomepageSettingsStatus } from "@/components/homepage-settings-provider"

function parseMenu(value: string) {
  return value.split(/\r?\n/).map((line) => {
    const [label, ...urlParts] = line.split("|")
    return {
      label: label.trim(),
      url: urlParts.join("|").trim(),
    }
  }).filter((item) => item.label && item.url)
}

export function Footer() {
  const [email, setEmail] = useState("")
  const { settings, isLoaded } = useHomepageSettingsStatus()
  const logoSrc = settings.logo_image
  const phoneHref = `tel:${settings.phone.replace(/[^\d+]/g, "")}`
  const aboutLinks = parseMenu(settings.footer_about_links)
  const helpLinks = parseMenu(settings.footer_help_links)
  const policyLinks = parseMenu(settings.footer_policy_links)
  const bottomLinks = parseMenu(settings.footer_bottom_links)
  const copyright = settings.footer_copyright.replaceAll("{year}", String(new Date().getFullYear()))

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
            <form onSubmit={handleSubmit} className="flex w-full flex-col gap-2 sm:flex-row md:w-auto">
              <Input
                type="email"
                placeholder="Your Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="bg-background/10 border-background/20 text-background placeholder:text-background/50 w-full md:w-64"
                required
              />
              <Button type="submit" className="w-full bg-secondary text-secondary-foreground hover:bg-secondary/90 sm:w-auto">
                {settings.newsletter_button}
              </Button>
            </form>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4 py-12">
        <div className="grid grid-cols-2 gap-x-5 gap-y-8 md:grid-cols-2 lg:grid-cols-6 lg:gap-x-6">
          <div className="col-span-2 lg:col-span-2">
            <a href="/" className="inline-flex items-center text-2xl font-bold">
              {logoSrc ? (
                <img
                  src={logoSrc}
                  alt={settings.logo_text}
                  width={250}
                  height={80}
                  className="h-auto object-contain"
                  style={{ width: settings.logo_size, maxWidth: "100%" }}
                  loading="eager"
                  fetchPriority="high"
                />
              ) : (
                <span className={isLoaded ? "" : "invisible"}>{settings.logo_text}</span>
              )}
            </a>
            <p className="mt-4 text-sm text-background/70 leading-relaxed">
              {settings.footer_about}
            </p>
            {[
              ["Facebook", settings.facebook_url],
              ["Instagram", settings.instagram_url],
              ["LinkedIn", settings.linkedin_url],
            ].some(([, url]) => Boolean(url)) ? (
              <div className="mt-6 flex items-center gap-4">
                {[
                  ["Facebook", settings.facebook_url],
                  ["Instagram", settings.instagram_url],
                  ["LinkedIn", settings.linkedin_url],
                ].filter(([, url]) => Boolean(url)).map(([label, url]) => (
                  <a
                    key={label}
                    href={url}
                    className="text-background/70 hover:text-background"
                    aria-label={label}
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    {label}
                  </a>
                ))}
              </div>
            ) : null}
          </div>

          <div>
            <h4 className="font-semibold mb-4">{settings.footer_about_title}</h4>
            <ul className="space-y-3">
              {aboutLinks.map((link) => (
                <li key={`${link.label}-${link.url}`}>
                  <a href={link.url} className="text-sm text-background/70 hover:text-background lg:whitespace-nowrap">
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">{settings.footer_categories_title}</h4>
            <ul className="space-y-3">
              {settings.nav_items.map((link) => (
                <li key={link.name}>
                  <a href={link.href} className="text-sm text-background/70 hover:text-background lg:whitespace-nowrap">
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">{settings.footer_help_title}</h4>
            <ul className="space-y-3">
              {helpLinks.map((link) => (
                <li key={`${link.label}-${link.url}`}>
                  <a href={link.url} className="text-sm text-background/70 hover:text-background lg:whitespace-nowrap">
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">{settings.footer_policies_title}</h4>
            <ul className="space-y-3">
              {policyLinks.map((link) => (
                <li key={`${link.label}-${link.url}`}>
                  <a href={link.url} className="text-sm text-background/70 hover:text-background lg:whitespace-nowrap">
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="mt-12 pt-8 border-t border-background/10">
          <div className="flex flex-col items-start gap-4 text-sm text-background/70 sm:flex-row sm:flex-wrap sm:items-center sm:gap-6">
            <div className="flex min-w-0 items-start gap-2">
              <MapPin className="h-4 w-4" />
              <span className="break-words">{settings.service_area}</span>
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
            <p>{copyright}</p>
            <div className="flex flex-wrap items-center gap-x-5 gap-y-3">
              {bottomLinks.map((link) => (
                <a key={`${link.label}-${link.url}`} href={link.url} className="hover:text-background">
                  {link.label}
                </a>
              ))}
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}
