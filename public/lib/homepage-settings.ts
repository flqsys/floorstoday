export type NavItem = {
  name: string
  href: string
}

export type ProcessStep = {
  title: string
  description: string
  button?: string
  image: string
}

export type CategoryItem = {
  name: string
  slug: string
  description: string
  image: string
}

export type OfferItem = {
  title: string
  description: string
}

export type TestimonialItem = {
  name: string
  location: string
  floorType: string
  text: string
}

export type HomepageSettings = {
  primary_color: string
  secondary_color: string
  background_color: string
  foreground_color: string
  phone: string
  email: string
  service_area: string
  logo_text: string
  logo_image: string
  favicon_image: string
  logo_size: string
  cta_label: string
  show_header: string | boolean
  show_footer: string | boolean
  facebook_url: string
  instagram_url: string
  linkedin_url: string
  youtube_url: string
  tiktok_url: string
  button_radius: string
  button_font_weight: string
  button_text_transform: string
  button_padding_x: string
  button_padding_y: string
  button_hover_mix: string
  button_border_width: string
  button_border_style: string
  button_border_color: string
  hero_badge: string
  hero_badge_bg_color: string
  hero_badge_text_color: string
  hero_badge_font_size: string
  hero_badge_padding_x: string
  hero_badge_padding_y: string
  hero_title: string
  hero_highlight: string
  hero_badge_animation_color_1: string
  hero_badge_animation_color_2: string
  hero_badge_animation_location: string
  hero_badge_animation_speed: string
  hero_text: string
  hero_image: string
  hero_show_background: string | boolean
  hero_show_overlay: string | boolean
  hero_overlay_opacity: string
  form_title: string
  form_subtitle: string
  process_title: string
  process_text: string
  process_bg_color_1: string
  process_bg_color_2: string
  process_bg_location: string
  process_steps: ProcessStep[]
  comparison_title: string
  comparison_table_title: string
  comparison_text: string
  comparison_rows: string[]
  comparison_button: string
  comparison_bg_color_1: string
  comparison_bg_color_2: string
  comparison_bg_location: string
  cta_title: string
  cta_subtitle: string
  cta_text: string
  cta_button: string
  cta_bg_color_1: string
  cta_bg_color_2: string
  cta_bg_location: string
  category_title: string
  category_text: string
  category_bg_color_1: string
  category_bg_color_2: string
  category_bg_location: string
  categories: CategoryItem[]
  guarantee_title: string
  guarantee_subtitle: string
  guarantee_text: string
  guarantee_link: string
  guarantee_image: string
  guarantee_bg_color_1: string
  guarantee_bg_color_2: string
  guarantee_bg_location: string
  deals_badge: string
  deals_title: string
  deals_text: string
  deals_body: string
  deals_card_title: string
  deals_card_subtitle: string
  deals_button: string
  deals_details_label: string
  deals_includes_title: string
  deals_includes: string
  deals_popup_eyebrow: string
  deals_popup_title: string
  deals_popup_intro: string
  deals_popup_button: string
  deals_popup_steps_title: string
  deals_popup_steps: string
  deals_popup_terms: string
  deals_popup_terms_extra: string
  deals_bg_color_1: string
  deals_bg_color_2: string
  deals_bg_location: string
  offers: OfferItem[]
  testimonials_title: string
  testimonials_text: string
  testimonials_bg_color_1: string
  testimonials_bg_color_2: string
  testimonials_bg_location: string
  testimonials: TestimonialItem[]
  newsletter_title: string
  newsletter_text: string
  newsletter_button: string
  footer_about: string
  footer_about_title: string
  footer_about_links: string
  footer_categories_title: string
  footer_help_title: string
  footer_help_links: string
  footer_policies_title: string
  footer_policy_links: string
  footer_bottom_links: string
  footer_copyright: string
  footer_bg_color_1: string
  footer_bg_color_2: string
  footer_bg_location: string
  nav_items: NavItem[]
}

const publicBasePath = process.env.NEXT_PUBLIC_BASE_PATH || "/public"

