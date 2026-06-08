import { HeroSection } from "@/components/hero-section"
import { ProcessSection } from "@/components/process-section"
import { ComparisonSection } from "@/components/comparison-section"
import { CategoriesSection } from "@/components/categories-section"
import { GuaranteeSection } from "@/components/guarantee-section"
import { DealsSection } from "@/components/deals-section"
import { TestimonialsSection } from "@/components/testimonials-section"
import { CTASection } from "@/components/cta-section"
import { HomepageSettingsProvider } from "@/components/homepage-settings-provider"
import { HeaderSlot, FooterSlot } from "@/components/homepage-visibility-slots"

export default function HomePage() {
  return (
    <HomepageSettingsProvider>
      <HeaderSlot />
      <main>
        <HeroSection />
        <ProcessSection />
        <ComparisonSection />
        <CategoriesSection />
        <GuaranteeSection />
        <DealsSection />
        <TestimonialsSection />
        <CTASection />
      </main>
      <FooterSlot />

      {/* Structured Data for SEO */}
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{
          __html: JSON.stringify({
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            name: "Floors Today",
            description: "Premium flooring installation in Ontario with all-inclusive pricing and free in-home estimates.",
            url: "https://floorstoday.com",
            telephone: "1-888-772-7848",
            areaServed: {
              "@type": "State",
              name: "Ontario",
              containedIn: "Canada",
            },
            priceRange: "$$",
            openingHours: "Mo-Fr 09:00-18:00, Sa 10:00-16:00",
            hasOfferCatalog: {
              "@type": "OfferCatalog",
              name: "Flooring Services",
              itemListElement: [
                {
                  "@type": "Offer",
                  itemOffered: {
                    "@type": "Service",
                    name: "Solid Hardwood Flooring Installation",
                  },
                },
                {
                  "@type": "Offer",
                  itemOffered: {
                    "@type": "Service",
                    name: "Engineered Hardwood Flooring Installation",
                  },
                },
                {
                  "@type": "Offer",
                  itemOffered: {
                    "@type": "Service",
                    name: "Laminate Flooring Installation",
                  },
                },
                {
                  "@type": "Offer",
                  itemOffered: {
                    "@type": "Service",
                    name: "Vinyl Flooring Installation",
                  },
                },
                {
                  "@type": "Offer",
                  itemOffered: {
                    "@type": "Service",
                    name: "Carpet Installation",
                  },
                },
              ],
            },
          }),
        }}
      />
    </HomepageSettingsProvider>
  )
}
