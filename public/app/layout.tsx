import { Analytics } from '@vercel/analytics/next'
import type { Metadata } from 'next'
import { Montserrat } from 'next/font/google'
import './globals.css'

const montserrat = Montserrat({
  subsets: ['latin'],
  variable: '--font-montserrat',
})

export const metadata: Metadata = {
  title: 'Floors Today | Premium Flooring Installation in Ontario | Free In-Home Estimates',
  description: 'Ontario\'s trusted flooring experts. All-inclusive pricing with no hidden fees. Free in-home estimates for hardwood, laminate, vinyl, and carpet installation. 50.50.50 Sale - Save up to 50% on select styles.',
  keywords: 'flooring Ontario, hardwood flooring, laminate flooring, vinyl flooring, carpet installation, free flooring estimate, flooring installation Toronto, flooring company Ontario',
  openGraph: {
    title: 'Floors Today | Premium Flooring Installation in Ontario',
    description: 'All-inclusive pricing with no hidden fees. Free in-home estimates for hardwood, laminate, vinyl, and carpet.',
    type: 'website',
    locale: 'en_CA',
  },
  icons: {
    icon: '/floorstoday/public/favicon.png',
    shortcut: '/floorstoday/public/favicon.png',
    apple: '/floorstoday/public/favicon.png',
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
        {process.env.NODE_ENV === 'production' && <Analytics />}
      </body>
    </html>
  )
}
