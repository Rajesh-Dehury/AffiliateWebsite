const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
puppeteer.use(StealthPlugin());

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';

async function scrapePage(url) {
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-web-security',
            '--disable-features=IsolateOrigins,site-per-process',
        ]
    });

    try {
        const page = await browser.newPage();
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36');
        
        await page.setExtraHTTPHeaders({
            'Accept-Language': 'en-US,en;q=0.9',
        });

        console.log('Navigating to ' + url);
        await page.goto(url, { 
            waitUntil: 'networkidle2', 
            timeout: 30000 
        });

        // Wait a bit for any dynamic content
        await page.evaluate(() => new Promise(r => setTimeout(r, 2000)));

        const html = await page.content();
        console.log('Page title:', await page.title());
        console.log('HTML length:', html.length);

        // Check for product items
        const productCount = await page.evaluate(() => {
            return document.querySelectorAll('.product-item').length;
        });
        console.log('Product items found:', productCount);

        // Check if Cloudflare challenge page
        const hasChallenge = html.includes('challenge-platform') || html.includes('Just a moment');
        console.log('Has Cloudflare challenge:', hasChallenge);

        // Check for deal data
        const dealData = await page.evaluate(() => {
            return document.querySelectorAll('#dealdata, .product-item, .product-list').length;
        });
        console.log('Deal containers found:', dealData);

        if (productCount > 0) {
            // Get first 3 deals
            const deals = await page.evaluate(() => {
                const items = document.querySelectorAll('.product-item');
                return Array.from(items).slice(0, 3).map(item => {
                    const titleEl = item.querySelector('a.item-title');
                    const priceEl = item.querySelector('.new-price p');
                    const mrpEl = item.querySelector('.old-price p');
                    const discountEl = item.querySelector('.off-discount p');
                    const shopBtn = item.querySelector('a.btn-shopnow');
                    const img = item.querySelector('.product-img img.lazy');
                    const storeLink = item.querySelector('a.barnd-logo-small');
                    
                    return {
                        title: titleEl ? titleEl.textContent.trim() : '',
                        price: priceEl ? priceEl.textContent.trim() : '',
                        mrp: mrpEl ? mrpEl.textContent.trim() : '',
                        discount: discountEl ? discountEl.textContent.trim() : '',
                        shopUrl: shopBtn ? shopBtn.getAttribute('href') : '',
                        image: img ? (img.getAttribute('data-original') || img.getAttribute('src') || '') : '',
                        isAmazon: storeLink ? (storeLink.getAttribute('href') || '').includes('/stores/amazon') : false,
                    };
                });
            });
            console.log(JSON.stringify(deals, null, 2));
        }

        await browser.close();
        return html;
    } catch (err) {
        console.error('Error:', err.message);
        await browser.close();
        throw err;
    }
}

// Main
const url = process.argv[2] || 'https://www.indiafreestuff.in/deals/trending';
scrapePage(url).catch(err => {
    console.error(err);
    process.exit(1);
});
