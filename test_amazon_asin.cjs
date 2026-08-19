const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
puppeteer.use(StealthPlugin());

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';

async function main() {
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    try {
        const page = await browser.newPage();
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        await page.goto('https://www.indiafreestuff.in/deals/trending', { waitUntil: 'networkidle2', timeout: 30000 });
        await page.evaluate(() => new Promise(r => setTimeout(r, 2000)));

        // Find an Amazon deal specifically
        const allDeals = await page.evaluate(() => {
            const items = document.querySelectorAll('.product-item');
            const results = [];
            items.forEach((item, i) => {
                const titleLink = item.querySelector('a.item-title');
                const shopBtn = item.querySelector('a.btn-shopnow');
                const catEl = item.querySelector('.product-category a, .deal-category a');
                const title = titleLink ? titleLink.textContent.trim() : '';
                const shopUrl = shopBtn ? shopBtn.getAttribute('href') : '';
                const cat = catEl ? catEl.textContent.trim() : '';
                results.push({ index: i, title, cat, shopUrl: shopUrl || '(none)' });
            });
            return results;
        });

        // Find Amazon deals
        const amazonDeals = allDeals.filter(d => 
            d.title.toLowerCase().includes('amazon') || 
            d.cat.toLowerCase().includes('amazon') ||
            d.shopUrl.includes('rto')
        );

        console.log('Amazon deals found:', amazonDeals.length);
        for (const d of amazonDeals.slice(0, 5)) {
            console.log(`\n[${d.index}] ${d.title}`);
            console.log(`  Category: ${d.cat || 'N/A'}`);
            console.log(`  Shop URL: ${d.shopUrl}`);
        }

        // Process the first Amazon deal
        const target = amazonDeals.find(d => d.shopUrl && d.shopUrl !== '(none)');
        if (!target) {
            console.log('No Amazon deal with shop URL found');
            await browser.close();
            return;
        }

        console.log(`\n\n=== Processing Amazon deal: ${target.title} ===`);
        console.log(`Shop URL: ${target.shopUrl}`);

        // Follow rto redirect
        const redirectChain = [];
        page.on('response', response => {
            const status = response.status();
            if (status >= 300 && status < 400) {
                redirectChain.push({
                    from: response.url(),
                    status,
                    location: response.headers()['location'] || '(none)'
                });
            }
        });

        try {
            await page.goto(target.shopUrl, { 
                waitUntil: 'networkidle0', 
                timeout: 45000 
            });
        } catch (e) {
            console.log('Navigation note:', e.message);
        }

        const finalUrl = page.url();
        console.log('\nFinal URL:', finalUrl);

        // Try to extract ASIN from various patterns
        const asinPatterns = [
            { name: '/dp/B0...', re: /\/(?:dp|gp\/product)\/([A-Z0-9]{10})/ },
            { name: 'lp_page_asin', re: /[?&]lp_page_asin=([A-Z0-9]{10})/ },
            { name: 'ASIN param', re: /[?&]ASIN=([A-Z0-9]{10})/i },
            { name: '/product/B0...', re: /\/product\/([A-Z0-9]{10})/ },
            { name: '/B0... (standalone)', re: /\/(B[A-Z0-9]{9})(?:[/?]|$)/ },
        ];

        for (const { name, re } of asinPatterns) {
            const m = finalUrl.match(re);
            console.log(`  ${name}: ${m ? m[1] : 'not found'}`);
        }

        // Also check the raw response headers
        console.log('\nFull redirect chain:');
        for (const r of redirectChain) {
            const loc = r.location;
            console.log(`  ${r.status} → ${loc.substring(0, 200)}`);
        }

        await page.screenshot({ path: 'rto_amazon.png', fullPage: false });
        await browser.close();
    } catch (err) {
        console.error('Error:', err.message);
        try { await browser.close(); } catch(e) {}
    }
}

main();
