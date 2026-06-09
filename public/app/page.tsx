import { HeroSection } from "@/components/hero-section"
import { ProcessSection } from "@/components/process-section"
import dynamic from "next/dynamic"
import { HomepageSettingsProvider } from "@/components/homepage-settings-provider"
import { HeaderSlot, FooterSlot } from "@/components/homepage-visibility-slots"

const ComparisonSection = dynamic(() =>
  import("@/components/comparison-section").then((module) => module.ComparisonSection),
)
const CategoriesSection = dynamic(() =>
  import("@/components/categories-section").then((module) => module.CategoriesSection),
)
const GuaranteeSection = dynamic(() =>
  import("@/components/guarantee-section").then((module) => module.GuaranteeSection),
)
const DealsSection = dynamic(() =>
  import("@/components/deals-section").then((module) => module.DealsSection),
)
const TestimonialsSection = dynamic(() =>
  import("@/components/testimonials-section").then((module) => module.TestimonialsSection),
)
const CTASection = dynamic(() =>
  import("@/components/cta-section").then((module) => module.CTASection),
)

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
        id="ft-home-schema"
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
