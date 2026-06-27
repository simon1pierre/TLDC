import sharp from 'sharp';
import { readFileSync, writeFileSync, mkdirSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const root = join(__dirname, '..');

const SRC = join(root, 'public/logo/New/THE LAST DAYS COVENANTS Logo.png');
const OUT = (dir, name) => join(root, dir, name);

async function main() {
  const srcBuffer = readFileSync(SRC);
  const img = sharp(srcBuffer);

  // Get metadata for centering crop if needed
  const meta = await img.metadata();
  console.log(`Source: ${meta.width}x${meta.height}`);

  // PWA icons
  console.log('Generating PWA icons...');
  mkdirSync(join(root, 'public/pwa'), { recursive: true });
  await sharp(srcBuffer).resize(192, 192, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } }).toFile(OUT('public/pwa', 'icon-192.png'));
  await sharp(srcBuffer).resize(512, 512, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } }).toFile(OUT('public/pwa', 'icon-512.png'));
  console.log('  ✓ PWA icons done');

  // Favicon sizes (as PNG, keeping existing favicon.ico as-is)
  console.log('Generating favicons...');
  mkdirSync(join(root, 'public/logo'), { recursive: true });
  await sharp(srcBuffer).resize(16, 16, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } }).toFile(OUT('public/logo', 'favicon-16x16.png'));
  await sharp(srcBuffer).resize(32, 32, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } }).toFile(OUT('public/logo', 'favicon-32x32.png'));
  await sharp(srcBuffer).resize(180, 180, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } }).toFile(OUT('public/logo', 'apple-touch-icon.png'));
  console.log('  ✓ Favicons done');

  // OG Image (1200x630 with brand name overlay)
  console.log('Generating OG image...');
  const ogWidth = 1200;
  const ogHeight = 630;
  const logoSize = Math.min(ogWidth, ogHeight) * 0.4;
  const logoLeft = Math.round((ogWidth - logoSize) / 2);
  const logoTop = Math.round((ogHeight - logoSize) / 2) - 40;

  const svgOgOverlay = Buffer.from(`<svg width="${ogWidth}" height="${ogHeight}" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" style="stop-color:#0f2b5e"/>
        <stop offset="50%" style="stop-color:#1a3a7a"/>
        <stop offset="100%" style="stop-color:#0f2b5e"/>
      </linearGradient>
    </defs>
    <rect width="${ogWidth}" height="${ogHeight}" fill="url(#bg)"/>
    <text x="${ogWidth / 2}" y="${logoTop + logoSize + 60}" text-anchor="middle" fill="white" font-family="Georgia, serif" font-size="48" font-weight="bold">THE LAST DAYS COVENANTS</text>
    <text x="${ogWidth / 2}" y="${logoTop + logoSize + 100}" text-anchor="middle" fill="#c9a84c" font-family="Arial, sans-serif" font-size="24" letter-spacing="4">Walking the Everlasting Covenant</text>
  </svg>`);

  // Create OG image with centered logo and text
  await sharp(srcBuffer)
    .resize(Math.round(logoSize), Math.round(logoSize), { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
    .toBuffer()
    .then(async (logoBuffer) => {
      await sharp({
        create: {
          width: ogWidth,
          height: ogHeight,
          channels: 4,
          background: { r: 15, g: 43, b: 94, alpha: 1 }
        }
      })
        .composite([
          { input: svgOgOverlay, top: 0, left: 0 },
          { input: logoBuffer, top: logoTop, left: logoLeft },
        ])
        .png()
        .toFile(OUT('public', 'og-image.jpg'));
    });
  console.log('  ✓ OG image done');

  // Twitter image (1200x600, similar but slightly different aspect)
  console.log('Generating Twitter image...');
  const twWidth = 1200;
  const twHeight = 600;
  const twLogoSize = Math.min(twWidth, twHeight) * 0.35;
  const twLogoLeft = Math.round((twWidth - twLogoSize) / 2);
  const twLogoTop = Math.round((twHeight - twLogoSize) / 2) - 30;

  const svgTwOverlay = Buffer.from(`<svg width="${twWidth}" height="${twHeight}" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="bg2" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" style="stop-color:#0f2b5e"/>
        <stop offset="50%" style="stop-color:#1a3a7a"/>
        <stop offset="100%" style="stop-color:#0f2b5e"/>
      </linearGradient>
    </defs>
    <rect width="${twWidth}" height="${twHeight}" fill="url(#bg2)"/>
    <text x="${twWidth / 2}" y="${twLogoTop + twLogoSize + 50}" text-anchor="middle" fill="white" font-family="Georgia, serif" font-size="44" font-weight="bold">THE LAST DAYS COVENANTS</text>
    <text x="${twWidth / 2}" y="${twLogoTop + twLogoSize + 85}" text-anchor="middle" fill="#c9a84c" font-family="Arial, sans-serif" font-size="22" letter-spacing="4">Walking the Everlasting Covenant</text>
  </svg>`);

  await sharp(srcBuffer)
    .resize(Math.round(twLogoSize), Math.round(twLogoSize), { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
    .toBuffer()
    .then(async (logoBuffer) => {
      await sharp({
        create: {
          width: twWidth,
          height: twHeight,
          channels: 4,
          background: { r: 15, g: 43, b: 94, alpha: 1 }
        }
      })
        .composite([
          { input: svgTwOverlay, top: 0, left: 0 },
          { input: logoBuffer, top: twLogoTop, left: twLogoLeft },
        ])
        .jpeg()
        .toFile(OUT('public', 'twitter-image.jpg'));
    });
  console.log('  ✓ Twitter image done');

  console.log('\nAll icons and images generated successfully!');
}

main().catch(err => { console.error(err); process.exit(1); });
