const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
puppeteer.use(StealthPlugin());

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const TARGET_URL = 'https://www.indiafreestuff.in/deals/trending';

async function scrapeDeals() {
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-web-security',
            '--disable-features=IsolateOrigins,site-per-process',
        ]
    });

    try {
        const page = await browser.newPage();
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36');
        await page.setExtraHTTPHeaders({ 'Accept-Language': 'en-US,en;q=0.9' });

        await page.goto(TARGET_URL, { waitUntil: 'networkidle2', timeout: 30000 });
        await page.evaluate(() => new Promise(r => setTimeout(r, 2000)));

        // Get all deals
        const deals = await page.evaluate(() => {
            const items = document.querySelectorAll('.product-item');
            return Array.from(items).map(item => {
                const titleEl = item.querySelector('a.item-title');
                const priceEl = item.querySelector('.new-price p');
                const mrpEl = item.querySelector('.old-price p');
                const discountEl = item.querySelector('.off-discount p');
                const shopBtn = item.querySelector('a.btn-shopnow');
                const img = item.querySelector('.product-img img.lazy');
                const storeLink = item.querySelector('a.barnd-logo-small');

                let title = titleEl ? titleEl.textContent.trim() : '';
                title = title.replace(/\s*[-–]\s*Amazon\s*$/i, '').trim();

                const priceText = priceEl ? priceEl.textContent.trim() : '0';
                const mrpText = mrpEl ? mrpEl.textContent.trim() : '0';
                const discountText = discountEl ? discountEl.textContent.trim() : '0';

                const offerPrice = parseInt(priceText.replace(/[^0-9]/g, '')) || 0;
                const mrp = parseInt(mrpText.replace(/[^0-9]/g, '')) || 0;
                const discount = parseInt(discountText.replace(/[^0-9]/g, '')) || 0;

                const shopUrl = shopBtn ? shopBtn.getAttribute('href') : '';
                const imageUrl = img ? (img.getAttribute('data-original') || img.getAttribute('src') || '') : '';
                const isAmazon = storeLink ? (storeLink.getAttribute('href') || '').includes('/stores/amazon') : false;

                return {
                    title,
                    offer_price: offerPrice,
                    mrp: mrp || offerPrice,
                    discount,
                    deal_url: shopUrl,
                    image_url: imageUrl,
                    is_amazon: isAmazon
                };
            }).filter(d => d.is_amazon && d.offer_price > 0);
        });

        console.log(JSON.stringify(deals));
    } catch (err) {
        console.error('PUPPETEER_ERROR:' + err.message);
        process.exit(1);
    } finally {
        await browser.close();
    }
}

scrapeDeals();
