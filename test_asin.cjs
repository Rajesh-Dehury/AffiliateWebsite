const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
puppeteer.use(StealthPlugin());

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';

async function testUrl(url, label) {
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage']
    });

    try {
        const page = await browser.newPage();
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        console.log(`\n--- ${label} ---`);
        console.log('URL:', url);
        
        const response = await page.goto(url, { 
            waitUntil: 'domcontentloaded', 
            timeout: 15000 
        });
        
        console.log('Status:', response.status());
        console.log('Final URL:', page.url());
        console.log('Title:', await page.title());
        
        // Check for Cloudflare
        const html = await page.content();
        if (html.includes('Just a moment') || html.includes('challenge-platform')) {
            console.log('BLOCKED by Cloudflare');
        } else {
            console.log('ACCESSIBLE, length:', html.length);
            // Try to find Amazon links
            const amazonLinks = await page.evaluate(() => {
                const links = document.querySelectorAll('a[href*="amazon"]');
                return Array.from(links).map(a => ({
                    text: a.textContent.trim().substring(0, 50),
                    href: a.getAttribute('href')
                }));
            });
            if (amazonLinks.length > 0) {
                console.log('Amazon links found:', amazonLinks.length);
                amazonLinks.forEach(l => console.log('  -', l.text, ':', l.href));
            } else {
                console.log('No Amazon links found on page');
            }
        }
    } catch (err) {
        console.log('Error:', err.message);
    } finally {
        await browser.close();
    }
}

async function main() {
    // Test 1: Deal detail page
    await testUrl(
        'https://www.indiafreestuff.in/dabur-gluco-c-instant-powder-energy-glucose-mango-flavour-1kg--replenishes-energy--20-more-glucose-in-every-sip--vitamin-c-helps-boosts-immunity--calcium-supports-bone-health',
        'Deal Detail Page'
    );

    // Test 2: RTO redirect URL
    await testUrl(
        'https://www.indiafreestuff.in/?rto=Mjg5NTMxNzEwNg==',
        'RTO Redirect'
    );

    // Test 3: Just try to go to the shop now link
    await testUrl(
        'https://www.indiafreestuff.in/?rto=Mjk1NjczMjA3NQ==',
        'Another RTO'
    );
}

main().catch(console.error);
