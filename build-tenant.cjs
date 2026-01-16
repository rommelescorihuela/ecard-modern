const { execSync } = require('child_process');
const fs = require('fs-extra');
const path = require('path');

const slug = process.argv[2];

if (!slug) {
    console.error('❌ Error: Please provide a tenant slug.');
    console.log('Usage: node build-tenant.js <slug>');
    process.exit(1);
}

const PROJECT_ROOT = __dirname;
const FRONTEND_DIR = path.join(PROJECT_ROOT, 'frontend-generator');
const PUBLIC_SITES_DIR = path.join(PROJECT_ROOT, 'public_html/sites');

console.log(`🚀 Starting build process for tenant: ${slug}`);

try {
    // 1. Export Data from Laravel
    console.log('📦 Exporting data from Laravel...');
    execSync(`php artisan tenant:export ${slug}`, { stdio: 'inherit', cwd: PROJECT_ROOT });

    // 2. Build Astro Site
    console.log('🛠️  Building static site...');
    // Build with Astro
    // We pass the slug as env var so Astro only generates routes for this tenant
    // We also pass BASE_PATH for correct asset resolution in subdirectories
    execSync(`export BUILD_TENANT_SLUG=${slug} BASE_PATH=/sites/${slug}/ && npm run build`, {
        cwd: path.join(__dirname, 'frontend-generator'),
        stdio: 'inherit',
        shell: '/bin/bash' // Ensure bash for export syntax
    });

    // 3. Deploy/Move Artifacts
    console.log('📂 Deploying artifacts...');

    // Target directory: public_html/sites/{slug}
    // In production, this might include the full domain, but for now we follow the slug
    const targetDir = path.join(PUBLIC_SITES_DIR, slug);
    const distDir = path.join(FRONTEND_DIR, 'dist');
    const builtPageDir = path.join(distDir, slug);
    const builtAssetsDir = path.join(distDir, '_astro');

    // Clean target
    fs.emptyDirSync(targetDir);

    // Copy HTML (files inside dist/{slug}/)
    if (fs.existsSync(builtPageDir)) {
        fs.copySync(builtPageDir, targetDir);
    } else {
        throw new Error(`Build failed: Directory ${builtPageDir} not found.`);
    }

    // Copy Assets (dist/_astro -> target/_astro)
    if (fs.existsSync(builtAssetsDir)) {
        fs.copySync(builtAssetsDir, path.join(targetDir, '_astro'));
    }

    console.log(`✅ Build and deploy successful for ${slug}!`);
    console.log(`👉 Site available at: public_html/sites/${slug}/index.html`);

} catch (error) {
    console.error('❌ Build failed:', error.message);
    process.exit(1);
}
