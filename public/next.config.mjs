/** @type {import('next').NextConfig} */
const nextConfig = {
  output: 'export',
  basePath: '/floorstest',
  assetPrefix: '/floorstest',
  typescript: {
    ignoreBuildErrors: true,
  },
  images: {
    unoptimized: true,
  },
}

export default nextConfig
