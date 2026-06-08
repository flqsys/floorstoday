/** @type {import('next').NextConfig} */
const nextConfig = {
  output: 'export',
  basePath: '/floorstoday/public',
  assetPrefix: '/floorstoday/public',
  typescript: {
    ignoreBuildErrors: true,
  },
  images: {
    unoptimized: true,
  },
}

export default nextConfig