export const homepageDefaults: HomepageSettings = {
  primary_color: "#155f99",
  secondary_color: "lab(76 3.16 65.32)",
  background_color: "oklch(0.985 0.002 90)",
  foreground_color: "oklch(0.20 0.02 30)",
  phone: "1-888-772-7848",
  email: "info@floorstoday.com",
  service_area: "Serving Ontario & Surrounding Areas",
  logo_text: "Floors Today",
  logo_image: "",
  favicon_image: `${publicBasePath}/favicon.png`,
  logo_size: "250px",
  cta_label: "Free Estimate",
  show_header: "1",
  show_footer: "1",
  facebook_url: "",
  instagram_url: "",
  linkedin_url: "",
  youtube_url: "",
  tiktok_url: "",
  button_radius: "8px",
  button_font_weight: "700",
  button_text_transform: "none",
  button_padding_x: "18px",
  button_padding_y: "12px",
  button_hover_mix: "88%",
  button_border_width: "0px",
  button_border_style: "solid",
  button_border_color: "transparent",
  hero_badge: "LIMITED TIME: 50.50.50 SALE",
  hero_badge_bg_color: "lab(76 3.16 65.32)",
  hero_badge_text_color: "#ffffff",
  hero_badge_font_size: "16px",
  hero_badge_padding_x: "16px",
  hero_badge_padding_y: "8px",
  hero_title: "Transform Your Home with",
  hero_highlight: "Premium Flooring",
  hero_badge_animation_color_1: "lab(76 3.16 65.32)",
  hero_badge_animation_color_2: "#ffffff",
  hero_badge_animation_location: "90deg",
  hero_badge_animation_speed: "4s",
  hero_text:
    "All-inclusive pricing with no hidden fees. Get a complete quote during your free in-home consultation.",
  hero_image: `${publicBasePath}/images/hero-living-room.png`,
  hero_show_background: "1",
  hero_show_overlay: "1",
  hero_overlay_opacity: "0.72",
  form_title: "Get Your FREE In-Home Estimate",
  form_subtitle: "No obligation. Takes just 2 minutes.",
  process_title: "How It Works",
  process_text:
    "Getting beautiful new floors has never been easier. Our simple 3-step process takes the stress out of flooring.",
  process_bg_color_1: "oklch(0.985 0.002 90)",
  process_bg_color_2: "oklch(0.985 0.002 90)",
  process_bg_location: "to bottom",
  process_steps: [
    {
      title: "Schedule a Free In-Home Consult with a Flooring Expert",
      description:
        "Book your free consultation at a time that works for you. Our experts come to your home with samples.",
      button: "Get Started Now",
      image:
        "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=300&fit=crop",
    },
    {
      title: "No Surprises, No Hidden Fees",
      description:
        "A flooring expert will bring samples, help you pick the right floors, measure your rooms, and give you an All-Inclusive Price Estimate.",
      image:
        "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=300&fit=crop",
    },
    {
      title: "Schedule-Friendly Installation",
      description:
        "Upon approval, you will be able to finance your purchase and schedule your professional installation.",
      image:
        "https://images.unsplash.com/photo-1600573472550-8090b5e0745e?w=400&h=300&fit=crop",
    },
  ],
  comparison_title: "All-Inclusive Price Estimate, No Hidden Fees",
  comparison_table_title: "What's Included",
  comparison_text:
    "Floors Today makes it easy with one, easy to understand price, complete with all the commonly up-charged items required for your floor to be installed. You will know the full project price during your free in-home appointment, upfront, before any installation work begins.",
  comparison_rows: [
    "Product Cost",
    "Measuring",
    "Professional Installation",
    "Padding/Underlayment",
    "Moving Furniture",
    "Haul Away of Old Flooring",
    "Thresholds/Transitions",
    "Clean Up",
    "All-Inclusive Price Estimate",
  ],
  comparison_button: "Book An Appointment",
  comparison_bg_color_1: "var(--primary)",
  comparison_bg_color_2: "var(--primary)",
  comparison_bg_location: "to bottom",
  cta_title: "Ready to Get Started?",
  cta_subtitle: "Schedule a FREE In-Home Estimate",
  cta_text:
    "Our flooring experts will bring samples to your home, measure your space, and provide an all-inclusive price quote with no hidden fees.",
  cta_button: "Schedule Now",
  cta_bg_color_1: "var(--primary)",
  cta_bg_color_2: "var(--primary)",
  cta_bg_location: "to bottom",
  category_title: "Shop By Category",
  category_text: "Explore our wide selection of premium flooring options for every style and budget",
  category_bg_color_1: "oklch(0.96 0.005 90)",
  category_bg_color_2: "oklch(0.96 0.005 90)",
  category_bg_location: "to bottom",
  categories: [
    {
      name: "Solid Hardwood",
      slug: "solid-hardwood",
      image: "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=500&fit=crop",
      description: "Timeless elegance with natural wood beauty",
    },
    {
      name: "Engineered Hardwood",
      slug: "engineered-hardwood",
      image: "https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=400&h=500&fit=crop",
      description: "Versatile and durable for any room",
    },
    {
      name: "Laminate",
      slug: "laminate",
      image: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=500&fit=crop",
      description: "Affordable style with easy maintenance",
    },
    {
      name: "Vinyl",
      slug: "vinyl",
      image: "https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=400&h=500&fit=crop",
      description: "Waterproof and pet-friendly options",
    },
    {
      name: "Carpet",
      slug: "carpet",
      image: "https://images.unsplash.com/photo-1600210492493-0946911123ea?w=400&h=500&fit=crop",
      description: "Soft comfort for bedrooms and living areas",
    },
  ],
  guarantee_title: "Low Price Guarantee",
  guarantee_subtitle: "We won't be beat on price!",
  guarantee_text:
    "If you find a lower price on a comparable product and installation, we'll beat any competitive offer, guaranteed! Our commitment to value means you get the best flooring at the best price, every time.",
  guarantee_link: "Get More Information",
  guarantee_image:
    "https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=600&h=400&fit=crop",
  guarantee_bg_color_1: "oklch(0.985 0.002 90)",
  guarantee_bg_color_2: "oklch(0.985 0.002 90)",
  guarantee_bg_location: "to bottom",
  deals_badge: "Limited Time Offers",
  deals_title: "Floors Today Coupons & Special Offers",
  deals_text: "Exclusive flooring deals designed to fit your budget - quality floors without the hidden costs",
  deals_body:
    "At Floors Today, we believe great flooring should be accessible without confusion or hidden costs. Along with our everyday competitive pricing, we offer limited-time promotions and special deals to help homeowners save on high-quality flooring and professional installation.",
  deals_card_title: "50.50.50",
  deals_card_subtitle: "SALE",
  deals_button: "Claim Your Savings",
  deals_details_label: "View offer details",
  deals_includes_title: "Your project includes",
  deals_includes: "Free home estimate\nSamples brought to you\nProfessional installation",
  deals_popup_eyebrow: "Limited-time offer",
  deals_popup_title: "Save on Your Complete Flooring Project",
  deals_popup_intro:
    "Choose your floors at home, receive transparent project pricing, and arrange professional installation.",
  deals_popup_button: "Book a Free Appointment",
  deals_popup_steps_title: "How the offer works",
  deals_popup_steps:
    "Free home estimate|Book online or call {phone}.\nShop from home|Compare samples and receive your project estimate on site.\nEligible savings|Save up to 60% on select installed flooring styles.\nProfessional finish|Your flooring is installed by qualified professionals.",
  deals_popup_terms:
    "*Get 15% off plus up to an additional 45% on carpet, or 25% on hardwood, laminate and vinyl, based on project size. Applies to select styles, basic installation, standard padding and materials. Excludes upgrades, stairs, specialized removal or preparation, non-standard furniture moving, miscellaneous charges and prior purchases. Residential installations only, while supplies last. Offer ends June 29, 2026 and is subject to change.",
  deals_popup_terms_extra:
    "Next-day installation applies only to eligible in-stock products and service areas. Installation may be completed by qualified independent professionals where applicable.",
  deals_bg_color_1: "oklch(0.96 0.005 90)",
  deals_bg_color_2: "oklch(0.985 0.002 90)",
  deals_bg_location: "to bottom",
  offers: [
    { title: "Up to 50% Off", description: "Select hardwood and engineered flooring styles" },
    { title: "Free Installation", description: "On qualifying orders over $2,500" },
    { title: "Price Match Plus", description: "We'll beat any competitor's price by 5%" },
    { title: "0% Financing", description: "For 12 months on approved credit" },
  ],
  testimonials_title: "What Our Customers Say",
  testimonials_text: "Join thousands of satisfied Ontario homeowners who trust Floors Today for their flooring needs",
  testimonials_bg_color_1: "oklch(0.985 0.002 90)",
  testimonials_bg_color_2: "oklch(0.985 0.002 90)",
  testimonials_bg_location: "to bottom",
  testimonials: [
    {
      name: "Sarah M.",
      location: "Toronto, ON",
      text: "The team at Floors Today was incredible. From the initial consultation to the final installation, everything was seamless. Our new hardwood floors look absolutely stunning!",
      floorType: "Solid Hardwood",
    },
    {
      name: "Michael R.",
      location: "Mississauga, ON",
      text: "I was worried about hidden fees after bad experiences elsewhere, but Floors Today delivered exactly what they promised. The all-inclusive pricing is the real deal.",
      floorType: "Engineered Hardwood",
    },
    {
      name: "Jennifer L.",
      location: "Hamilton, ON",
      text: "Best decision we made for our home renovation. The vinyl flooring is perfect for our busy family with kids and pets. Highly recommend their professional installation.",
      floorType: "Luxury Vinyl",
    },
  ],
  newsletter_title: "Subscribe to Newsletter",
  newsletter_text: "Get the latest deals and flooring tips",
  newsletter_button: "Subscribe",
  footer_about:
    "We believe in transparent pricing. That's why our all-inclusive estimates include every essential detail in delivering a seamless flooring experience with no unexpected costs.",
  footer_about_title: "About Us",
  footer_about_links: "About Us|/about-us/\nContact Us|/contact/",
  footer_categories_title: "Categories",
  footer_help_title: "Help Area",
  footer_help_links: "How Shop at Home Works|#how-it-works\nProduct Care|/product-care/\nContact|/contact/",
  footer_policies_title: "Our Policies",
  footer_policy_links: "Terms Of Use|/terms-of-use/\nFAQs|/faqs/\nPrivacy Policy|/privacy-policy/\nWarranty Information|/warranty/",
  footer_bottom_links: "Careers|/careers/\nPrivacy Policy|/privacy-policy/\nSitemap|/sitemap_index.xml\nTerms Of Use|/terms-of-use/",
  footer_copyright: "Floors Today Copyright {year} All Rights Reserved",
  footer_bg_color_1: "oklch(0.20 0.02 30)",
  footer_bg_color_2: "oklch(0.20 0.02 30)",
  footer_bg_location: "to bottom",
  nav_items: [
    { name: "Solid Hardwood", href: "#hardwood" },
    { name: "Engineered Hardwood", href: "#engineered" },
    { name: "Laminate", href: "#laminate" },
    { name: "Vinyl", href: "#vinyl" },
    { name: "Carpet", href: "#carpet" },
  ],
}

