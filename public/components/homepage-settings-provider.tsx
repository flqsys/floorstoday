"use client"

import {
  createContext,
  useContext,
  useEffect,
  useLayoutEffect,
  useMemo,
  useState,
  type CSSProperties,
  type ReactNode,
} from "react"
import {
  homepageDefaults,
  mergeHomepageSettings,
  type HomepageSettings,
} from "@/lib/homepage-settings"

function wordpressEndpoint(path: string) {
  if (typeof window === "undefined") return path

  const publicMarker = "/public/"
  const publicIndex = window.location.pathname.indexOf(publicMarker)
  const installPath = publicIndex >= 0
    ? window.location.pathname.slice(0, publicIndex)
    : window.location.pathname.replace(/\/$/, "")

  return `${installPath}${path}`
}

declare global {
  interface Window {
    __FT_HOMEPAGE_SETTINGS__?: Partial<HomepageSettings>
  }
}

type HomepageSettingsContextValue = {
  settings: HomepageSettings
  isLoaded: boolean
}

const HomepageSettingsContext = createContext<HomepageSettingsContextValue>({
  settings: homepageDefaults,
  isLoaded: false,
})

export function HomepageSettingsProvider({ children }: { children: ReactNode }) {
  const [settings, setSettings] = useState<HomepageSettings>(homepageDefaults)
  const [isLoaded, setIsLoaded] = useState(false)

  useLayoutEffect(() => {
    if (!window.__FT_HOMEPAGE_SETTINGS__) {
      return
    }

    setSettings(mergeHomepageSettings(window.__FT_HOMEPAGE_SETTINGS__))
    setIsLoaded(true)
  }, [])

  useEffect(() => {
    let alive = true

    fetch(wordpressEndpoint("/wp-json/floors-today/v1/homepage"), { cache: "no-store" })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Homepage settings request failed: ${response.status}`)
        }

        return response.json()
      })
      .then((data) => {
        if (alive) {
          setSettings(mergeHomepageSettings(data))
          setIsLoaded(true)
        }
      })
      .catch(() => {
        if (alive) {
          setIsLoaded(true)
        }
      })

    return () => {
      alive = false
    }
  }, [])

  useEffect(() => {
    const favicon = settings.favicon_image

    if (!favicon) {
      return
    }

    const rels = ["icon", "shortcut icon", "apple-touch-icon"]

    rels.forEach((rel) => {
      let link = document.querySelector<HTMLLinkElement>(`link[rel="${rel}"]`)

      if (!link) {
        link = document.createElement("link")
        link.rel = rel
        document.head.appendChild(link)
      }

      link.href = favicon
    })
  }, [settings.favicon_image])

  const style = useMemo(
    () =>
      ({
        "--primary": settings.primary_color,
        "--ring": settings.primary_color,
        "--chart-1": settings.primary_color,
        "--sidebar-primary": settings.primary_color,
        "--sidebar-ring": settings.primary_color,
        "--secondary": settings.secondary_color,
        "--accent": settings.secondary_color,
        "--chart-2": settings.secondary_color,
        "--background": settings.background_color,
        "--foreground": settings.foreground_color,
        "--ft-button-radius": settings.button_radius,
        "--ft-button-font-weight": settings.button_font_weight,
        "--ft-button-text-transform": settings.button_text_transform,
        "--ft-button-padding-x": settings.button_padding_x,
        "--ft-button-padding-y": settings.button_padding_y,
        "--ft-button-hover-mix": settings.button_hover_mix,
        "--ft-button-border-width": settings.button_border_width,
        "--ft-button-border-style": settings.button_border_style,
        "--ft-button-border-color": settings.button_border_color,
        "--ft-hero-badge-bg": settings.hero_badge_bg_color,
        "--ft-hero-badge-text": settings.hero_badge_text_color,
        "--ft-hero-badge-font-size": settings.hero_badge_font_size,
        "--ft-hero-badge-padding-x": settings.hero_badge_padding_x,
        "--ft-hero-badge-padding-y": settings.hero_badge_padding_y,
        "--ft-hero-badge-animation-color-1": settings.hero_badge_animation_color_1,
        "--ft-hero-badge-animation-color-2": settings.hero_badge_animation_color_2,
        "--ft-hero-badge-animation-location": settings.hero_badge_animation_location,
        "--ft-hero-badge-animation-speed": settings.hero_badge_animation_speed,
      }) as CSSProperties,
    [settings],
  )

  return (
    <HomepageSettingsContext.Provider value={{ settings, isLoaded }}>
      <div className="ft-homepage-shell" style={style}>
        {children}
      </div>
    </HomepageSettingsContext.Provider>
  )
}

export function useHomepageSettings() {
  return useContext(HomepageSettingsContext).settings
}

export function useHomepageSettingsStatus() {
  return useContext(HomepageSettingsContext)
}
