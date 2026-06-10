import type { Metadata } from 'next'
import { Montserrat } from 'next/font/google'
import './globals.css'

const montserrat = Montserrat({
  subsets: ['latin'],
  variable: '--font-montserrat',
})

export const metadata: Metadata = {
  metadataBase: new URL('https://floorstoday.ca'),
  title: 'Floors Today | Flooring Installation in Ontario',
  description: 'Shop premium hardwood, laminate, vinyl and carpet flooring with free in-home estimates and professional installation across Ontario.',
  alternates: {
    canonical: '/',
  },
  openGraph: {
    title: 'Floors Today | Premium Flooring in Ontario',
    description: 'Free in-home flooring estimates, transparent pricing and professional installation across Ontario.',
    url: '/',
    siteName: 'Floors Today',
    images: ['/public/images/hero-living-room.png'],
    type: 'website',
    locale: 'en_CA',
  },
  twitter: {
    card: 'summary_large_image',
    title: 'Floors Today | Premium Flooring in Ontario',
    description: 'Free in-home flooring estimates, transparent pricing and professional installation across Ontario.',
    images: ['/public/images/hero-living-room.png'],
  },
  robots: {
    index: true,
    follow: true,
  },
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html lang="en" className={montserrat.variable}>
      <body className="font-sans antialiased bg-background">
        {children}
      </body>
    </html>
  )
}