export function mergeHomepageSettings(
  settings: Partial<HomepageSettings> | null | undefined,
): HomepageSettings {
  if (!settings) {
    return homepageDefaults
  }

  const merged = {
    ...homepageDefaults,
    ...settings,
    nav_items: settings.nav_items?.length ? settings.nav_items : homepageDefaults.nav_items,
    process_steps: settings.process_steps?.length
      ? settings.process_steps
      : homepageDefaults.process_steps,
    comparison_rows: settings.comparison_rows?.length
      ? settings.comparison_rows
      : homepageDefaults.comparison_rows,
    categories: settings.categories?.length ? settings.categories : homepageDefaults.categories,
    offers: settings.offers?.length ? settings.offers : homepageDefaults.offers,
    testimonials: settings.testimonials?.length
      ? settings.testimonials
      : homepageDefaults.testimonials,
  }

  const oldBlue = "#155f99"
  const sectionFallback = "var(--primary)"

  ;[
    "comparison_bg_color_1",
    "comparison_bg_color_2",
    "cta_bg_color_1",
    "cta_bg_color_2",
  ].forEach((field) => {
    const key = field as keyof HomepageSettings

    if (String(merged[key]).toLowerCase() === oldBlue) {
      ;(merged as Record<string, unknown>)[field] = sectionFallback
    }
  })

  return merged
}
