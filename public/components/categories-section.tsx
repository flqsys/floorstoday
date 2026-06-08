"use client"

import Link from "next/link"
import { useHomepageSettings } from "@/components/homepage-settings-provider"

export function CategoriesSection() {
  const settings = useHomepageSettings()

  return (
    <section
      className="py-20"
      style={{
        background: `linear-gradient(${settings.category_bg_location}, ${settings.category_bg_color_1}, ${settings.category_bg_color_2})`,
      }}
      aria-labelledby="categories-heading"
    >
      <div className="mx-auto max-w-[1280px] px-3 sm:px-4 lg:px-4">
        <div className="text-center mb-12">
          <h2 id="categories-heading" className="font-serif text-3xl font-bold text-foreground sm:text-4xl">
            {settings.category_title}
          </h2>
          <p className="mt-4 text-lg text-muted-foreground max-w-2xl mx-auto">
            {settings.category_text}
          </p>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          {settings.categories.map((category) => (
            <Link
              key={category.slug}
              href={`#${category.slug}`}
              className="group relative aspect-[4/5] overflow-hidden rounded-xl"
            >
              <img
                src={category.image}
                alt={category.name}
                className="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
              <div className="absolute bottom-0 left-0 right-0 p-4 text-white">
                <h3 className="font-semibold text-lg">{category.name}</h3>
                <p className="text-sm text-white/80 mt-1 hidden sm:block">{category.description}</p>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}
