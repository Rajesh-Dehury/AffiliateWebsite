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

        // Get shop URL from the first Amazon deal
        const dealInfo = await page.evaluate(() => {
            const productItems = document.querySelectorAll('.product-item');
            for (const item of productItems) {
                const catEl = item.querySelector('.product-category a');
                const cat = catEl ? catEl.textContent.trim().toLowerCase() : '';
                if (cat.includes('amazon') || cat.includes('all')) {
                    const shopBtn = item.querySelector('a.btn-shopnow');
                    const titleLink = item.querySelector('a.item-title');
                    return {
                        shopUrl: shopBtn ? shopBtn.getAttribute('href') : null,
                        title: titleLink ? titleLink.textContent.trim() : null,
                    };
                }
            }
            // fallback: just get first
            const item = document.querySelector('.product-item');
            if (!item) return null;
            const shopBtn = item.querySelector('a.btn-shopnow');
            const titleLink = item.querySelector('a.item-title');
            return {
                shopUrl: shopBtn ? shopBtn.getAttribute('href') : null,
                title: titleLink ? titleLink.textContent.trim() : null,
            };
        });

        console.log('Deal:', dealInfo?.title);
        console.log('Shop URL:', dealInfo?.shopUrl);

        if (dealInfo?.shopUrl) {
            console.log('\n--- Following rto redirect ---');
            
            // Collect redirect chain
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

            // Navigate to rto URL - this will redirect multiple times
            try {
                await page.goto(dealInfo.shopUrl, { 
                    waitUntil: 'networkidle0', 
                    timeout: 45000 
                });
            } catch (e) {
                console.log('Navigation error (expected with redirects):', e.message);
            }

            const finalUrl = page.url();
            console.log('\nFinal URL:', finalUrl);
            console.log('\nRedirect chain:');
            for (const r of redirectChain) {
                console.log(`  ${r.status} ${r.from} → ${r.location}`);
            }

            // Extract ASIN
            const asinMatch = finalUrl.match(/\/(?:dp|gp\/product)\/([A-Z0-9]{10})/);
            const asinFromParam = finalUrl.match(/[?&]lp_page_asin=([A-Z0-9]{10})/);
            const asinFromAsin = finalUrl.match(/[?&]ASIN=([A-Z0-9]{10})/i);
            const dpMatch = finalUrl.match(/\/([A-Z0-9]{10})(?:[/?]|$)/);

            console.log('\nASIN extraction:');
            console.log('  /dp/ match:', asinMatch ? asinMatch[1] : null);
            console.log('  lp_page_asin param:', asinFromParam ? asinFromParam[1] : null);
            console.log('  ASIN param:', asinFromAsin ? asinFromAsin[1] : null);
            console.log('  Any 10-char code:', dpMatch ? dpMatch[1] : null);

            // Take screenshot
            await page.screenshot({ path: 'rto_final.png', fullPage: false });
            console.log('\nScreenshot saved to rto_final.png');
        }

        await browser.close();
    } catch (err) {
        console.error('Error:', err.message);
        try { await browser.close(); } catch(e) {}
    }
}

main();
